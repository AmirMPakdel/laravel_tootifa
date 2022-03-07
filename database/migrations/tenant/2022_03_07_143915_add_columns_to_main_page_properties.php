<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToMainPageProperties extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('main_page_properties', function (Blueprint $table) {
            $table->string('page_cover_title')->nullable();
            $table->text('page_cover_text')->nullable();
            $table->integer('page_cover_has_link')->nullable();
            $table->string('page_cover_link')->nullable();
            $table->string('page_cover_link_title')->nullable();
            $table->integer('page_cover_template')->nullable();
            $table->json('telephones')->nullable();
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
            $table->dropColumn('page_cover_title');
            $table->dropColumn('page_cover_text');
            $table->dropColumn('page_cover_has_link');
            $table->dropColumn('page_cover_link');
            $table->dropColumn('page_cover_link_title');
            $table->dropColumn('page_cover_template');
            $table->dropColumn('footer_telephones');
        });
    }
}
