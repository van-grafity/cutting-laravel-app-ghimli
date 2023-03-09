<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LayingPlanningDetailSize;

class LayingPlanningDetailSizesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LayingPlanningDetailSize::insert([
            [
                'id' => 1,
                'laying_planning_detail_id' => '1',
                'size_id' => '1',
                'ratio_per_size' => '1',
                'qty_per_size' => '80',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
                
            ],
            [
                'id' => 2,
                'laying_planning_detail_id' => '1',
                'size_id' => '2',
                'ratio_per_size' => '3',
                'qty_per_size' => '240',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
                
            ],
            [
                'id' => 3,
                'laying_planning_detail_id' => '1',
                'size_id' => '3',
                'ratio_per_size' => '3',
                'qty_per_size' => '240',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
                
            ],
            [
                'id' => 4,
                'laying_planning_detail_id' => '1',
                'size_id' => '4',
                'ratio_per_size' => '1',
                'qty_per_size' => '80',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
                
            ],
            [
                'id' => 5,
                'laying_planning_detail_id' => '2',
                'size_id' => '2',
                'ratio_per_size' => '1',
                'qty_per_size' => '80',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
                
            ],
            [
                'id' => 6,
                'laying_planning_detail_id' => '2',
                'size_id' => '3',
                'ratio_per_size' => '2',
                'qty_per_size' => '160',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
                
            ],
            [
                'id' => 7,
                'laying_planning_detail_id' => '2',
                'size_id' => '4',
                'ratio_per_size' => '3',
                'qty_per_size' => '240',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
                
            ],
            [
                'id' => 8,
                'laying_planning_detail_id' => '3',
                'size_id' => '2',
                'ratio_per_size' => '1',
                'qty_per_size' => '80',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
                
            ],
            [
                'id' => 9,
                'laying_planning_detail_id' => '3',
                'size_id' => '3',
                'ratio_per_size' => '2',
                'qty_per_size' => '160',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
                
            ],
            [
                'id' => 10,
                'laying_planning_detail_id' => '3',
                'size_id' => '4',
                'ratio_per_size' => '3',
                'qty_per_size' => '240',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
                
            ],
        ]);
    }
}
