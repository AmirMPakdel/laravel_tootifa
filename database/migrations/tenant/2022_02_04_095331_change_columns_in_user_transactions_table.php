<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnsInUserTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_transactions', function (Blueprint $table) {
            $table->dropColumn('issue_tracking_no');
            $table->renameColumn('res_number', 'invoice_transaction_id');
            $table->string('uuid')->nullable();
            $table->string('ref_id')->nullable();
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
        Schema::table('user_transactions', function (Blueprint $table) {
            $table->string('issue_tracking_no')->nullable();
            $table->renameColumn('invoice_transaction_id', 'res_number');
            $table->dropColumn('uuid');
            $table->dropColumn('ref_id');
            $table->dropColumn('error_msg');
        });
    }
}
