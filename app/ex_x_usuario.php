<?php

namespace Asistencias;

use Illuminate\Database\Eloquent\Model;

class ex_x_usuario extends Model
{
    protected $table = 'ex_x_usuario';
    protected $primaryKey = 'exu_id';
    protected $fillable = ['exu_ex_id', 'exu_ced'];
}
