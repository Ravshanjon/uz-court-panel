<?php

namespace App\Filament\Resources\InstancesResource\Pages;

use App\Filament\Resources\InstancesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInstances extends EditRecord
{
    protected static string $resource = InstancesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
