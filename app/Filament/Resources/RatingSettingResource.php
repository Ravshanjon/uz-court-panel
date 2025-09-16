<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RatingSettingResource\Pages;
use App\Filament\Resources\RatingSettingResource\RelationManagers;
use App\Models\RatingSetting;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RatingSettingResource extends Resource
{
    protected static ?string $model = RatingSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';
    protected static ?string $navigationLabel = 'Рейтинг созламалари';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
                    Forms\Components\Grid::make()->schema([
                        TextInput::make('quality_score')
                            ->label('Суд қарорларининг сифати')
                            ->numeric()
                            ->required(),

                        TextInput::make('etiquette_score')
                            ->label('Судьянинг одоби')
                            ->numeric()
                            ->required(),

                        TextInput::make('ethics_score')
                            ->label('Судьянинг масъулияти')
                            ->numeric()
                            ->required(),

                        TextInput::make('foreign_language_bonus')
                            ->label('Чет тили учун қўшимча балл')
                            ->numeric(),

                        TextInput::make('adding_rating')
                            ->label('Қўшимча мезонлар')
                            ->numeric()
                    ])
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('quality_score')->label('Суд қарорларининг сифати'),
                Tables\Columns\TextColumn::make('etiquette_score')->label('Судьянинг одоби'),
                Tables\Columns\TextColumn::make('ethics_score')->label('Судьянинг масъулияти'),
                Tables\Columns\TextColumn::make('foreign_language_bonus')->label('Чет тили учун қўшимча балл'),
                Tables\Columns\TextColumn::make('adding_rating')->label('Қўшимча мезонлар'),
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
            'index' => Pages\ListRatingSettings::route('/'),
            'create' => Pages\CreateRatingSetting::route('/create'),
            'edit' => Pages\EditRatingSetting::route('/{record}/edit'),
        ];
    }
    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('super_admin');
    }
}
