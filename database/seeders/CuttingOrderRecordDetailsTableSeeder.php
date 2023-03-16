<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CuttingOrderRecordDetail;

class CuttingOrderRecordDetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CuttingOrderRecordDetail::insert([
            [
                'id' => 1,
                'cutting_order_record_id' => 1,
                'fabric_roll' => "34",
                'fabric_batch' => "#72192",
                'color_id' => 1,
                'yardage' => 61,
                'weight' => 21.8,
                'layer' => 11,
                'joint' => 21.8,
                'balance_end' => -2,
                'remarks' => "2yd",
                'operator' => "Juhri",
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 2,
                'cutting_order_record_id' => 1,
                'fabric_roll' => "22",
                'fabric_batch' => "#72192",
                'color_id' => 1,
                'yardage' => 87,
                'weight' => 25.02,
                'layer' => 10,
                'joint' => 21.8,
                'balance_end' => -2,
                'remarks' => "1yd",
                'operator' => "Juhri",
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 3,
                'cutting_order_record_id' => 1,
                'fabric_roll' => "9",
                'fabric_batch' => "#72192",
                'color_id' => 1,
                'yardage' => 70,
                'weight' => 11.8,
                'layer' => 10,
                'joint' => 25.8,
                'balance_end' => -2,
                'remarks' => "1yd",
                'operator' => "Juhri",
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 4,
                'cutting_order_record_id' => 1,
                'fabric_roll' => "31",
                'fabric_batch' => "#72192",
                'color_id' => 1,
                'yardage' => 48,
                'weight' => 29.8,
                'layer' => 13,
                'joint' => 21.8,
                'balance_end' => -2,
                'remarks' => "4yd",
                'operator' => "Juhri",
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 5,
                'cutting_order_record_id' => 1,
                'fabric_roll' => "5",
                'fabric_batch' => "#72192",
                'color_id' => 1,
                'yardage' => 59,
                'weight' => 20.5,
                'layer' => 15,
                'joint' => 21,
                'balance_end' => 2,
                'remarks' => "4yd",
                'operator' => "Juhri",
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 6,
                'cutting_order_record_id' => 2,
                'fabric_roll' => "10",
                'fabric_batch' => "#14523",
                'color_id' => 1,
                'yardage' => 61,
                'weight' => 21.8,
                'layer' => 22,
                'joint' => 21.8,
                'balance_end' => -2,
                'remarks' => "2yd",
                'operator' => "Supri",
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 7,
                'cutting_order_record_id' => 2,
                'fabric_roll' => "20",
                'fabric_batch' => "#14523",
                'color_id' => 1,
                'yardage' => 87,
                'weight' => 25.02,
                'layer' => 33,
                'joint' => 21.8,
                'balance_end' => -2,
                'remarks' => "1yd",
                'operator' => "Supri",
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 8,
                'cutting_order_record_id' => 2,
                'fabric_roll' => "30",
                'fabric_batch' => "#14523",
                'color_id' => 1,
                'yardage' => 70,
                'weight' => 11.8,
                'layer' => 44,
                'joint' => 25.8,
                'balance_end' => -2,
                'remarks' => "1yd",
                'operator' => "Supri",
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 9,
                'cutting_order_record_id' => 3,
                'fabric_roll' => "212",
                'fabric_batch' => "#58686",
                'color_id' => 1,
                'yardage' => 48,
                'weight' => 29.8,
                'layer' => 13,
                'joint' => 21.8,
                'balance_end' => -2,
                'remarks' => "4yd",
                'operator' => "Yanto",
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 10,
                'cutting_order_record_id' => 3,
                'fabric_roll' => "313",
                'fabric_batch' => "#58686",
                'color_id' => 1,
                'yardage' => 59,
                'weight' => 20.5,
                'layer' => 20,
                'joint' => 21,
                'balance_end' => 2,
                'remarks' => "4yd",
                'operator' => "Yanto",
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ]);
    }
}
