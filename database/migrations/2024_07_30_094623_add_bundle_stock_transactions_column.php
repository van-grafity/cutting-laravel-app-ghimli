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

        // if (!Schema::hasTable('bundle_stock_transaction_details')) {
        //     throw new \Exception('Table bundle_stock_transaction_details does not exist.');
        // }

        Schema::table('bundle_stock_transactions', function (Blueprint $table) {
            // Add foreign key constraint
            $table->unsignedBigInteger('bundle_transaction_detail_id')->index()->nullable();
            $table->foreign('bundle_transaction_detail_id')
            ->references('id')
            ->on('bundle_stock_transaction_details');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('bundle_stock_transactions', function (Blueprint $table) {
            $table->dropForeign(['bundle_transaction_detail_id']);
            $table->dropColumn('bundle_transaction_detail_id');
        });
    }
};
