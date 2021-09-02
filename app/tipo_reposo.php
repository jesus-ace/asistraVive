<?php

namespace Asistencias;

use Illuminate\Database\Eloquent\Model;

class tipo_reposo extends Model
{
    protected $table = 'tipo_reposo';

    protected $primaryKey = 'tire_id';
    protected $fillable = ['tire_tipo', 'tire_status'];
}

