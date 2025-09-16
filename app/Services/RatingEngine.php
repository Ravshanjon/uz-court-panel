<?php

namespace App\Services;

use App\Models\Judges;
use App\Models\Proceeding;
use App\Models\AppliedPenalty;
use Illuminate\Support\Facades\Schema;

class RatingEngine
{
    public const BASELINE = 50;

    /** ✅ Bitta sudyaning sifat ballini qayta hisoblash (faqat aktiv jarimalar). */
    public static function recomputeJudge(Judges $judge): void
    {
        $activeSum = (int) $judge->penaltiesApplied()
            ->where('voided', false)
            ->where(function ($q) {
                $q->whereNull('effective_until')->orWhere('effective_until', '>', now());
            })
            ->sum('amount');

        $effective = max(0, self::BASELINE - min(self::BASELINE, $activeSum));

        // faqat quality_score ga yozamiz
        if (Schema::hasColumn('judges', 'quality_score')) {
            $judge->forceFill(['quality_score' => $effective])->saveQuietly();
        }
    }

    /**
     * ✅ Berilgan proceeding zanjiri bo‘yicha (root-first ga bog‘liq)
     * ta’sirlangan BARCHA sudyalarni topib, qayta hisoblaydi.
     */
    public static function recomputeAllAffected(Proceeding $p): void
    {
        // root-first ni topish
        $root = self::findRootFirst($p);
        if (!$root) {
            // agar root topilmasa – faqat target sudyani yangilab qo'yamiz
            $p->judge?->exists && self::recomputeJudge($p->judge);
            return;
        }

        // shu zanjirdagi barcha proceeding id’lari
        $chainIds = Proceeding::query()
            ->where('root_first_id', $root->id)
            ->orWhere('id', $root->id)
            ->pluck('id');

        // ta’sirlangan sudyalar: applied_penalties dagi + target (1-inst sudyasi)
        $affectedJudgeIds = AppliedPenalty::query()
            ->whereIn('proceeding_id', $chainIds)
            ->pluck('judge_id')
            ->push($root->judge_id) // 1-inst sudyasi doimo ta’sirlangan
            ->unique()
            ->values();

        Judges::query()
            ->whereIn('id', $affectedJudgeIds)
            ->get()
            ->each(fn (Judges $j) => self::recomputeJudge($j));
    }

    /** 🔹 Yordamchi: tepaga ko‘tarilib 1-instansiyani topish. */
    private static function findRootFirst(Proceeding $p): ?Proceeding
    {
        $curr = $p;
        while ($curr && $curr->type !== 'first' && $curr->parent_id) {
            $curr = Proceeding::find($curr->parent_id);
        }
        return ($curr && $curr->type === 'first') ? $curr : null;
    }

    /* 🔸 Agar oldin yuborgan bo‘lsam: rebuildAppliedPenalties($p) ham shu faylda bo‘lishi kerak.
       U penaltilarni (applied_penalties) qayta quradi, so‘ng self::recomputeAllAffected($p) ni chaqiradi. */
}
