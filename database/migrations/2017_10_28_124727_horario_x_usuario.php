<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class HorarioXUsuario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('horario_x_usuario', function(Blueprint $table){
            $table->increments('hxu_id');
            $table->integer('hxu_cedula')->unsigned();
            $table->foreign('hxu_cedula')->references('us_ced')->on('usuarios')->onDelete('no action')->onUpdate('cascade');
            $table->integer('hxu_tiho_id')->unsigned();
            $table->foreign('hxu_tiho_id')->references('tiho_id')->on('tipos_horarios')->onDelete('no action')->onUpdate('cascade');
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
        Schema::drop('horario_x_usuario');
    }
}
