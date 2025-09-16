<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class MismatchedRegionsModal extends Widget
{
    protected static string $view = 'filament.widgets.mismatched-regions-modal';
    protected static ?int $sort = 999;
    protected int|string|array $columnSpan = 'full';
}
