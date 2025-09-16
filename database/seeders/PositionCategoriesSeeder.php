<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $position_categories = [
            'Суд раиси',
            'Раис биринчи ўринбосари',
            'Раис ўринбосари',
            'Судья',
        ];

        foreach ($position_categories as $position_category) {
            DB::table('position_categories')->insert([  // Use the correct table name here
                'name' => $position_category,
            ]);
        }
    }
}
