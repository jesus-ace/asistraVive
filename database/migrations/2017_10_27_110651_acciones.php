<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Acciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acciones', function(Blueprint $table){
            $table->increments('ac_id');
            $table->string('ac_nom',30)->required();
            $table->string('ac_alias')->required();
            $table->string('ac_desc')->required();
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
        Schema::drop('acciones');
    }
}
