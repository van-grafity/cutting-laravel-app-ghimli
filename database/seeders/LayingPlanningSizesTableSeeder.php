<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LayingPlanningSize;

class LayingPlanningSizesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LayingPlanningSize::insert([
            [
                'id' => 1,
                'laying_planning_id' => 1,
                'size_id' => 1,
                'quantity' => 20,
            ],
        ]);
    }
}
