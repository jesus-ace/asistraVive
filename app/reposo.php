<?php

namespace Asistencias;

use Illuminate\Database\Eloquent\Model;

class reposo extends Model
{
    protected $table = 'reposo';

    protected $primaryKey = 're_id';
    protected $fillable = ['re_fecha_ini', 're_fecha_fin', 're_ce_medico', 're_diagnostico', 're_validado', 're_tire_id', 're_status'];
}