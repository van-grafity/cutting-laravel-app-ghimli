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
            $table->unsignedBigInteger('id_status_layer')->after('laying_planning_detail_id')->default(1);
            $table->foreign('id_status_layer')->references('id')->on('status_layers')->onDelete('cascade');
            $table->unsignedBigInteger('id_status_cut')->after('id_status_layer')->default(1);
            $table->foreign('id_status_cut')->references('id')->on('status_cuts')->onDelete('cascade');
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
            $table->dropForeign(['id_status_layer']);
            $table->dropColumn('id_status_layer');
            $table->dropForeign(['id_status_cut']);
            $table->dropColumn('id_status_cut');
        });
    }
};