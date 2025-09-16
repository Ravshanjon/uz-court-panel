<?php
// app/Observers/ProceedingObserver.php
namespace App\Observers;

use App\Models\Proceeding;
use App\Services\RatingEngine;

class ProceedingObserver
{
    public function created(Proceeding $p): void
    {
        RatingEngine::rebuildAppliedPenalties($p);
    }

    public function updated(Proceeding $p): void
    {
        RatingEngine::rebuildAppliedPenalties($p);
    }

    public function deleted(Proceeding $p): void
    {
        // delete → shu proceeding uchun AP ni void qilamiz va quality'ni qayta hisoblaymiz
        \App\Models\AppliedPenalty::where('proceeding_id', $p->id)->update(['voided' => true]);
        RatingEngine::recomputeAllAffected($p);
    }

    public function restored(Proceeding $p): void
    {
        RatingEngine::rebuildAppliedPenalties($p);
    }
}
