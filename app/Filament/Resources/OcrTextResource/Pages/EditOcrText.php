<?php

namespace App\Filament\Resources\OcrTextResource\Pages;

use App\Filament\Resources\OcrTextResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOcrText extends EditRecord
{
    protected static string $resource = OcrTextResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
