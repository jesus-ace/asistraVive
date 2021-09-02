<?php

namespace Asistencias;

use Illuminate\Database\Eloquent\Model;

class tipo_usuario extends Model
{
    protected $table = 'tipo_usuarios';

    protected $primaryKey = 'tdu_id';
    protected $fillable = ['tdu_tipo','tdu_status'];

}
