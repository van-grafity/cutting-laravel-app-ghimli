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
        Schema::table('cutting_order_records', function (Blueprint $table) {
            $table->unsignedBigInteger('id_status_layer_cut')->after('laying_planning_detail_id')->default(1);
            $table->foreign('id_status_layer_cut')->references('id')->on('status_layers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cutting_order_records', function (Blueprint $table) {
            $table->dropForeign(['id_status_layer_cut']);
            $table->dropColumn('id_status_layer_cut');
        });
    }
};