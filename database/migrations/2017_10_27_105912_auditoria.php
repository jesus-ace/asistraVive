<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Auditoria extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auditoria', function(Blueprint $table){
            $table->increments('aud_id');
            $table->string('aud_tipo',15)->required();
            $table->string('aud_desc')->required();
            $table->string('aud_machine_ip')->required();
            $table->string('aud_machine_name')->required();
            $table->string('aud_machine_os')->required();
            $table->string('aud_machine_explorer')->required();
            $table->string('aud_fecha')->required();
            $table->integer('aud_ced')->unsigned();
            $table->foreign('aud_ced')->references('us_ced')->on('usuarios')->onDelete('no action')->onUpdate('cascade');
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
        Schema::drop('auditoria');
    }
}
