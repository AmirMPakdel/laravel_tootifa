<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContentVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_videos', function (Blueprint $table) {
            $table->id();
            $table->string('url')->nullable();
            $table->double('size')->nullable(); // MB
            $table->integer('encoding')->default(0)->nullable();
            $table->string('key')->nullable();
            $table->integer('content_videoable_id')->nullable();
            $table->string('content_videoable_type')->nullable();
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
        Schema::dropIfExists('content_videos');
    }
}
