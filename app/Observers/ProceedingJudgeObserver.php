<?php
namespace App\Observers;

use App\Models\ProceedingJudge;
use App\Services\RatingEngine;

class ProceedingJudgeObserver
{
    public function created(ProceedingJudge $pj) { app(RatingEngine::class)->rebuildAppliedPenalties($pj->proceeding); app(RatingEngine::class)->recomputeAllAffected($pj->proceeding); }
    public function updated(ProceedingJudge $pj) { app(RatingEngine::class)->rebuildAppliedPenalties($pj->proceeding); app(RatingEngine::class)->recomputeAllAffected($pj->proceeding); }
    public function deleted(ProceedingJudge $pj) { app(RatingEngine::class)->rebuildAppliedPenalties($pj->proceeding); app(RatingEngine::class)->recomputeAllAffected($pj->proceeding); }
}
