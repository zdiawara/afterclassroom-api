<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notions', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string('title', 100);
            $table->smallInteger('position')->default(1);
            $table->longText('content')->nullable();
            $table->boolean('is_active')->default(false);
            $table->string('classe_id');
            $table->string('specialite_id')->nullable();
            $table->string('matiere_id');
            $table->timestamps();
            //$table->softDeletes();

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
        Schema::dropIfExists('notions');
    }
}
