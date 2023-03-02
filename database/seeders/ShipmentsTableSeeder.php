<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Shipment;

class ShipmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Shipment::insert([
            [
                'id' => 1,
                'delivery_number' => '001',
                'gl_id' => 1,
                'delivery_date' => date('2023-03-01'), 
                'quantity' => 1000,
                'license' => 'Ecommerce , MALL order',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
                
            ],
            [
                'id' => 2,
                'delivery_number' => '002',
                'gl_id' => 1,
                'delivery_date' => date('2023-03-02'), 
                'quantity' => 2000,
                'license' => 'Ecommerce , MALL order',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 3,
                'delivery_number' => '003',
                'gl_id' => 1,
                'delivery_date' => date('2023-03-03'), 
                'quantity' => 3000,
                'license' => 'MALL order',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 4,
                'delivery_number' => '004',
                'gl_id' => 1,
                'delivery_date' => date('2023-03-04'), 
                'quantity' => 4000,
                'license' => ' Ecommerce',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 5,
                'delivery_number' => '001',
                'gl_id' => 2,
                'delivery_date' => date('2023-03-10'), 
                'quantity' => 2500,
                'license' => 'MALL order',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 6,
                'delivery_number' => '002',
                'gl_id' => 2,
                'delivery_date' => date('2023-03-11'), 
                'quantity' => 3500,
                'license' => 'MALL order',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 7,
                'delivery_number' => '001',
                'gl_id' => 3,
                'delivery_date' => date('2023-03-15'), 
                'quantity' => 11111,
                'license' => 'Ecommerce , MALL order',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ]);
    }
}
