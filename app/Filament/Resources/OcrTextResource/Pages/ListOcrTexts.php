<?php

namespace App\Filament\Resources\OcrTextResource\Pages;

use App\Filament\Resources\OcrTextResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\Action;
use Illuminate\Support\HtmlString;

class ListOcrTexts extends ListRecords
{
    protected static string $resource = OcrTextResource::class;

    protected function getTableRecordActions(): array
    {
        return [
            Action::make('viewOcrPages')
                ->label('OCR матнларни кўриш')
                ->icon('heroicon-o-eye')
                ->modalHeading('OCR матн – саҳифалар бўйича')
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Ёпиш')
                ->modalContent(function ($record) {
                    if (!is_array($record->page_texts)) {
                        return new HtmlString('<p>Маълумот йўқ</p>');
                    }

                    return new HtmlString(
                        collect($record->page_texts)->map(function ($item, $index) {
                            $page = $index + 1;
                            $text = e($item['text'] ?? '-');
                            return "<div class='mb-4'>
                                <strong>Саҳифа {$page}</strong>
                                <div class='text-sm whitespace-pre-line border p-2 rounded bg-gray-50 mt-1'>{$text}</div>
                            </div>";
                        })->implode('')
                    );
                }),
        ];
    }
}
