<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeacherWritersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_writer', function (Blueprint $table) {
            $table->string('teacher_id');
            $table->string('writer_id');
            $table->timestamps();
            $table->primary(['teacher_id', 'writer_id']);
            $table->foreign('writer_id')->references('id')->on('writers');
            $table->foreign('teacher_id')->references('id')->on('teachers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teacher_writer');
    }
}
