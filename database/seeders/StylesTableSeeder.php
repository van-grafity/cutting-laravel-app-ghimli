<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Style;

class StylesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Style::insert([
            [
                'id' => 1,
                'style' => '4169AU23',
                'description' => 'Short Sleeve Polos',
                'gl_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
                
            ],
            [
                'id' => 2,
                'style' => '4163AU23',
                'description' => 'Short Sleeve Polos',
                'gl_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 3,
                'style' => '6000AU23',
                'description' => 'Short Sleeve Motif',
                'gl_id' => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 4,
                'style' => '6500AU23',
                'description' => 'Short Sleeve Polos',
                'gl_id' => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 5,
                'style' => '7000AU23',
                'description' => 'Long Sleeve Polos',
                'gl_id' => 3,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 6,
                'style' => '8000AU23',
                'description' => 'Long Sleeve Motif',
                'gl_id' => 4,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 7,
                'style' => '9000AU23',
                'description' => 'Jacket',
                'gl_id' => 5,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ]);
    }
}
