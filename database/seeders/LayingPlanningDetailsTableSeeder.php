<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LayingPlanningDetail;

class LayingPlanningDetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LayingPlanningDetail::insert([
            [
                'id' => 1,
                'no_laying_sheet' => '62843-001',
                'table_number' => '1',
                'laying_planning_id' => '1',
                'layer_qty' => '80',
                'marker_code' => 'A',
                'marker_yard' => '6',
                'marker_inch' => '11.25',
                'marker_length' => '6.35',
                'total_length' => '508.20',
                'total_all_size' => '1120',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
                
            ],
            [
                'id' => 2,
                'no_laying_sheet' => '62843-002',
                'table_number' => '2',
                'laying_planning_id' => '1',
                'layer_qty' => '80',
                'marker_code' => 'A',
                'marker_yard' => '6',
                'marker_inch' => '11.25',
                'marker_length' => '6.35',
                'total_length' => '508.20',
                'total_all_size' => '1120',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
                
            ],
            [
                'id' => 3,
                'no_laying_sheet' => '62843-003',
                'table_number' => '3',
                'laying_planning_id' => '1',
                'layer_qty' => '80',
                'marker_code' => 'A',
                'marker_yard' => '6',
                'marker_inch' => '11.25',
                'marker_length' => '6.35',
                'total_length' => '508.20',
                'total_all_size' => '1120',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
                
            ],
        ]);
    }
}
