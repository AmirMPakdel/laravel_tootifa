<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncrementalPackages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incremental_packages', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('prt')->nullable();
            $table->double('price')->default(0)->nullable();
            $table->double('value')->default(0)->nullable();
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
        Schema::dropIfExists('incremental_packages');
    }
}
