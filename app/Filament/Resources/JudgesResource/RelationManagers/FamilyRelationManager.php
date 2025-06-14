<?php

namespace App\Filament\Resources\JudgesResource\RelationManagers;


use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FamilyRelationManager extends RelationManager
{
    protected static string $relationship = 'family';

    public function getTableHeading(): string
    {
        return 'Оилавий ҳолатини қўшинг'; // Custom heading
    }
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('parents_id')
                    ->relationship('parents', 'name')
                    ->label('Қариндошлиги')->required(),
                TextInput::make('first_name')->label('Исми')->required(),
                TextInput::make('last_name')->label('Фамилияси')->required(),
                TextInput::make('middle_name')->label('Отасининг исми')->required(),
                DatePicker::make('brith_date')->required()
                    ->icon('heroicon-o-calendar')
                    ->native(false)
                    ->label('Туғилган санаси')->required(),
                Forms\Components\Select::make('regions_id')
                    ->relationship('regions', 'name')
                    ->label('Туғилган вилояти')->required(),
                TextInput::make('live_place')->label('Яшаш манзили')->required(),
                TextInput::make('working')->label('Иш жойи'),
                TextInput::make('passport')->label('Паспорт маълумотлари')->required(),
                TextInput::make('pinfl')->label('ЖШИР')->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Family')->pluralModelLabel('')
            ->columns([
                Tables\Columns\TextColumn::make('parents.name')->label('Қариндошлиги'),
                Tables\Columns\TextColumn::make('first_name')->label('Исми'),
                Tables\Columns\TextColumn::make('last_name')->label('Фамилияси'),
                Tables\Columns\TextColumn::make('middle_name')->label('Отасининг исми'),
                Tables\Columns\TextColumn::make('brith_date')
                    ->date('d.m.Y')
                    ->label('Туғилган санаси'),
                Tables\Columns\TextColumn::make('passport')->label('Паспорти'),
                Tables\Columns\TextColumn::make('pinfl')->label('ЖШИР'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('+')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
