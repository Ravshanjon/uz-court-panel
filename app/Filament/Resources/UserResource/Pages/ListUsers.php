<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\Judges;
use App\Models\User;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Hash;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
        ];
    }
//    protected function getHeaderActions(): array
//    {
//        return [
//            Action::make('create')->label('Янги фойдаланувчи яратиш')
//                ->form([
//
//                    Grid::make(2)
//                        ->schema([
//                            Grid::make(3)->schema([
//                                Select::make('role')
//                                    ->options([
//                                        'Фойдаланувчи' => 'Фойдаланувчи',
//                                        'Кузатувчи' => 'Кузатувчи',
//                                    ])
//                                    ->required()
//                                    ->reactive()
//                                    ->default('Кузатувчи')
//                                    ->label('Турини танланг')
//                                    ->extraAttributes(['class' => 'text-center']),
//
//                                Select::make('status')
//                                    ->label('Кузатувчи тури')
//                                    ->options([
//                                        'Олий суд раиси',
//                                        'Олий суд раиси ўринбосари',
//                                        'Вилоят раиси',
//                                        'Вилоят раиси ўринбосари',
//                                        'Судья',
//                                    ])
//                                    ->visible(fn($get) => $get('role') === 'Кузатувчи'),
//
//                                Toggle::make('status')
//                                    ->default(true)
//                                    ->label('Фойдаланувчи актив ҳолатда')
//                                    ->inline(false)
//                            ])
//                        ])->extraAttributes(['class' => 'items-center']),
//
//
//
//                    Fieldset::make('data')->schema([
//                        TextInput::make('pinfl')->label('ЖШИР')
//                            ->numeric()
//                            ->suffixIcon('heroicon-o-credit-card')
//                            ->unique(ignoreRecord: true)
//                            ->reactive(),
//
//                        TextInput::make('pasport')->label('Паспорт')
//                            ->maxWidth(9)
//                            ->minLength(9)
//                            ->unique(ignoreRecord: true)
//                            ->suffixIcon('heroicon-o-credit-card')
//                            ->reactive()
//                    ])
//                        ->visible(fn($get) => $get('role') === 'Фойдаланувчи')
//                        ->label('Пасспорт маълумоти'),
//
//                    Fieldset::make('Шаҳсий маълумотлар')->schema([
//                        TextInput::make('name')
//                            ->label('Исми')
//                            ->reactive(),
//                        TextInput::make('name')
//                            ->label('Фамилияси')
//                            ->reactive(),
//
//                        TextInput::make('name')
//                            ->label('Отасининг исми')
//                            ->reactive(),
//
//                        TextInput::make('brith_day')
//                            ->label('Туғилган санаси')
//                            ->reactive(),
//                    ])
//                        ->visible(fn($get) => $get('role') === 'Фойдаланувчи')
//                        ->label('Шаҳсий маълумотлари'),
//
//                    Fieldset::make()->schema([
//                        TextInput::make('region_id')
//                            ->label('Ҳудуд')
//                            ->visible(fn($get) => $get('role') === 'Фойдаланувчи'),
//
//                        TextInput::make('email')
//                            ->label('Email')
//                            ->visible(fn($get) => $get('role') === 'Фойдаланувчи'),
//
//                        TextInput::make('number_code')
//                            ->label('Kod')
//                            ->live(debounce: 500)
//                            ->afterStateUpdated(function ($state, callable $set) {
//                                $judge = Judges::where('codes', $state)->first();
//
//                                if ($judge) {
//                                    $set('name', $judge->first_name);
//                                    $set('pinfl', $judge->pinfl);
//                                    $set('birth_date', $judge->birth_date);
//                                } else {
//                                    $set('name', null);
//                                    $set('pinfl', null);
//                                    $set('birth_date', null);
//                                }
//                            }),
//
//
//                        TextInput::make('email'),
//
//
//                        TextInput::make('password')
//                            ->dehydrateStateUsing(fn($state) => Hash::make($state))
//                            ->password('password')
//                            ->unique(ignoreRecord: true)
//                            ->revealable(),
//
//                        Select::make('region')
//                            ->relationship('region', 'name')
//                            ->label('Район'),
//
//                        Select::make('roles')
//                            ->relationship('roles', 'name')
//                            ->multiple()
//                            ->preload()
//                            ->searchable(),
//                    ])
//                ])
//        ];
//    }
}
