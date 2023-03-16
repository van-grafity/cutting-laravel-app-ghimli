<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Buyer;

class BuyersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Buyer::insert([
            [
                'id' => 1,
                'name' => 'Aeropostale',
                'address' => 'Jalan Ahmad Yani',
                'shipment_address' => 'Jalan Ahmad Yani',
                'code' => 'AERO',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
                
            ],
            [
                'id' => 2,
                'name' => 'Peter Says Denim',
                'address' => 'Jalan Soekarno',
                'shipment_address' => 'Jalan Soekarno',
                'code' => 'PSD',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 3,
                'name' => 'Levis',
                'address' => 'Jalan Muhammad Hatta',
                'shipment_address' => 'Jalan Manggis',
                'code' => 'LEVIS',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 4,
                'name' => 'Uniqlo',
                'address' => 'Jalan Semangka',
                'shipment_address' => 'Jalan Atmajaya',
                'code' => 'UNQLO',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 5,
                'name' => 'The Executive',
                'address' => 'Jalan lorem Ipsum',
                'shipment_address' => 'Jalan lorem Dolor Maquise',
                'code' => 'EXCU',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ]);
    }
}
