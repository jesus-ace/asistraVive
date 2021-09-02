<?php

namespace Asistencias;

use Illuminate\Database\Eloquent\Model;

class auditoria extends Model
{
    protected $table = 'auditoria';
    protected $primaryKey = 'aud_id';

    protected $fillable = ['aud_tipo','aud_desc','aud_fr','aud_us_id'];
}
