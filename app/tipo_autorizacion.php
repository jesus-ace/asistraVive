<?php

namespace Asistencias;

use Illuminate\Database\Eloquent\Model;

class tipo_autorizacion extends Model
{
    protected $table = 'tipo_autorizacion';

    protected $primaryKey = 'tiau_id';
    protected $fillable = ['tiau_tipo'];
}
