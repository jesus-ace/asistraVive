<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExXUsuario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ex_x_usuario', function(Blueprint $table){
            $table->increments('exu_id');
            $table->integer('exu_ex_id')->unsigned();
            $table->foreign('exu_ex_id')->references('ex_id')->on('excepciones')->onDelete('no action')->onUpdate('cascade');
            $table->integer('exu_ced')->unsigned();
            $table->foreign('exu_ced')->references('us_ced')->on('usuarios')->onDelete('no action')->onUpdate('cascade');
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
        Schema::drop('ex_x_usuario');
    }
}
