<?php

namespace App\Filament\Resources\JudgesResource\Pages;

use App\Filament\Exports\JudgesExporter;
use App\Filament\Imports\JudgesImporter;
use App\Filament\Resources\JudgesResource;
use App\Filament\Resources\JudgesResource\Widgets\StatOverview;
use App\Models\Judges;
use App\Models\User;
use Filament\Actions;
use Filament\Actions\ExportAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;


class ListJudges extends ListRecords
{
    protected static string $resource = JudgesResource::class;
    public function mount(): void
    {
        parent::mount();

        $user = auth()->user();

        if ($user && $user->hasRole('panel_user') && $user->pinfl) {
            $judge = \App\Models\Judges::where('pinfl', $user->pinfl)->first();

            if ($judge) {
                $this->redirectRoute('filament.admin.resources.judges.view', ['record' => $judge->id]);
            }
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }


}
