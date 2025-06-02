<?php

namespace App\Filament\Resources\InstancesResource\Pages;

use App\Filament\Resources\InstancesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInstances extends ListRecords
{
    protected static string $resource = InstancesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
