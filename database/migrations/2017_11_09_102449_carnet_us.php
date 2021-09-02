<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CarnetUs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carnet_us', function(Blueprint $table){
            $table->increments('carus_id');
            $table->integer('carus_ced')->required();
            $table->integer('carus_codigo')->required();
            $table->string('carus_qr',10)->required();
            $table->integer('carus_huella')->required();
            $table->integer('carus_hxu_id')->unsigned();
            $table->foreign('carus_hxu_id')->references('hxu_id')->on('horario_x_usuario')->onDelete('no action')->onUpdate('cascade');
            $table->integer('carus_status')->required();
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
        Schema::drop('carnet_us');
    }
}
