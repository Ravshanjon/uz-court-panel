<?php

namespace App\Filament\Resources\InspectionAdultsResource\Pages;

use App\Filament\Resources\InspectionAdultsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInspectionAdults extends ListRecords
{
    protected static string $resource = InspectionAdultsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
