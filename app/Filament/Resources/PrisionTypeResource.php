<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PrisionTypeResource\Pages;
use App\Filament\Resources\PrisionTypeResource\RelationManagers;
use App\Models\Prision_Type;
use App\Models\PrisionType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Laravel\Pulse\Livewire\Card;

class PrisionTypeResource extends Resource
{
    protected static ?string $model = Prision_Type::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $modelLabel = 'Жазо турлари';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                    Forms\Components\Card::make()->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('name')->label('Номи'),
                        Forms\Components\TextInput::make('score')->label('Олиб ташланадиган баллар')->numeric()
                    ])
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Номи'),
                Tables\Columns\TextColumn::make('score')->label('Олиб ташланадиган баллар')->numeric()
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
            'index' => Pages\ListPrisionTypes::route('/'),
            'create' => Pages\CreatePrisionType::route('/create'),
            'edit' => Pages\EditPrisionType::route('/{record}/edit'),
        ];
    }
}
