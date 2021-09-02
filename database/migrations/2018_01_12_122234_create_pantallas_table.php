<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePantallasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pantallas', function (Blueprint $table) {
            $table->increments('pnt_id');
            $table->string('pnt_nombre');
            $table->string('pnt_descripcion');
            $table->integer('pnt_mod_id')->unsigned();
            $table->foreign('pnt_mod_id')->references('mod_id')->on('modulos')->onDelete('no action')->onUpdate('cascade');
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
        Schema::drop('pantallas');
    }
}
