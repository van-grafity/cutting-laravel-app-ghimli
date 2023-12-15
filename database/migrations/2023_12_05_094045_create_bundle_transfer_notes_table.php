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
        Schema::create('bundle_transfer_notes', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number');
            $table->foreignId('location_from_id')->constrained('bundle_locations');
            $table->foreignId('location_to_id')->constrained('bundle_locations');
            $table->string('issued_by')->nullable();
            $table->string('authorized_by')->nullable();
            $table->string('received_by')->nullable();

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
        Schema::dropIfExists('bundle_transfer_notes');
    }
};
