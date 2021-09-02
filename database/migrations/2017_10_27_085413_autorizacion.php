<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Autorizacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('autorizacion', function(Blueprint $table){
            $table->increments('au_id');
            $table->date('au_permiso')->required();
            $table->string('au_desc')->required();
            $table->integer('au_tiau_id')->unsigned();
            $table->foreign('au_tiau_id')->references('tiau_id')->on('tipo_autorizacion')->onDelete('no action')->onUpdate('cascade');
            $table->integer('au_status')->required();
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
        Schema::drop('autorizacion');
    }
}
