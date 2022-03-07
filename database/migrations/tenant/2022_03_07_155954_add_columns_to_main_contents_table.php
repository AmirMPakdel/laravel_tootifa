<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToMainContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('main_contents', function (Blueprint $table) {
            $table->integer('has_link')->nullable();
            $table->integer('visible')->default(1)->nullable();
            $table->string('link_title')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('main_contents', function (Blueprint $table) {
            $table->dropColumn('has_link');
            $table->dropColumn('visible');
            $table->dropColumn('link_title');
        });
    }
}
