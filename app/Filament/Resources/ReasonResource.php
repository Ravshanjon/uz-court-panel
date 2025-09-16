<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReasonResource\Pages;
use App\Filament\Resources\ReasonResource\RelationManagers;
use App\Models\Reason;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReasonResource extends Resource
{
    protected static ?string $model = Reason::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static function getPluralModelLabel(): string
    {
        return 'Ўзгартириш-ёки бекор қилиш асослари ва сабаблари';
    }
    public static function getModelLabel(): string
    {
        return 'Aсос ёки сабаб';
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Select::make('type_of_decision_id')
                            ->label('Суд қарори тури')
                            ->relationship('typeOfDecision', 'name') // ← modeldagi aloqa nomi
                            ->getOptionLabelFromRecordUsing(
                                fn ($record) => $record->name . ' (' . ($record->jobCategory?->name ?? '—').')'
                            ),

                        Select::make('instances_id')->label('Cуд қарори')
                            ->relationship('instances', 'name'),
                        ])->label('Юқори интанцияда кўрилиш натижаси'),
                        Forms\Components\TextInput::make('name')->label('Номланиши'),
                        Forms\Components\TextInput::make('score')->label('Бахо')
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('№'),
                Tables\Columns\TextColumn::make('TypeOfDecision.name')->label('Суд қарори тури'),
                Tables\Columns\TextColumn::make('instances.name')->label('Юқори интанцияда кўрилиш натижаси'),
                Tables\Columns\TextColumn::make('name')->label('Номланиши'),
                Tables\Columns\TextColumn::make('score')->label('Бахо'),
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
            'index' => Pages\ListReasons::route('/'),
//            'create' => Pages\CreateReason::route('/create'),
            'edit' => Pages\EditReason::route('/{record}/edit'),
        ];
    }
    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('super_admin');
    }
}
