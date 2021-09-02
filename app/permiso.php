<?php

namespace Asistencias;

use Illuminate\Database\Eloquent\Model;
use Asistencias\ex_x_usuario;
use DB;

class permiso extends Model
{
    protected $table = 'permiso';

    protected $primaryKey = 'per_id';
    protected $fillable = ['per_fecha_ini', 'per_fecha_fin', 'per_desc', 'per_remunerado', 'per_status'];


    protected static function permiso_acco($us_dep)
    {
        $dp_acco = dp_x_us::where('dxu_us_ced',$_SESSION['id'])->pluck('dxu_dp_id');

        $excepcion = DB::table('ex_x_usuario')
        ->join('usuarios', 'exu_ced', '=', 'us_ced')
        ->join('departamentos', 'us_dp_id', '=', 'dp_id')
        ->join('roles', 'us_ro_id','=','ro_id')
        ->join('excepciones','exu_ex_id','=', 'ex_id')
        ->join('permiso as per', 'ex_per_id', '=' , 'per_id')           
        ->whereIn('dp_id',$dp_acco)
        ->orderBy('per_fecha_ini', 'desc')
        ->where('per_status',1)
        ->Paginate(5);
        
        return $excepcion;
    }
}
