<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDailySmsReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daily_sms_cost_reports', function (Blueprint $table) {
            $table->integer('total_count')->default(0)->change();
            $table->float('total_cost')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('daily_sms_cost_reports', function (Blueprint $table) {
            $table->string('total_count')->change();
            $table->string('total_cost')->change();
        });
    }
}
