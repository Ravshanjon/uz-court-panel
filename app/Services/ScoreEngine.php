<?php
// app/Services/ScoreEngine.php
namespace App\Services;

use App\Models\Appeal;
use App\Models\Judges;
use App\Models\JudgeScoreEntry;
use Illuminate\Support\Facades\DB;

class ScoreEngine
{
    const BASE = 50.0; // har bir sudya boshlang'ich ball

    public function recomputeForAppeal(int $appealId): void
    {
        DB::transaction(function () use ($appealId) {
            /** @var Appeal $appeal */
            $appeal = Appeal::with(['appelation.reason','cassation.reason','tf1.firstReason','tf2.reason','presidium.reason'])
                ->lockForUpdate()
                ->findOrFail($appealId);

            $mainJudge = $appeal->judge_id;

            // Avvalgi ledgerdagi tegishli sudyalarni yig'ib olamiz (keyin qayta hisobdan so'ng o‘sha sudyalarni yangilaymiz)
            $touched = JudgeScoreEntry::where('appeal_id', $appeal->id)->pluck('judge_id')->all();
            $touched[] = $mainJudge;

            // Eski ledger yozuvlarini tozalaymiz (faqat shu appeal bo‘yicha)
            JudgeScoreEntry::where('appeal_id', $appeal->id)->delete();

            $mainTotal = 0.0;

            // --- 1) Appelation: asosiy sudyaga ta'sir ---
            if ($ap = $appeal->appelation) {
                $score = (float) ($ap->reason->score ?? 0);
                $deltaMainAp = -1 * abs($score); // talabingiz: faqat asosiy sudyadan olinsin
                if ($deltaMainAp != 0) {
                    $this->addLedger($appeal->id, $mainJudge, 'appelation', $deltaMainAp, 'Apellyatsiya');
                    $mainTotal += $deltaMainAp;
                }
                // snapshot (ixtiyoriy)
                $ap->forceFill(['delta_main' => $deltaMainAp])->saveQuietly();
                // panel sudyalarga hozircha ta’sir YO‘Q (Taftish-1 da bo‘ladi)
                // touched listga ap panelini ham qo‘shamiz (Taftish-1da kerak bo‘lishi mumkin)
                foreach (['speaker_judge_id','presiding_judge_id','jury_judge_id'] as $k) {
                    if ($ap->$k) $touched[] = $ap->$k;
                }
            }

            // --- 2) Cassation: xuddi apellyatsiya kabi asosiyga ta’sir ---
            if ($cs = $appeal->cassation) {
                $score = (float) ($cs->reason->score ?? 0);
                $deltaMainCs = -1 * abs($score);
                if ($deltaMainCs != 0) {
                    $this->addLedger($appeal->id, $mainJudge, 'cassation', $deltaMainCs, 'Kassatsiya');
                    $mainTotal += $deltaMainCs;
                }
                $cs->forceFill(['delta_main' => $deltaMainCs])->saveQuietly();
            }

            // --- 3) Taftish-1: panel 100/50/50 va asosiyga qo‘shimcha ---
            if ($tf1 = $appeal->tf1) {
                // 1-inst reason snapshot
                $tf1->first_instance_score = (float) ($tf1->firstReason->score ?? 0);

                // panel baza (agar bo'sh bo‘lsa, apellyatsiya |delta| dan olamiz)
                $base = $tf1->panel_base ?? abs((float)($appeal->appelation->delta_main ?? 0));

                $sp = $tf1->speaker_judge_id ?: ($appeal->appelation->speaker_judge_id ?? null);
                $pr = $tf1->presiding_judge_id ?: ($appeal->appelation->presiding_judge_id ?? null);
                $ju = $tf1->jury_judge_id ?: ($appeal->appelation->jury_judge_id ?? null);

                // Panel jarimalar (manfiy)
                $penSpeaker = $base ? (-1.0 * $base)       : 0;
                $penPres    = $base ? (-1.0 * $base / 2.0) : 0;
                $penJury    = $base ? (-1.0 * $base / 2.0) : 0;

                if ($sp && $penSpeaker) $this->addLedger($appeal->id, $sp, 'taftish1', $penSpeaker, 'TF1 speaker 100%');
                if ($pr && $penPres)    $this->addLedger($appeal->id, $pr, 'taftish1', $penPres, 'TF1 presiding 50%');
                if ($ju && $penJury)    $this->addLedger($appeal->id, $ju, 'taftish1', $penJury, 'TF1 jury 50%');

                // Asosiy sudya uchun qo‘shimcha delta (apel natijasini o‘zgartirishi mumkin)
                $mainDeltaTf1 = (float)($tf1->main_delta ?? 0);
                if ($mainDeltaTf1 != 0) {
                    $this->addLedger($appeal->id, $mainJudge, 'taftish1', $mainDeltaTf1, 'TF1 main adjust');
                    $mainTotal += $mainDeltaTf1;
                }

                $tf1->forceFill([
                    'penalty_speaker' => abs($penSpeaker),
                    'penalty_presiding' => abs($penPres),
                    'penalty_jury' => abs($penJury),
                ])->saveQuietly();

                foreach ([$sp,$pr,$ju] as $pid) if ($pid) $touched[] = $pid;
            }

            // --- 4) Taftish-2: asosiyga qo‘shimcha ---
            if ($tf2 = $appeal->tf2) {
                $delta = (float) ($tf2->main_delta ?? 0);
                if ($delta != 0) {
                    $this->addLedger($appeal->id, $mainJudge, 'taftish2', $delta, 'TF2 main adjust');
                    $mainTotal += $delta;
                }
            }

            // --- 5) Presidium: asosiyga qo‘shimcha ---
            if ($pr = $appeal->presidium) {
                $delta = (float) ($pr->main_delta ?? 0);
                if ($delta != 0) {
                    $this->addLedger($appeal->id, $mainJudge, 'presidium', $delta, 'Presidium main');
                    $mainTotal += $delta;
                }
            }

            // Appeal yakuniy natija (faqat asosiy sudya kesimida)
            $appeal->forceFill(['score' => round($mainTotal, 2)])->saveQuietly();

            // Tegishli sudyalarni qayta hisoblaymiz (faqat shu appeal tegib ketganlar)
            $this->recalcJudges(array_values(array_unique(array_filter($touched))));
        });
    }

