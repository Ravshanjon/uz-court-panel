<?php

namespace App\Filament\Resources\JudgesResource\RelationManagers;


use App\Services\OCRFamilyService;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FamilyRelationManager extends RelationManager
{
    protected static string $relationship = 'family';

    public function getTableHeading(): string
    {
        return 'Оилавий ҳолатини қўшинг'; // Custom heading
    }
    public function form(Form $form): Form
    {
        return $form
            ->schema([


                Forms\Components\Select::make('parents_id')
                    ->relationship('parents', 'name')
                    ->label('Қариндошлиги'),
                TextInput::make('name')->label('ФИШ'),
                DatePicker::make('birth_date')->label('Туғилган санаси'),
                TextInput::make('birth_place')->label('Туғилган жойи'),
                TextInput::make('working_place')->label('Ишлаган жойи'),
                TextInput::make('live_place')->label('Яшаш жойи'),
                Toggle::make('is_deceased')
                    ->label('Вафот этган')
                    ->reactive(),

                Textarea::make('death_note')
                    ->label('Вафоти ҳақида изоҳ')
                    ->visible(fn ($get) => $get('is_deceased') === true)
//                    ->required(fn ($get) => $get('is_deceased') === true)
                    ->rows(2),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Family')->pluralModelLabel('')

            ->columns([

                Tables\Columns\BadgeColumn::make('parents.name')
                    ->label('Қариндошлиги'),
                TextColumn::make('name')->label('ФИШ'),
//                TextColumn::make('birth_date')
//                    ->date('d.m.Y')
//                    ->label('Туғилган санаси'),
                TextColumn::make('birth_place')
                    ->label('Туғилган жойи')
                    ->formatStateUsing(fn ($state) => str_replace(',', ',<br>', $state))
                    ->html(),

                TextColumn::make('live_place')
                    ->label('Яшаш жойи')
                    ->formatStateUsing(fn ($state) => str_replace(',', ',<br>', $state))
                    ->html(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Оилавий ҳолатини қўшиш')
                    ->icon('heroicon-o-plus-circle')
                    ->outlined()
                    ->size('sm')
                    ->outlined()
                    ->color('primary'),
                Action::make('import_word')
                    ->label('Маълумот импорт қилиш')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->outlined()
                    ->size('sm')
                    ->modalHeading('Word файлни юкланг')
                    ->modalSubmitActionLabel('Юклаш ва OCR қилиш')
                    ->form([
                        FileUpload::make('word')
                            ->label('Word файл')
                            ->directory('family') // public/family degani
                            ->visibility('public')
                            ->preserveFilenames()
                            ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                            ->required()
                    ])
                    ->action(function (array $data, $livewire) {

                        $judgeId = $livewire->getOwnerRecord()->id ?? null;



                        $relativePath = $data['word'];
                        $filePath = storage_path('app/' . $relativePath);


                        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                            $filePath = utf8_decode($filePath);
                        }


                        if (! $judgeId) {
                            Notification::make()
                                ->title('❌ Судья ID топилмади')
                                ->danger()
                                ->send();
                            return;
                        }

                        $result = OCRFamilyService::extractFromDocx($relativePath, $judgeId);

                        Notification::make()
                            ->title('✅ Word OCR якунланди')
                            ->success()
                            ->body(is_array($result)
                                ? 'Сақланган сатрлар сони: ' . count($result)
                                : 'Натижа: ' . json_encode($result))
                            ->send();
                    }),


            ])

            ->actions([
                Tables\Actions\EditAction::make()->label(''),
                Tables\Actions\DeleteAction::make()->label(''),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    public static function saved(Form $form, Model $record): void
    {
        if ($record->pdf && $record->judge_id) {
            // ❗ To‘g‘ri chaqiruv: faqat saqlashni amalga oshiradi
            \App\Services\OCRFamilyService::extractAndSave($record->pdf, $record->judge_id);

            // ❗ Loggerga oddiy xabar chiqaring
            logger("✅ OCRdan oila a'zolari saqlandi: $record->pdf");
        }
    }
    public static function afterSave(RelationManager $livewire): void
    {
        $filePath = $livewire->getRecord()->word; // masalan: "private/family/11.docx"
        $judgeId = $livewire->getOwnerRecord()->id ?? null;

        if (!$filePath || !$judgeId) {
            \Filament\Notifications\Notification::make()
                ->title('Fayl yoki sudya topilmadi')
                ->danger()
                ->send();
            return;
        }

        $results = OCRFamilyService::extractFromDocx($filePath, $judgeId);

        \Filament\Notifications\Notification::make()
            ->title('OCR bajarildi')
            ->success()
            ->body('Сатрлар: ' . count($results))
            ->send();
    }
}
