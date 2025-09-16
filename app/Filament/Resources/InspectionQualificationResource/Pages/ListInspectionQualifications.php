<?php

namespace App\Filament\Resources\InspectionQualificationResource\Pages;

use App\Filament\Resources\InspectionQualificationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInspectionQualifications extends ListRecords
{
    protected static string $resource = InspectionQualificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
