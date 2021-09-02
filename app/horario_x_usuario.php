<?php

namespace Asistencias;

use Illuminate\Database\Eloquent\Model;

class horario_x_usuario extends Model
{
    protected $table = 'horario_x_usuario';

    protected $primaryKey = 'hxu_id';
    protected $fillable = ['hxu_ced','hxu_tiho_id'];
}
