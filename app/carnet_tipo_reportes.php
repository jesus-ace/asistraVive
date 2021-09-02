<?php

namespace Asistencias;

use Illuminate\Database\Eloquent\Model;

class carnet_tipo_reportes extends Model
{
    protected $table = 'carnet_tipo_reportes';
    protected $primaryKey = 'ctr_id';
    protected $fillable = ['ctr_descripcion'];
}