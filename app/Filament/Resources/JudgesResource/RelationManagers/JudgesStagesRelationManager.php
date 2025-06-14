<?php

namespace App\Filament\Resources\JudgesResource\RelationManagers;

use App\Models\Establishment;
use App\Models\Positions;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class JudgesStagesRelationManager extends RelationManager
{
    protected static string $relationship = 'judges_stages';
    protected static ?string $title = 'Меҳнат фаолиятини қўшинг';
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Placeholder::make('sudya_info')
                    ->label('')
                    ->columnSpanFull(),

                Checkbox::make('is_judge_stage')
                    ->label('Судьялик лавозими')
                    ->default(false)
                    ->reactive(),
                Grid::make(2)->schema([
                    TextInput::make('number_state')
                        ->hidden(fn($get) => !$get('is_judge_stage'))
                        ->label('Штат рақами')
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set, callable $get, $livewire) {
                            $inputStartDate = $get('start_date') ? \Carbon\Carbon::parse($get('start_date')) : now();
                            $inputEndDate = $get('end_date') ? \Carbon\Carbon::parse($get('end_date')) : now()->addYears(50);
                            $stage = Establishment::where('number_state', $state)->first();

                            if (!$stage) {
                                \Filament\Notifications\Notification::make()
                                    ->title('Shtat topilmadi')
                                    ->body('Bunday raqamga ega shtat topilmadi.')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            if (!$livewire->ownerRecord || !$livewire->ownerRecord->exists) {
                                \Filament\Notifications\Notification::make()
                                    ->title('Судья топилмади')
                                    ->body('Илтимос, аввал судьяни яратиб сақланг.')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            $busyJudge = \App\Models\Judges::where('establishment_id', $stage->id)
                                ->whereHas('judges_stages', function ($query) use ($inputStartDate, $inputEndDate) {
                                    $query->where('is_judge_stage', true)
                                        ->where(function ($q) use ($inputStartDate, $inputEndDate) {
                                            $q->where('start_date', '<=', $inputEndDate)
                                                ->where(function ($q2) use ($inputStartDate) {
                                                    $q2->whereNull('end_date')
                                                        ->orWhere('end_date', '>=', $inputStartDate);
                                                });
                                        });
                                })
                                ->when($livewire->ownerRecord, fn($query, $ownerRecord) => $query->where('id', '!=', $ownerRecord->id))
                                ->first();

                            if ($busyJudge) {
                                Notification::make()
                                    ->title('Bu shtat band')
                                    ->body("Bu shtat {$busyJudge->last_name} {$busyJudge->first_name} {$busyJudge->middle_name} tomonidan band qilingan.")
                                    ->danger()
                                    ->send();
                                return;
                            }

                            $set('court_specialty_id', $stage->court_specialty_id);
                            $set('court_types_id', $stage->court_type_id);
                            $set('provinces_districts_id', $stage->provinces_districts_id);
                            $set('district_type_id', $stage->district_type_id);
                            $set('region_id', $stage->region_id);
                            $set('court_name_id', $stage->court_name_id);
                            $set('position_id', $stage->position_id);
                            $set('position_category_id', $stage->position_category_id);
                            $set('document_type_id', $stage->document_type_id);
                            $set('working_place', \App\Models\Positions::find($stage->position_id)?->name);
                            $set('start_date', $stage->start_date);
                            $set('end_date', $stage->end_date);

                            $livewire->ownerRecord->update([
                                'establishment_id' => $stage->id,
                            ]);
                        }),
                    Select::make('region_id')
                        ->relationship('region', 'name')
                        ->label('Вилоят')
                        ->preload()
                        ->hidden(fn($get) => !$get('is_judge_stage'))
                        ->searchable(),
                    Grid::make(3)->schema([
                        Select::make('court_types_id')
                            ->relationship('court_type', 'name')
                            ->label('Суд тури')
                            ->preload()
                            ->hidden(fn($get) => !$get('is_judge_stage'))
                            ->searchable(),

                        Select::make('provinces_districts_id')
                            ->relationship('provinces_district', 'name')
                            ->label('Вилоят / Туман')
                            ->hidden(fn($get) => !$get('is_judge_stage'))
                            ->preload()
                            ->searchable(),

                        Select::make('district_type_id')
                            ->relationship('district_types', 'name')
                            ->label('Туман тури')
                            ->preload()
                            ->hidden(fn($get) => !$get('is_judge_stage'))
                            ->searchable(),
                    ]),


                    Select::make('court_name_id')
                        ->relationship('court_names', 'name')
                        ->label('Суд номи')
                        ->preload()
                        ->hidden(fn($get) => !$get('is_judge_stage'))
                        ->searchable(),

                    Select::make('court_specialty_id')
                        ->relationship('court_specialty', 'name')
                        ->label('Суд ихтисослиги')
                        ->preload()
                        ->hidden(fn($get) => !$get('is_judge_stage'))
                        ->searchable()
                        ->reactive(),

                    Select::make('position_id')
                        ->label('Лавозим номи')
                        ->relationship('position', 'name')
                        ->searchable()
                        ->reactive()
                        ->dehydrated(false)
                        ->hidden(fn($get) => !$get('is_judge_stage'))
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            if ($state && empty($get('working_place'))) {
                                $position = \App\Models\Positions::find($state);
                                if ($position?->name) {
                                    $set('working_place', $position->name);
                                }
                            }
                        }),

                    Select::make('position_category_id')
                        ->relationship('position_category', 'name')
                        ->label('Лавозим тоифаси')
                        ->preload()
                        ->hidden(fn($get) => !$get('is_judge_stage'))
                        ->searchable(),

                    Select::make('document_type_id')
                        ->label('Ҳужжат тури')
                        ->hidden(fn($get) => !$get('is_judge_stage'))
                        ->relationship('document_type', 'name'),


                    TextInput::make('document_number')
                        ->hidden(fn($get) => !$get('is_judge_stage'))
                        ->suffixIcon('heroicon-o-newspaper')
                        ->label('Хужжат рақами'),
                    Grid::make(3)->schema([

                        DatePicker::make('document')
                            ->icon('heroicon-o-calendar')
                            ->native(false)
                            ->hidden(fn($get) => !$get('is_judge_stage'))
                            ->label('Хужжат кучга кирган сана'),

                        DatePicker::make('start_date')
                            ->suffixIcon('heroicon-o-calendar')
                            ->required()
                            ->label('Стаж бошланган сана')
                            ->format('Y-m-d')
                            ->displayFormat('d.m.Y')
                            ->native(false)
                            ->closeOnDateSelection(false)
                            ->reactive()
                            ->extraAttributes([
                                'class' => 'allow-input',
                            ])
                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                static::calculateExperience($get, $set);
                            }),
                        DatePicker::make('end_date')
                            ->suffixIcon('heroicon-o-calendar')
                            ->required()
                            ->label('Стаж тугаган сана')
                            ->format('Y-m-d')
                            ->displayFormat('d.m.Y')
                            ->native(false)
                            ->disabled(fn($get) => $get('is_busy'))
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                static::calculateExperience($get, $set);
                            }),

                    ]),
                    TextInput::make('working_place')
                        ->reactive()
                        ->suffixIcon('heroicon-o-briefcase')
                        ->label('Ишлаган жойи'),

                    TextInput::make('counter')
                        ->label('Стаж (йил, ой, кун)')
                        ->readOnly()
                        ->default('')
                        ->live(),

                ]),
            ]);

    }
    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('start_date')->date('d.m.Y')->label('Фаолият бошланган сана'),
                Tables\Columns\TextColumn::make('end_date')->date('d.m.Y')->label('Фаолият тугаган сана'),
                Tables\Columns\TextColumn::make('working_place')
                    ->wrap()
                    ->label('Ишлаган жойлари'),
                Tables\Columns\TextColumn::make('counter')->label('Стажи'),         ])->selectable(false)
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Фаолият қўшиш')
                    ->icon('heroicon-s-plus')
                    ->closeModalByClickingAway(false)
                    ->disabled(fn ($livewire) => !$livewire->ownerRecord?->exists)
                    ->tooltip('Илтимос, аввал "Судья маълумотлари"ни сақланг.')
                    ->modalHeading(function ($livewire) {
                        $judge = $livewire->ownerRecord;

                        if (!$judge) {
                            return 'Маълумот топилмади';
                        }

                        $fullName = e($judge->middle_name . ' ' . $judge->first_name . ' ' . $judge->last_name);


                        $imageUrl = $judge->image
                            ? asset('storage/' . $judge->image)
                            : asset('image/default-avatar.png');

                        return new \Illuminate\Support\HtmlString(<<<HTML
            <div class="flex items-center space-x-4 mt-2 mb-2">
                <img src="{$imageUrl}" class="w-16 h-16 rounded-full border object-cover" alt="Sudya rasmi">
                <div>
                    <div class="text-lg font-semibold ml-2">{$fullName}</div>

                </div>
            </div>
        HTML);
                    })
                    ->modalSubmitActionLabel('Сақлаш')
                    ->stickyModalHeader()
                    ->stickyModalFooter()
                    ->color('gray')
                    ->size('sm')
                    ->hiddenLabel()
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label(' '),
                Tables\Actions\DeleteAction::make()->label(' '),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function calculateExperience(callable $get, callable $set): void
    {
        $startDate = $get('start_date');
        $endDate = $get('end_date');

        if (!$startDate) {
            $set('counter', null);
            return;
        }

        try {
            $start = Carbon::parse($startDate);
            $end = $endDate ? Carbon::parse($endDate) : now();

            // if end date is in the future or null → use now()
            if (!$endDate || $end->isFuture()) {
                $end = now();
            }

            if ($start->greaterThan($end)) {
                $set('counter', 'Хатолик: бошланиш санаси тугаш санасидан кейин.');
                return;
            }

            $diff = $start->diff($end);
            $years = $diff->y;
            $months = $diff->m;
            $days = $diff->d;

            $set('counter', "{$years} йил, {$months} ой, {$days} кун");

        } catch (\Exception $e) {
            $set('counter', 'Хато');
        }
    }


}


