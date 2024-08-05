<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LayingPlanningDetailType;

class LayingPlanningDetailTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'type' => 'NORMAL',
                'description' => 'Normat Table / Lot',
            ],
            [
                'type' => 'BINDING',
                'description' => 'Table / Lot for Binding',
            ],
            [
                'type' => 'PIPING',
                'description' => 'Table / Lot for Piping',
            ],
            [
                'type' => 'BALANCE',
                'description' => 'Table / Lot for Balance',
            ],
        ];

        foreach ($data as $key => $type) {
            LayingPlanningDetailType::create($type);
        }
    }
}
