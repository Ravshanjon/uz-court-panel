<?php

namespace App\Filament\Resources\InspectionCasesResource\Pages;

use App\Filament\Resources\InspectionCasesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInspectionCases extends EditRecord
{
    protected static string $resource = InspectionCasesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
