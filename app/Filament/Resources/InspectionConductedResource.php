<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InspectionConductedResource\Pages;
use App\Filament\Resources\InspectionConductedResource\RelationManagers;
use App\Models\inspection_conducted;
use App\Models\InspectionConducted;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InspectionConductedResource extends Resource
{
    protected static ?string $model = inspection_conducted::class;
    protected static ?string $navigationLabel = "Ташаббус";
    protected static ?string $modelLabel = "Ташаббус";

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Интизомий созламалар';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInspectionConducteds::route('/'),
            'create' => Pages\CreateInspectionConducted::route('/create'),
            'edit' => Pages\EditInspectionConducted::route('/{record}/edit'),
        ];
    }
}
