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
        Schema::create('cloth_rolls', function (Blueprint $table) {
            $table->id();
            $table->integer('po_id');
            $table->string('fabric_type', 20);
            $table->string('color', 20);
            $table->string('roll_no', 20);
            $table->integer('width');
            $table->integer('length');
            $table->integer('weight');
            $table->string('batch_no', 20);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
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
        Schema::dropIfExists('cloth_rolls');
    }
};
