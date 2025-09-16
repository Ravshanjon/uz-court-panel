<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TypeOfDecisionResource\Pages;
use App\Filament\Resources\TypeOfDecisionResource\RelationManagers;
use App\Models\TypeOfDecision;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TypeOfDecisionResource extends Resource
{
    protected static ?string $model = TypeOfDecision::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    protected static ?string $modelLabel = 'Қарор тури';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Select::make('job_category_id')
                            ->label('Иш категорияси')
                            ->relationship('jobCategory', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('name')->label('Номланиши'),

                    ])
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginated([25])
            ->columns([
                Tables\Columns\TextColumn::make('index')->rowIndex()->label('№'),
                Tables\Columns\TextColumn::make('name')->label('Номланиши'),
                TextColumn::make('jobCategory.name')
                    ->label('Иш тоифаси')

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
            'index' => Pages\ListTypeOfDecisions::route('/'),
            'create' => Pages\CreateTypeOfDecision::route('/create'),
            'edit' => Pages\EditTypeOfDecision::route('/{record}/edit'),
        ];
    }
    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('super_admin');
    }
}
