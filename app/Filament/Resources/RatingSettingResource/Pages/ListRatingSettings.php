<?php

namespace App\Filament\Resources\RatingSettingResource\Pages;

use App\Filament\Resources\RatingSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRatingSettings extends ListRecords
{
    protected static string $resource = RatingSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
