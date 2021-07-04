<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('password')->nullable();
            $table->string('phone_number')->unique()->nullable();
            $table->string('national_code')->unique()->nullable();
            $table->string('email')->unique()->nullable();
            $table->dateTime('phone_verified_at')->nullable();
            $table->dateTime('email_verified_at')->nullable();
            $table->string('verification_code')->nullable();
            $table->string('token')->nullable();
            $table->string('tenant_id')->nullable(); // username
            $table->integer('u_profile_id')->nullable();
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
        Schema::dropIfExists('users');
    }
}
