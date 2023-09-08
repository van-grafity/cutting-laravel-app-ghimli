<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::rename("fabric_requisitions", "fabric_requisitions");
        Schema::table('fabric_requisitions', function (Blueprint $table) {
            $table->boolean('is_issue')->default(false)->after('laying_planning_detail_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("fabric_requisitions", function (Blueprint $table) {
            $table->dropColumn('is_issue');
        });
    }
};
