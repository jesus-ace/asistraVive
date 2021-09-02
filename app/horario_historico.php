<?php

namespace Asistencias;

use Illuminate\Database\Eloquent\Model;

class horario_historico extends Model
{
    public $timestamps = false;

    protected $table = 'horario_historicos';

    protected $primaryKey = 'hh_id';
    
    protected $fillable = ['hh_cedula','hh_tiho_id','hh_us_reg'];
}
