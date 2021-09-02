<?php

namespace Asistencias;

use Illuminate\Database\Eloquent\Model;

class vicepresidencia extends Model
{
    protected $table = 'vicepresidencias';
    protected $primaryKey = 'vice_id';
    protected $fillable = ['vice_nombre', 'vice_sede_id','vice_status'];
}
