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
            $table->string('fabric_cons_desc')->nullable()->after('fabric_cons_qty');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('laying_plannings', 'fabric_cons_desc'))
        {
            Schema::table('laying_plannings', function (Blueprint $table)
            {
                $table->dropColumn('fabric_cons_desc');
            });
        }
    }
};
