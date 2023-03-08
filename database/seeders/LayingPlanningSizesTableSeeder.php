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
            [
                'id' => 2,
                'laying_planning_id' => 1,
                'size_id' => 2,
                'quantity' => 30,
            ],
            [
                'id' => 3,
                'laying_planning_id' => 1,
                'size_id' => 3,
                'quantity' => 40,
            ],
            [
                'id' => 4,
                'laying_planning_id' => 1,
                'size_id' => 4,
                'quantity' => 35,
            ],
            [
                'id' => 5,
                'laying_planning_id' => 2,
                'size_id' => 2,
                'quantity' => 11,
            ],
            [
                'id' => 6,
                'laying_planning_id' => 2,
                'size_id' => 3,
                'quantity' => 22,
            ],
            [
                'id' => 7,
                'laying_planning_id' => 2,
                'size_id' => 4,
                'quantity' => 33,
            ],
        ]);
    }
}
