<?php

namespace Asistencias;

use Illuminate\Database\Eloquent\Model;

class vacaciones extends Model
{
     protected $table = 'vacaciones';

    protected $primaryKey = 'vac_id';
    protected $fillable = ['vac_fecha_ini', 'vac_fecha_fin', 'vac_cant', 'vac_pago', 'vac_status'];
}
