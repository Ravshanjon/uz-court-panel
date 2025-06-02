<?php

namespace App\Filament\Resources\InspectionConductedResource\Pages;

use App\Filament\Resources\InspectionConductedResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInspectionConducteds extends ListRecords
{
    protected static string $resource = InspectionConductedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
