<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE cutting_order_records MODIFY created_by bigint unsigned AFTER status_print');
         
        Schema::table('cutting_order_records', function (Blueprint $table) {
            $table->unsignedBigInteger('updated_by')->after('created_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');

            $table->unsignedBigInteger('deleted_by')->after('updated_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');
            
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE cutting_order_records MODIFY created_by bigint unsigned AFTER id_status_cut');

        Schema::table('cutting_order_records', function (Blueprint $table) {
            $table->dropForeign(['updated_by']);
            $table->dropColumn('updated_by'); 
            $table->dropForeign(['deleted_by']);
            $table->dropColumn('deleted_by');
            $table->dropSoftDeletes();
        });
    }
};
