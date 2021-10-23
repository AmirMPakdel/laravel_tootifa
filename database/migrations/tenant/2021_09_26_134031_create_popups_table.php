<?php

use App\Includes\Constant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePopupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('popups', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('text')->nullable();
            $table->string('link')->nullable();
            $table->string('link_title')->nullable();
            $table->string('submit_text')->default(Constant::$FORM_SUBMIT_TEXT)->nullable();
            $table->integer('has_email_input')->default(0)->nullable();
            $table->integer('has_name_input')->default(0)->nullable();
            $table->integer('has_phone_input')->default(0)->nullable();
            $table->integer('has_city_input')->default(0)->nullable();
            $table->integer('has_province_input')->default(0)->nullable();
            $table->integer('active')->default(1)->nullable();
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
        Schema::dropIfExists('popups');
    }
}
