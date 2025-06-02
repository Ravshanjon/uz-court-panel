<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class DisciplinaryChart extends Widget
{
    protected static string $view = 'filament.widgets.disciplinary-chart';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Интизомий жазо',
                    'data' => [3, 1, 4, 3, 2, 3, 1, 8, 2, 0, 5, 1, 5, 3, 0, 0], // bu yerda real statistikani qo'yasiz
                ],
            ],
            'labels' => [
                'ҚР', 'Андижон', 'Бухоро', 'Жиззах', 'Қашқадарё', 'Навоий', 'Наманган', 'Самарқанд',
                'Сурхондарё', 'Сирдарё', 'Фарғона', 'Хоразм', 'Тошкент в.', 'Тошкент ш.', 'Олий суд', 'Ҳарбий суд',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // yoki 'line', 'pie'
    }
}
