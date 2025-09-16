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

        $positionCategoryMap = [
            'Суд раиси' => 1,
            'Раис биринчи ўринбосари' => 2,
            'Раис ўринбосари' => 3,
            'Судья' => 4,
        ];

        foreach ($this->chunk as $row) {
            try {
                $numberState = intval($row['number_state'] ?? 0);
                if (!$numberState) continue;

                // 1. Region aniqlash
                $regionName = trim($row['region_name'] ?? $row['c'] ?? '');
                $regionId = $regionMapping[$regionName]
                    ?? Regions::where('name', $regionName)->value('id');

                if (!$regionId && $regionName) {
                    Log::warning("❗ Region topilmadi: [$regionName]", $row);
                }

                // 2. Sud turi
                $courtType = trim($row['court_type'] ?? '');
                $courtTypeId = \App\Models\CourtType::where('name', $courtType)->value('id');

                // 3. Sud nomi
                $courtName = trim($row['court_name_id'] ?? '');
                $courtNameId = CourtName::where('name', $courtName)->value('id');
                if (!$courtNameId && $courtName) {
                    Log::warning("❗ Sud nomi topilmadi: [$courtName]", $row);
                }

                // 4. Lavozim (positions)
                $positionName = trim($row['positions'] ?? '');
                $positionId = Positions::where('name', $positionName)->value('id');
                if (!$positionId && $positionName) {
                    Log::warning("❗ Lavozim topilmadi: [$positionName]", $row);
                }

                // 5. Lavozim тоифаси (kategoriya)
                $positionCategoryName = trim($row['position_id'] ?? '');
                $positionCategoryId = $positionCategoryMap[$positionCategoryName] ?? null;
                if (!$positionCategoryId && $positionCategoryName) {
                    Log::warning("❗ Lavozim тоифаси нотўғри: [$positionCategoryName]", $row);
                }

                // 6. Хужжат тури
                $documentTypeName = trim($row['Хужжат тури'] ?? '');
                $documentTypeId = \App\Models\DocumentType::where('name', $documentTypeName)->value('id');

                if (!$documentTypeId && $documentTypeName) {
                    Log::warning("❗ Хужжат тури топилмади: [$documentTypeName]", $row);
                }
                $courtSpecialtyName = trim($row['court_specialty'] ?? ''); // Excel faylingizdagi ustun nomi bo‘yicha

                $courtSpecialtyId = null;

                if (!empty($courtSpecialtyName)) {
                    $specialty = \App\Models\CourtSpeciality::where('name', $courtSpecialtyName)->first();
                    $courtSpecialtyId = $specialty?->id;

                    if (!$courtSpecialtyId) {
                        Log::warning("❗ Суд ихтисослиги топилмади: [$courtSpecialtyName]", $row);
                    }
                }

                // 7. Ma'lumotlar saqlash
                $data = [
                    'region_id'            => $regionId,
                    'court_type_id'        => $courtTypeId,
                    'court_name_id'        => $courtNameId,
                    'position_id'          => $positionId,
                    'position_category_id' => $positionCategoryId,
                    'document_type_id'     => $documentTypeId,
                    'court_specialty_id'   => $courtSpecialtyId,
                ];

                Establishment::updateOrCreate(
                    ['number_state' => $numberState],
                    array_filter($data, fn($v) => $v !== null)
                );

                Log::info("✅ Saqlandi: number_state={$numberState}, region={$regionName} (ID={$regionId})");

            } catch (\Throwable $e) {
                Log::error("❌ Import xatoligi: " . $e->getMessage(), [
                    'row' => $row,
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        }
    }
}
