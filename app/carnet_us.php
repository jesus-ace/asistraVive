<?php

namespace Asistencias;

use Illuminate\Database\Eloquent\Model;

class carnet_us extends Model
{
    protected $table = 'carnet_us';
    protected $primaryKey = 'carus_id';
    protected $fillable = ['carus_ced','carus_codigo','carus_qr','carus_huella','carus_hxu_id', 'carus_status'];
}
