<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFooterTelephonesColumnToMainPagePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('main_page_properties', function (Blueprint $table) {
            $table->dropColumn('telephones');
            $table->json('footer_telephones')->nullable();
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
            $table->dropColumn('footer_telephones');
            $table->json('telephones')->nullable();
        });
    }
}
