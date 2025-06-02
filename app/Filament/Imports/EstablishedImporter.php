<?php

namespace App\Filament\Imports;

use App\Models\CourtName;
use App\Models\Establishment;
use App\Models\Regions;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class EstablishedImporter implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public $chunk, public $file)
    {
    }

    public function handle(): void
    {
        $courtTypeMapping = [
            'Олий суд' => 1,
            'Умумюрисдикция суди' => 2,
            'Маъмурий суд' => 3,
            'Ҳарбий суд' => 4,
        ];

        foreach ($this->chunk as $row) {
            // number_state
            $numberState = intval($row['number_state']);

            // region_id (null bo‘lishi mumkin)
            $regionId = null;
            if (!empty(trim($row['region_name'] ?? ''))) {
                $regionName = trim($row['region_name']);
                $region = \App\Models\Regions::where('name', $regionName)->first();
                $regionId = $region?->id;

                if (!$regionId) {
                    Log::warning("❗ Region topilmadi: [$regionName]", $row);
                }
            }

            // court_name_id (majburiy)
            $courtName = trim($row['court_name_id'] ?? '');
            $court = \App\Models\CourtName::where('name', $courtName)->first();

            if (!$court) {
                Log::warning("❗ Court topilmadi: [$courtName]", $row);
                continue;
            }

            // court_type_id (majburiy)
            $courtTypeRaw = trim($row['court_type'] ?? '');
            $courtTypeId = $courtTypeMapping[$courtTypeRaw] ?? null;

            if (!$courtTypeId) {
                Log::warning("❗ Court turi mos emas: [$courtTypeRaw]", $row);
                continue;
            }

            // position_id (majburiy)
            $positionName = trim($row['position_id'] ?? '');
            $position = \App\Models\Positions::where('name', $positionName)->first();

            if (!$position) {
                Log::warning("❗ Lavozim topilmadi: [$positionName]", $row);
                continue;
            }

            // create or update
            \App\Models\Establishment::updateOrCreate(
                [
                    'number_state'   => $numberState,
                    'court_name_id'  => $court->id,
                    'court_type_id'  => $courtTypeId,
                    'region_id'      => $regionId,
                ],
                [
                    'position_id'    => $position->id,
                    'position_type'  => null, // agar kerak bo‘lsa o‘zgartiring
                ]
            );
        }
    }
}

