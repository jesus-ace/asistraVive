<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Excepciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('excepciones', function(Blueprint $table){
            $table->increments('ex_id');
            $table->integer('ex_au_id')->unsigned();
            $table->foreign('ex_au_id')->references('au_id')->on('autorizacion')->onDelete('no action')->onUpdate('cascade');
            $table->integer('ex_re_id')->unsigned();
            $table->foreign('ex_re_id')->references('re_id')->on('reposo')->onDelete('no action')->onUpdate('cascade');
            $table->integer('ex_per_id')->unsigned();
            $table->foreign('ex_per_id')->references('per_id')->on('permiso')->onDelete('no action')->onUpdate('cascade');
            $table->integer('ex_vac_id')->unsigned();
            $table->foreign('ex_vac_id')->references('vac_id')->on('vacaciones')->onDelete('no action')->onUpdate('cascade');
            $table->integer('ex_status')->required();
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
        Schema::drop('excepciones');
    }
}
