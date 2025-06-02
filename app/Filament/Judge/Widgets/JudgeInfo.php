<?php

namespace App\Filament\Judge\Widgets;

use App\Models\Judges;
use Filament\Widgets\Widget;

class JudgeInfo extends Widget
{
    protected static string $view = 'filament.judge.widgets.judge-info';

    public ?Judges $record = null;

    public function mount(): void
    {
        $this->record = Judges::where('pinfl', auth()->user()->pinfl)->first();
    }

    protected function getViewData(): array
    {
        return [
            'record' => $this->record,
        ];
    }
}
