<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class AddColumnsToUprofiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('u_profiles', function (Blueprint $table) {
            $table->renameColumn('balance', 'm_balance');
            $table->bigInteger('s_balance')->default(0);
            $table->integer('holdable_test_count')->default(0);
            $table->date('infinit_test_finish_date')->default(Carbon::now());
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
            $table->renameColumn('m_balance', 'balance');
            $table->dropColumn('s_balance');
            $table->dropColumn('holdable_test_count');
            $table->dropColumn('infinit_test_finish_date');
        });
    }
}
