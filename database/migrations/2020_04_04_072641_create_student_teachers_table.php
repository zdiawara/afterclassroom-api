<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_teacher', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('classe_id');
            $table->unsignedBigInteger('matiere_id');
            $table->unsignedBigInteger('enseignement_id'); // enseignement | faq | exam_subject
            $table->unsignedBigInteger('college_year_id');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('enseignement_id')->references('id')->on('referentiels');
            $table->foreign('teacher_id')->references('id')->on('teachers');
            $table->foreign('student_id')->references('id')->on('students');
            $table->foreign('classe_id')->references('id')->on('classes');
            $table->foreign('matiere_id')->references('id')->on('matieres');
            $table->foreign('college_year_id')->references('id')->on('college_years');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_teacher');
    }
}
