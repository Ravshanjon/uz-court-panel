<?php

namespace App\Filament\Resources\JudgesResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Notifications\Notification;

class ProceedingsRelationManager extends RelationManager
{
    protected static string $relationship = 'proceedings';

    public function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('appeals')
            ->columns([])
            ->filters([


            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->modalHeading('Биринчи инстанция')
                    ->label('+'),
            ])
            ->actions([
                // row actions
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
