<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $relations = [
            'Отаси',
            'Онаси',
            'Опаси',
            'Синглиси',
            'Акаси',
            'Укаси',
            'Турмуш ўртоғи',
            'Қизи',
            'Ўғли',
            'Қайнотаси',
            'Қайнонаси',
            'Қайнакаси',
            'Қайнопаси',
            'Қайнукаси',
            'Қайнсингилиси',
        ];

        foreach ($relations as $name) {
            DB::table('parents')->updateOrInsert(
                ['name' => $name],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
