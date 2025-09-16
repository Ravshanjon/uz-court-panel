<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MistakeResource\Pages;
use App\Filament\Resources\MistakeResource\RelationManagers;
use App\Models\Mistake;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Laravel\Pulse\Livewire\Card;

class MistakeResource extends Resource
{
    protected static ?string $model = Mistake::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Хато ва камчиликлар';

    protected static ?string $navigationGroup = 'Интизомий созламалар';
    protected static ?string $pluralModelLabel = 'Хато ва камчиликлар';

    public static function getBreadcrumb(): string
    {
        return 'Хато ва камчиликлар';
    }
    public static function getPluralModelLabel(): string
    {
        return 'Камчиликлар';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('name')->label('Номланиши'),
                        Forms\Components\Select::make('type')
                            ->options([
                                'Маьсулияти' => 'Маъсулияти',
                                'Одоби' => 'Одоби'
                            ])
                            ->label('Тури'),
                    ])
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')->rowIndex()->label('№'),
                Tables\Columns\TextColumn::make('name')->label('Номланиши'),
                Tables\Columns\TextColumn::make('type')->label('Тури'),
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
            'index' => Pages\ListMistakes::route('/'),
            'create' => Pages\CreateMistake::route('/create'),
            'edit' => Pages\EditMistake::route('/{record}/edit'),
        ];
    }
    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('super_admin');
    }
}
