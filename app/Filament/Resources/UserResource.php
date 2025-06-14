<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\Judges;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
                    Forms\Components\Grid::make(2)->schema([
                        Select::make('type_of_users_id')
                            ->relationship('typeOfUser', 'name') // ← bu method nomi modeldagi bilan aynan bir xil bo‘lishi kerak
                            ->label('Фойдалувчи тури'),


                        Select::make('position_categories_id')
                            ->relationship('position_categories', 'name')
                            ->label('Лавозим тоифаси')
                            ->preload()
                            ->searchable(),
                    ]),

                    Forms\Components\Grid::make(4)->schema([

                        TextInput::make('pinfl')
                            ->label('ПИНФЛ'),

                        TextInput::make('number_code')
                            ->label('Kod')
                            ->live(debounce: 500)
                            ->afterStateUpdated(function ($state, callable $set) {
                                $judge = Judges::where('codes', $state)->first();
                                if ($judge) {
                                    $set('name', $judge->middle_name . ' ' . $judge->first_name . ' ' . $judge->last_name);
                                    $set('pinfl', $judge->pinfl);
                                    $set('brith_date', $judge->birth_date);
                                    $set('judge_id', $judge->id);

                                } else {
                                    $set('name', null);
                                    $set('pinfl', null);
                                    $set('brith_date', null);
                                    $set('judge_id', null);

                                }
                            }),

                        Forms\Components\TextInput::make('name')
                            ->reactive()
                            ->unique(ignoreRecord: true),
                        Forms\Components\DatePicker::make('brith_date')->label('Туғилган санаси')
                            ->date('d.m.Y'),


                        TextInput::make('email')
                            ->label('Email')
                            ->default(function () {
                                do {
                                    $email = 'sudya' . rand(1000, 9999) . '@gmail.com';
                                } while (User::where('email', $email)->exists());

                                return $email;
                            })
                            ->required()
                            ->unique(ignoreRecord: true),


                        TextInput::make('password')
                            ->label('Parol')
                            ->password()
                            ->revealable()
                            ->dehydrateStateUsing(fn($state) => \Illuminate\Support\Facades\Hash::make($state)) // 🔐 Bcrypt
                            ->unique(ignoreRecord: true)
                            ->extraAttributes(['readonly' => true])
                            ->suffixAction(
                                \Filament\Forms\Components\Actions\Action::make('generate')
                                    ->label('Yaratish')
                                    ->action(fn($state, callable $set) => $set('password', Str::random(10))) // bu holatda xam 10 belgili random parol
                                    ->color('success')
                                    ->icon('heroicon-o-shield-check')
                            ),

                        Forms\Components\Select::make('region')
                            ->relationship('region', 'name')
                            ->label('Район'),

                        Forms\Components\Select::make('roles')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable(),

                    ])
                ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judge_id'),

                Tables\Columns\TextColumn::make('region.name')
                    ->label('Вилоят')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('ФИШ')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('roles.name')
                    ->label('Роллар'),
            ]) ->groups([
                Group::make('region.name')->label('Bилоятлар')
                    ->collapsible(),
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
            'region-wise' => Pages\RegionWiseJudges::route('/regions'),

        ];
    }
}
