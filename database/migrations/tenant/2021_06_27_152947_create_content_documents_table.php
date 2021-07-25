<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContentDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_documents', function (Blueprint $table) {
            $table->id();
            $table->string('url')->nullable();
            $table->double('size')->nullable(); // MB
            $table->integer('content_documentable_id')->nullable();
            $table->string('content_documentable_type')->nullable();
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
        Schema::dropIfExists('content_documents');
    }
}
