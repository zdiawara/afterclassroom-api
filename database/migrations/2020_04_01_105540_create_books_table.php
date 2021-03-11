<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            
            $table->string('title',100);
            $table->longText('resume')->nullable();
            $table->boolean('active');
            $table->integer('price')->nullable();
            $table->string('cover')->nullable();
            $table->longText('content')->nullable();
            $table->unsignedBigInteger('teacher_id');
            
            $table->unsignedBigInteger('specialite_id')->nullable();
            $table->unsignedBigInteger('matiere_id');
            $table->timestamps();

            $table->foreign('teacher_id')->references('id')->on('teachers');
            //$table->foreign('category_id')->references('id')->on('referentiels');
            $table->foreign('specialite_id')->references('id')->on('specialites');
            $table->foreign('matiere_id')->references('id')->on('matieres');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('books');
    }
}
