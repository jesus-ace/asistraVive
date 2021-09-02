<?php

namespace Asistencias;

use Illuminate\Database\Eloquent\Model;

class dias_feriados extends Model
{
     protected $table = 'dias_feriados';
    protected $primaryKey = 'diaf_id';
    protected $fillable = ['diaf_feriado', 'diaf_desc', 'diaf_tife_id','diaf_status'];
}
