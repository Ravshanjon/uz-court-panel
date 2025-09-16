<?php
// app/Services/JudgeScoreService.php
namespace App\Services;

use App\Models\Judges;

class JudgeScoreService
{
    public function adjustQuality(string $judgeId, float $delta): void
    {
        $j = Judges::lockForUpdate()->findOrFail($judgeId);
        $before = (float)($j->quality_score ?? 50);
        $after  = round($before + $delta, 2);
        if ($after < 0) $after = 0;
        $j->forceFill(['quality_score' => $after])->saveQuietly();
    }
}
