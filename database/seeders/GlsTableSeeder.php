<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Gl;

class GlsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Gl::insert([
            [
                'id' => 1,
                'gl_number' => '62843-00',
                'season' => 'FALL 23',
                'size_order' => 'XS-XL',
                'buyer_id' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
                
            ],
            [
                'id' => 2,
                'gl_number' => '62845-00',
                'season' => 'FALL 23',
                'size_order' => 'XS-XXL',
                'buyer_id' => '2',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 3,
                'gl_number' => '62850-00',
                'season' => 'WINTER 23',
                'size_order' => 'S-XL',
                'buyer_id' => '3',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 4,
                'gl_number' => '62888-00',
                'season' => 'SPRING 24',
                'size_order' => 'XS-XL',
                'buyer_id' => '4',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 5,
                'gl_number' => '62900-00',
                'season' => 'SUMMER 24',
                'size_order' => 'XS-XXXL',
                'buyer_id' => '5',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ]);
    }
}
