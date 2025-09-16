<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['id' => 1, 'name' => 'Озод'],
            ['id' => 2, 'name' => 'Янги муддат'],
            ['id' => 3, 'name' => 'Илк бор'],
            ['id' => 4, 'name' => 'Навбатдаги муддат'],
            ['id' => 5, 'name' => 'Узайтириш'],
        ];

        foreach ($types as $type) {
            DB::table('types')->updateOrInsert(
                ['id' => $type['id']],
                ['name' => trim($type['name'])]
            );
        }
    }
}
