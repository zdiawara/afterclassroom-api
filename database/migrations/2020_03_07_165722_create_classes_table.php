<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classes', function (Blueprint $table) {

            $table->id();
            $table->string('name', 50);
            $table->string('abreviation', 10)->unique();
            $table->string('code', 20)->unique();
            $table->smallInteger('position');
            $table->boolean('is_exam_class')->default(false);
            $table->boolean('has_faq')->default(false);
            $table->unsignedBigInteger('level_id');
            $table->timestamps();

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
        Schema::dropIfExists('classes');
    }
}
