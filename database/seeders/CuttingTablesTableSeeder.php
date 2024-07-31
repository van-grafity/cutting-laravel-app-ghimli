<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CuttingTable;

class CuttingTablesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'number' => '1',
                'description' => 'Cutting Table number 1',
            ],
            [
                'number' => '2',
                'description' => 'Cutting Table number 2',
            ],
            [
                'number' => '3',
                'description' => 'Cutting Table number 3',
            ],
            [
                'number' => '4',
                'description' => 'Cutting Table number 4',
            ],
            [
                'number' => '5',
                'description' => 'Cutting Table number 5',
            ],
            [
                'number' => '6',
                'description' => 'Cutting Table number 6',
            ],
            [
                'number' => '7',
                'description' => 'Cutting Table number 7',
            ],
            [
                'number' => '8',
                'description' => 'Cutting Table number 8',
            ],
            [
                'number' => '9',
                'description' => 'Cutting Table number 9',
            ],
            [
                'number' => '10',
                'description' => 'Cutting Table number 10',
            ],
            [
                'number' => '11',
                'description' => 'Cutting Table number 11',
            ],
            [
                'number' => '12',
                'description' => 'Cutting Table number 12',
            ],
            [
                'number' => '13',
                'description' => 'Cutting Table number 13',
            ],
        ];

        foreach ($data as $key => $cutting_table) {
            CuttingTable::create($cutting_table);
        }
    }
}
