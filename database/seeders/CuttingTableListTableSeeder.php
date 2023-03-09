<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CuttingTableList;

class CuttingTableListTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CuttingTableList::insert([
            [
                'id' => 1,
                'no_laying_sheet' => '62843-001',
                'total_qty' => 1120,
                'marker_code' => 'MC-0001',
                'marker_length' => 6.35,
                'total_length' => 10000,
                'layer_qty' => 80,
                'status_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 2,
                'no_laying_sheet' => '62843-002',
                'total_qty' => 1120,
                'marker_code' => 'MC-0002',
                'marker_length' => 6.35,
                'total_length' => 20000,
                'layer_qty' => 80,
                'status_id' => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 3,
                'no_laying_sheet' => '62843-003',
                'total_qty' => 1120,
                'marker_code' => 'MC-0003',
                'marker_length' => 6.35,
                'total_length' => 30000,
                'layer_qty' => 80,
                'status_id' => 3,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 4,
                'no_laying_sheet' => '62843-004',
                'total_qty' => 1120,
                'marker_code' => 'MC-0004',
                'marker_length' => 6.35,
                'total_length' => 40000,
                'layer_qty' => 80,
                'status_id' => 3,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 5,
                'no_laying_sheet' => '62843-005',
                'total_qty' => 1120,
                'marker_code' => 'MC-0005',
                'marker_length' => 6.35,
                'total_length' => 50000,
                'layer_qty' => 80,
                'status_id' => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 6,
                'no_laying_sheet' => '62843-006',
                'total_qty' => 1120,
                'marker_code' => 'MC-0006',
                'marker_length' => 6.35,
                'total_length' => 60000,
                'layer_qty' => 80,
                'status_id' => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 7,
                'no_laying_sheet' => '62843-007',
                'total_qty' => 1120,
                'marker_code' => 'MC-0007',
                'marker_length' => 6.35,
                'total_length' => 70000,
                'layer_qty' => 80,
                'status_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ]);
    }
}