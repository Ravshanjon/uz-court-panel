<?php

namespace App\Filament\Resources\JudgesRegistryResource\Pages;

use App\Filament\Resources\JudgesRegistryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJudgesRegistry extends EditRecord
{
    protected static string $resource = JudgesRegistryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
