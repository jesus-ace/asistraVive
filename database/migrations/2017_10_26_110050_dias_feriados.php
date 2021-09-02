<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DiasFeriados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dias_feriados', function(Blueprint $table){
            $table->increments('diaf_id');
            $table->date('diaf_feriado');
            $table->string('diaf_desc');
            $table->integer('diaf_tife_id')->unsigned();
            $table->foreign('diaf_tife_id')->references('tife_id')->on('tipo_dia_fe')->onDelete('no action')->onUpdate('cascade');
            $table->integer('diaf_status')->required();
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
        schema::drop('dias_feriados');
    }
}
