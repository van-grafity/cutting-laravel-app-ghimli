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
            $table->string('serial_number')->unique();
            $table->foreignId('gl_id')->constrained('gls')->onDelete('cascade');
            $table->foreignId('style_id')->constrained('styles')->onDelete('cascade');
            $table->foreignId('buyer_id')->constrained('buyers')->onDelete('cascade');
            $table->foreignId('color_id')->constrained('colors')->onDelete('cascade');
            $table->integer('order_qty');
            $table->date('delivery_date');
            $table->date('plan_date');
            $table->string('fabric_po');
            $table->foreignId('fabric_cons_id')->constrained('fabric_cons')->onDelete('cascade');
            $table->foreignId('fabric_type_id')->constrained('fabric_types')->onDelete('cascade');
            $table->float('fabric_cons_qty');
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