    public function deleteAppealCascade(Appeal $appeal): void
    {
        DB::transaction(function () use ($appeal) {
            $appeal->loadMissing(['appelation','tf1','tf2','presidium']);

            // Qayta hisoblanishi kerak bo‘lgan sudyalar
            $touched = [$appeal->judge_id];
            if ($ap = $appeal->appelation) {
                foreach (['speaker_judge_id','presiding_judge_id','jury_judge_id'] as $k) {
                    if ($ap->$k) $touched[] = $ap->$k;
                }
            }
            if ($tf1 = $appeal->tf1) {
                foreach (['speaker_judge_id','presiding_judge_id','jury_judge_id'] as $k) {
                    if ($tf1->$k) $touched[] = $tf1->$k;
                }
            }

            // Ledgerdan ushbu appeal yozuvlarini olib tashlaymiz
            JudgeScoreEntry::where('appeal_id', $appeal->id)->delete();

            // Childlarni va parentni o‘chiramiz
            $appeal->presidium()?->delete();
            $appeal->tf2()?->delete();
            $appeal->tf1()?->delete();
            $appeal->cassation()?->delete();
            $appeal->appelation()?->delete();
            $appeal->delete();

            // Tegishli sudyalar bo‘yicha umumiy yig'indi asosida quality_score ni yangilash
            $this->recalcJudges(array_values(array_unique(array_filter($touched))));
        });
    }

    private function addLedger(int $appealId, string $judgeId, string $stage, float $amount, ?string $note = null): void
    {
        if (!$judgeId || $amount == 0.0) return;

        JudgeScoreEntry::create([
            'appeal_id' => $appealId,
            'judge_id'  => $judgeId,
            'stage'     => $stage,
            'amount'    => round($amount, 2),
            'note'      => $note,
        ]);
    }

    public function recalcJudges(array $judgeIds): void
    {
        if (empty($judgeIds)) return;

        // Har bir sudya uchun ledger yig'indisini hisoblab, quality_score = BASE + sum
        $sums = JudgeScoreEntry::selectRaw('judge_id, COALESCE(SUM(amount),0) as s')
            ->whereIn('judge_id', $judgeIds)
            ->groupBy('judge_id')
            ->pluck('s','judge_id');

        // Bazaga kirmagan, lekin ro‘yxatda bo‘lgan sudyalarni ham 0 deb hisoblaymiz
        foreach ($judgeIds as $jid) {
            $total = (float)($sums[$jid] ?? 0);
            $new   = round(self::BASE + $total, 2);
            if ($new < 0) $new = 0;

            $j = Judges::lockForUpdate()->find($jid);
            if ($j) $j->forceFill(['quality_score' => $new])->saveQuietly();
        }
    }
}
