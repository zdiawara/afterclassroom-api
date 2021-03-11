<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollegeYearsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('college_years', function (Blueprint $table) {
            $table->id();
            $table->string('name',50);
            $table->year('year')->unique();
            $table->unsignedBigInteger('etat_id');
            $table->timestamps();
            $table->foreign('etat_id')->references('id')->on('referentiels');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('college_years');
    }
}
