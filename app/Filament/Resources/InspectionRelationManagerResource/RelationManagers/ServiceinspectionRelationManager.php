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
use Filament\Forms\Form;
use Filament\Forms\Get;
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
use Psy\VersionUpdater\Downloader\FileDownloader;
use Svg\Tag\Text;
use Telegram\Bot\Actions;
use thiagoalessio\TesseractOCR\TesseractOCR;

class ServiceinspectionRelationManager extends RelationManager
{
    protected static string $relationship = 'Serviceinspection';


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)->schema([
                    DatePicker::make('inspection_qualification_dates')
                        ->label('Xizmat tekshiruvi xulosasi tuzilgan sana')
                        ->icon('heroicon-o-calendar')
                        ->displayFormat('d.m.Y')
                        ->native(false),

                    Select::make('regions_id')
                        ->relationship('region', 'name')
                        ->label('Ҳудуд')
                        ->searchable()
                        ->preload()
                        ->placeholder('Ҳудудни танланг'),



                    Select::make('inspection_adults_id')
                        ->relationship('inspectionAdult', 'name')
                        ->required(fn(string $context) => $context === 'edit')
                        ->label('Xizmat tekshiruvini o‘tkazishga asos'),


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

                    Select::make('inspection_cases_id')
                        ->relationship('inspectionCase', 'name')
                        ->label('Текширувда ҳолатлар тасдиғини топдими?'),


                    FileUpload::make('file')
                        ->label('Fayl biriktirish')
                        ->acceptedFileTypes(['application/pdf'])
                        ->directory('attachments')
                        ->required(fn(string $context) => $context === 'edit')
                        ->downloadable()
                        ->openable()
                        ->columnSpanFull(),

                    DatePicker::make('date_referred')
                        ->label('Malaka hayʼatiga yuborilgan sana')
                        ->icon('heroicon-o-calendar')
                        ->displayFormat('d.m.Y')
                        ->required(fn(string $context) => $context === 'edit')
                        ->native(false),

                    Select::make('inspection_regulations_id')
                        ->searchable()
                        ->preload()
                        ->relationship('inspectionRegulation', 'name')
                        ->reactive()
                        ->label('Текширувда ҳолатлар тасдиғини топдими?'),

                    DatePicker::make('inspection_qualification_dates')
                        ->label('Интизомий иш қўзғатилган сана')
                        ->icon('heroicon-o-calendar')
                        ->displayFormat('d.m.Y')
                        ->reactive()
                        ->visible(fn($get) => $get('inspection_regulations_id') == 1)
                        ->native(false),

                    TextInput::make('report_qualification_judgement')
                        ->visible(fn($get) => $get('inspection_regulations_id') == 1)
                        ->label('Малака ҳайъатида маъруза қилган судья Ф.И.Ш.'),

                    DatePicker::make('date_case')->label('Интизомий иш муҳокама қилинган сана')
                        ->displayFormat('d.m.Y')
                        ->reactive()
                        ->icon('heroicon-o-calendar')
                        ->visible(fn($get) => $get('inspection_regulations_id') == 1)
                        ->native(false),

                    Select::make('prision_type_id')
                        ->relationship('prision_type', 'name')
                        ->label('Қўлланилган интизомий жазо (чора) тури')
                        ->searchable()
                        ->preload()
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set, $livewire, $old) {
                            $judge = $livewire->getOwnerRecord();

                            $newPrision = Prision_Type::find($state);
                            $oldPrision = $old ? Prision_Type::find($old) : null;

                            $newScore = $newPrision?->score ?? 0;
                            $oldScore = $oldPrision?->score ?? 0;

                            $judge->ethics_score = max(0, ($judge->ethics_score ?? 95) + $oldScore - $newScore);
                            $judge->rating = $judge->ethics_score;
                            $judge->save();

                            Notification::make()
                                ->title("✅ Yangilandi: Одоби={$judge->ethics_score}, Rating={$judge->rating}")
                                ->success()
                                ->send();
                        })
                ]),
                Grid::make(2)->schema([


                ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
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
                    })
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('inspection_qualification_dates')

            ], layout: FiltersLayout::AboveContent)
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->icon('heroicon-s-plus')
                    ->color('gray')
                    ->size('sm')
                    ->stickyModalHeader()
                    ->stickyModalFooter()
                    ->closeModalByClickingAway(false)
                    ->modalSubmitActionLabel('Saqlash')
                    ->hiddenLabel()
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

    public function saved(): void
    {
        Log::info('✅✅✅ saved() METODI ISHLADI');

        $record = $this->record;
        if (!$record || !$record->file) {
            Log::warning('❌ Fayl yo‘q yoki record topilmadi');
        }

    }


}
