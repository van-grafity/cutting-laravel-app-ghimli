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
        Schema::create('laying_plannings', function (Blueprint $table) {
            $table->id();
            $table->integer('gl_id');
            $table->foreignId('style_id')->constrained('styles')->onDelete('cascade');
            $table->foreignId('buyer_id')->constrained('buyers')->onDelete('cascade');
            $table->integer('color_id');
            $table->integer('quantity');
            $table->date('delivery_date');
            $table->date('plan_date');
            $table->string('fabric_po');
            $table->string('fabric_cons_id');
            $table->string('fabric_type_id');
            $table->string('fabric_cons_qty');
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
        Schema::dropIfExists('laying_plannings');
    }
};
