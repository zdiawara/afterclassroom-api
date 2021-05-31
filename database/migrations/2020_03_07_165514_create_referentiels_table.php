<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferentielsTable extends Migration
{

    public function up()
    {
        Schema::create('referentiels', function (Blueprint $table) {
            $table->string("id")->primary();
            $table->string('name', 50);
            $table->string('type', 20);
            $table->smallInteger('position');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('referentiels');
    }
}
