<?php

namespace Asistencias;

use Illuminate\Database\Eloquent\Model;

class notificaciones extends Model{

	protected $table = 'notificaciones';
    protected $primaryKey = 'notmcj_id';
    protected $fillable = ['notmcj_fecha', 'notmcj_descripcion', 'notmcj_ipmaquina', 'id_cnt_srlinut', 'crntuser_id', 'notmcj_motivo', 'notmcj_hora', 'notmcj_codigo'];
    
}