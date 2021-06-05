<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExercisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exercises', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->smallInteger('position')->default(1);
            $table->string('notions')->nullable();
            $table->string('prerequis')->nullable();
            $table->boolean('is_public')->default(true);
            $table->longText('enonce')->nullable();
            $table->boolean('is_enonce_active')->default(false);;
            $table->longText('correction')->nullable();
            $table->boolean('is_correction_active')->default(false);;
            $table->string('type_id');
            $table->uuid('chapter_id');
            $table->timestamps();

            $table->foreign('chapter_id')->references('id')->on('chapters');
            $table->foreign('type_id')->references('id')->on('referentiels');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exercises');
    }
}
