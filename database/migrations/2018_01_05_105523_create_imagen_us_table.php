<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagenUsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('imagen_usuario')->create('imagen_us', function (Blueprint $table) {
            $table->increments('iu_id');
            $table->string('iu_foto');
            $table->integer('iu_us_id');
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
        Schema::drop('imagen_us');
    }
}
