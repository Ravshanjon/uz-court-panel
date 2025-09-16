<?php

namespace App\Filament\Resources\JudgesRegistryResource\Pages;

use App\Filament\Resources\JudgesRegistryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJudgesRegistries extends ListRecords
{
    protected static string $resource = JudgesRegistryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
