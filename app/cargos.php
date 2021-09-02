<?php

namespace Asistencias;

use Illuminate\Database\Eloquent\Model;

class cargos extends Model
{
	public $timestamps = false;
   	protected $table = 'cargos';
    protected $primaryKey = 'car_id';
    protected $fillable =['car_cod','car_nombre'];
}