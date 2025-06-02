<?php

namespace App\Filament\Resources\InspectionOfficeResource\Pages;

use App\Filament\Resources\InspectionOfficeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInspectionOffice extends EditRecord
{
    protected static string $resource = InspectionOfficeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
