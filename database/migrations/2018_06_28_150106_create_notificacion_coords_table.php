<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificacionCoordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notificacion_coord', function (Blueprint $table) {
            $table->increments('not_id');
            $table->string('not_coord')->required();
            $table->integer('not_ced_emp')->required();
            $table->string('not_motivo')->required();
            $table->integer('not_asi_id')->required();
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
        Schema::dropIfExists('notificacion_coord');
    }
}
