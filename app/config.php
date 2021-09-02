<?php

namespace Asistencias;

use Illuminate\Database\Eloquent\Model;

class config extends Model
{
    protected $table = 'configs';
    protected $primaryKey = 'cof_id';
    protected $fillable = ['cof_tipo','cof_nombre','cof_alias','cof_value','cof_desc'];
}
