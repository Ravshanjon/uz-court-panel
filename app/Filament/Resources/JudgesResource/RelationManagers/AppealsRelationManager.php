<?php

namespace App\Filament\Resources\JudgesResource\RelationManagers;

use App\Models\Establishment;
use App\Models\Instance;
use App\Models\Judges;
use App\Models\Reason;
use App\Models\TypeOfDecision;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Log;

class AppealsRelationManager extends RelationManager
{

    protected static string $relationship = 'appeals';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(4)->schema([
                    DatePicker::make('appeal_date')
                        ->label('Кўриб чиқилган сана')
                        ->icon('heroicon-o-calendar')
                        ->displayFormat('d.m.Y')
                        ->required(fn(string $context) => $context === 'edit')
                        ->native(false)
                        ->columnSpan(2),

                    TextInput::make('judge_full_name')
                        ->label('Судья')
                        ->disabled()
                        ->dehydrated(false)
                        ->default(function () {
                            $judge = \App\Models\Judges::with('judges_stages')->find(optional($this->getOwnerRecord())->id);
                            return implode(' ', array_filter([
                                $judge?->middle_name,
                                $judge?->first_name,
                                $judge?->last_name,
                            ]));
                        })
                        ->columnSpan(2),

                    Select::make('court_names_id')
                        ->label('Суд номи')
                        ->options(\App\Models\CourtName::pluck('name', 'id')->toArray())
                        ->default(function () {
                            $judge = \App\Models\Judges::with('judges_stages')->find(optional($this->getOwnerRecord())->id);
                            return $judge?->judges_stages?->last()?->court_name_id;
                        })
                        ->disabled() // readonly holat
                        ->dehydrated(true) // saqlanmasin
                        ->columnSpan(2),

                    Select::make('court_type_id')
                        ->label('Суд тури')
                        ->options(\App\Models\CourtType::pluck('name', 'id')->toArray())
                        ->default(function () {
                            $judge = \App\Models\Judges::with('judges_stages')->find(optional($this->getOwnerRecord())->id);
                            return $judge?->judges_stages?->last()?->court_type_id;
                        })
                        ->disabled() // foydalanuvchi o‘zgartira olmasin
                        ->dehydrated(true) // formga saqlanmaydi (readonly bo‘lsa)
                        ->columnSpan(2),

                    Select::make('court_specialty_id')
                        ->label('Суд ихтисослиги')
                        ->options(\App\Models\CourtSpeciality::pluck('name', 'id')->toArray())
                        ->default(function () {
                            $judge = \App\Models\Judges::with('judges_stages')->find(optional($this->getOwnerRecord())->id);
                            return $judge?->judges_stages?->last()?->court_specialty_id;
                        })
                        ->disabled() // readonly holat
                        ->dehydrated(true) // saqlash shart bo‘lmasa
                        ->columnSpan(2),

                    TextInput::make('case_type')->label('Иш рақами')->columnSpan(2),

                    Select::make('job_category_id')
                        ->label('Иш тоифаси')
                        ->relationship('jobCategory', 'name') // ✅ aloqa nomi
                        ->reactive()
                        ->afterStateUpdated(fn($state, callable $set) => $set('type_of_decision_id', null))
                        ->columnSpan(2),

                    Select::make('type_of_decision_id')
                        ->label('Қарори тури')
                        ->options(fn(callable $get) => $get('job_category_id')
                            ? TypeOfDecision::where('job_category_id', $get('job_category_id'))
                                ->pluck('name', 'id')
                                ->toArray()
                            : [])
                        ->disabled(fn(callable $get) => !$get('job_category_id'))
                        ->reactive()
                        ->columnSpan(2),

                    Textarea::make('sides')
                        ->label('Ишдаги тарафлар')
                        ->rows(3)
                        ->columnSpan(2),

                    Textarea::make('content')
                        ->label('Иш мазмуни')
                        ->rows(3)
                        ->columnSpan(2),

                    FileUpload::make('file')
                        ->label('Файлни юклаш')
                        ->directory('appeals')
                        ->downloadable()
                        ->openable()
                        ->preserveFilenames()
                        ->columnSpanFull(),


//                    Forms\Components\Section::make('Бонус маълумотлари')
//                        ->collapsed()
//                        ->schema([
//                            Grid::make(3)->schema([
//                                DatePicker::make('appeal_date')
//                                    ->label('Кўриб чиқилган сана'),
//
//                                TextInput::make('first_instance_decision')
//                                    ->label('1-инстанция қарори'),
//
//                                Textarea::make('appeal_reason')
//                                    ->label('Апелляция асослари')
//                                    ->rows(2),
//
//                                Textarea::make('cassation')
//                                    ->label('Кассация')
//                                    ->rows(2),
//
//                                Textarea::make('repeat_cassation')
//                                    ->label('Такрорий кассация')
//                                    ->rows(2),
//                            ]),
//                        ])
//                        ->columnSpanFull(),
                ]),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('appeals')
            ->columns([

                Tables\Columns\BadgeColumn::make('case_type')->color('primary')->label('Иш рақами')->wrap(10),
                Tables\Columns\BadgeColumn::make('jobCategory.name')->color('warning')->disableClick()
                    ->label('Иш тоифаси'),
                Tables\Columns\TextColumn::make('sides')->label('Ишдаги тарафлар')->wrap(20)->disableClick(),
                Tables\Columns\TextColumn::make('content')->label('Иш мазмуни')->wrap(20)->disableClick(),
                TextColumn::make('typeOfDecision.name')
                    ->label('Қарор тури')->disableClick(),

                Tables\Columns\BadgeColumn::make('reason.instances.name')
                    ->label('Апелляция натижаси')
                    ->color('danger'),

                TextColumn::make('cassation')->label('Кассация натижаси')->disableClick(),
                Tables\Columns\BadgeColumn::make('reason.score')->color('danger')->label('Бахо')->disableClick(),

            ])
            ->filters([
                SelectFilter::make('reason.instances_id')
                    ->label('Суд қарори натижаси')
                    ->relationship('reason.instances', 'name')
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->modalHeading('Биринчи инстанция')->label('+'),
            ])
            ->actions([
                Tables\Actions\Action::make('viewAndEditAppelation')
                    ->label('Маълумот киритиш')
                    ->icon('heroicon-o-eye')
                    ->modalHeading(fn($record) => implode(' ', array_filter([
                        $record->judge?->middle_name,
                        $record->judge?->first_name,
                        $record->judge?->last_name,
                    ])))->modalWidth('7xl')
                    ->form([
                        Forms\Components\Fieldset::make('appelation')
                            ->label('Апелляция')
                            ->schema([
                                Grid::make(4)->schema([
                                    DatePicker::make('appeal_date')
                                        ->label('Апелляция кўриб чиқилган сана')
                                        ->format('Y-m-d')
                                        ->icon('heroicon-o-calendar')
                                        ->native(false)
                                        ->default(fn($record) => $record->appeal_date)
                                        ->displayFormat('d.m.Y'),

                                    Select::make('regions_id')
                                        ->label('Ҳудуд')
                                        ->relationship('region', 'name')
                                        ->default(fn($record) => $record->region_id),

                                    Select::make('judges_id')
                                        ->label('Маърузачи судья')
                                        ->placeholder('Маърузачи судья'),

                                    Select::make('judges_id')
                                        ->label('Раислик қилувчи судья')
                                        ->placeholder('Раислик қилувчи судья'),

                                    Select::make('judges_id')
                                        ->label('Ҳаъат судьяси')
                                        ->placeholder('Ҳаъат судьяси'),

                                    Select::make('instances_id')
                                        ->label('Суд қарори натижаси')
                                        ->default(fn($record) => $record->instances_id)
                                        ->options(Instance::pluck('name', 'id')->toArray())
                                        ->reactive()
                                        ->afterStateUpdated(function ($state, callable $set) {
                                            // optional: clear previous reason
                                            $set('reasons_id', null);
                                        }),

                                    Select::make('reasons_id')
                                        ->label('Сабаб')
                                        ->default(fn($record) => $record->reasons_id)
                                        ->options(fn(callable $get) => $get('instances_id')
                                            ? \App\Models\Reason::where('instances_id', $get('instances_id'))->pluck('name', 'id')->toArray()
                                            : []
                                        )
                                        ->reactive()
                                        ->afterStateUpdated(function ($state, callable $set, callable $get, $livewire) {
                                            $reason = \App\Models\Reason::find($state);
                                            $ownerRecord = $livewire->getOwnerRecord();
                                            $judgeId = $ownerRecord->judge_id ?? $ownerRecord->id; // Judge id ni olish

                                            if ($reason && $judgeId) {
                                                $set('score', $reason->score);

                                                $judge = Judges::find($judgeId);
                                                if ($judge) {
                                                    $judge->quality_score = max(0, ($judge->quality_score ?? 0) - $reason->score);

                                                    $judge->rating = ($judge->quality_score ?? 0);
                                                    $judge->save();
                                                }
                                            }
                                        }),

                                    TextInput::make('score')
                                        ->label('Балл')
                                        ->reactive()
                                        ->disabled()
                                        ->default(fn (callable $get) => $get('score'))
                                        ->dehydrated(true)
                                        ->extraAttributes(['readonly' => true]),


                                    FileUpload::make('first_instance_decision')->label('Файл юклаш')->columnSpanFull()
                                ])
                            ]),
                        Placeholder::make('first-instance')
                            ->label('')
                            ->content(fn($record) => view('components.first-instance', ['record' => $record])),
                    ])

                    ->action(function ($record, array $data) {
                        $record->fill($data)->save();
                        Notification::make()
                            ->title('Муваффақиятли сақланди')
                            ->success()
                            ->send();
                    }),


                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()->modalHeading('Биринчи инстанция')
                        ->label('1-инс.ни кўриш'),
                    Tables\Actions\DeleteAction::make()->label('Ўчириш'),
                ]),


            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
