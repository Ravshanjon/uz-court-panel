<?php

namespace Database\Seeders;

use App\Models\Instance;
use App\Models\Reason;
use App\Models\TypeOfDecision;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reasons = [
            ['type_of_decision_id' => 1, 'instances_id' => 3, 'name' => 'Моддий зарар миқдори ўзгартирилган', 'score' => 2],
            ['type_of_decision_id' => 1, 'instances_id' => 4, 'name' => 'Моддий зарар миқдори ўзгартирилган', 'score' => 1],
            ['type_of_decision_id' => 1, 'instances_id' => 3, 'name' => 'Ашёвий далил тақдири қисми бекор қилинган ёки ўзгартирилган', 'score' => 2],
            ['type_of_decision_id' => 1, 'instances_id' => 4, 'name' => 'Ашёвий далил тақдири қисми бекор қилинган ёки ўзгартирилган', 'score' => 1],
            ['type_of_decision_id' => 1, 'instances_id' => 3, 'name' => 'Зарарни қоплаш манбаи ёки ундириш усули ўзгартирилган', 'score' => 2],
            ['type_of_decision_id' => 1, 'instances_id' => 4, 'name' => 'Зарарни қоплаш манбаи ёки ундириш усули ўзгартирилган', 'score' => 1],
            ['type_of_decision_id' => 1, 'instances_id' => 4, 'name' => 'Ўта хавфли рецидивист деб топиш қисми чиқарилган', 'score' => 2],
            ['type_of_decision_id' => 1, 'instances_id' => 4, 'name' => 'Ўта хавфли рецидивист деб топилган', 'score' => 2],
            ['type_of_decision_id' => 1, 'instances_id' => 3, 'name' => 'Ўта хавфли рецидивист деб топилган', 'score' => 4],
            ['type_of_decision_id' => 1, 'instances_id' => 4, 'name' => 'Колония тури ўзгартирилган', 'score' => 2],
            ['type_of_decision_id' => 1, 'instances_id' => 3, 'name' => 'Колония тури ўзгартирилган', 'score' => 4],
            ['type_of_decision_id' => 1, 'instances_id' => 3, 'name' => 'Жазо тури ва миқдори ўзгартирилган', 'score' => 6],
            ['type_of_decision_id' => 1, 'instances_id' => 4, 'name' => 'Жиноят малакаси ўзгартирилган', 'score' => 3],
            ['type_of_decision_id' => 1, 'instances_id' => 3, 'name' => 'Жиноят малакаси ўзгартирилган', 'score' => 6],
            ['type_of_decision_id' => 1, 'instances_id' => 5, 'name' => 'Иш янгидан кўриш учун юборилган', 'score' => 6],
            ['type_of_decision_id' => 1, 'instances_id' => 5, 'name' => 'Иш янгидан кўриш учун юборилган', 'score' => 3],
            ['type_of_decision_id' => 1, 'instances_id' => 5, 'name' => 'Иш янгидан кўриш учун юборилган (ЖПК)', 'score' => 6],
            ['type_of_decision_id' => 1, 'instances_id' => 5, 'name' => 'Иш янгидан кўриш учун юборилган (ЖПК)', 'score' => 3],
            ['type_of_decision_id' => 1, 'instances_id' => 5, 'name' => 'Иш янгидан кўриш учун юборилган (Халқаро)', 'score' => 6],
            ['type_of_decision_id' => 1, 'instances_id' => 5, 'name' => 'Иш янгидан кўриш учун юборилган (Халқаро)', 'score' => 3],
        ];

        foreach ($reasons as $reason) {
            // Check if foreign keys exist to avoid constraint errors
            $typeExists = TypeOfDecision::find($reason['type_of_decision_id']);
            $instanceExists = Instance::find($reason['instances_id']);

            if ($typeExists && $instanceExists) {
                Reason::create($reason);
            } else {
                echo "❌ Otib ketdi: type_of_decision_id {$reason['type_of_decision_id']} yoki instances_id {$reason['instances_id']} topilmadi!\n";
            }
        }
    }
}
