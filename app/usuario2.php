<?php

namespace Asistencias;

use Illuminate\Database\Eloquent\Model;

class usuario2 extends Model
{
    protected $connection = 'vive_2016';
    protected $table ='sno_personalnomina as pe';
    protected $primaryKey = 'codper';
    
}
