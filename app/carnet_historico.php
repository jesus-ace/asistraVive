<?php

namespace Asistencias;

use Illuminate\Database\Eloquent\Model;

class carnet_historico extends Model
{
    protected $table = 'carnet_historico';
    protected $primaryKey = 'carth_id';
    protected $fillable = ['carth_ip_maquina','carth_usuario','carth_departamento','carth_so','carth_navegador', 'carth_serial_carnet','carth_tipo_carnet'];
}