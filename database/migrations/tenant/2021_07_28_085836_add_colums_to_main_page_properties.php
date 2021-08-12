<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumsToMainPageProperties extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('main_page_properties', function (Blueprint $table) {
            $table->integer('store_open')->default(1)->nullable();
            $table->integer('blog_open')->default(1)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('main_page_properties', function (Blueprint $table) {
            $table->dropColumn('store_open');
            $table->dropColumn('blog_open');
        });
    }
}
