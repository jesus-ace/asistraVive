<?php

namespace Asistencias;

use Illuminate\Database\Eloquent\Model;

class Alertas extends Model
{
    protected $table = 'alertas';    
    protected $primaryKey = 'alert_id';
    protected $fillable = ['alert_alerta'];
    public $timestamps = false;
}
