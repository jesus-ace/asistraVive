<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Roles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function(Blueprint $table){
            $table->increments('ro_id');
            $table->string('ro_nom',20)->required();
            $table->string('ro_desc')->required();
            $table->string('ro_time_reg')->required();
            $table->string('ro_imagen')->required();
            $table->integer('ro_status')->required();
            $table->integer('ro_user_reg')->unsigned();
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
        schema::drop('roles');
    }
}
