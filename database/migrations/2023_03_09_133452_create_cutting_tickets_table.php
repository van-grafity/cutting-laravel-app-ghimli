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
        Schema::create('cutting_tickets', function (Blueprint $table) {
            $table->id();

            $table->integer('ticket_number');
            $table->foreignId('size_id')->constrained('sizes')->onDelete('cascade');
            $table->integer('layer');
            $table->foreignId('cutting_order_record_id')->constrained('cutting_order_records')->onDelete('cascade');
            $table->foreignId('cutting_order_record_detail_id')->constrained('cutting_order_record_details')->onDelete('cascade');
            $table->integer('table_number');
            $table->string('fabric_roll');

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
        Schema::dropIfExists('cutting_tickets');
    }
};
