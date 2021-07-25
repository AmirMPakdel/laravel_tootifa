<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMainPagePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('main_page_properties', function (Blueprint $table) {
            $table->id();
            $table->integer('is_banner_on')->default(0)->nullable();
            $table->string('banner_link')->nullable();
            $table->string('banner_cover')->nullable();
            $table->string('banner_text')->nullable();
            $table->string('page_title')->nullable();
            $table->string('page_logo')->nullable();
            $table->json('content_hierarchy')->nullable();
            $table->json('footer_links')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('main_page_properties');
    }
}
