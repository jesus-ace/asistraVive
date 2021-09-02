<?php

namespace Asistencias;

use Illuminate\Database\Eloquent\Model;

class roles extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'ro_id';
    protected $fillable = ['ro_nom','ro_desc', 'ro_status','ro_imagen'];
    
}
