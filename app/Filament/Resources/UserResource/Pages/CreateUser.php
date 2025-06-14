<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\Judges;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
    protected function afterCreate(): void
    {
        $user = $this->record;

        $judge = Judges::where('pinfl', $user->pinfl)->orWhere('codes', $user->number_code)->first();

        if ($judge) {
            $user->judge_id = $judge->id;
            $user->save();
        }
    }
}
