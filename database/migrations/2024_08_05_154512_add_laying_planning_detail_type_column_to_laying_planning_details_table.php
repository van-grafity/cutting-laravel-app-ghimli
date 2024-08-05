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
        Schema::table('laying_planning_details', function (Blueprint $table) {
            $table->foreignId('laying_planning_detail_type_id')->nullable()->constrained('laying_planning_detail_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('laying_planning_details', function (Blueprint $table) {
            $table->dropForeign(['laying_planning_detail_type_id']);
            $table->dropColumn(['laying_planning_detail_type_id']);
        });
    }
};
