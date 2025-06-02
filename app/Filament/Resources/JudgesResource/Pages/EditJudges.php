<?php

namespace App\Filament\Resources\JudgesResource\Pages;

use App\Filament\Resources\JudgesResource;
use Filament\Actions;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\EditRecord;
use Filament\Tables\Table;

class EditJudges extends EditRecord
{
    protected static string $resource = JudgesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    public function getTitle(): string
    {
        return 'Таҳрирлаш';  // This will hide the title
    }
    public static function table(Table $table): Table
    {
        return $table;
    }


}
