<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferentielsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referentiels', function (Blueprint $table) {
            $table->id();
            $table->string('name',50);
            $table->string('code',20);
            $table->string('type',20);
            $table->smallInteger('position');
            $table->unique(['code','type']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('referentiels');
    }
}
