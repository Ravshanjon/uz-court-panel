<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityResource\Pages;
use App\Filament\Resources\ActivityResource\RelationManagers;
use App\Models\Activity;
use App\Models\JudgeActivity;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Laravel\Pulse\Livewire\Card;

class ActivityResource extends Resource
{
    protected static ?string $model = JudgeActivity::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
                    Grid::make(3)->schema([
                            TextInput::make('criminal_first_instance_avg')
                                ->label('Жиноят иши 1-инстанцияда ўртача иш ҳажми')
                                ->numeric()->default(0),

                            TextInput::make('criminal_appeal_avg')
                                ->label('Жиноят иши апелляцияда ўртача иш ҳажми')
                                ->numeric()->default(0),

                            TextInput::make('criminal_cassation_avg')
                                ->label('Жиноят иши кассацияда ўртача иш ҳажми')
                                ->numeric()->default(0),

                            TextInput::make('admin_violation_first_instance_avg')
                                ->label('Маъмурий ҳуқуқбузарлик 1-инстанцияда ўртача иш ҳажми')
                                ->numeric()->default(0),

                            TextInput::make('admin_violation_appeal_avg')
                                ->label('Маъмурий ҳуқуқбузарлик апелляцияда ўртача иш ҳажми')
                                ->numeric()->default(0),

                            TextInput::make('admin_violation_cassation_avg')
                                ->label('Маъмурий ҳуқуқбузарлик кассацияда ўртача иш ҳажми')
                                ->numeric()->default(0),

                            TextInput::make('materials_first_instance_avg')
                                ->label('Материаллар 1-инстанцияда ўртача иш ҳажми')
                                ->numeric()->default(0),

                            TextInput::make('materials_appeal_avg')
                                ->label('Материаллар апелляцияда ўртача иш ҳажми')
                                ->numeric()->default(0),

                            TextInput::make('materials_cassation_avg')
                                ->label('Материаллар кассацияда ўртача иш ҳажми')
                                ->numeric()->default(0),

                            TextInput::make('civil_appeal_avg')
                                ->label('Фуқаролик иши апелляцияда ўртача иш ҳажми')
                                ->numeric()->default(0),

                            TextInput::make('civil_cassation_avg')
                                ->label('Фуқаролик иши кассацияда ўртача иш ҳажми')
                                ->numeric()->default(0),

                            TextInput::make('economic_first_instance_avg')
                                ->label('Иқтисодий иш 1-инстанцияда ўртача иш ҳажми')
                                ->numeric()->default(0),

                            TextInput::make('economic_appeal_avg')
                                ->label('Иқтисодий иш апелляцияда ўртача иш ҳажми')
                                ->numeric()->default(0),

                            TextInput::make('economic_cassation_avg')
                                ->label('Иқтисодий иш кассацияда ўртача иш ҳажми')
                                ->numeric()->default(0),

                            TextInput::make('administrative_case_first_instance_avg')
                                ->label('Маъмурий иш 1-инстанцияда ўртача иш ҳажми')
                                ->numeric()->default(0),

                            TextInput::make('administrative_case_appeal_avg')
                                ->label('Маъмурий иш апелляцияда ўртача иш ҳажми')
                                ->numeric()->default(0),

                            TextInput::make('administrative_case_cassation_avg')
                                ->label('Маъмурий иш кассацияда ўртача иш ҳажми')
                                ->numeric()->default(0),

                            TextInput::make('forum_topics_count')
                                ->label('Форум мавзулар сони')
                                ->numeric()->default(0),

                            TextInput::make('forum_comments_count')
                                ->label('Форум изоҳлар сони')
                                ->numeric()->default(0),

                            TextInput::make('min_workload_first_instance')
                                ->label('1-инстанция минимал иш ҳажми')
                                ->numeric()->default(0),

                            TextInput::make('min_workload_appeal')
                                ->label('Апелляция минимал иш ҳажми')
                                ->numeric()->default(0),

                            TextInput::make('min_workload_cassation')
                                ->label('Кассация минимал иш ҳажми')
                                ->numeric()->default(0),

                    ])
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

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
            'index' => Pages\ListActivities::route('/'),
            'create' => Pages\CreateActivity::route('/create'),
            'edit' => Pages\EditActivity::route('/{record}/edit'),
        ];
    }
    public static function canAccess(): bool
    {
        $user = auth()->user();

        // malaka bo'lsa URL orqali ham kira olmaydi (403)
        return ! ($user && $user->getRoleNames()->contains(
                fn ($r) => Str::lower($r) === 'malaka'
            ));
    }
}
