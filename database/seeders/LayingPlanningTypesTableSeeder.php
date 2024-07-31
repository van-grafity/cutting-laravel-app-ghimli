<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LayingPlanningType;

class LayingPlanningTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'type' => 'BODY',
                'description' => 'Main Part of the Product.',
            ],
            [
                'type' => 'COMBINASI',
                'description' => 'Support Part of the Product. Not Count as Output',
            ],
            [
                'type' => 'INTERLINING',
                'description' => 'Support Part of the Product. Not Count as Output',
            ],
        ];

        foreach ($data as $key => $type) {
            LayingPlanningType::create($type);
        }
    }
}
