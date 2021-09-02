<?php

namespace Asistencias;

use Illuminate\Database\Eloquent\Model;

class coordinacion extends Model
{
    protected $table = 'coordinacions';
    protected $primaryKey = 'co_id';
    protected $fillable = ['co_nombre', 'co_vice_id','co_status'];
}
