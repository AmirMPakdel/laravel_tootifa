<?php

use App\Includes\Constant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_types', function (Blueprint $table) {
            $table->string('name')->unique()->primary();
            $table->string('pattern')->nullable();
            $table->string('new_pattern')->nullable();
            $table->integer('is_default')->default(0)->nullable();
            $table->string('validation_status')->default(Constant::$VALIDATION_STATUS_IS_CHECKING)->nullable();
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
        Schema::dropIfExists('sms_types');
    }
}
