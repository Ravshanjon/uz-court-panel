<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InspectionCasesResource\Pages;
use App\Filament\Resources\InspectionCasesResource\RelationManagers;
use App\Models\inspection_cases;
use App\Models\InspectionCases;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InspectionCasesResource extends Resource
{
    protected static ?string $model = inspection_cases::class;

    protected static ?string $navigationLabel = "Тасдиғини топдими";
    protected static ?string $navigationGroup = 'Интизомий созламалар';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
            'index' => Pages\ListInspectionCases::route('/'),
            'create' => Pages\CreateInspectionCases::route('/create'),
            'edit' => Pages\EditInspectionCases::route('/{record}/edit'),
        ];
    }
}
