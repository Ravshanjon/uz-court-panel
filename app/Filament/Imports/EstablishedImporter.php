<?php

namespace App\Filament\Imports;

use App\Models\CourtName;
use App\Models\Establishment;
use App\Models\Positions;
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
            $numberState = intval($row['number_state'] ?? 0);

            $regionMapping = [
                'Фарғона вилояти' => 3,
                'Тошкент вилояти' => 14,
                'Тошкент шаҳри' => 13,
                'Самарқанд вилояти' => 10,
                'Қашқадарё вилояти' => 8,
                'Сурхондарё вилояти' => 12,
                'Жиззах вилояти' => 4,
                'Андижон вилояти' => 1,
                'Бухоро вилояти' => 2,
                'Қорақалпоғистон Республикаси' => 9,
                'Навоий вилояти' => 7,
                'Сирдарё вилояти' => 11,
                'Наманган вилояти' => 6,
                'Хоразм вилояти' => 5,
            ];

            $regionName = trim(mb_convert_kana($row['region_name'] ?? '', 's'));

            $regionId = $regionMapping[$regionName] ?? null;


            $regionId = null;
            $regionName = trim($row['region_name'] ?? $row['c'] ?? '');

            if (!empty($regionName)) {
                if (isset($regionMapping[$regionName])) {
                    $regionId = intval($regionMapping[$regionName]);
                } else {
                    $region = \App\Models\Regions::where('name', $regionName)->first();
                    $regionId = $region?->id;

                }
            }

            $courtNameId = null;
            $courtName = trim($row['court_name_id'] ?? '');
            if (!empty($courtName)) {
                $court = \App\Models\CourtName::where('name', $courtName)->first();
                $courtNameId = $court?->id;
                if (!$courtNameId) {
                    Log::warning("❗ Sud nomi topilmadi: [$courtName]", $row);
                }
            }

            $positionId = null;
            $positionName = trim($row['position_id'] ?? '');
            if (!empty($positionName)) {
                $position = \App\Models\Positions::where('name', $positionName)->first();
                $positionId = $position?->id;
                if (!$positionId) {
                    Log::warning("❗ Lavozim topilmadi: [$positionName]", $row);
                }
            }

            $courtTypeId = null;
            $courtType = trim($row['court_type'] ?? '');
            if (!empty($courtType)) {
                $type = \App\Models\CourtType::where('name', $courtType)->first();
                $courtTypeId = $type?->id;

            }

            if ($numberState && ($regionId !== null || $courtType === 'Олий суд')) {
                $data = [
                    'court_name_id'    => $courtNameId,
                    'court_type_id'    => $courtTypeId,
                    'position_id'      => $positionId,
                ];

                if ($regionId !== null) {
                    $data['region_id'] = $regionId;
                }

                \App\Models\Establishment::updateOrCreate(
                    ['number_state' => $numberState],
                    $data
                );
            }
        }
    }
}
