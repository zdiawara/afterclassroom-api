<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatiereTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matiere_teacher', function (Blueprint $table) {
            $table->unsignedBigInteger('matiere_id');
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('etat_id');
            $table->unsignedBigInteger('level_id');
            //$table->string('justificatif')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('matiere_id')->references('id')->on('matieres');
            $table->foreign('teacher_id')->references('id')->on('teachers');
            $table->foreign('etat_id')->references('id')->on('referentiels');
            $table->foreign('level_id')->references('id')->on('referentiels');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('matiere_teacher');
    }
}
