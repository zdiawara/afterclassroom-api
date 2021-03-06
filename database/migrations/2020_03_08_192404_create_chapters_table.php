<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChaptersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chapters', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string('title', 100);
            $table->smallInteger('position')->default(1);
            $table->string('resume', 255)->nullable();
            $table->longText('content')->nullable();
            $table->longText('toc')->nullable();
            $table->boolean('is_public')->default(true);
            $table->boolean('is_active')->default(false);
            $table->string('teacher_id');
            $table->string('classe_id');
            $table->string('specialite_id')->nullable();
            $table->string('matiere_id');
            $table->timestamps();
            //$table->softDeletes();

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
        Schema::dropIfExists('chapters');
    }
}
