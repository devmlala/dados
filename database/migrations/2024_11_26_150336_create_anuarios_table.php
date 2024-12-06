<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnuariosTable extends Migration
{
    public function up()
    {
        Schema::create('anuarios', function (Blueprint $table) {
            $table->id();
            $table->integer('ano');
            $table->string('secao');
            $table->string('titulo');
            $table->text('conteudo');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('anuarios');
    }
}
