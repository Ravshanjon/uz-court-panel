<?php

namespace App\Observers;

use App\Models\BonusJudges;
use Illuminate\Support\Facades\Log;

class BonusJudgeObserver
{
    /**
     * Handle the BonusJudges "created" event.
     */
    public function created(BonusJudges $bonusJudges): void
    {
        //
    }

    /**
     * Handle the BonusJudges "updated" event.
     */
    public function updated(BonusJudges $bonusJudges): void
    {
        //
    }

    /**
     * Handle the BonusJudges "deleted" event.
     */
    public function deleted(BonusJudges $bonusJudge): void
    {
        // Sudya va bonusni yuklaymiz
        $bonusJudge->loadMissing('bonus', 'judge'); // ✅ to‘g‘ri relationship nom

        $bonus = $bonusJudge->bonus;
        $judge = $bonusJudge->judge; // ✅ to‘g‘ri o‘zgaruvchi nom

        if ($bonus && $judge) {
            $score = $bonus->score ?? 0;

            if ($score > 0) {
                $judge->adding_rating = max(0, ($judge->adding_rating ?? 0) - $score);
                $judge->rating = $judge->adding_rating ?? 0;
                $judge->save();

                Log::info('✅ Ball QAYTARILDI', [
                    'score' => $score,
                    'judge_id' => $judge->id,
                    'adding_rating' => $judge->adding_rating,
                    'rating' => $judge->rating,
                ]);
            } else {
                Log::warning('⚠️ Bonus score yo‘q', ['bonus_id' => $bonus->id]);
            }
        } else {
            Log::warning('⚠️ Bonus yoki Judge topilmadi', [
                'bonus_id' => $bonusJudge->bonus_id,
                'judge_id' => $bonusJudge->judge_id,
            ]);
        }
    }
    /**
     * Handle the BonusJudges "restored" event.
     */
    public function restored(BonusJudges $bonusJudges): void
    {
        //
    }

    /**
     * Handle the BonusJudges "force deleted" event.
     */
    public function forceDeleted(BonusJudges $bonusJudges): void
    {
        //
    }
}
