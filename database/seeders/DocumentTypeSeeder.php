<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $document_type = [
            'Сенат қарори',
            'Кенгаш қарори',
            'Кенгаш қарори',
            'Жўқорғи Кенгеси',
            'ЖК қарори',
            'Фармойиш',
            'Фармон',

        ];

        foreach ($document_type as $document_types) {
            DB::table('document_types')->insert([  // Use the correct table name here
                'name' => $document_types,
            ]);
        }
    }
}
