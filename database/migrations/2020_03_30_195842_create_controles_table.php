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
            $table->uuid("id")->primary();
            $table->smallInteger('position')->default(1);
            $table->year('year');
            $table->boolean('is_public')->default(true);
            $table->longText('enonce')->nullable();
            $table->boolean('is_enonce_active')->default(false);;
            $table->longText('correction')->nullable();
            $table->boolean('is_correction_active')->default(false);;

            $table->string('teacher_id');
            $table->string('classe_id');
            $table->string('matiere_id');
            $table->string('specialite_id')->nullable();

            $table->string('type_id');
            $table->string('trimestre_id')->nullable();


            $table->timestamps();

            $table->foreign('type_id')->references('id')->on('referentiels');
            $table->foreign('trimestre_id')->references('id')->on('referentiels');
            $table->foreign('teacher_id')->references('id')->on('teachers');
            $table->foreign('classe_id')->references('id')->on('classes');
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
