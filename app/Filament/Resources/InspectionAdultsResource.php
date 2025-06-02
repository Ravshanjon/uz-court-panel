<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InspectionAdultsResource\Pages;
use App\Filament\Resources\InspectionAdultsResource\RelationManagers;
use App\Models\inspection_adult;
use App\Models\InspectionAdults;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InspectionAdultsResource extends Resource
{
    protected static ?string $model = inspection_adult::class;
    protected static ?string $navigationLabel = "Хизмат текширувини ўтказишга асос";
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
            'index' => Pages\ListInspectionAdults::route('/'),
            'create' => Pages\CreateInspectionAdults::route('/create'),
            'edit' => Pages\EditInspectionAdults::route('/{record}/edit'),
        ];
    }
}
