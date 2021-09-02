<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Vacaciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vacaciones', function(Blueprint $table){
            $table->increments('vac_id');
            $table->date('vac_fecha_ini')->required();//fecha inicio
            $table->date('vac_fecha_fin')->required(); //fecha de culminacion
            $table->integer('vac_cant')->required(); //cantidad de vacaciones
            $table->integer('vac_pago')->required(); //Si fueron pagadas dichas vacaciones o no
            $table->integer('vac_status')->required();
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
        Schema::drop('vacaciones');
    }
}
