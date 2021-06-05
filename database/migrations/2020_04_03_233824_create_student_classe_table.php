<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentClasseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_classe', function (Blueprint $table) {
            $table->id();
            $table->string('student_id');
            $table->string('classe_id');
            $table->year('college_year_id');
            $table->integer('changed')->default(1)->min(1);
            $table->timestamps();

            $table->unique(['student_id', 'college_year_id']);
            $table->foreign('student_id')->references('id')->on('students');
            $table->foreign('classe_id')->references('id')->on('classes');
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
        Schema::dropIfExists('student_classe');
    }
}
