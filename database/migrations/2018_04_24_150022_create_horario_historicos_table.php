<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHorarioHistoricosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('horario_historicos', function (Blueprint $table) {
            $table->increments('hh_id');
            $table->integer('hh_cedula')->required();
            $table->integer('hh_tiho_id')->required();
            $table->integer('hh_us_reg')->required();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('horario_historicos');
    }
}
