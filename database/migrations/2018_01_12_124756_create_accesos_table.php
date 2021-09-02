<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccesosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accesos', function (Blueprint $table) {
            $table->increments('aco_id');
            $table->integer('aco_ro_id')->unsigned();
            $table->foreign('aco_ro_id')->references('ro_id')->on('roles')->onUpdate('cascade')->onDelete('no action');
            $table->integer('aco_mod_id')->unsigned();
            $table->foreign('aco_mod_id')->references('mod_id')->on('modulos')->onUpdate('cascade')->onDelete('no action');
            $table->integer('aco_pnt_id')->unsigned();
            $table->foreign('aco_pnt_id')->references('pnt_id')->on('pantallas')->onUpdate('cascade')->onDelete('no action');
            $table->integer('aco_ac_id')->unsigned();
            $table->foreign('aco_ac_id')->references('ac_id')->on('acciones')->onUpdate('cascade')->onDelete('no action');
            $table->integer('aco_user_reg')->unsigned();
            $table->foreign('aco_user_reg')->references('us_ced')->on('usuarios')->onUpdate('cascade')->onDelete('no action');
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
        Schema::drop('accesos');
    }
}
