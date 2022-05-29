<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBankingsToUProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('u_profiles', function (Blueprint $table) {
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('national_cart_image')->nullable();
            $table->string('account_owner_first_name')->nullable();
            $table->string('account_owner_last_name')->nullable();
            $table->string('bank')->nullable();
            $table->string('account_number')->nullable();
            $table->string('shaba_number')->nullable();
            $table->string('credit_cart_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('u_profiles', function (Blueprint $table) {
            //
        });
    }
}
