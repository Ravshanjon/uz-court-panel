<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusCandidateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['id' => 1, 'name' => 'Муҳокама қилинган'],
            ['id' => 2, 'name' => 'Қайтарилган'],
            ['id' => 3, 'name' => 'Ўрганишда'],
        ];
        foreach ($statuses as $status) {
            DB::table('status_candidates')->updateOrInsert(
                ['id' => $status['id']],
                ['name' => trim($status['name'])]
            );
        }
    }
}
