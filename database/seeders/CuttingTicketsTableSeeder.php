<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CuttingTicket;

class CuttingTicketsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CuttingTicket::insert([
            [
                'id' => 1,
                'ticket_number' => 1,
                'size_id' => 1,
                'layer' => 11,
                'cutting_order_record_id' => 1,
                'cutting_order_record_detail_id' => 1,
                'table_number' => 26,
                'fabric_roll' => "34",
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 2,
                'ticket_number' => 2,
                'size_id' => 1,
                'layer' => 12,
                'cutting_order_record_id' => 1,
                'cutting_order_record_detail_id' => 2,
                'table_number' => 26,
                'fabric_roll' => "22",
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 3,
                'ticket_number' => 3,
                'size_id' => 1,
                'layer' => 10,
                'cutting_order_record_id' => 1,
                'cutting_order_record_detail_id' => 2,
                'table_number' => 26,
                'fabric_roll' => "9",
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 4,
                'ticket_number' => 4,
                'size_id' => 1,
                'layer' => 10,
                'cutting_order_record_id' => 1,
                'cutting_order_record_detail_id' => 4,
                'table_number' => 26,
                'fabric_roll' => "12",
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 5,
                'ticket_number' => 5,
                'size_id' => 1,
                'layer' => 12,
                'cutting_order_record_id' => 1,
                'cutting_order_record_detail_id' => 5,
                'table_number' => 1,
                'fabric_roll' => "31",
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            
        ]);
    }
}
