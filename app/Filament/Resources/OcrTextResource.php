<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OcrTextResource\Pages;
use App\Filament\Resources\OcrTextResource\RelationManagers;
use App\Models\OcrText;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class OcrTextResource extends Resource
{
    protected static ?string $model = OcrText::class;

    protected static ?string $relationship = 'ocrTexts';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('judges.last_name'),

                TextColumn::make('modda_descriptions')
                    ->label('Модда бузилишлари')
                    ->getStateUsing(fn($record) => implode("\n", $record->getViolationDescriptions()))
                    ->wrap()
                    ->color('warning')
                    ->icon('heroicon-o-exclamation-circle'),
                TextColumn::make('violated_articles')
                    ->label('Бузилган моддалар')
                    ->getStateUsing(fn($record) => implode(', ', $record->getViolatedArticles()))
                    ->wrap()
                    ->badge()
                    ->color('danger'),
                TextColumn::make('source_pdf')
                    ->label('Юкланган файл номи')
                    ->formatStateUsing(fn($state) => basename($state))
                    ->wrap(),

//                TextColumn::make('ocr_text')
//                    ->label('OCR матн')
//                    ->limit(1500) // faqat 100 ta belgini ko‘rsatadi
//                    ->toggleable(isToggledHiddenByDefault: true) // ko‘rsatish/berkitish mumkin
//                    ->wrap(),

                TextColumn::make('pages')
                    ->label('Саҳифалар')
                    ->formatStateUsing(function ($state, $record) {
                        if (!is_array($state)) return '—';

                        return new \Illuminate\Support\HtmlString(
                            collect($state)->map(function ($page) use ($record) {
                                $text = e($record->page_texts[$page - 1]['text'] ?? '');
                                return "<a href='#'
                    class='text-primary-600 underline text-sm'
                    onclick='window.dispatchEvent(new CustomEvent(\"open-ocr-modal\", { detail: { page: $page, text: `" . addslashes($text) . "` } }))'>
                    $page</a>";
                            })->implode(', ')
                        );
                    })
                    ->html(),

                TextColumn::make('page_texts')
                    ->label('Саҳифалар OCR матни')
                    ->formatStateUsing(function ($state) {
                        if (!is_array($state)) return '—';

                        return collect($state)->map(function ($item, $index) {
                            $page = $index + 1;
                            $text = e($item['text'] ?? '-');

                            return "<a
                href='#'
                class='text-primary-600 underline text-sm'
                x-data
                @click.prevent=\"window.dispatchEvent(new CustomEvent('open-ocr-modal', {
                    detail: {
                        page: {$page},
                        text: `" . addslashes($text) . "`
                    }
                }))\">
                [$page]
            </a>";
                        })->implode(' | ');
                    })
                    ->html()
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListOcrTexts::route('/'),
            'create' => Pages\CreateOcrText::route('/create'),
        ];
    }
    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->hasRole('super_admin');
    }

}
