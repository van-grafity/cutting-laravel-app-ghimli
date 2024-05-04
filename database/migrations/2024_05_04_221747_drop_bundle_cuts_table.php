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
        Schema::dropIfExists('bundle_cuts');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('bundle_cuts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('cutting_tickets')->onDelete('cascade');
            $table->foreignId('status_id')->constrained('bundle_statuses')->onDelete('cascade');
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }
};
