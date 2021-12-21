<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeSizeColumnTypeInUploadTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('upload_transactions', function (Blueprint $table) {
            $table->bigInteger('file_size')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('upload_transactions', function (Blueprint $table) {
            $table->double('file_size')->change();
        });
    }
}
