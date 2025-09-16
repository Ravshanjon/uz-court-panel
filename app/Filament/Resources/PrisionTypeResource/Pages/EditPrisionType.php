<?php

namespace App\Filament\Resources\PrisionTypeResource\Pages;

use App\Filament\Resources\PrisionTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPrisionType extends EditRecord
{
    protected static string $resource = PrisionTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
