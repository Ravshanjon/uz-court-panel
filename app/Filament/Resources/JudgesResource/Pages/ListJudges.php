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
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;


class ListJudges extends ListRecords
{
    protected static string $resource = JudgesResource::class;
    public function mount(): void
    {
        parent::mount();

        if (auth()->user()->hasRole('judges')) {
            $judge = \App\Models\Judges::where('pinfl', auth()->user()->pinfl)->first();

            if (! $judge) {
                Notification::make()
                    ->danger()
                    ->title('Маълумот топилмади')
                    ->body('Сизга тегишли маълумот базада мавжуд эмас.')
                    ->send();
                $this->redirect('/');
                return;
            }

            // Faqat oddiy sudya bo‘lsa (masalan, position_category_id == 5) redirect qilinsin
            if ($judge->position_category_id == 5) {
                $this->redirect(JudgesResource::getUrl('view', ['record' => $judge->getKey()]));
            }

            // Sud raisi bo‘lsa (masalan, position_category_id == 1) → hech narsa qilinmaydi
        }

    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }


}
