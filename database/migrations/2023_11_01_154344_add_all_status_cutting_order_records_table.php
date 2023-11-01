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
            $table->dateTime('pilot_run')->after('is_pilot_run')->nullable();
            $table->dateTime('layer')->after('pilot_run')->nullable();
            $table->dateTime('cut')->after('layer')->nullable();
            $table->boolean('status_print')->default(false)->after('cut');
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
            $table->dropColumn('pilot_run');
            $table->dropColumn('layer');
            $table->dropColumn('cut');
            $table->dropColumn('status_print');
        });
    }
};
