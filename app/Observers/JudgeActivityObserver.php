<?php

namespace App\Observers;

use App\Models\JudgeActivity;
use App\Models\Judges;

class JudgeActivityObserver
{
    /**
     * Handle the JudgeActivity "created" event.
     */

    public function created(JudgeActivity $judgeActivity): void
    {
        //
    }

    /**
     * Handle the JudgeActivity "updated" event.
     */
    public function updated(JudgeActivity $judgeActivity): void
    {
        //
    }

    /**
     * Handle the JudgeActivity "deleted" event.
     */

    /**
     * Handle the JudgeActivity "restored" event.
     */
    public function restored(JudgeActivity $judgeActivity): void
    {
        //
    }

    /**
     * Handle the JudgeActivity "force deleted" event.
     */
    public function forceDeleted(JudgeActivity $judgeActivity): void
    {
        //
    }
}
