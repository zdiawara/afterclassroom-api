<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string('title', 255);
            $table->longText('content')->nullable();
            $table->smallInteger('position')->default(1);
            $table->boolean('is_active')->default(false);
            $table->boolean('is_public')->default(true);
            $table->uuid('notion_id');
            $table->timestamps();
            $table->foreign('notion_id')->references('id')->on('notions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questions');
    }
}
