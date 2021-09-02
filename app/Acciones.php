<?php

namespace Asistencias;

use Illuminate\Database\Eloquent\Model;

class Acciones extends Model
{
    protected $table = 'acciones';
    protected $primaryKey = 'ac_id';
    protected $fillable = ['ac_nom', 'ac_clave', 'ac_desc'];
}
