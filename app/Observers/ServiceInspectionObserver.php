<?php

namespace App\Observers;

use App\Models\Judges;
use App\Models\service_inspection;
use Illuminate\Support\Facades\Log;

class ServiceInspectionObserver
{
    /**
     * Handle the service_inspection "created" event.
     */
    public function created(service_inspection $service_inspection): void
    {
        if ($service_inspection->inspection_cases_id == 3 && $service_inspection->judge) {
            $amount = (int) $service_inspection->responsive_value;

            $service_inspection->judge->responsive = max(0, $service_inspection->judge->responsive - $amount);
            $service_inspection->judge->save();

            Log::info("Yangi Кенгаш inspection yaratildi. Judge ID: {$service_inspection->judge->id}, -{$amount} responsive.");
        }
    }

    /**
     * Handle the service_inspection "updated" event.
     */
    public function updated(service_inspection $service_inspection): void
    {
        //
    }

    /**
     * Handle the service_inspection "deleted" event.
     */
    public function deleted(service_inspection $service_inspection): void
    {

        if ($service_inspection->inspection_cases_id == 3 && $service_inspection->judge_id) {
            $judge = Judges::find($service_inspection->judge_id);
            if ($judge) {
                $judge->responsive += 15;
                $judge->save();
                Log::info("Кенгаш yozuvi Repeaterdan o‘chirildi, Judge ID {$judge->id} responsive +15 qilindi.");
            }
        }
    }

    /**
     * Handle the service_inspection "restored" event.
     */
    public function restored(service_inspection $service_inspection): void
    {
        //
    }

    /**
     * Handle the service_inspection "force deleted" event.
     */
    public function forceDeleted(service_inspection $service_inspection): void
    {
        //
    }
}
