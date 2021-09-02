<?php

namespace Asistencias;

use Illuminate\Database\Eloquent\Model;

class sedes extends Model
{
	protected $table = 'sedes';
    protected $primaryKey = 'sede_id';
    protected $fillable = ['sede_nombre', 'sede_status'];

}
