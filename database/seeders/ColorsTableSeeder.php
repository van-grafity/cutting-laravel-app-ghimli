<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Color;

class ColorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Color::insert([
            [
                'id' => 1,
                'color' => 'Med Heather Grey',
                'color_code' => 'MHG (053)',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
                
            ],
            [
                'id' => 2,
                'color' => 'Bleach',
                'color_code' => 'BLC (002)',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 3,
                'color' => 'Black',
                'color_code' => 'BLCK (020)',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 4,
                'color' => 'Med Soft Pink',
                'color_code' => 'MSPK (151)',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 5,
                'color' => 'Aqua Dream',
                'color_code' => 'AQD (078)',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 6,
                'color' => 'True Red',
                'color_code' => 'TRD (087)',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 7,
                'color' => 'Royal Plum',
                'color_code' => 'RYLP (091)',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ]);
    }
}
