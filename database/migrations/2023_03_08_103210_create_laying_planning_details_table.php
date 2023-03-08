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
        Schema::create('laying_planning_details', function (Blueprint $table) {
            $table->id();
            $table->string('no_laying_sheet');
            $table->integer('table_number');
            $table->foreignId('laying_planning_id')->constrained('laying_plannings')->onDelete('cascade');
            $table->integer('layer_qty');
            $table->string('marker_code')->nullable();
            $table->integer('marker_yard');
            $table->integer('marker_inch');
            $table->integer('marker_length');
            $table->integer('total_length');
            $table->integer('total_all_size');
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
        Schema::dropIfExists('laying_planning_details');
    }
};
