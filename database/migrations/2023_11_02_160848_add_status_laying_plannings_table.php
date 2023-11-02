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
        Schema::table('laying_plannings', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->after('remark')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('status_print')->default(false)->after('remark');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('laying_plannings', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');
            $table->dropColumn('status_print');
        });
    }
};
