<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeacherMatieresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_matiere', function (Blueprint $table) {
            $table->string('matiere_id');
            $table->string('teacher_id');
            $table->string('etat_id');
            $table->string('level_id');
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
        Schema::dropIfExists('teacher_matiere');
    }
}
