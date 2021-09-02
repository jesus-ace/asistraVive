<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Modulos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modulos', function(Blueprint $table){
            $table->increments('mod_id');
            $table->string('mod_nom',30)->required();
            $table->string('mod_alias')->required();
            $table->string('mod_desc')->required();
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
        schema::drop('modulos');
    }
}
