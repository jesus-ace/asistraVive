<?php

namespace Asistencias;

use Illuminate\Database\Eloquent\Model;

class Acceso_cliente extends Model
{
    protected $table = 'acceso_cliente';    
    protected $primaryKey = 'mcjacc_id';
    protected $fillable = ['mcjacc_ip', 'mcjacc_descripcion', 'mcjacc_status'];
}
