<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\Judges;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $pluralLabel = 'Фойдаланувчи';

    protected static ?string $navigationGroup = 'Фойдаланувчилар';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function form(Form $form): Form
    {
        return $form

            ->schema([
                Forms\Components\Card::make()->schema([
                    Forms\Components\Grid::make(4)->schema([
                        TextInput::make('region_id')
                            ->label('Ҳудуд')
                            ->visible(fn($get) => $get('role') === 'Фойдаланувчи'),

                        TextInput::make('pinfl')
                            ->label('ПИНФЛ')
                            ->visible(fn($get) => $get('role') === 'Фойдаланувчи'),

                        TextInput::make('email')
                            ->label('Email')
                            ->visible(fn($get) => $get('role') === 'Фойдаланувчи'),

                        TextInput::make('number_code')
                            ->label('Kod')
                            ->live(debounce: 500)
                            ->afterStateUpdated(function ($state, callable $set) {
                                $judge = Judges::where('codes', $state)->first();
                                if ($judge) {
                                    $set('name', $judge->first_name);
                                    $set('pinfl', $judge->pinfl);
                                    $set('birth_date', $judge->birth_date);
                                } else {
                                    $set('name', null);
                                    $set('pinfl', null);
                                    $set('birth_date', null);
                                }
                            }),

                        Forms\Components\TextInput::make('name')
                            ->reactive()
                            ->unique(ignoreRecord: true),

                        TextInput::make('pinfl')
                            ->reactive(),

                        Forms\Components\TextInput::make('email'),

                        Forms\Components\TextInput::make('password')
                            ->dehydrateStateUsing(fn($state) => Hash::make($state))
                            ->password('password')
                            ->unique(ignoreRecord: true)
                            ->revealable(),

                        Forms\Components\Select::make('region')
                            ->relationship('region', 'name')
                            ->label('Район'),

                        Forms\Components\Select::make('roles')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable(),

//                        Forms\Components\Select::make('position')
//                            ->relationship('position_category', 'name')
//                            ->label('Лавозим номи')

                    ])
                ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('region_id.name'),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\BadgeColumn::make('roles.name'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
