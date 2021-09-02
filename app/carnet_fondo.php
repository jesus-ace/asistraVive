<?php

namespace Asistencias;

use Illuminate\Database\Eloquent\Model;

class carnet_fondo extends Model
{
   protected $table = 'carnet_fondos';
    protected $primaryKey = 'id';
    protected $fillable =['fondo_carnet'];
}