<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Departamentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departamentos',function(blueprint $table){
            $table->increments('dp_id');
            $table->string('dp_nombre',30)->required();
            $table->string('dp_tlf_ppl',13)->required();
            $table->string('dp_tlf_sec',13)->required();
            $table->integer('dp_codigo')->required();
            $table->integer('dp_co_id')->unsigned();
            $table->foreign('dp_co_id')->references('co_id')->on('coordinacions')->onUpdate('cascade')->onDelete('no action');
            $table->integer('dp_status')->required();
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
        schema::drop('departamentos');
    }
}
