<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDailyMaintenanceReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daily_maintenance_cost_reports', function (Blueprint $table) {
            $table->float('total_size')->default(0)->change();

            $table->float('posts_size')->default(0)->change();
            $table->float('courses_size')->default(0)->change();
            $table->float('main_size')->default(0)->change();

            $table->float('videos_size')->default(0)->change();
            $table->float('images_size')->default(0)->change();
            $table->float('voices_size')->default(0)->change();
            $table->float('documents_size')->default(0)->change();

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
        Schema::table('daily_maintenance_cost_reports', function (Blueprint $table) {
            $table->double('total_size')->change();

            $table->double('posts_size')->change();
            $table->double('courses_size')->change();
            $table->double('main_size')->change();

            $table->double('videos_size')->change();
            $table->double('images_size')->change();
            $table->double('voices_size')->change();
            $table->double('documents_size')->change();;

            $table->double('total_cost')->change();
        });
    }
}
