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
            $table->unsignedBigInteger('created_by')->after('id_status_cut')->default(1);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('is_pilot_run')->after('created_by')->default(false);
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
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');
            $table->dropColumn('is_pilot_run');
        });
    }
};
