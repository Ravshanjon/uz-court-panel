<?php

namespace App\Filament\Resources\InspectionRelationManagerResource\RelationManagers;

use App\Models\Mistake;
use App\Models\OcrText;
use App\Models\Prision_Type;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Livewire\Notifications;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Imagick;
use PhpParser\Node\Expr\AssignOp\Mod;
use Psy\VersionUpdater\Downloader\FileDownloader;
use Svg\Tag\Text;
use Telegram\Bot\Actions;
use thiagoalessio\TesseractOCR\TesseractOCR;

class ServiceinspectionRelationManager extends RelationManager
{
    protected static string $relationship = 'Serviceinspection';

    public function getTableHeading(): string
    {
        return 'Хизмат текшируви'; // Custom heading
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)->schema([
                    Toggle::make('under_study')
                        ->label('Ўрганишда')
                        ->live()
                        ->afterStateUpdated(function (bool $state, Set $set, Get $get) {
                            if ($state) {
                                // Sana -> string (Y-m-d) bo‘lsin
                                if (!$get('study_started_at')) {
                                    $set('study_started_at', now()->toDateString());
                                }

                                // Snapshot'lar bo‘sh bo‘lsa, faqat MATN yozamiz (obyekt emas!)
                                if (
                                    !$get('judge_fullname_snapshot')
                                    || !$get('judge_region_snapshot')
                                    || !$get('judge_workplace_snapshot')
                                ) {
                                    $judgeId = $get('judge_id');

                                    if ($judgeId && ($j = \App\Models\Judges::with('region')->find($judgeId))) {
                                        // F.I.Sh ni xavfsiz yig‘amiz (yoki $j->full_name accessor bo‘lsa undan)
                                        $full = trim(($j->last_name ?? '') . ' ' . ($j->first_name ?? '') . ' ' . ($j->middle_name ?? ''));
                                        $full = $full !== '' ? $full : ($j->full_name ?? null);

                                        // Region -> faqat nom (relation bo‘lsa ->name), aks holda agar string bo‘lsa o‘sha
                                        $regionName = optional($j->region)->name
                                            ?? (is_string($j->region) ? $j->region : null);

                                        // Ish joyi -> faqat string (agar relation bo‘lsa ->name ga almashtiring)
                                        $workplace = is_string($j->workplace)
                                            ? $j->workplace
                                            : (optional($j->workplace)->name ?? null);

                                        $set('judge_fullname_snapshot', $full);
                                        $set('judge_region_snapshot', $regionName);
                                        $set('judge_workplace_snapshot', $workplace);
                                    }
                                }

                                // Boshlangan bo‘lsa – tugash sanasini tozalaymiz
                                $set('study_finished_at', null);
                            } else {
                                // O‘chirilganda tugash sanasini qo‘yamiz (string formatda)
                                if ($get('study_started_at') && !$get('study_finished_at')) {
                                    $set('study_finished_at', now()->toDateString());
                                }
                            }
                        }),
                    DatePicker::make('inspection_qualification_dates')
                        ->label('Хизмат текшируви хулосаси тузилган сана')
                        ->icon('heroicon-o-calendar')
                        ->displayFormat('d.m.Y')
                        ->native(false),

                    DatePicker::make('study_started_at')
                        ->label('Бошланган вақти')
                        ->icon('heroicon-o-calendar')
                        ->displayFormat('d.m.Y')
                        ->native(false),

                    DatePicker::make('study_finished_at')
                        ->label('Тугаган вақти')
                        ->icon('heroicon-o-calendar')
                        ->displayFormat('d.m.Y')
                        ->native(false),

                    Forms\Components\Section::make()
                        ->visible(fn(Get $get) => !$get('under_study'))->schema([
                            Grid::make()->schema([
                                Select::make('regions_id')
                                    ->relationship('region', 'name')
                                    ->label('Ҳудуд')
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Ҳудудни танланг'),


                                Select::make('inspection_adults_id')
                                    ->relationship('inspectionAdult', 'name')
                                    ->required(fn(string $context) => $context === 'edit')
                                    ->label('Хизмат текшируви ўтказилишига асос'),


                                Select::make('inspection_offices_id')
                                    ->relationship('inspectionOffice', 'name')
                                    ->required(fn(string $context) => $context === 'edit')
                                    ->label('Хизмат текшируви ўтказган идора'),

                                Select::make('inspection_conducted_id')
                                    ->relationship('inspectionConducted', 'name')
                                    ->label('Кенгаш ташаббуси билан ўтказилганми?')
                                    ->required(fn(string $context) => $context === 'edit'),

                                TextInput::make('codes')
                                    ->label('Судья коди')
                                    ->placeholder('4444')
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $judge = \App\Models\Judges::where('codes', $state)->first();

                                        if ($judge) {
                                            $set('full_name', $judge->middle_name . ' ' . $judge->first_name . ' ' . $judge->last_name);
                                        } else {
                                            $set('full_name', null);
                                        }
                                    }),

                                TextInput::make('full_name')
                                    ->label('Хизмат текширув ўтказган судья')
                                    ->disabled()
                                    ->placeholder('Автоматик тўлади')
                                    ->columns(),

                                Select::make('mistake_id')
                                    ->label('Аниқланган хато')
                                    ->relationship('mistake', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->reactive()
                                    ->columnSpanFull(),


                                FileUpload::make('file')
                                    ->label('Fayl biriktirish')
                                    ->disk('public') // ← diskni aniq ko'rsatamiz
                                    ->acceptedFileTypes(['application/pdf'])
                                    ->directory('attachments')
                                    ->downloadable()
                                    ->openable()
                                    // under_study = true bo'lsa file umuman DB ga yozilmasin:
                                    ->dehydrated(fn(Get $get) => !$get('under_study'))
                                    // faqat under_study = false bo'lsa talab qilamiz:
                                    ->required(fn(Get $get) => !$get('under_study'))
                                    ->visible(fn(Get $get) => !$get('under_study'))
                                    ->columnSpanFull(),

                                DatePicker::make('date_referred')
                                    ->label('Малака ҳайъатига юболиган сана')
                                    ->icon('heroicon-o-calendar')
                                    ->displayFormat('d.m.Y')
                                    ->required(fn(string $context) => $context === 'edit')
                                    ->native(false),

                                Select::make('inspection_cases_id')
                                    ->relationship('inspectionCase', 'name')
                                    ->live()
                                    ->label('Текширувда ҳолатлар тасдиғини топдими?'),

                                DatePicker::make('inspection_qualification_dates')
                                    ->label('Интизомий иш қўзғатилган сана')
                                    ->icon('heroicon-o-calendar')
                                    ->displayFormat('d.m.Y')
                                    ->reactive()
                                    ->visible(fn($get) => $get('inspection_cases_id') == 1)
                                    ->native(false),

                                TextInput::make('report_qualification_judgement')
                                    ->visible(fn($get) => $get('inspection_cases_id') == 1)
                                    ->label('Малака ҳайъатида маъруза қилган судья Ф.И.Ш.'),

                                DatePicker::make('date_case')->label('Интизомий иш муҳокама қилинган сана')
                                    ->displayFormat('d.m.Y')
                                    ->reactive()
                                    ->icon('heroicon-o-calendar')
                                    ->visible(fn($get) => $get('inspection_cases_id') == 1)
                                    ->native(false),


                                Select::make('prision_type_id')
                                    ->relationship('prision_type', 'name')
                                    ->label('Қўлланилган интизомий жазо (чора) тури')
                                    ->searchable()
                                    ->preload()
                                    ->reactive()
                                    ->visible(fn($get) => $get('inspection_cases_id') == 1)
                            ]),
                        ])

                ]),
                Grid::make(2)->schema([


                ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->poll('60s')
            ->columns([

                TextColumn::make('inspection_qualification_dates')
                    ->label('Хизмат текшируви ўтказилган сана')
                    ->date('d.m.Y'),

                TextColumn::make('inspectionCase.name')->label('Тасдғини топдими'),

                TextColumn::make('removed_points')
                    ->label('Олиб ташланган балл')
                    ->badge()
                    ->color(function ($state, $record) {
                        $expired = \Carbon\Carbon::parse($record->inspection_qualification_dates)
                                ->addYear()
                                ->toDateString() < now()->toDateString();

                        return $expired ? 'success' : 'warning';
                    })
                    ->alignCenter()
                    ->formatStateUsing(function ($state, $record) {
                        $score = $record->prision_type?->score ?? 0;

                        if ($score === 0) return null;

                        $expired = \Carbon\Carbon::parse($record->inspection_qualification_dates)
                                ->addYear()
                                ->toDateString() < now()->toDateString();

                        return $expired
                            ? "1 йил ўтди: +{$score}"
                            : "Амалда: -{$score}";
                    }),

                Tables\Columns\BadgeColumn::make('study_started_at_pretty')
                    ->label('Ўрганишда')
                    ->extraAttributes(['class' => 'text-sm'])
                    ->getStateUsing(function ($record) {
                        if (empty($record->study_started_at)) {
                            return '—';
                        }

                        $started = $record->study_started_at instanceof \Carbon\Carbon
                            ? $record->study_started_at
                            : \Carbon\Carbon::parse($record->study_started_at);

                        $now = now();
                        $days = $started->diffInDays($now);

                        $afterDays = $started->copy()->addDays($days);
                        $hours = $afterDays->diffInHours($now);

                        // faqat kun va soat
                        return sprintf('%d кун', $days);
                    })
                    ->color(fn($record) => empty($record->study_started_at) ? 'gray' : 'success')
                    ->icon('heroicon-m-clock'),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('inspection_qualification_dates')

            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Хизмат текширувини қўшиш')
                    ->color('primary')
                    ->size('sm')
                    ->icon('heroicon-o-plus-circle')
                    ->outlined()
                    ->stickyModalHeader()
                    ->stickyModalFooter()
                    ->closeModalByClickingAway(false)
                    ->modalSubmitActionLabel('Saqlash')
                    ->modalHeading(function ($livewire) {
                        $judge = $livewire->ownerRecord;

                        if (!$judge) {
                            return 'Судья маълумоти топилмади';
                        }

                        $fullName = e($judge->middle_name . ' ' . $judge->first_name . ' ' . $judge->last_name);
                        $position = optional(optional($judge->establishment)->position)->name ?? 'Лавозим номаълум';

                        $imageUrl = $judge->image
                            ? asset('storage/' . $judge->image)
                            : asset('image/default.jpg');

                        return new \Illuminate\Support\HtmlString(<<<HTML
                <div class="flex items-center space-x-4 mt-2 mb-2">
                    <img src="{$imageUrl}" class="w-16 h-16 rounded-full border object-cover" alt="Sudya rasmi">
                    <div>
                        <div class="text-lg font-semibold ml-2">{$fullName}</div>
                        <div class="text-sm text-gray-500 ml-2">{$position}</div>
                    </div>
                </div>
            HTML
                        );
                    })
                    ->after(function ($record, $livewire) {
                        Log::info('📌 CreateAction after() ishladi');

                        $judgeId = $livewire->getOwnerRecord()->id;
                        $pdfPath = Storage::disk('public')->path($record->file);

                        if (!file_exists($pdfPath)) {
                            Log::warning("❌ Fayl topilmadi: $pdfPath");
                            return;
                        }

                        try {
                            $imagick = new \Imagick();
                            $imagick->setResolution(300, 300);
                            $imagick->readImage($pdfPath);
                            $pageCount = $imagick->getNumberImages();

                            $fullText = '';
                            $pageTexts = [];

                            foreach (range(0, $pageCount - 1) as $i) {
                                $imagick->setIteratorIndex($i);
                                $imagick->setImageFormat('png');
                                $pageImage = Storage::disk('public')->path("tmp_page_{$i}.png");
                                $imagick->writeImage($pageImage);

                                $ocrText = (new TesseractOCR($pageImage))
                                    ->lang('uzb', 'rus')
                                    ->run();

                                $pageTexts[] = ['page' => $i + 1, 'text' => $ocrText];
                                $fullText .= "📄 Sahifa " . ($i + 1) . ":\n" . $ocrText . "\n\n";
                                @unlink($pageImage);
                            }

                            $imagick->clear();
                            $imagick->destroy();

                            \App\Models\OcrText::create([
                                'judge_id' => $judgeId,
                                'source_pdf' => $record->file,
                                'ocr_text' => $fullText,
                                'pages' => array_column($pageTexts, 'page'),
                                'page_texts' => $pageTexts,
                            ]);

                            Log::info("✅ OCR saqlandi: Sudya ID $judgeId");

                        } catch (\Throwable $e) {
                            Log::error('❌ OCR xatolik: ' . $e->getMessage());
                        }
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('')
                    ->modalHeading('Таҳрирлаш'),
                Tables\Actions\DeleteAction::make()->label(''),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);


    }

}
