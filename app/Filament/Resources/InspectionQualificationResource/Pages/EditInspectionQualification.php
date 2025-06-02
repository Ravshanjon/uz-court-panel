<?php

namespace App\Filament\Resources\InspectionQualificationResource\Pages;

use App\Filament\Resources\InspectionQualificationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInspectionQualification extends EditRecord
{
    protected static string $resource = InspectionQualificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
