<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InspectionOfficeResource\Pages;
use App\Filament\Resources\InspectionOfficeResource\RelationManagers;
use App\Models\inspection_office;
use App\Models\InspectionOffice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Laravel\Pulse\Livewire\Card;

class InspectionOfficeResource extends Resource
{
    protected static ?string $model = inspection_office::class;
    protected static ?string $navigationLabel = "Текшируви ўтказган идора";
    protected static ?string $modelLabel = "Текшируви ўтказган идора";

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Интизомий созламалар';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
                    Forms\Components\Grid::make()->schema([
                        Forms\Components\TextInput::make('name')
                    ])
                ])

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
            'index' => Pages\ListInspectionOffices::route('/'),
            'create' => Pages\CreateInspectionOffice::route('/create'),
            'edit' => Pages\EditInspectionOffice::route('/{record}/edit'),
        ];
    }
}
