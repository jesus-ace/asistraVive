<?php

namespace Asistencias;

use Illuminate\Database\Eloquent\Model;

class tipo_dia_fe extends Model
{
    protected $table = 'tipo_dia_fe';

    protected $primaryKey = 'tife_id';
    protected $fillable = ['tife_tipo'];
}
