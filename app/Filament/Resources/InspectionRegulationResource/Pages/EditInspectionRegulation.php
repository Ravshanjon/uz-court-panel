<?php

namespace App\Filament\Resources\InspectionRegulationResource\Pages;

use App\Filament\Resources\InspectionRegulationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInspectionRegulation extends EditRecord
{
    protected static string $resource = InspectionRegulationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
