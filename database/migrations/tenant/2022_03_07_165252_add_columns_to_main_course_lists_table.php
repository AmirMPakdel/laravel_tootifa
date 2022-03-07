<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToMainCourseListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('main_course_lists', function (Blueprint $table) {
            $table->integer('level_one_group_id')->nullable();
            $table->integer('level_two_group_id')->nullable();
            $table->integer('level_three_group_id')->nullable();
            $table->string('default_type')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('main_course_lists', function (Blueprint $table) {
            $table->dropColumn('level_one_group_id');
            $table->dropColumn('level_two_group_id');
            $table->dropColumn('level_three_group_id');
            $table->integer('default_type')->change();
        });
    }
}
