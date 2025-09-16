<?php

namespace App\Filament\Resources\JudgesResource\RelationManagers;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\{Action, ActionGroup, CreateAction, DeleteAction};
use Filament\Tables\Columns\{TextColumn};
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class JudgeActivityRelationManager extends RelationManager
{
    protected static string $relationship = 'judge_activity_entries';

    public function getTableHeading(): string
    {
        return 'Фаоллиги';
    }

    public function form(\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        return $form->schema([
            Grid::make(3)->schema([
                TextInput::make('criminal_first_instance_avg')->label('Жиноят 1-инстанция')->numeric()->default(0),
                TextInput::make('criminal_appeal_avg')->label('Жиноят апелляция')->numeric()->default(0),
                TextInput::make('criminal_cassation_avg')->label('Жиноят кассация')->numeric()->default(0),

                TextInput::make('admin_violation_first_instance_avg')->label('Маъмурий ҳуқуқбузарлик 1-инстанция')->numeric()->default(0),
                TextInput::make('admin_violation_appeal_avg')->label('Маъмурий ҳуқуқбузарлик апелляция')->numeric()->default(0),
                TextInput::make('admin_violation_cassation_avg')->label('Маъмурий ҳуқуқбузарлик кассация')->numeric()->default(0),

                TextInput::make('materials_first_instance_avg')->label('Материаллар 1-инстанция')->numeric()->default(0),
                TextInput::make('materials_appeal_avg')->label('Материаллар апелляция')->numeric()->default(0),
                TextInput::make('materials_cassation_avg')->label('Материаллар кассация')->numeric()->default(0),

                TextInput::make('civil_appeal_avg')->label('Фуқаролик иши апелляция')->numeric()->default(0),
                TextInput::make('civil_cassation_avg')->label('Фуқаролик иши кассация')->numeric()->default(0),

                TextInput::make('economic_first_instance_avg')->label('Иқтисодий иш 1-инстанция')->numeric()->default(0),
                TextInput::make('economic_appeal_avg')->label('Иқтисодий иш апелляция')->numeric()->default(0),
                TextInput::make('economic_cassation_avg')->label('Иқтисодий иш кассация')->numeric()->default(0),

                TextInput::make('administrative_case_first_instance_avg')->label('Маъмурий иш 1-инстанция')->numeric()->default(0),
                TextInput::make('administrative_case_appeal_avg')->label('Маъмурий иш апелляция')->numeric()->default(0),
                TextInput::make('administrative_case_cassation_avg')->label('Маъмурий иш кассация')->numeric()->default(0),

                TextInput::make('forum_topics_count')->label('Форум мавзулар сони')->numeric()->default(0),
                TextInput::make('forum_comments_count')->label('Форум изоҳлар сони')->numeric()->default(0),

                TextInput::make('min_workload_first_instance')->label('Мин. иш ҳажми 1-инстанция')->numeric()->default(0),
                TextInput::make('min_workload_appeal')->label('Мин. иш ҳажми апелляция')->numeric()->default(0),
                TextInput::make('min_workload_cassation')->label('Мин. иш ҳажми кассация')->numeric()->default(0),
            ])
        ]);
    }
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('bonus')
            ->columns([

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Фаоллик қўшиш')
                    ->icon('heroicon-o-presentation-chart-line')
                    ->outlined()
                    ->size('sm')
                    ->color('primary')
                    ->modalHeading('')

                    // 🔕 Filament'ning default "created" notify sini o‘chir
                    ->successNotification(null)

                    // 🧮 O‘zimiz hisoblaymiz va 1 ta notify yuboramiz
                    ->using(function (array $data, RelationManager $livewire) {
                        $fields = [
                            'criminal_first_instance_avg','criminal_appeal_avg','criminal_cassation_avg',
                            'admin_violation_first_instance_avg','admin_violation_appeal_avg','admin_violation_cassation_avg',
                            'materials_first_instance_avg','materials_appeal_avg','materials_cassation_avg',
                            'civil_appeal_avg','civil_cassation_avg',
                            'economic_first_instance_avg','economic_appeal_avg','economic_cassation_avg',
                            'administrative_case_first_instance_avg','administrative_case_appeal_avg','administrative_case_cassation_avg',
                            'forum_topics_count','forum_comments_count',
                            'min_workload_first_instance','min_workload_appeal','min_workload_cassation',
                        ];

                        $added = 0.0;
                        foreach ($fields as $key) {
                            $added += (float) ($data[$key] ?? 0);
                        }

                        /** @var \App\Models\Judges $owner */
                        $owner  = $livewire->getOwnerRecord();
                        $record = $owner->judge_activity_entries()->create($data);

                        // +5 formatida ko‘rsatish (butun bo‘lsa .00siz)
                        $fmt = fn($n) => rtrim(rtrim(number_format((float)$n, 2, '.', ''), '0'), '.');

                        \Filament\Notifications\Notification::make()
                            ->title('Фаоллик сақланди')
                            ->body('+ ' . $fmt($added) . ' балл')
                            ->success()
                            ->send();

                        return $record;
                    }),
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
}
