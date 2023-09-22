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
            $table->string('remark')->nullable()->after('fabric_cons_desc');
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
            $table->dropColumn('remark');
        });
    }
};
