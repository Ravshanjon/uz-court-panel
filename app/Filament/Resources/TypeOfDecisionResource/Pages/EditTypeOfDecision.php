<?php

namespace App\Filament\Resources\TypeOfDecisionResource\Pages;

use App\Filament\Resources\TypeOfDecisionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTypeOfDecision extends EditRecord
{
    protected static string $resource = TypeOfDecisionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
