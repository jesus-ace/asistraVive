<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TiposHorarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipos_horarios',function(blueprint $table){
            $table->increments('tiho_id');
            $table->string('tiho_dias',59)->required();
            $table->time('tiho_hora_en')->default('08:00:00');
            $table->time('tiho_hora_sa')->default('05:00:00');
            $table->time('tiho_holgura_en')->required();
            $table->string('tiho_turno')->required();
            $table->integer('tiho_status')->required();
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
        schema::drop('tipos_horarios');
    }
}
