<?php

namespace Asistencias;

use Illuminate\Database\Eloquent\Model;

class excepciones extends Model
{
    protected $table = 'excepciones';
    protected $primaryKey = 'ex_id';
    protected $fillable = ['ex_au_id','ex_re_id', 'ex_per_id', 'ex_vac_id','ex_status'];
}
