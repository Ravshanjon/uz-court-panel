<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Жиноят иши',
            'Маъмурий ҳуқуқбузарлик',
            'Фуқаролик иши',
            'Иқтисодий иш',
            'Маъмурий иш',
        ];

        foreach ($categories as $name) {
            DB::table('job_categories')->updateOrInsert(
                ['name' => $name],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
