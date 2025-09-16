<?php

namespace App\Filament\Resources\JudgesResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use GuzzleHttp\Psr7\UploadedFile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PrivateRelationManager extends RelationManager
{
    protected static string $relationship = 'private_awards';
    public function getTableHeading(): string
    {
        return 'Хусусий ажрим'; // Custom heading
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(2)->schema([
                    DatePicker::make('date')
                        ->label('Хусусий ажрим чиқарилган сана')
                        ->format('Y-m-d')
                        ->native(false)
                        ->dehydrated()
                        ->displayFormat('d.m.Y'),
                    Forms\Components\TextInput::make('name')->label('Номланиши'),
                    Forms\Components\FileUpload::make('file')->label('Хусусий ажрим')->columnSpanFull()
                ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('private')
            ->columns([
                Tables\Columns\TextColumn::make('date')->label('Сана')->date('d.m.Y'),
                Tables\Columns\TextColumn::make('name')->label('Номланиши'),
                Tables\Columns\BadgeColumn::make('file')
                    ->label('Номланиши')
                    ->color('primary')
                    ->url(fn($record) => asset('storage/' . $record->file))
                    ->openUrlInNewTab()
                    ->limit(30),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Хусусий ажримни қўшиш')
                    ->color('primary')
                    ->size('sm')
                    ->icon('heroicon-o-plus-circle')
                    ->outlined()
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
}
