<?php

namespace Asistencias;

use Illuminate\Database\Eloquent\Model;

class modulos extends Model
{
    protected $table = 'modulos';

    protected $primaryKey = 'mod_id';
    protected $fillable = ['mod_nom','mod_desc','mod_alias'];
}
