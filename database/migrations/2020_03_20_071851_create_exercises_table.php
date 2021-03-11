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
            $table->id();
            $table->smallInteger('position')->default(1);
            $table->string('notions')->nullable();
            $table->string('prerequis')->nullable();
            $table->boolean('active_enonce')->default(false);;
            $table->longText('enonce')->nullable();
            $table->boolean('active_correction')->default(false);;
            $table->longText('correction')->nullable();
            $table->unsignedBigInteger('type_id');
            $table->unsignedBigInteger('chapter_id');
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
