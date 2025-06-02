<?php

namespace App\Filament\Resources\PrisionTypeResource\Pages;

use App\Filament\Resources\PrisionTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPrisionTypes extends ListRecords
{
    protected static string $resource = PrisionTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
