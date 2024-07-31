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
            $table->foreignId('laying_planning_type_id')->after('remark')->nullable()->constrained('laying_planning_types');
            $table->foreignId('parent_laying_planning_id')->after('laying_planning_type_id')->nullable()->constrained('laying_plannings');
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
            $table->dropForeign(['laying_planning_type_id']);
            $table->dropForeign(['parent_laying_planning_id']);
            $table->dropColumn(['laying_planning_type_id', 'parent_laying_planning_id']);
        });
    }
};
