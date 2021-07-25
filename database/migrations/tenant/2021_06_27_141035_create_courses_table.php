<?php

use App\Includes\Constant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('logo')->nullable();
            $table->string('cover')->nullable();
            $table->string('introduction_video')->nullable();
            $table->text('short_desc')->nullable();
            $table->longText('long_desc')->nullable();
            $table->integer('duration')->default(0)->nullable();
            $table->integer('score')->default(0)->nullable();
            $table->double('price')->default(0)->nullable();
            $table->json('discount')->nullable();
            $table->string('holding_status')->default(Constant::$HOLDING_STATUS_COMING_SOON)->nullable();
            $table->string('validation_status')->default(Constant::$VALIDATION_STATUS_IS_CHECKING)->nullable();
            $table->text('validation_status_message')->nullable();
            $table->date('release_date')->nullable();
            $table->json('subjects')->nullable();
            $table->json('requirements')->nullable();
            $table->json('content_hierarchy')->nullable();
            $table->json('suggested_courses')->nullable();
            $table->json('suggested_posts')->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('level_one_group_id')->nullable();
            $table->integer('level_two_group_id')->nullable();
            $table->integer('level_three_group_id')->nullable();
            $table->integer('course_introduction_id')->nullable();
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
        Schema::dropIfExists('courses');
    }
}
