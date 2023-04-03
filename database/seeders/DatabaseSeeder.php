<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\RolessTableSeeder;
use Database\Seeders\UsersTableSeeder;
use Database\Seeders\ColorsTableSeeder;
use Database\Seeders\SizesTableSeeder;
use Database\Seeders\BuyersTableSeeder;
use Database\Seeders\GlsTableSeeder;
use Database\Seeders\StylesTableSeeder;
use Database\Seeders\ShipmentsTableSeeder;
use Database\Seeders\FabricTypesTableSeeder;
use Database\Seeders\FabricConssTableSeeder;
use Database\Seeders\LayingPlanningsTableSeeder;
use Database\Seeders\LayingPlanningSizesTableSeeder;
use Database\Seeders\LayingPlanningDetailsTableSeeder;
use Database\Seeders\LayingPlanningDetailSizesTableSeeder;
use Database\Seeders\CuttingOrderRecordsTableSeeder;
use Database\Seeders\CuttingOrderRecordDetailsTableSeeder;
use Database\Seeders\CuttingTicketsTableSeeder;
use Database\Seeders\UserRolePermissionsSeeder;
use Database\Seeders\RemarkTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(RolesTableSeeder::class);
        // $this->call(UsersTableSeeder::class);
        $this->call(UserRolePermissionsSeeder::class);
        $this->call(ColorsTableSeeder::class);
        $this->call(SizesTableSeeder::class);
        $this->call(BuyersTableSeeder::class);
        $this->call(GlsTableSeeder::class);
        $this->call(StylesTableSeeder::class);
        $this->call(ShipmentsTableSeeder::class);
        $this->call(FabricTypesTableSeeder::class);
        $this->call(FabricConssTableSeeder::class);
        $this->call(LayingPlanningsTableSeeder::class);
        $this->call(LayingPlanningSizesTableSeeder::class);
        $this->call(LayingPlanningDetailsTableSeeder::class);
        $this->call(LayingPlanningDetailSizesTableSeeder::class);
        $this->call(CuttingOrderRecordsTableSeeder::class);
        $this->call(CuttingOrderRecordDetailsTableSeeder::class);
        // $this->call(CuttingTicketsTableSeeder::class);
        $this->call(RemarkTableSeeder::class);

    }
}