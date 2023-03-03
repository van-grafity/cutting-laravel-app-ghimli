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
        Schema::create('laying_planning_sizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laying_planning_id')->constrained('laying_plannings')->onDelete('cascade');
            $table->string('size_id');
            $table->string('quantity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('laying_planning_sizes');
    }
};
