<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Usuarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios', function(Blueprint $table){
            $table->integer('us_ced')->unique();
            $table->integer('us_local')->required();
            $table->integer('us_ldap')->required();
            $table->string('us_nom',20)->required();
            $table->string('us_ape',20)->required();
            $table->string('us_login',15)->required();
            $table->string('us_pass')->required();
            $table->string('us_mail',15)->required();
            $table->string('us_preg')->required();
            $table->string('us_resp')->required();
            $table->integer('us_status')->required();
            $table->string('us_time_reg');
            $table->string('us_last_aco');


            $table->integer('us_ro_id')->unsigned();
            $table->foreign('us_ro_id')->references('ro_id')->on('roles')->onDelete('no action')->onUpdate('cascade');            
            $table->integer('us_dp_id')->unsigned();
            $table->foreign('us_dp_id')->references('dp_id')->on('departamentos')->onDelete('no action')->onUpdate('cascade');
            $table->integer('us_user_reg')->unsigned();
            $table->integer('us_tdu_id')->unsigned();
            $table->foreign('us_tdu_id')->references('tdu_id')->on('tipo_usuarios')->onDelete('no action')->onUpdate('cascade');
            $table->integer('us_sex_id')->unsigned();
            $table->foreign('us_sex_id')->references('sex_id')->on('sexo')->onDelete('no action')->onUpdate('cascade');

            $table->timestamps();
            $table->rememberToken();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('usuarios');
    }
}
