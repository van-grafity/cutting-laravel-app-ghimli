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
                'fabric_roll' => "27",
                'fabric_batch' => "#72192",
                'color_id' => 1,
                'yardage' => 61,
                'weight' => 21.8,
                'layer' => 11,
                'joint' => 21.8,
                'balance_end' => -2,
                'remarks' => "2yd",
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ]);
    }
}
