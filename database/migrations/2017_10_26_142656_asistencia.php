<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Asistencia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asistencia', function(Blueprint $table){
            $table->increments('asi_id');
            $table->date('asi_entrada')->required();
            $table->date('asi_salida')->required();
            $table->time('asi_entrada_hora')->required();
            $table->time('asi_salida_hora')->required();
            $table->integer('asi_carus_id')->unsigned();
            $table->foreign('asi_carus_id')->references('carus_id')->on('carnet_us')->onDelete('no action')->onUpdate('cascade');
            $table->integer('asi_diaf_id')->unsigned();
            $table->foreign('asi_diaf_id')->references('diaf_id')->on('dias_feriados')->onDelete('no action')->onUpdate('cascade');
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
        Scheme::drop('asistencia');
    }
}
