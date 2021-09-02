<?php

namespace Asistencias;

use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    protected $table = 'asistencia';    
    protected $primaryKey = 'asi_id';
    protected $fillable = ['asi_entrada', 'asi_entrada_hora', 'asi_salida', 'asi_salida_hora','asi_carus_id','asi_diaf_id'];

    protected function insert_entrada($carnet,$dia,$hora,$diaf)
    {
    	$asistencia = new Asistencia;
        $asistencia->asi_entrada = $dia;
        $asistencia->asi_carus_id =  $carnet;
        $asistencia->asi_entrada_hora = $hora;
        $asistencia->asi_diaf_id = $diaf;
        $asistencia->save();
        $insert = $asistencia->save();
        
        return $insert;
    }
    protected function b_usuario($carnet,$asi)
    {
    	$ced = asistencia::join('carnet_us','carus_id','=','asi_carus_id')
        ->join('horario_x_usuario','hxu_id','=','carus_hxu_id')
        ->join('usuarios','hxu_cedula','=','us_ced')
        ->join('departamentos','us_dp_id','=','dp_id')
        ->where('asi_carus_id',$carnet)
        ->where('asi_id',$asi)
        ->get()->pluck('us_ced')->last();
        return $ced;
    }

    protected function b_dep($carnet,$asi)
    {
    	$dep = asistencia::join('carnet_us','carus_id','=','asi_carus_id')
        ->join('horario_x_usuario','hxu_id','=','carus_hxu_id')
        ->join('usuarios','hxu_cedula','=','us_ced')
        ->join('departamentos','us_dp_id','=','dp_id')
        ->where('asi_carus_id',$carnet)
        ->where('asi_id',$asi)
        ->get()->pluck('dp_nombre')->last();
        return $dep;
    }
    protected function usuario_get($carnet,$fecha)
    {
    	$usuario = asistencia::join('carnet_us','carus_id','=','asi_carus_id')
        ->join('horario_x_usuario','hxu_id','=','carus_hxu_id')
        ->join('usuarios','hxu_cedula','=','us_ced')
        ->join('departamentos','us_dp_id','=','dp_id')
        ->where('asi_carus_id',$carnet)
        ->where('asi_entrada',$fecha)
        ->orderBy('asi_id','desc')
        ->take(1)
        ->get();
        return $usuario;
    }
    
}
