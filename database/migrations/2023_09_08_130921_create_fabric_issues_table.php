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
        Schema::create('fabric_issues', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('fabric_request_id')->unsigned();
            $table->foreign('fabric_request_id')->references('id')->on('fabric_requisitions')->onDelete('cascade');
            $table->string('roll_no');
            $table->string('weight');
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
        Schema::dropIfExists('fabric_issues');
    }
};
