<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToStudentTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_transactions', function (Blueprint $table) {
            $table->dropColumn('issue_tracking_no');
            $table->string('course_title')->nullable();
            $table->string('portal')->nullable();
            $table->text('redirect_url')->nullable();
            $table->string('uuid')->nullable();
            $table->string('ref_id')->nullable();
            $table->string('invoice_transaction_id')->nullable();
            $table->string('error_msg')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_transactions', function (Blueprint $table) {
            $table->string('issue_tracking_no')->nullable();
            $table->dropColumn('portal');
            $table->dropColumn('redirect_url');
            $table->dropColumn('uuid');
            $table->dropColumn('ref_id');
            $table->dropColumn('error_msg');
            $table->dropColumn('invoice_transaction_id');
            $table->dropColumn('course_title');
        });
    }
}
