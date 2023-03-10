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
        Schema::create('cutting_order_record_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cutting_order_record_id')->constrained('cutting_order_records')->onDelete('cascade');
            
            $table->string('fabric_roll');
            $table->string('fabric_batch');
            $table->foreignId('color_id')->constrained('colors')->onDelete('cascade');
            $table->float('yardage');
            $table->float('weight');
            $table->integer('layer');
            $table->float('joint');
            $table->integer('balance_end');
            $table->string('remarks');
            $table->string('operator');
            
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
        Schema::dropIfExists('cutting_order_record_details');
    }
};
