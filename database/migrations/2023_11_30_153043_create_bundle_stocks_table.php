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
        Schema::create('bundle_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laying_planning_id')->constrained('laying_plannings');
            $table->foreignId('size_id')->constrained('sizes');
            $table->integer('current_qty');
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
        Schema::dropIfExists('bundle_stocks');
    }
};
