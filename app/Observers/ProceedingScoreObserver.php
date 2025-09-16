<?php
namespace App\Observers;


use App\Models\ProceedingScore;
use App\Services\RatingEngine;

class ProceedingScoreObserver
{
    public function created(ProceedingScore $ps) { app(RatingEngine::class)->rebuildAppliedPenalties($ps->proceeding); app(RatingEngine::class)->recomputeAllAffected($ps->proceeding); }
    public function updated(ProceedingScore $ps) { app(RatingEngine::class)->rebuildAppliedPenalties($ps->proceeding); app(RatingEngine::class)->recomputeAllAffected($ps->proceeding); }
    public function deleted(ProceedingScore $ps) { app(RatingEngine::class)->rebuildAppliedPenalties($ps->proceeding); app(RatingEngine::class)->recomputeAllAffected($ps->proceeding); }
}
