<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InspectionRegulationResource\Pages;
use App\Filament\Resources\InspectionRegulationResource\RelationManagers;
use App\Models\inspection_regulation;
use App\Models\InspectionRegulation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InspectionRegulationResource extends Resource
{
    protected static ?string $model = inspection_regulation::class;
    protected static ?string $navigationLabel = "Аниқланган хато ва камчиликлар";
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
                Tables\Columns\TextColumn::make('name')->limit(80)
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
            'index' => Pages\ListInspectionRegulations::route('/'),
            'create' => Pages\CreateInspectionRegulation::route('/create'),
            'edit' => Pages\EditInspectionRegulation::route('/{record}/edit'),
        ];
    }

}
