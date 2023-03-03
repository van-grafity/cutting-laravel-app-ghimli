<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FabricType;

class FabricTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FabricType::insert([
            [
                'id' => 1,
                'name' => 'Spandex Pique',
                'description' => '57 % COTTON 38% polyesteer 5% SPANDEX PIQUE 185 GM/M',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
                
            ],
            [
                'id' => 2,
                'name' => 'flat knit collar',
                'description' => '60% Cotton 40% Polester 20s/1 x 3ply flat knit collar and cuffs',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 3,
                'name' => 'Denim',
                'description' => '70 % Denim 20% Cotton 10% Polyester',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 4,
                'name' => 'Leather',
                'description' => '90% Leather 10% Cotton',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 5,
                'name' => 'Linen',
                'description' => '100% Linen',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ]);
    }
}
