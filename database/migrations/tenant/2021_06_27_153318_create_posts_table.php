<?php

use App\Includes\Constant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('logo')->nullable();
            $table->string('cover')->nullable();
            $table->integer('score')->default(0)->nullable();
            $table->string('validation_status')->default(Constant::$VALIDATION_STATUS_IS_CHECKING)->nullable();
            $table->text('validation_status_message')->nullable();
            $table->json('content_hierarchy')->nullable();
            $table->json('suggested_courses')->nullable();
            $table->json('suggested_posts')->nullable();
            $table->bigInteger('visits_count')->default(0)->nullable();
            $table->integer('is_comments_open')->default(1)->nullable();
            $table->integer('all_comments_valid')->default(0)->nullable();
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
        Schema::dropIfExists('posts');
    }
}
