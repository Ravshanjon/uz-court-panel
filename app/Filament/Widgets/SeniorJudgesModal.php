<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class SeniorJudgesModal extends Widget
{
    protected static string $view = 'filament.widgets.senior-judges-modal';
    protected static ?int $sort = 999;
    protected int|string|array $columnSpan = 'full';
}
