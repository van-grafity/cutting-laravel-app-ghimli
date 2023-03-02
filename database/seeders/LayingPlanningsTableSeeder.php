<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LayingPlanning;

class LayingPlanningsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LayingPlanning::insert([
            [
                'id' => 1,
                'gl_id' => 1,
                'style_id' => 1,
                'buyer_id' => 1,
                'color_id' => 1,
                'quantity' => 100, 
                'fabric_po' => 100, 
                'fabric_type_id' => 1, 
                'fabric_cons_id' => 1, 
                'fabric_cons_qty' => 100, 
                'plan_date' => date('2023-03-01'), 
                'delivery_date' => date('2023-03-01'), 
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ]);
    }
}
