<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $regions = [
            'Андижон вилояти',
            'Бухоро вилояти',
            'Фарғона вилояти',
            'Жиззах вилояти',
            'Хоразм вилояти',
            'Наманган вилояти',
            'Навоий вилояти',
            'Қашқадарё вилояти',
            'Қорақалпоғистон Республикаси',
            'Самарқанд вилояти',
            'Сирдарё вилояти',
            'Сурхондарё вилояти',
            'Тошкент шаҳри',
            'Тошкент вилояти',
        ];

        foreach ($regions as $region) {
            DB::table('regions')->insert([
                'name' => $region,
            ]);
        }
    }
}
