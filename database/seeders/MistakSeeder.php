<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MistakSeeder extends Seeder
{
    public function run(): void
    {
        $mistakes = [
            ['name' => 'Суд мажлиси баённомаси юритилмаган ёки имзоланмаган', 'type' => 'Масъулият'],
            ['name' => 'Суд муҳокамаси муддатига риоя этилмаган', 'type' => 'Масъулият'],
            ['name' => 'Якуний суд қарорини ўз вақтида ёзилмаган ёки имзоланмаган', 'type' => 'Масъулият'],
            ['name' => 'Якуний суд қарори ўз вақтида тарафларга юборилмаган', 'type' => 'Масъулият'],
            ['name' => 'Якуний суд қарори ўз вақтида ижрога қаратилмаган', 'type' => 'Масъулият'],
            ['name' => 'Ишни юритишда процессуал қонун нормаларига риоя қилинмаган', 'type' => 'Масъулият'],
            ['name' => 'Меҳнат қонунчилигига риоя қилинмаган', 'type' => 'Масъулият'],
            ['name' => 'Ижро интизомига риоя қилинмаган', 'type' => 'Масъулият'],
            ['name' => 'Судьянинг одоби умумий қоидаларига риоя этилмаган', 'type' => 'Одоб'],
            ['name' => 'Касбий фаолиятни амалга оширишда одоб принциплари ва қоидаларига риоя этилмаган', 'type' => 'Одоб'],
            ['name' => 'Хизматдан ташқари вақтдаги одоб қоидаларига риоя этилмаган', 'type' => 'Одоб'],
            ['name' => 'Маъмурий ҳуқуқбузарлик содир этилган', 'type' => 'Одоб'],
        ];

        foreach ($mistakes as $mistake) {
            DB::table('mistakes')->updateOrInsert(
                ['name' => $mistake['name']],
                [
                    'type' => $mistake['type'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }

}
