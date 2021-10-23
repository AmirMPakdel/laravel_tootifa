<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyMaintenanceCostReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_maintenance_cost_reports', function (Blueprint $table) {
            $table->id();
            $table->double('total_size')->nullable();

            $table->double('posts_size')->nullable();
            $table->double('courses_size')->nullable();
            $table->double('main_size')->nullable();

            $table->double('videos_size')->nullable();
            $table->double('images_size')->nullable();
            $table->double('voices_size')->nullable();
            $table->double('documents_size')->nullable();

            $table->double('total_cost')->nullable();
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
        Schema::dropIfExists('daily_maintenance_cost_reports');
    }
}
