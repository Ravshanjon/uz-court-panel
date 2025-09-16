<?php

namespace App\Filament\Resources\RatingSettingResource\Pages;

use App\Filament\Resources\RatingSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRatingSetting extends EditRecord
{
    protected static string $resource = RatingSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
