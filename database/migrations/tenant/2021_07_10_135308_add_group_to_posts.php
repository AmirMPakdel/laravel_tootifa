<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGroupToPosts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->integer('level_one_group_id')->nullable();
            $table->integer('level_two_group_id')->nullable();
            $table->integer('level_three_group_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('level_one_group_id');
            $table->dropColumn('level_two_group_id');
            $table->dropColumn('level_three_group_id');
        });
    }
}
