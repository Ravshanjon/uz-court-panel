<?php

namespace App\Support;

use App\Models\Judges;
use Illuminate\Support\Facades\DB;

class JudgeScore
{
    public static function adjust(?string $judgeId, float $amount, string $dir = 'down'): void
    {
        if (!$judgeId || $amount <= 0) return;

        DB::transaction(function () use ($judgeId, $amount, $dir) {
            $j = Judges::whereKey($judgeId)->lockForUpdate()->first();
            if (!$j) return;

            $before = (float) $j->quality_score;
            $after  = $dir === 'down' ? max(0, $before - $amount) : $before + $amount;

            $j->quality_score = round($after, 2);
            $j->saveQuietly();
        });
    }
}
