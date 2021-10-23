<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('success')->nullable();
            $table->string('title')->nullable();
            $table->string('issue_tracking_no')->nullable();
            $table->string('order_no')->nullable();
            $table->double('price')->nullable();
            $table->integer('course_id')->nullable();
            $table->integer('student_id')->nullable();
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
        Schema::dropIfExists('student_transactions');
    }
}
