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
        Schema::create('bundle_stock_transaction_details', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number')->unique();
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
        Schema::dropIfExists('bundle_stock_transaction_details');
    }
};
