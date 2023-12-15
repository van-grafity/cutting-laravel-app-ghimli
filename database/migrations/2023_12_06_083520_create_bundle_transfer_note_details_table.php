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
        Schema::create('bundle_transfer_note_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bundle_transfer_note_id')->constrained('bundle_transfer_notes');
            $table->foreignId('bundle_transaction_id')->constrained('bundle_stock_transactions');
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
        Schema::dropIfExists('bundle_transfer_note_details');
    }
};
