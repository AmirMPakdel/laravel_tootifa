<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('success')->nullable();
            $table->string('title')->nullable();
            $table->string('issue_tracking_no')->nullable();
            $table->string('order_no')->nullable();
            $table->double('price')->nullable();
            $table->string('pt')->nullable();
            $table->string('prt')->nullable();
            $table->double('value')->nullable();
            $table->integer('days')->nullable();
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
        Schema::dropIfExists('user_transactions');
    }
}
