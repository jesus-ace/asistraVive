<?php

namespace Asistencias;

use Illuminate\Database\Eloquent\Model;

class pantallas extends Model
{
    protected $table = 'pantallas';
    protected $primaryKey = 'pnt_id';
    protected $fillable = ['pnt_nombre','pnt_descripcion','pnt_md_id'];
}
