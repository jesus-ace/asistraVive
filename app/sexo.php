<?php

namespace Asistencias;

use Illuminate\Database\Eloquent\Model;

class sexo extends Model
{
    protected $table = 'sexo';

    protected $primaryKey = 'sex_id';
    protected $fillable = ['sex_sexo', 'sex_status'];

}
