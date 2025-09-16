<?php

namespace App\Services;

use App\Models\Family;
use App\Models\Parents;
use Filament\Resources\RelationManagers\RelationManager;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\IOFactory;

use thiagoalessio\TesseractOCR\TesseractOCR;

class OCRFamilyService
{
    public static function extractFromDocx(string $relativePath, ?string $judgeId = null): array
    {
        $fullPath = storage_path('app/public/' . $relativePath);

        if (!file_exists($fullPath)) {
            logger("❌ Fayl topilmadi: $fullPath");
            return ['error' => 'Fayl topilmadi'];
        }

        try {
            $phpWord = IOFactory::load($fullPath);
            $structuredRows = [];

            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    if (method_exists($element, 'getRows')) {
                        foreach ($element->getRows() as $row) {
                            $cells = $row->getCells();
                            $values = [];

                            foreach ($cells as $cell) {
                                $text = '';
                                foreach ($cell->getElements() as $cellElement) {
                                    if (method_exists($cellElement, 'getText')) {
                                        $text .= $cellElement->getText();
                                    }
                                }
                                $values[] = trim($text);
                            }

                            if (count($values) >= 5) {
                                $structuredRows[] = [
                                    'relationship'   => $values[0],
                                    'name'           => $values[1],
                                    'birth_place'    => $values[2],
                                    'working_place'  => $values[3],
                                    'live_place'     => $values[4],
                                ];
                            }
                        }
                    }
                }
            }

            logger('🧾 Worddan ajratilgan rows:', $structuredRows);
            if ($judgeId) {
                foreach ($structuredRows as $row) {
                    $relationship = trim(mb_strtolower($row['relationship']));
                    $parent = Parents::whereRaw('LOWER(name) = ?', [$relationship])->first();

                    if (! $parent) {
                        logger("⚠️ Qarindoshlik topilmadi: " . $relationship);
                        continue;
                    }

                    $birthDate = null;
                    if (preg_match('/\d{4}/', $row['birth_place'], $match)) {
                        $birthDate = $match[0] . '-01-01';
                    }

                    Family::create([
                        'judge_id'       => $judgeId,
                        'parents_id'     => $parent->id,
                        'name'           => $row['name'],
                        'birth_date'     => $birthDate,
                        'birth_place'    => $row['birth_place'],
                        'working_place'  => $row['working_place'],
                        'live_place'     => $row['live_place'],
                    ]);
                }
            }

            return $structuredRows;

        } catch (\Throwable $e) {
            logger("❌ Xatolik: " . $e->getMessage());
            return ['error' => 'DOCX o‘qishda xato: ' . $e->getMessage()];
        }
    }

}
