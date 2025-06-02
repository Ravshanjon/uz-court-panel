@if ($record)
@livewire(
        \App\Filament\Resources\InspectionRelationManagerResource\RelationManagers\ServiceinspectionRelationManager::class,
        [
        'ownerRecord' => $record,
        'pageClass' => \App\Filament\Resources\JudgesResource\Pages\EditJudges::class,
        ]
)
@endif
