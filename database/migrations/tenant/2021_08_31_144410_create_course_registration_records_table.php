<?php

use App\Includes\Constant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseRegistrationRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_registration_records', function (Blueprint $table) {
            $table->id();
            $table->integer('course_id')->nullable();
            $table->integer('student_id')->nullable();
            $table->double('course_price')->default(0)->nullable();
            $table->string('registration_type')->default(Constant::$REGISTRATION_TYPE_WEBSITE)->nullable();
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
        Schema::dropIfExists('course_registration_records');
    }
}
