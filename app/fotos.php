<?php

namespace Asistencias;

use Illuminate\Database\Eloquent\Model;

class fotos extends Model
{
    protected $connection = 'fotos';
    protected $table = 'fotos';
    protected $primaryKey = 'ft_id';
    protected $fillable = ['ft_foto'];

}
