<?php

namespace Database\Seeders;

use App\Models\SousCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SousCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SousCategory::factory()->create();
        SousCategory::factory()->legume()->create();
        SousCategory::factory()->cereale()->create();
        SousCategory::factory()->tubercule()->create();

        SousCategory::factory()->bovin()->create();
        SousCategory::factory()->volaille()->create();
        SousCategory::factory()->ovin()->create();
        SousCategory::factory()->caprin()->create();




    }
}
