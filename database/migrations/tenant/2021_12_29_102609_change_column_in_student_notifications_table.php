<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnInStudentNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_notifications', function (Blueprint $table) {
            $table->date('expiration_date')->nullable();
            $table->dropColumn('read');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_notifications', function (Blueprint $table) {
            $table->dropColumn('expiration_date');
            $table->integer('read')->default(0)->nullable();
        });
    }
}
