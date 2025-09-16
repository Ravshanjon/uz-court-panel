<?php

namespace App\Filament\Resources\CandidatesResource\Pages;

use App\Filament\Resources\CandidatesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCandidates extends EditRecord
{
    protected static string $resource = CandidatesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
