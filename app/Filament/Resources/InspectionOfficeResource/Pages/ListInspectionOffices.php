<?php

namespace App\Filament\Resources\InspectionOfficeResource\Pages;

use App\Filament\Resources\InspectionOfficeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInspectionOffices extends ListRecords
{
    protected static string $resource = InspectionOfficeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
