<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Reposo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reposo', function(Blueprint $table){
            $table->increments('re_id');
            $table->date('re_fecha_ini')->required();
            $table->date('re_fecha_fin')->required();
            $table->string('re_ce_med',50)->required();
            $table->string('re_diagnostico')->required();
            $table->integer('re_validado')->required();
            $table->integer('re_tire_id')->unsigned();
            $table->foreign('re_tire_id')->references('tire_id')->on('tipo_reposo')->onDelete('no action')->onUpdate('cascade');
            $table->integer('re_status')->required();
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
        Schema::drop('reposo');
    }
}
