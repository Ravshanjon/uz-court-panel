<?php

namespace App\Filament\Resources\ReasonResource\Pages;

use App\Filament\Resources\ReasonResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReason extends EditRecord
{
    protected static string $resource = ReasonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
