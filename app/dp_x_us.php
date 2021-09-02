<?php

namespace Asistencias;

use Illuminate\Database\Eloquent\Model;

class dp_x_us extends Model
{
    protected $table = 'dp_x_usu';
    protected $primaryKey = 'dxu_id';
    protected $fillable = ['dxu_us_ced','dxu_dp_id'];
    public $timestamps = false;

    public static function registra_dp($ced,$iddp)
    {
        $dxu = new dp_x_us;
        $dxu->dxu_us_ced = $ced;
        $dxu->dxu_dp_id = $iddp;
        $dxu->save();
    }
}
