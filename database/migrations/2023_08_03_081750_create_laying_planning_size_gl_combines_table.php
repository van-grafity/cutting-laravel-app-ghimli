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
        Schema::create('laying_planning_size_gl_combines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_laying_planning_size');
            $table->foreign('id_laying_planning_size')->references('id')->on('laying_planning_sizes')->onDelete('cascade');
            $table->unsignedBigInteger('id_gl_combine');
            $table->foreign('id_gl_combine')->references('id')->on('gl_combines')->onDelete('cascade');
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
        Schema::dropIfExists('laying_planning_size_gl_combines');
    }
};
