<?php

namespace App\Filament\Resources\InspectionRegulationResource\Pages;

use App\Filament\Resources\InspectionRegulationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInspectionRegulations extends ListRecords
{
    protected static string $resource = InspectionRegulationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
