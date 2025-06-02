@if ($record)
    @livewire(
    \App\Filament\Resources\JudgesResource\RelationManagers\JudgesStagesRelationManager::class,
    [
    'ownerRecord' => $record,
    'pageClass' => \App\Filament\Resources\JudgesResource\Pages\EditJudges::class,
    ]
    )
@endif
