<?php

namespace Database\Seeders;

use App\Models\JobCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeOfDecisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'Айблов ҳукми', 'job_category_id' => 1],
            ['name' => 'Оқлов ҳукми', 'job_category_id' => 1],
            ['name' => 'Ишни тугатиш ажрими', 'job_category_id' => 1],
            ['name' => 'Жазо қўллаш тўғрисида қарор', 'job_category_id' => 2],
            ['name' => 'Ишни тугатиш тўғрисида қарор', 'job_category_id' => 2],
            ['name' => 'Ҳал қилув қарори', 'job_category_id' => 3],
            ['name' => 'Ишни тугатиш ажрими', 'job_category_id' => 3],
            ['name' => 'Кўрмасдан қолдириш ажрими', 'job_category_id' => 3],
            ['name' => 'Ҳал қилув қарори', 'job_category_id' => 4],
            ['name' => 'Ишни тугатиш ажрими', 'job_category_id' => 4],
            ['name' => 'Кўрмасдан қолдириш ажрими', 'job_category_id' => 4],
            ['name' => 'Ҳал қилув қарори', 'job_category_id' => 5],
            ['name' => 'Ишни тугатиш ажрими', 'job_category_id' => 5],
            ['name' => 'Кўрмасдан қолдириш ажрими', 'job_category_id' => 5],
        ];

        foreach ($data as $item) {
            \App\Models\TypeOfDecision::create($item);
        }
    }
}
