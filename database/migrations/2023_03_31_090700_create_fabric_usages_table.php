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
        Schema::create('fabric_usages', function (Blueprint $table) {
            $table->id();
            $table->string('portion');
            $table->string('content');
            $table->string('type')->unique();
            $table->string('type_description')->nullable();
            $table->float('quantity_consumed');
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
        Schema::dropIfExists('fabric_usages');
    }
};
