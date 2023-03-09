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
        Schema::create('cutting_table_lists', function (Blueprint $table) {
            $table->id();
            $table->string('no_laying_sheet');
            $table->integer('total_qty');
            $table->string('marker_code');
            $table->double('marker_length');
            $table->integer('total_length');
            $table->integer('layer_qty');
            $table->foreignId('status_id')->constrained('statuses');
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
        Schema::dropIfExists('cutting_table_lists');
    }
};
