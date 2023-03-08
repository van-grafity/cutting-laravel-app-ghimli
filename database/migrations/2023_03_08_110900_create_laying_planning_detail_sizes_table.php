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
        Schema::create('laying_planning_detail_sizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laying_planning_detail_id')->constrained('laying_planning_details')->onDelete('cascade');
            $table->integer('ratio_per_size');
            $table->integer('qty_per_size')->nullable();
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
        Schema::dropIfExists('laying_planning_detail_sizes');
    }
};
