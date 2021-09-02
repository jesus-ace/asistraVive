<?php

namespace Asistencias;

use Illuminate\Database\Eloquent\Model;

class Tipos_horarios extends Model
{
    protected $table = 'tipos_horarios';
    protected $primaryKey = 'tiho_id';
    protected $fillable = ['tiho_dias','tiho_hora_en','tiho_hora_sa','tiho_turno', 'tiho_status'];
}
      