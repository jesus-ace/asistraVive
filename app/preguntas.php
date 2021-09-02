<?php

namespace Asistencias;

use Illuminate\Database\Eloquent\Model;

class preguntas extends Model
{
    protected $table = 'preguntas';
    public $timestamps = false;
    protected $primaryKey = 'prg_id';
    protected $fillable = ['prg_pregunta'];
}
