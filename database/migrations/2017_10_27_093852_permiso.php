<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Permiso extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permiso', function(Blueprint $table){
            $table->increments('per_id');
            $table->date('per_fecha_ini')->required();
            $table->date('per_fecha_fin')->required();
            $table->string('per_desc')->required();
            $table->integer('per_remunerado')->required();
            $table->integer('per_status')->required();
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
        Schema::drop('permiso');
    }
}
