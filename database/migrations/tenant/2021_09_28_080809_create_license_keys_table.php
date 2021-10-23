<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLicenseKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('license_keys', function (Blueprint $table) {
            $table->id();
            $table->string('key')->nullable();
            $table->integer('course_id')->nullable();
            $table->integer('student_id')->nullable();
            $table->json('device_one')->nullable();
            $table->json('device_two')->nullable();
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
        Schema::dropIfExists('license_keys');
    }
}
