<?php

namespace Asistencias;

use Illuminate\Database\Eloquent\Model;

class autorizacion extends Model
{
    protected $table = 'autorizacion';

    protected $primaryKey = 'au_id';
    protected $fillable = ['au_permiso', 'au_desc', 'au_tiau_id', 'au_status'];
}
