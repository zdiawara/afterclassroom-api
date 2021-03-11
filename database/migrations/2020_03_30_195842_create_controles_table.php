<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateControlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('controles', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('position')->default(1);
            $table->year('year')->nullable();
            $table->boolean('active_enonce')->default(false);
            $table->longText('enonce')->nullable();
            $table->boolean('active_correction')->default(false);
            $table->longText('correction')->nullable();

            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('classe_id');
            $table->unsignedBigInteger('matiere_id');
            $table->unsignedBigInteger('option_id')->nullable();
            $table->unsignedBigInteger('specialite_id')->nullable();

            $table->unsignedBigInteger('type_id');
            $table->unsignedBigInteger('trimestre_id')->nullable();;
            $table->unsignedBigInteger('subject_id')->nullable();;


            $table->timestamps();

            $table->foreign('subject_id')->references('id')->on('referentiels');
            $table->foreign('type_id')->references('id')->on('referentiels');
            $table->foreign('trimestre_id')->references('id')->on('referentiels');
            $table->foreign('teacher_id')->references('id')->on('teachers');
            $table->foreign('classe_id')->references('id')->on('classes');
            $table->foreign('option_id')->references('id')->on('options');
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
        Schema::dropIfExists('controles');
    }
}
