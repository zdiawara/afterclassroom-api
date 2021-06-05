<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string('student_id');
            $table->string('teacher_id')->nullable();
            $table->string('classe_id');
            $table->string('matiere_id')->nullable();
            $table->string('enseignement_id'); // enseignement | faq | exam_subject
            $table->year('college_year_id');
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
        Schema::dropIfExists('subscriptions');
    }
}
