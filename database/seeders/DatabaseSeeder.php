<?php

namespace Database\Seeders;

use App\Models\PositionCategories;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RegionSeeder::class,
            CourtNamesSeeder::class,
            CourtSpecialty::class,
            CourtTypeSeeder::class,
            DistrictTypeSeeder::class,
            DurationSeeder::class,
            NationalsSeeder::class,
            PositionSeeder::class,
            ProvinceSeeder::class,
            UniversitySeeder::class,
            VacancySeeder::class,
            DocumentTypeSeeder::class,
            PositionCategoriesSeeder::class,
        ]);
    }
}
