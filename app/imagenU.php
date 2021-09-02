<?php

namespace Asistencias;

use Illuminate\Database\Eloquent\Model;

class imagenU extends Model
{
    protected $connection = 'imagen_usuario';
    protected $table ='imagen_us';
    protected $primaryKey = 'iu_id';
    protected $fillable = ['iu_foto', 'iu_us_id'];
}
