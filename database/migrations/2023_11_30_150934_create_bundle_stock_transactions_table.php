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
        Schema::create('bundle_stock_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('cutting_tickets');
            $table->enum('transaction_type', ['IN', 'OUT']);
            $table->foreignId('location_id')->constrained('bundle_locations');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bundle_stock_transactions');
    }
};
