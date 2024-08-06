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
        Schema::table('bundle_stock_transactions', function (Blueprint $table) {
            $table->foreignId('transaction_group_id')
            ->nullable()
            ->constrained('bundle_stock_transaction_groups');
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
        Schema::table('bundle_stock_transactions', function (Blueprint $table) {
            $table->dropForeign(['transaction_group_id']);
            $table->dropColumn('transaction_group_id');
            $table->dropSoftDeletes();
        });
    }
};
