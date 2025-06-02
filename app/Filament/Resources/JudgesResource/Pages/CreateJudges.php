<?php

namespace App\Filament\Resources\JudgesResource\Pages;
use App\Notifications\JudgeCreatedNotification;
use Filament\Forms\Components\Tabs;
use Filament\Forms;
use App\Filament\Resources\JudgesResource;
use Filament\Actions;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CreateJudges extends CreateRecord
{
    protected static string $resource = JudgesResource::class;

    protected function afterCreate(): void
    {
        $fullName = $this->record->last_name . ' ' . $this->record->first_name . ' ' . $this->record->middle_name;

        $judge = $this->record; // This refers to the newly created judge
        $judge->notify(new JudgeCreatedNotification($fullName));

        Notification::make()
            ->title('New Judge Created')
            ->body("Judge: {$judge->first_name} {$judge->last_name}")
            ->success()
            ->sendToDatabase($judge)
            ->send();
    }


}
