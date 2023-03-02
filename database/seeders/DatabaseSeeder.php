<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\ColorsTableSeeder;
use Database\Seeders\SizesTableSeeder;
use Database\Seeders\BuyersTableSeeder;
use Database\Seeders\GlsTableSeeder;
use Database\Seeders\StylesTableSeeder;
use Database\Seeders\ShipmentsTableSeeder;
use Database\Seeders\FabricTypesTableSeeder;
use Database\Seeders\FabricConssTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ColorsTableSeeder::class);
        $this->call(SizesTableSeeder::class);
        $this->call(BuyersTableSeeder::class);
        $this->call(GlsTableSeeder::class);
        $this->call(StylesTableSeeder::class);
        $this->call(ShipmentsTableSeeder::class);
        $this->call(FabricTypesTableSeeder::class);
        $this->call(FabricConssTableSeeder::class);
    }
}
