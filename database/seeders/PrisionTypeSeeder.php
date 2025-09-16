<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrisionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['name' => 'Ҳайфсан', 'score' => 15],
            ['name' => 'Огохлантириш', 'score' => 15],
            ['name' => 'Жарима', 'score' => 15],
            ['name' => 'Жазо қўлланилмасдан тугатилган', 'score' => 0],
            ['name' => 'Ваколатларини муддатидан илгари тугатиш', 'score' => 0],
            ['name' => 'Малака даражасини бир поғонага пасайтириш', 'score' => 0],
            ['name' => 'Муҳокама билан чегараланиб, иш юритишдан тугатилган', 'score' => 0],
        ];
        foreach ($items as $item) {
            DB::table('prision_type')->insert($item);
        }
    }
}
