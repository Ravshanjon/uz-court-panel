<?php

namespace App\Filament\Resources\InspectionCasesResource\Pages;

use App\Filament\Resources\InspectionCasesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInspectionCases extends ListRecords
{
    protected static string $resource = InspectionCasesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
