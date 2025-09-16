<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InstancesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $instances = [
            ['id' => 1, 'name' => 'Тўлиқ бекор қилинган'],
            ['id' => 2, 'name' => 'Қисман бекор қилинган'],
            ['id' => 3, 'name' => 'Тўлиқ ўзгартирилган'],
            ['id' => 4, 'name' => 'Қисман ўзгартирилган'],
            ['id' => 5, 'name' => 'Ўзгартирилмаган'],
        ];

        foreach ($instances as $instance) {
            DB::table('instances')->updateOrInsert(
                ['id' => $instance['id']],
                [
                    'name' => $instance['name'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

    }
}
