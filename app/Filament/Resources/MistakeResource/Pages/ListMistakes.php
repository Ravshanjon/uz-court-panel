<?php

namespace App\Filament\Resources\MistakeResource\Pages;

use App\Filament\Resources\MistakeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMistakes extends ListRecords
{
    protected static string $resource = MistakeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
