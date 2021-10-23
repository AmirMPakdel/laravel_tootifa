<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUploadTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upload_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('upload_key')->nullable();
            $table->string('upload_type')->nullable();
            $table->float('file_size')->nullable();
            $table->string('file_type')->nullable();
            $table->integer('is_public')->nullable();
            $table->integer('is_encrypted')->nullable();
            $table->string('enc_key')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('upload_transactions');
    }
}
