<?php

namespace Asistencias\Http\Controllers;

use Illuminate\Http\Request;

use Asistencias\Http\Requests;
use Asistencias\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Asistencias\Tipos_horarios;
use Asistencias\horario_x_usuario;
use Asistencias\dias_feriados;
use Asistencias\carnet_us;
use Asistencias\departamentos;
use Asistencias\Usuarios;
use Asistencias\usuario2;
use Asistencias\excepciones;
use Asistencias\ex_x_usuario;
use Asistencias\reposo;
use Asistencias\permiso;
use Asistencias\config;
use Asistencias\vacaciones;
use Asistencias\autorizacion;
use Asistencias\Asistencia;
use Asistencias\roles;
use Asistencias\acceso;
use Asistencias\dp_x_us;
use Asistencias\auditoria;
use Session;
use DB;
use PDF;
use Excel;
use Carbon\Carbon;
//sol123 sol321
class ControlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        session_start();
        if (isset($_SESSION['foto'])) {
            
            $dp_acco = dp_x_us::where('dxu_us_ced',$_SESSION['id'])->pluck('dxu_dp_id');

            $departamentos = DB::table('departamentos')
                ->whereIn('dp_id',$dp_acco)
                ->orderBy('dp_nombre','asc')
                ->get();

            $pdo = DB::table('carnet_us')
                ->join('horario_x_usuario','carus_hxu_id','=','hxu_id')
                ->join('usuarios','hxu_cedula','=','us_ced')
                ->join ('departamentos','us_dp_id','=','dp_id')
                ->join ('tipo_usuarios','us_tdu_id','=','tdu_id')
                ->orderBy('us_nom','asc')
                ->whereIn('dp_id',$dp_acco)
                ->where('us_status',1)
                ->Paginate(5);
            
            
            $rol = roles::where('ro_id',$_SESSION['acceso'])->get()->pluck('ro_id')->last();

            $aco_cnt = acceso::join('pantallas','aco_pnt_id','=','pnt_id')
            ->where('pnt_nombre','p_control')
            ->where('aco_ro_id',$rol)->get()->pluck('pnt_nombre')->last();
            $depp = '';

            return view('Asistencia/control', compact('pdo','departamentos','aco_cnt','depp'));
        }
        else{ 
            //Si la sesion no existe...
            Session::flash('session','A expirado la sesión');
            return view('login');
        } 
    }

    public function guardar_status(Request $data)
    {
        $habilitar = explode(',', $data["select1"]);
        $inhabilitar = explode(',', $data["select2"]);

        //Habilita las asistencias
        if (isset($habilitar)){
            $num_dias = count($habilitar)-2;
            $current = 0;
            for ($i=0; $i <= $num_dias; $i++) { 
                if ($current != $num_dias)
                {
                    $hab = DB::table('asistencia')
                    ->where('asi_id', $habilitar[$i])
                    ->update([ 'asi_status' => 1]);
                }
                else{
                    $hab = DB::table('asistencia')
                    ->where('asi_id', $habilitar[$i])
                    ->update([ 'asi_status' => 1]);
                }
                $current++;                
            }
        } 

        //Inhabilita las asistencias
        if (isset($inhabilitar)){
            $num_dias = count($inhabilitar)-2;
            $current = 0;
            for ($i=0; $i <= $num_dias; $i++) { 
                if ($current != $num_dias)
                {
                    $inah = DB::table('asistencia')
                    ->where('asi_id', $inhabilitar[$i])
                    ->update([ 'asi_status' => 0]);
                }
                else{
                    $inah = DB::table('asistencia')
                    ->where('asi_id', $inhabilitar[$i])
                    ->update([ 'asi_status' => 0]);
                }  
                $current++;             
            }
        }

    }


    public function asi_actual()
    {   
        session_start();
        $departamento = 'no';
        $sabdom = 'no';
        if (isset($_SESSION['foto'])) {
            $fecha = Carbon::now();
            $fecha->toDateString();
            $actual = substr($fecha, 0,10);

            $dp_acco = dp_x_us::where('dxu_us_ced',$_SESSION['id'])->pluck('dxu_dp_id');

            $departamentos = DB::table('departamentos')
            ->whereIn('dp_id',$dp_acco)
            ->orderBy('dp_nombre','asc')
            ->get();

            $asistencia = asistencia::where('asi_entrada',$actual)
            ->join('carnet_us','asi_carus_id','=','carus_id')
            ->join('horario_x_usuario','carus_hxu_id','=','hxu_id')
            ->join('usuarios','hxu_cedula','=','us_ced')
            ->join('departamentos','us_dp_id','=','dp_id')
            ->orderBy('asi_entrada_hora','desc')
            ->whereIn('dp_id',$dp_acco)
            ->get();

            $dia = $actual;
            $desde ='';
            $hasta ='';
            $usuario = DB::table('usuarios')->join('departamentos','us_dp_id','=','dp_id')->get();
            $fecha = $dia;
            $anio1 = substr($fecha,0,4);
            $mes1 = substr($fecha,5,2);
            $day1 = substr($fecha,8,2);

            $fecha = $day1.'-'.$mes1.'-'.$anio1;
            $tipo ='ASISTENCIA';
            $panel ='panel-success';
            return view('Asistencia/resumeng', compact('usuario','fecha','mes','anio','day','dia','desde','hasta','asistencia','tipo','panel','departamento','sabdom'));
        }
        else{ 
            //Si la sesion no existe...
            Session::flash('session','A expirado la sesión');
            return view('login');
        }
    }


    public function inasis_act()
    {
        session_start();
        $departamento = 'no';
        $sabdom = 'no';
        if (isset($_SESSION['foto'])) {
            $usuario = DB::table('usuarios')->where('us_ced',Input::get('cedula'))->join('departamentos','us_dp_id','=','dp_id')->get();
            $dia = new Carbon;
            $dia->toDateString();
            $desde = '0000-00-00';
            $hasta = '0000-00-00';
            $panel ='panel-danger';

            $us_dp = Usuarios::where('us_ced', $_SESSION['id'])->get()->pluck('us_dp_id')->last();
            $us_dep = departamentos::where('dp_id',$us_dp)->get()->pluck('dp_nombre')->last(); 

            $dp_acco = dp_x_us::where('dxu_us_ced',$_SESSION['id'])->pluck('dxu_dp_id');

            $carnet = asistencia::select('asi_carus_id')->where('asi_entrada',$dia->toDateString())->pluck('asi_carus_id');

            $reposo = DB::table('ex_x_usuario')
            ->join('usuarios','exu_ced','=','us_ced')
            ->join('excepciones','exu_ex_id','=','ex_id')
            ->join('reposo','ex_re_id','=','re_id')
            ->join('tipo_reposo','re_tire_id','=','tire_id')
            ->where('re_fecha_ini','<=',$dia->toDateString())
            ->where('re_fecha_fin','>=', $dia->toDateString())
            ->where('re_status',1)
            ->pluck('us_ced');
            

            
            $permisos = DB::table('ex_x_usuario')
            ->join('usuarios','exu_ced','=','us_ced')
            ->join('departamentos','us_dp_id','=','dp_id')
            ->join('roles','us_ro_id','=','ro_id')
            ->join('excepciones','exu_ex_id','=','ex_id')
            ->join('permiso as per','ex_per_id','=','per_id')
            ->where('per_fecha_ini','<=',$dia->toDateString())
            ->where('per_fecha_fin','>=', $dia->toDateString())
            ->where('per_status', 1)
            ->pluck('us_ced');

            $vacaciones =  DB::table('ex_x_usuario')
            ->join('usuarios','exu_ced','=','us_ced')
            ->join('departamentos','us_dp_id','=','dp_id')
            ->join('roles','us_ro_id','=','ro_id')
            ->join('excepciones','exu_ex_id','=','ex_id')
            ->join('vacaciones','ex_vac_id','=','vac_id')
            ->where('vac_fecha_ini','<=',$dia->toDateString())
            ->where('vac_status',1)
            ->where('vac_fecha_fin','>=', $dia->toDateString())
            ->pluck('us_ced');


            $asistencia = carnet_us::join('horario_x_usuario','hxu_id','=','carus_hxu_id')
            ->join('usuarios','us_ced','=','hxu_cedula')
            ->join('departamentos','dp_id','=','us_dp_id')
            ->where('us_status',1)
            ->whereNotIn('carus_id',$carnet)
            ->whereNotIn('us_ced',$reposo)
            ->whereNotIn('us_ced',$permisos)
            ->whereNotIn('us_ced',$vacaciones)
            ->whereIn('dp_id',$dp_acco)
            ->get();    
                       
                    
            $fecha = $dia->toDateString();
            
            $anio1 = substr($fecha,0,4);
            $mes1 = substr($fecha,5,2);
            $day1 = substr($fecha,8,2);

            $fecha = $day1.'-'.$mes1.'-'.$anio1;
            $tipo ='INASISTENCIA';
            return view('Asistencia/resumeng', compact('usuario','fecha','dia','desde','hasta','asistencia','tipo','panel','anio','mes','day','departamento','sabdom'));
        }
        else{ 
            //Si la sesion no existe...
            Session::flash('session','A expirado la sesión');
            return view('login');
        }
    }



    public function getDepartamento(Request $request, $id){
        if($request->ajax()){
            $departamento = departamentos::where('dp_co_id','=', $id)->get();
            return response()->json($departamento);
        }
    }
    public function getUsuarios(Request $request){
            if($request->ajax()){
            $usuario = DB::table('carnet_us')
            ->join('horario_x_usuario','carus_hxu_id','=','hxu_id')
            ->join('usuarios','hxu_cedula','=','us_ced')
            ->join ('departamentos','us_dp_id','=','dp_id')
            ->join ('tipo_usuarios','us_tdu_id','=','tdu_id')
            ->orderBy('us_nom','asc')
            ->where('us_status',1)
            ->get();
            return response()->json($usuario);
        }
    }

    public function controldp(Request $request)
    {
        session_start();        
        $pdo = DB::table('carnet_us')
        ->join('horario_x_usuario','carus_hxu_id','=','hxu_id')
        ->join('usuarios','hxu_cedula','=','us_ced')
        ->join ('departamentos','us_dp_id','=','dp_id')
        ->join ('tipo_usuarios','us_tdu_id','=','tdu_id')
        ->where('dp_id',$request->departamento)
        ->where('us_status',1)
        ->paginate(100);            
        $dp_acco = dp_x_us::where('dxu_us_ced',$_SESSION['id'])->pluck('dxu_dp_id');

        $departamentos = DB::table('departamentos')
            ->whereIn('dp_id',$dp_acco)
            ->orderBy('dp_nombre','asc')
            ->get();
        
        
        $rol = roles::where('ro_id',$_SESSION['acceso'])->get()->pluck('ro_id')->last();

        $aco_cnt = acceso::join('pantallas','aco_pnt_id','=','pnt_id')
        ->where('pnt_nombre','p_control')
        ->where('aco_ro_id',$rol)->get()->pluck('pnt_nombre')->last();
        $depp = $request->departamento;

        return view('Asistencia/control', compact('pdo','departamentos','aco_cnt','depp'));
        
    }

    public function controldpp($depto)
    {
        session_start();        
        $pdo = DB::table('carnet_us')
        ->join('horario_x_usuario','carus_hxu_id','=','hxu_id')
        ->join('usuarios','hxu_cedula','=','us_ced')
        ->join ('departamentos','us_dp_id','=','dp_id')
        ->join ('tipo_usuarios','us_tdu_id','=','tdu_id')
        ->where('dp_id',$depto)
        ->where('us_status',1)
        ->paginate(5);            
        $dp_acco = dp_x_us::where('dxu_us_ced',$_SESSION['id'])->pluck('dxu_dp_id');

        $departamentos = DB::table('departamentos')
            ->whereIn('dp_id',$dp_acco)
            ->orderBy('dp_nombre','asc')
            ->get();
        
        
        $rol = roles::where('ro_id',$_SESSION['acceso'])->get()->pluck('ro_id')->last();

        $aco_cnt = acceso::join('pantallas','aco_pnt_id','=','pnt_id')
        ->where('pnt_nombre','p_control')
        ->where('aco_ro_id',$rol)->get()->pluck('pnt_nombre')->last();
        $depp = $depto;

        return view('Asistencia/control', compact('pdo','departamentos','aco_cnt','depp'));
        
    }
    public function regresar_dpto(Request $request)
    {
        session_start();        
        $pdo = DB::table('carnet_us')
        ->join('horario_x_usuario','carus_hxu_id','=','hxu_id')
        ->join('usuarios','hxu_cedula','=','us_ced')
        ->join ('departamentos','us_dp_id','=','dp_id')
        ->join ('tipo_usuarios','us_tdu_id','=','tdu_id')
        ->where('dp_id',$request->departamento)
        ->where('us_status',1)
        ->paginate(100);            
        $dp_acco = dp_x_us::where('dxu_us_ced',$_SESSION['id'])->pluck('dxu_dp_id');

        $departamentos = DB::table('departamentos')
            ->whereIn('dp_id',$dp_acco)
            ->orderBy('dp_nombre','asc')
            ->get();
        
        
        $rol = roles::where('ro_id',$_SESSION['acceso'])->get()->pluck('ro_id')->last();

        $aco_cnt = acceso::join('pantallas','aco_pnt_id','=','pnt_id')
        ->where('pnt_nombre','p_control')
        ->where('aco_ro_id',$rol)->get()->pluck('pnt_nombre')->last();
        $depp = $request->departamento;

        return view('Asistencia/control', compact('pdo','departamentos','aco_cnt','depp')); 
    }


    public function getAsistencia(Request $request, $id)
    {
       if($request->ajax()){
            $usuario = DB::table('asistencia')
            ->join('carnet_us','asi_carus_id','=','carus_id')
            ->join ('horario_x_usuario','carus_hxu_id','=','hxu_id')
            ->join('tipos_horarios','hxu_tiho_id','=','tiho_id')
            ->join('usuarios','hxu_cedula','=','us_ced') 
            ->join('departamentos','us_dp_id','=','dp_id')
            ->join('tipo_usuarios','us_tdu_id','=','tdu_id')
            ->where('us_ced',$id)
            ->where('us_status',1)
            ->orderBy('asi_entrada','desc')
            ->get();
            return response()->json($usuario);
        }
    }
    public function getfecha()
    {
        session_start();
        $depto =  Input::get('depto');
        if (isset($_SESSION['foto'])) {
            $usuario = DB::table('usuarios')->where('us_ced',Input::get('cedula'))->join('departamentos','us_dp_id','=','dp_id')->get();

            $dFecha = new Carbon;
            $dFecha->toDateString();
            $dia = Input::get('dia');
            $hasta = Input::get('hasta');
            $desde =Input::get('desde');
            if (Input::get('asistencia')=='on') {
                $tipo ='ASISTENCIA';
                $panel ='panel-success';
                if (Input::get('asistencia')=='on') {
                    $tipo ='ASISTENCIA';
                    $panel ='panel-success';
                    if (Input::get('sabdomi') != '') {
                        $sabdom = 'sabdom';

                        if ($dia =='') {
                            $anio1 = substr($desde,0,4);
                            $mes1 = substr($desde,5,2);
                            $day1 = substr($desde,8,2);
                            $anio2 = substr($hasta,0,4);
                            $mes2 = substr($hasta,5,2);
                            $day2 = substr($hasta,8,2);
                            $fecha = $day1.'-'.$mes1.'-'.$anio1.'/'.$day2.'-'.$mes2.'-'.$anio2;
                        }
                        else{ 
                            $anio1 = substr($dia,0,4);
                            $mes1 = substr($dia,5,2);
                            $day1 = substr($dia,8,2);

                            $fecha = $day1.'-'.$mes1.'-'.$anio1;
                        }

                        $tipo ='ASISTENCIA';
                        $panel ='panel-success';
                        $asistencia = DB::table('asistencia')
                        ->join('carnet_us','carus_id','=','asi_carus_id')
                        ->join('horario_x_usuario','hxu_id','=','carus_hxu_id')
                        ->join('tipos_horarios','tiho_id','=','hxu_tiho_id')
                        ->join('usuarios','us_ced','=','hxu_cedula') 
                        ->join('departamentos','dp_id','=','us_dp_id')
                        ->join('tipo_usuarios','tdu_id','=','us_tdu_id')
                        ->where('us_ced',  Input::get('cedula'))
                        ->whereBetween('asi_entrada', [$desde, $hasta])
                        ->orderBy('asi_entrada','desc')
                        ->get(); 

                        return view('Asistencia/sabdomp', compact('asistencia','fecha','usuario','dia','desde','hasta','us','panel','tipo','anio','mes','day','departamento','sabdom','depto'));
                    }
                    elseif ($dia =='') {
                        $sabdom = 'no';
                        $asistencia = DB::table('asistencia')
                        ->join('carnet_us','carus_id','=','asi_carus_id')
                        ->join('horario_x_usuario','hxu_id','=','carus_hxu_id')
                        ->join('tipos_horarios','tiho_id','=','hxu_tiho_id')
                        ->join('usuarios','us_ced','=','hxu_cedula') 
                        ->join('departamentos','dp_id','=','us_dp_id')
                        ->join('tipo_usuarios','tdu_id','=','us_tdu_id')
                        ->where('us_ced',  Input::get('cedula'))
                        ->whereBetween('asi_entrada', [$desde, $hasta])
                        ->orderBy('asi_entrada','desc')
                        ->get(); 
                    }
                    else{
                        $sabdom = 'no';
                        $asistencia = DB::table('asistencia')
                        ->join('carnet_us','carus_id','=','asi_carus_id')
                        ->join('horario_x_usuario','hxu_id','=','carus_hxu_id')
                        ->join('tipos_horarios','tiho_id','=','hxu_tiho_id')
                        ->join('usuarios','us_ced','=','hxu_cedula') 
                        ->join('departamentos','dp_id','=','us_dp_id')
                        ->join('tipo_usuarios','tdu_id','=','us_tdu_id')
                        ->where('us_ced',  Input::get('cedula'))
                        ->where('asi_entrada', $dia)
                        ->orderBy('asi_entrada','desc')
                        ->get();
                    }
                }
                else{
                    $sabdom = 'no';
                    $tipo ='INASISTENCIA';
                    $panel ='panel-danger';
                     if ($dia =='') {
                        
                    }
                    else{
                        $carnet = asistencia::select('asi_carus_id')->where('asi_entrada',$dia)->pluck('asi_carus_id');
                                    
                        $reposo = DB::table('ex_x_usuario')
                        ->join('usuarios','exu_ced','=','us_ced')
                        ->join('excepciones','exu_ex_id','=','ex_id')
                        ->join('reposo','ex_re_id','=','re_id')
                        ->join('tipo_reposo','re_tire_id','=','tire_id')
                        ->where('re_fecha_ini','<=',$dia)
                        ->where('re_fecha_fin','>=', $dia)
                        ->where('us_ced',  Input::get('cedula'))
                        ->where('re_status', 1)
                        ->pluck('us_ced');
                
                        
                        $permisos = DB::table('ex_x_usuario')
                        ->join('usuarios','exu_ced','=','us_ced')
                        ->join('departamentos','us_dp_id','=','dp_id')
                        ->join('roles','us_ro_id','=','ro_id')
                        ->join('excepciones','exu_ex_id','=','ex_id')
                        ->join('permiso as per','ex_per_id','=','per_id')
                        ->where('per_fecha_ini','<=',$dia)
                        ->where('per_fecha_fin','>=', $dia)
                        ->where('per_status', 1)
                        ->where('us_ced',  Input::get('cedula'))
                        ->pluck('us_ced');

                        $vacaciones =  DB::table('ex_x_usuario')
                        ->join('usuarios','exu_ced','=','us_ced')
                        ->join('departamentos','us_dp_id','=','dp_id')
                        ->join('roles','us_ro_id','=','ro_id')
                        ->join('excepciones','exu_ex_id','=','ex_id')
                        ->join('vacaciones','ex_vac_id','=','vac_id')
                        ->where('vac_fecha_ini','<=',$dia)
                        ->where('vac_status',1)
                        ->where('us_ced',  Input::get('cedula'))
                        ->where('vac_fecha_fin','>=', $dia)
                        ->pluck('us_ced');


                        $asistencia = carnet_us::join('horario_x_usuario','carus_hxu_id','=','hxu_id')
                        ->join('usuarios','hxu_cedula','=','us_ced')
                        ->join('departamentos','us_dp_id','=','dp_id')
                        ->where('us_ced',  Input::get('cedula'))
                        ->whereNotIn('carus_id',$carnet)
                        ->whereNotIn('us_ced',$reposo)
                        ->whereNotIn('us_ced',$permisos)
                        ->whereNotIn('us_ced',$vacaciones) 
                        ->get();
                    }
                }

            
            if ($dia =='') {
                $anio1 = substr($desde,0,4);
                $mes1 = substr($desde,5,2);
                $day1 = substr($desde,8,2);
                $anio2 = substr($hasta,0,4);
                $mes2 = substr($hasta,5,2);
                $day2 = substr($hasta,8,2);
                $fecha = $day1.'-'.$mes1.'-'.$anio1.'/'.$day2.'-'.$mes2.'-'.$anio2;
            }
            else{ 
                $anio1 = substr($dia,0,4);
                $mes1 = substr($dia,5,2);
                $day1 = substr($dia,8,2);

                $fecha = $day1.'-'.$mes1.'-'.$anio1;
            }
            return view('Asistencia/resumen', compact('asistencia','fecha','usuario','dia','desde','hasta','panel','tipo','sabdom','depto'));
        }
        else{ 
            //Si la sesion no existe...
            Session::flash('session','A expirado la sesión');
            return view('login');
        }
    }}
    public function resumeng(Request $request)
    {
        session_start();
        if (isset($_SESSION['foto'])) {
            $usuario = DB::table('usuarios')->join('departamentos','us_dp_id','=','dp_id')->get();
            $us = DB::table('usuarios')->join('departamentos','us_dp_id','=','dp_id')->get()->pluck('us_ced')->last();
            $dia = Input::get('dia');
            $desde = Input::get('desde');
            $hasta = Input::get('hasta');            
            $dp_acco = dp_x_us::where('dxu_us_ced',$_SESSION['id'])->pluck('dxu_dp_id');
            if (isset($request->asistencia)) {
                if (isset($request->departamento) ) {
                    $departamento = $request->departamento;
                    if (isset($request->sabdom)) {
                        $sabdom = 'sabdom';

                        if ($dia =='') {
                            $anio1 = substr($desde,0,4);
                            $mes1 = substr($desde,5,2);
                            $day1 = substr($desde,8,2);
                            $anio2 = substr($hasta,0,4);
                            $mes2 = substr($hasta,5,2);
                            $day2 = substr($hasta,8,2);
                            $fecha = $day1.'-'.$mes1.'-'.$anio1.'/'.$day2.'-'.$mes2.'-'.$anio2;
                        }
                        else{ 
                            $anio1 = substr($dia,0,4);
                            $mes1 = substr($dia,5,2);
                            $day1 = substr($dia,8,2);

                            $fecha = $day1.'-'.$mes1.'-'.$anio1;
                        }

                        $tipo ='ASISTENCIA';
                        $panel ='panel-success';
                        $asistencia = DB::table('asistencia')
                        ->join('carnet_us','carus_id','=','asi_carus_id')
                        ->join('horario_x_usuario','hxu_id','=','carus_hxu_id')
                        ->join('tipos_horarios','tiho_id','=','hxu_tiho_id')
                        ->join('usuarios','us_ced','=','hxu_cedula') 
                        ->join('departamentos','dp_id','=','us_dp_id')
                        ->join('tipo_usuarios','tdu_id','=','us_tdu_id')
                        ->whereBetween('asi_entrada', [$desde, $hasta])
                        ->where('dp_id',$request->departamento)
                        ->orderBy('asi_entrada','desc')
                        ->groupBy('us_ced','asi_id','carus_id','hxu_id','tiho_id','dp_id','tdu_id')
                        ->get();

                        return view('Asistencia/sabdom', compact('asistencia','fecha','usuario','dia','desde','hasta','us','panel','tipo','anio','mes','day','departamento','sabdom'));
                    }
                    elseif ($dia =='') {

                        $sabdom = 'no';
                        $asistencia = DB::table('asistencia')
                        ->join('carnet_us','carus_id','=','asi_carus_id')
                        ->join('horario_x_usuario','hxu_id','=','carus_hxu_id')
                        ->join('tipos_horarios','tiho_id','=','hxu_tiho_id')
                        ->join('usuarios','us_ced','=','hxu_cedula') 
                        ->join('departamentos','dp_id','=','us_dp_id')
                        ->join('tipo_usuarios','tdu_id','=','us_tdu_id')
                        ->whereBetween('asi_entrada', [$desde, $hasta])
                        ->where('dp_id',$request->departamento)
                        ->orderBy('asi_entrada','desc')
                        ->groupBy('us_ced','asi_id','carus_id','hxu_id','tiho_id','dp_id','tdu_id')
                        ->get();
                    }
                    else{
                        $sabdom = 'no';
                        $asistencia = DB::table('asistencia')
                        ->join('carnet_us','carus_id','=','asi_carus_id')
                        ->join('horario_x_usuario','hxu_id','=','carus_hxu_id')
                        ->join('tipos_horarios','tiho_id','=','hxu_tiho_id')
                        ->join('usuarios','us_ced','=','hxu_cedula') 
                        ->join('departamentos','dp_id','=','us_dp_id')
                        ->join('tipo_usuarios','tdu_id','=','us_tdu_id')
                        ->where('asi_entrada', $dia)                    
                        ->where('dp_id',$request->departamento)
                        ->orderBy('asi_entrada','desc')
                        ->groupBy('us_ced','asi_id','carus_id','hxu_id','tiho_id','dp_id','tdu_id')
                        ->get();
                    }
                    
                }
                else{
                    $departamento = 'no';

                    if (isset($request->sabdom)) {
                        $sabdom = 'sabdom';

                        if ($dia =='') {
                            $anio1 = substr($desde,0,4);
                            $mes1 = substr($desde,5,2);
                            $day1 = substr($desde,8,2);
                            $anio2 = substr($hasta,0,4);
                            $mes2 = substr($hasta,5,2);
                            $day2 = substr($hasta,8,2);
                            $fecha = $day1.'-'.$mes1.'-'.$anio1.'/'.$day2.'-'.$mes2.'-'.$anio2;
                        }
                        else{ 
                            $anio1 = substr($dia,0,4);
                            $mes1 = substr($dia,5,2);
                            $day1 = substr($dia,8,2);

                            $fecha = $day1.'-'.$mes1.'-'.$anio1;
                        }

                        $tipo ='ASISTENCIA';
                        $panel ='panel-success';
                        $asistencia = DB::table('asistencia')
                        ->join('carnet_us','carus_id','=','asi_carus_id')
                        ->join('horario_x_usuario','hxu_id','=','carus_hxu_id')
                        ->join('tipos_horarios','tiho_id','=','hxu_tiho_id')
                        ->join('usuarios','us_ced','=','hxu_cedula') 
                        ->join('departamentos','dp_id','=','us_dp_id')
                        ->join('tipo_usuarios','tdu_id','=','us_tdu_id')
                        ->whereBetween('asi_entrada', [$desde, $hasta])
                        ->whereIn('dp_id',$dp_acco)
                        ->orderBy('asi_entrada','desc')
                        ->groupBy('us_ced','asi_id','carus_id','hxu_id','tiho_id','dp_id','tdu_id')
                        ->get();

                        return view('Asistencia/sabdom', compact('asistencia','fecha','usuario','dia','desde','hasta','us','panel','tipo','anio','mes','day','departamento','sabdom'));
                    }
                    elseif ($dia =='') {
                        $sabdom = 'no';
                        $asistencia = DB::table('asistencia')
                        ->join('carnet_us','carus_id','=','asi_carus_id')
                        ->join('horario_x_usuario','hxu_id','=','carus_hxu_id')
                        ->join('tipos_horarios','tiho_id','=','hxu_tiho_id')
                        ->join('usuarios','us_ced','=','hxu_cedula') 
                        ->join('departamentos','dp_id','=','us_dp_id')
                        ->join('tipo_usuarios','tdu_id','=','us_tdu_id')
                        ->whereBetween('asi_entrada', [$desde, $hasta])
                        ->whereIn('dp_id',$dp_acco)
                        ->orderBy('asi_entrada','desc')
                        ->groupBy('us_ced','asi_id','carus_id','hxu_id','tiho_id','dp_id','tdu_id')
                        ->get();
                    }
                    else{
                        
                        $sabdom = 'no';
                        $asistencia =DB::table('asistencia')
                         ->join('carnet_us','carus_id','=','asi_carus_id')
                        ->join('horario_x_usuario','hxu_id','=','carus_hxu_id')
                        ->join('tipos_horarios','tiho_id','=','hxu_tiho_id')
                        ->join('usuarios','us_ced','=','hxu_cedula') 
                        ->join('departamentos','dp_id','=','us_dp_id')
                        ->join('tipo_usuarios','tdu_id','=','us_tdu_id')
                        ->where('asi_entrada',$dia)
                        ->whereIn('dp_id',$dp_acco)
                        ->orderBy('asi_entrada','desc')
                        ->groupBy('us_ced','asi_id','carus_id','hxu_id','tiho_id','dp_id','tdu_id')
                        ->get();
                    }      
                }

                $tipo ='ASISTENCIA';
                $panel ='panel-success';
            }
            else{
                $sabdom = 'no';
                if (isset($request->departamento) ) {


                    $departamento = $request->departamento;
                    if ($dia =='') {

                        
                    }
                    else{
                        
                        $carnet = asistencia::select('asi_carus_id')->where('asi_entrada',$dia)->pluck('asi_carus_id');

                        $reposo = DB::table('ex_x_usuario')
                        ->join('usuarios','exu_ced','=','us_ced')
                        ->join('excepciones','exu_ex_id','=','ex_id')
                        ->join('reposo','ex_re_id','=','re_id')
                        ->join('tipo_reposo','re_tire_id','=','tire_id')
                        ->join('departamentos','us_dp_id','=','dp_id')
                        ->where('dp_id',$request->departamento)
                        ->where('re_fecha_ini','<=',$dia)
                        ->where('re_fecha_fin','>=', $dia)
                        ->where('re_status', 1)
                        ->pluck('us_ced');
                        

                        
                        $permisos = DB::table('ex_x_usuario')
                        ->join('usuarios','exu_ced','=','us_ced')
                        ->join('departamentos','us_dp_id','=','dp_id')
                        ->join('roles','us_ro_id','=','ro_id')
                        ->join('excepciones','exu_ex_id','=','ex_id')
                        ->join('permiso as per','ex_per_id','=','per_id')
                        ->where('per_fecha_ini','<=',$dia)
                        ->where('per_fecha_fin','>=', $dia)
                        ->where('dp_id',$request->departamento)
                        ->where('per_status', 1)
                        ->pluck('us_ced');

                        $vacaciones =  DB::table('ex_x_usuario')
                        ->join('usuarios','exu_ced','=','us_ced')
                        ->join('departamentos','us_dp_id','=','dp_id')
                        ->join('roles','us_ro_id','=','ro_id')
                        ->join('excepciones','exu_ex_id','=','ex_id')
                        ->join('vacaciones','ex_vac_id','=','vac_id')
                        ->where('vac_fecha_ini','<=',$dia)
                        ->where('vac_status',1)
                        ->where('vac_fecha_fin','>=', $dia)
                        ->where('dp_id',$request->departamento)
                        ->pluck('us_ced');


                        $asistencia = carnet_us::join('horario_x_usuario','hxu_id','=','carus_hxu_id')
                        ->join('usuarios','hxu_cedula','=','us_ced')
                        ->join('departamentos','dp_id','=','us_dp_id')
                        ->where('us_status',1)
                        ->where('dp_id',$request->departamento)
                        ->whereNotIn('carus_id',$carnet)
                        ->whereNotIn('us_ced',$reposo)
                        ->whereNotIn('us_ced',$permisos)
                        ->whereNotIn('us_ced',$vacaciones)
                        ->get();   
                    }  
                              

                }
                else{
                    $departamento = 'no';
                    //if ($_SESSION['rol'] =='SUPER ADMINISTRADOR') {

                    if ($dia =='0000-00-00') {

                        $carnet = asistencia::select('asi_carus_id','asi_entrada')->whereBetween('asi_entrada',[$desde,$hasta])->pluck('asi_carus_id');

                        $reposo = DB::table('ex_x_usuario')
                        ->join('usuarios','exu_ced','=','us_ced')
                        ->join('excepciones','exu_ex_id','=','ex_id')
                        ->join('reposo','ex_re_id','=','re_id')
                        ->join('tipo_reposo','re_tire_id','=','tire_id')
                        ->whereBetween('re_fecha_ini',[$desde,$hasta])
                        ->whereBetween('re_fecha_fin',[$desde,$hasta])
                        ->where('re_status', 1)
                        ->pluck('us_ced');
                        

                        
                        $permisos = DB::table('ex_x_usuario')
                        ->join('usuarios','exu_ced','=','us_ced')
                        ->join('departamentos','us_dp_id','=','dp_id')
                        ->join('roles','us_ro_id','=','ro_id')
                        ->join('excepciones','exu_ex_id','=','ex_id')
                        ->join('permiso as per','ex_per_id','=','per_id')
                        ->whereBetween('per_fecha_ini',[$desde,$hasta])
                        ->whereBetween('per_fecha_fin',[$desde,$hasta])
                        ->where('per_status', 1)
                        ->pluck('us_ced');

                        $vacaciones =  DB::table('ex_x_usuario')
                        ->join('usuarios','exu_ced','=','us_ced')
                        ->join('departamentos','us_dp_id','=','dp_id')
                        ->join('roles','us_ro_id','=','ro_id')
                        ->join('excepciones','exu_ex_id','=','ex_id')
                        ->join('vacaciones','ex_vac_id','=','vac_id')
                        ->whereBetween('vac_fecha_ini',[$desde,$hasta])
                        ->where('vac_status',1)
                        ->whereBetween('vac_fecha_fin',[$desde,$hasta])
                        ->pluck('us_ced');


                        $asistencia = carnet_us::join('horario_x_usuario','hxu_id','=','carus_hxu_id')
                        ->join('usuarios','us_ced','=','hxu_cedula')
                        ->join('departamentos','dp_id','=','us_dp_id')
                        ->where('us_status',1)
                        ->whereNotIn('carus_id',$carnet)
                        ->whereNotIn('us_ced',$reposo)
                        ->whereNotIn('us_ced',$permisos)
                        ->whereNotIn('us_ced',$vacaciones)
                        ->whereIn('dp_id',$dp_acco)
                        ->get();                            
                    }
                    else{
                        $carnet = asistencia::select('asi_carus_id')->where('asi_entrada',$dia)->pluck('asi_carus_id');

                        $reposo = DB::table('ex_x_usuario')
                        ->join('usuarios','exu_ced','=','us_ced')
                        ->join('excepciones','exu_ex_id','=','ex_id')
                        ->join('reposo','ex_re_id','=','re_id')
                        ->join('tipo_reposo','re_tire_id','=','tire_id')
                        ->where('re_fecha_ini','<=',$dia)
                        ->where('re_fecha_fin','>=', $dia)
                        ->where('re_status',1)
                        ->pluck('us_ced');
                        

                        
                        $permisos = DB::table('ex_x_usuario')
                        ->join('usuarios','exu_ced','=','us_ced')
                        ->join('departamentos','us_dp_id','=','dp_id')
                        ->join('roles','us_ro_id','=','ro_id')
                        ->join('excepciones','exu_ex_id','=','ex_id')
                        ->join('permiso as per','ex_per_id','=','per_id')
                        ->where('per_fecha_ini','<=',$dia)
                        ->where('per_fecha_fin','>=', $dia)
                        ->where('per_status', 1)
                        ->pluck('us_ced');

                        $vacaciones =  DB::table('ex_x_usuario')
                        ->join('usuarios','exu_ced','=','us_ced')
                        ->join('departamentos','us_dp_id','=','dp_id')
                        ->join('roles','us_ro_id','=','ro_id')
                        ->join('excepciones','exu_ex_id','=','ex_id')
                        ->join('vacaciones','ex_vac_id','=','vac_id')
                        ->where('vac_fecha_ini','<=',$dia)
                        ->where('vac_status',1)
                        ->where('vac_fecha_fin','>=', $dia)
                        ->pluck('us_ced');


                        $asistencia = carnet_us::join('horario_x_usuario','hxu_id','=','carus_hxu_id')
                        ->join('usuarios','us_ced','=','hxu_cedula')
                        ->join('departamentos','dp_id','=','us_dp_id')
                        ->where('us_status',1)
                        ->whereNotIn('carus_id',$carnet)
                        ->whereNotIn('us_ced',$reposo)
                        ->whereNotIn('us_ced',$permisos)
                        ->whereNotIn('us_ced',$vacaciones)
                        ->whereIn('dp_id',$dp_acco)
                        ->get();   
                    }  
                             
                }
                $tipo ='INASISTENCIA';
                $panel ='panel-danger';
            }
            if ($dia =='') {
                $anio1 = substr($desde,0,4);
                $mes1 = substr($desde,5,2);
                $day1 = substr($desde,8,2);
                $anio2 = substr($hasta,0,4);
                $mes2 = substr($hasta,5,2);
                $day2 = substr($hasta,8,2);
                $fecha = $day1.'-'.$mes1.'-'.$anio1.'/'.$day2.'-'.$mes2.'-'.$anio2;
            }
            else{ 
                $anio1 = substr($dia,0,4);
                $mes1 = substr($dia,5,2);
                $day1 = substr($dia,8,2);

                $fecha = $day1.'-'.$mes1.'-'.$anio1;
            }
            return view('Asistencia/resumeng', compact('asistencia','fecha','usuario','dia','desde','hasta','us','panel','tipo','anio','mes','day','departamento','sabdom'));
        }
        else{ 
            //Si la sesion no existe...
            Session::flash('session','A expirado la sesión');
            return view('login');
        } 
    }
    public function buscarexepRango(Request $request, $id, $desde, $hasta)
    {
        if($request->ajax()){
            $usuario = DB::table('ex_x_usuario')
            ->join('excepciones','exu_ex_id','=','ex_id')
            ->join('reposo','ex_re_id','=','re_id')
            ->join ('usuarios','exu_ced','=','us_ced')
            ->where('us_id',$id)
            ->whereBetween('re_fecha_ini',[Input::get('desde'), Input::get('hasta')])
            ->get();
            return response()->json($usuario);
        }
    }
    
    public function buscarexepDia(Request $request, $id, $dia)
    {
        if($request->ajax()){
            $usuario = DB::table('ex_x_usuario')
            ->join('excepciones','exu_ex_id','=','ex_id')
            ->join('reposo','ex_re_id','=','re_id')
            ->join ('usuarios','exu_ced','=','us_ced')
            ->where('us_id',$id)
            ->where('re_fecha_ini',Input::get('dia'))
            ->get();
            return response()->json($usuario);
        }
    }
    public function getRango(Request $request, $id, $desde, $hasta)
    {
        if($request->ajax()){
            $usuario = DB::table('asistencia')
            ->join('carnet_us','asi_carus_id','=','carus_id')
            ->join ('horario_x_usuario','carus_hxu_id','=','hxu_id')
            ->join('tipos_horarios','hxu_tiho_id','=','tiho_id')
            ->join ('usuarios','hxu_cedula','=','us_ced') 
            ->join ('departamentos','us_dp_id','=','dp_id')
            ->join ('tipo_usuarios','us_tdu_id','=','tdu_id')
            ->where('us_ced', $id)
            ->whereBetween('asi_entrada', [$desde, $hasta])
            ->orderBy('asi_entrada','desc')
            ->get();
            return response()->json($usuario);
        }        
    }
    public function getDia(Request $request, $id, $dia)
    {
        if($request->ajax()){
            $usuario = DB::table('asistencia')
            ->join('carnet_us','asi_carus_id','=','carus_id')
            ->join('horario_x_usuario','carus_hxu_id','=','hxu_id')
            ->join('tipos_horarios','hxu_tiho_id','=','tiho_id')
            ->join('usuarios','hxu_cedula','=','us_ced') 
            ->join('departamentos','us_dp_id','=','dp_id')
            ->join('tipo_usuarios','us_tdu_id','=','tdu_id')
            ->where('us_ced',$id)
            ->where('asi_entrada', $dia)
            ->orderBy('asi_entrada','desc')
            ->get();
            return response()->json($usuario);
        }        
    }



    public function getUsuarioc(Request $request, $ced){
       //if($request->ajax()){
        session_start();
            $dp_acco = dp_x_us::where('dxu_us_ced',$_SESSION['id'])->pluck('dxu_dp_id');

            $usuario = Usuarios::where('us_ced',$ced)
            ->join ('departamentos','us_dp_id','=','dp_id')
            ->join ('tipo_usuarios','us_tdu_id','=','tdu_id')
            ->whereIn('dp_id',$dp_acco)
            ->where('us_status',1)
            ->get();
            
            return $usuario;//response()->json($usuario);
        //}
    }


    public function getUsuariocj(Request $request, $ced){
        if($request->ajax()){
            $usuario = DB::table('asistencia')
            ->join('carnet_us','asi_carus_id','=','carus_id')
            ->join('horario_x_usuario','carus_hxu_id','=','hxu_id') 
            ->join('usuarios','hxu_cedula','=','us_ced') 
            ->join('departamentos','us_dp_id','=','dp_id')
            ->join('tipo_usuarios','us_tdu_id','=','tdu_id')
            ->where('us_ced',$ced)
            ->where('us_status',1)
            ->get();
            return response()->json($usuario);
        }
    }

    public function generar_pdf($ced,$dia,$desde,$hasta,$sabdom,$tipo){

        session_start();
        $usuario = DB::table('usuarios')->where('us_ced', $ced)->join('departamentos','us_dp_id','=','dp_id')->join('horario_x_usuario','hxu_cedula','=','us_ced')->join('tipos_horarios','hxu_tiho_id','=','tiho_id')->paginate(1);
        $sess = DB::table('usuarios')->where('us_ced', $_SESSION['id'])->join('departamentos','us_dp_id','=','dp_id')->join('horario_x_usuario','hxu_cedula','=','us_ced')->join('tipos_horarios','hxu_tiho_id','=','tiho_id')->paginate(1);
        if ($tipo =='ASISTENCIA') {
            if ($sabdom == 'sabdom') {
                $sabdom = 'sabdom';

                if ($dia =='') {
                    $anio1 = substr($desde,0,4);
                    $mes1 = substr($desde,5,2);
                    $day1 = substr($desde,8,2);
                    $anio2 = substr($hasta,0,4);
                    $mes2 = substr($hasta,5,2);
                    $day2 = substr($hasta,8,2);
                    $fecha = $day1.'-'.$mes1.'-'.$anio1.'/'.$day2.'-'.$mes2.'-'.$anio2;
                }
                else{ 
                    $anio1 = substr($dia,0,4);
                    $mes1 = substr($dia,5,2);
                    $day1 = substr($dia,8,2);

                    $fecha = $day1.'-'.$mes1.'-'.$anio1;
                }

                $tipo ='ASISTENCIA';
                $panel ='panel-success';
                $asistencia = DB::table('asistencia')
                ->join('carnet_us','asi_carus_id','=','carus_id')
                ->join('horario_x_usuario','carus_hxu_id','=','hxu_id')
                ->join('tipos_horarios','hxu_tiho_id','=','tiho_id')
                ->join('usuarios','hxu_cedula','=','us_ced') 
                ->join('departamentos','us_dp_id','=','dp_id')
                ->join('tipo_usuarios','us_tdu_id','=','tdu_id')
                ->where('us_ced',$ced)
                ->where('asi_status',1)
                ->whereBetween('asi_entrada', [$desde, $hasta])
                ->orderBy('asi_entrada','desc')
                ->get();

                $fecha = Carbon::now();
                $fecha->toDateString();
                //return View('Asistencia/pdf', compact('usuario','dia','desde','hasta','asistencia','fecha'));
                $pdf = PDF::loadView('Asistencia/sabdomp_pdf', compact('usuario','dia','desde','hasta','asistencia','fecha','dias_feriados','emitido','tipo','sabdom','panel','sess'))->setPaper('A4','landscape')->setWarnings(false);
                return $pdf->stream($ced.'_Resumen_de_asistencia.pdf');
            }
            elseif($dia =='0000-00-00') {
                $sabdom = 'no';
                $asistencia = DB::table('asistencia')
                ->join('carnet_us','asi_carus_id','=','carus_id')
                ->join('horario_x_usuario','carus_hxu_id','=','hxu_id')
                ->join('tipos_horarios','hxu_tiho_id','=','tiho_id')
                ->join('usuarios','hxu_cedula','=','us_ced') 
                ->join('departamentos','us_dp_id','=','dp_id')
                ->join('tipo_usuarios','us_tdu_id','=','tdu_id')
                ->where('us_ced',$ced)
                ->where('asi_status',1)
                ->whereBetween('asi_entrada', [$desde, $hasta])
                ->orderBy('asi_entrada','desc')
                ->get();
            }
            else{
                $sabdom = 'no';
                $asistencia = DB::table('asistencia')
                    ->join('carnet_us','carus_id','=','asi_carus_id')
                    ->join('horario_x_usuario','carus_hxu_id','=','hxu_id')
                    ->join('tipos_horarios','hxu_tiho_id','=','tiho_id')
                    ->join('usuarios','hxu_cedula','=','us_ced') 
                    ->join('departamentos','us_dp_id','=','dp_id')
                    ->join('tipo_usuarios','us_tdu_id','=','tdu_id')
                    ->where('us_ced',  $ced)
                    ->where('asi_status',1)
                    ->where('asi_entrada', $dia)
                    ->orderBy('asi_entrada','desc')
                    ->get();
            }   
        }
        else{
            $sabdom = 'no';
            if ($dia =='0000-00-00') {
                    
            }
            else{
                $carnet = asistencia::select('asi_carus_id')->where('asi_entrada',$dia)->pluck('asi_carus_id');
                            
                $reposo = DB::table('ex_x_usuario')
                ->join('usuarios','exu_ced','=','us_ced')
                ->join('excepciones','exu_ex_id','=','ex_id')
                ->join('reposo','ex_re_id','=','re_id')
                ->join('tipo_reposo','re_tire_id','=','tire_id')
                ->where('re_fecha_ini','<=',$dia)
                ->where('re_fecha_fin','>=',$dia)
                ->where('us_ced',$ced)
                ->where('re_status',1)
                ->pluck('us_ced');
        
                
                $permisos = DB::table('ex_x_usuario')
                ->join('usuarios','exu_ced','=','us_ced')
                ->join('departamentos','us_dp_id','=','dp_id')
                ->join('roles','us_ro_id','=','ro_id')
                ->join('excepciones','exu_ex_id','=','ex_id')
                ->join('permiso as per','ex_per_id','=','per_id')
                ->where('per_fecha_ini','<=',$dia)
                ->where('per_fecha_fin','>=',$dia)
                ->where('per_status',1)
                ->where('us_ced',$ced)
                ->pluck('us_ced');

                $vacaciones =  DB::table('ex_x_usuario')
                ->join('usuarios','exu_ced','=','us_ced')
                ->join('departamentos','us_dp_id','=','dp_id')
                ->join('roles','us_ro_id','=','ro_id')
                ->join('excepciones','exu_ex_id','=','ex_id')
                ->join('vacaciones','ex_vac_id','=','vac_id')
                ->where('vac_fecha_ini','<=',$dia)
                ->where('vac_status',1)
                ->where('us_ced',$ced)
                ->where('vac_fecha_fin','>=',$dia)
                ->pluck('us_ced');


                $asistencia = carnet_us::join('horario_x_usuario','carus_hxu_id','=','hxu_id')
                ->join('usuarios','hxu_cedula','=','us_ced')
                ->join('departamentos','us_dp_id','=','dp_id')
                ->where('us_ced',  $ced)
                ->whereNotIn('carus_id',$carnet)
                ->whereNotIn('us_ced',$reposo)
                ->whereNotIn('us_ced',$permisos)
                ->whereNotIn('us_ced',$vacaciones) 
                ->get();
            }
        }
        
        $fecha = Carbon::now();
        $fecha->toDateString();


        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $navegador = $this->getBrowser($user_agent);

        //Registramos en la tabla de auditoría
        $aud = new auditoria;
        $audId ='aud_id';
        $aud->aud_tipo = strtoupper('Impresion de reporte PDF');
        $aud->aud_desc = strtoupper('Impresion de reporte en PDF general del usuario:'.$ced);
        $aud->aud_machine_ip = $_SERVER['REMOTE_ADDR'];
        $aud->aud_machine_name = gethostname();
        $aud->aud_machine_os =PHP_OS.'-'.php_uname();
        $aud->aud_machine_explorer = $navegador;
        $aud->aud_ced = $_SESSION['id'];
        $aud->aud_fecha =$fecha;
        $aud->save();
        //return View('Asistencia/pdf', compact('usuario','dia','desde','hasta','asistencia','fecha'));
        $pdf = PDF::loadView('Asistencia/pdf', compact('usuario','dia','desde','hasta','asistencia','fecha','dias_feriados','emitido','tipo','sabdom','sess'))->setPaper('A4','landscape')->setWarnings(false);
        return $pdf->stream($ced.'_Resumen_de_asistencia.pdf');
    }

    public function getBrowser($user_agent){

        if(strpos($user_agent,'MSIE') !== FALSE)
            return'Internet explorer';
        elseif(strpos($user_agent,'Edge') !== FALSE) //Microsoft Edge
            return'Microsoft Edge';
        elseif(strpos($user_agent,'Trident') !== FALSE) //IE 11
            return'Internet explorer';
        elseif(strpos($user_agent,'Opera Mini') !== FALSE)
            return "Opera Mini";
        elseif(strpos($user_agent,'Opera') || strpos($user_agent,'OPR') !== FALSE)
            return "Opera";
        elseif(strpos($user_agent,'Firefox') !== FALSE)
            return'Mozilla Firefox';
        elseif(strpos($user_agent,'Chrome') !== FALSE)
            return'Google Chrome';
        elseif(strpos($user_agent,'Safari') !== FALSE)
            return "Safari";
        else
            return'No hemos podido detectar su navegador';
    }



    public function generar_pdf_g($dia,$desde,$hasta,$departamento,$sabdom,$tipo){

       session_start();
        $sess = DB::table('usuarios')->where('us_ced', $_SESSION['id'])->join('departamentos','us_dp_id','=','dp_id')->join('horario_x_usuario','hxu_cedula','=','us_ced')->join('tipos_horarios','hxu_tiho_id','=','tiho_id')->paginate(1);

        $dp_acco = dp_x_us::where('dxu_us_ced',$_SESSION['id'])->pluck('dxu_dp_id');
        if (isset($_SESSION['foto'])) {
            $us = DB::table('usuarios')->join('departamentos','us_dp_id','=','dp_id')->get()->pluck('us_ced')->last();
            if ($tipo =='ASISTENCIA') {
                
                if ($departamento != 'no' ) {
                    if ($sabdom == 'sabdom') {
                        $usuario = DB::table('usuarios')->where('us_ced', $_SESSION['id'])->join('departamentos','us_dp_id','=','dp_id')->join('horario_x_usuario','hxu_cedula','=','us_ced')->join('tipos_horarios','hxu_tiho_id','=','tiho_id')->paginate(1);
                        $tipo ='ASISTENCIA';
                        $panel ='panel-success';
                        $asistencia = DB::table('asistencia')
                        ->join('carnet_us','carus_id','=','asi_carus_id')
                        ->join('horario_x_usuario','hxu_id','=','carus_hxu_id')
                        ->join('tipos_horarios','tiho_id','=','hxu_tiho_id')
                        ->join('usuarios','us_ced','=','hxu_cedula') 
                        ->join('departamentos','dp_id','=','us_dp_id')
                        ->join('tipo_usuarios','tdu_id','=','us_tdu_id')
                        ->whereBetween('asi_entrada', [$desde, $hasta])
                        ->where('dp_id',$departamento)
                        ->orderBy('us_nom','asc')
                        ->orderBy('asi_entrada','desc')
                        ->groupBy('us_ced','asi_id','carus_id','hxu_id','tiho_id','dp_id','tdu_id')
                        ->get();

                        $fecha = Carbon::now();
                        $fecha->toDateString();

                        if ($departamento != '') {
                            $dp = $departamento;
                        }
                        else{
                            $dp = $dp_acco;
                        }
                        if ($departamento == 'no') {
                            $dp = 'General';
                        }

                        $user_agent = $_SERVER['HTTP_USER_AGENT'];
                        $navegador = $this->getBrowser($user_agent);

                        //Registramos en la tabla de auditoría
                        $aud = new auditoria;
                        $audId ='aud_id';
                        $aud->aud_tipo = strtoupper('Impresion de reporte PDF');
                        $aud->aud_desc = strtoupper('Impresion de reporte en PDF sabados y domingos general:'.$dp);
                        $aud->aud_machine_ip = $_SERVER['REMOTE_ADDR'];
                        $aud->aud_machine_name = gethostname();
                        $aud->aud_machine_os =PHP_OS.'-'.php_uname();
                        $aud->aud_machine_explorer = $navegador;
                        $aud->aud_ced = $_SESSION['id'];
                        $aud->aud_fecha =$fecha;
                        $aud->save();


                        $hora = $fecha->subHour(4)->toTimeString(); 
                        
                        $anio1 = substr($fecha,0,4);
                        $mes1 = substr($fecha,5,2);
                        $day1 = substr($fecha,8,2);

                        $fecha = $day1.'-'.$mes1.'-'.$anio1;
                        $pdf = PDF::loadView('Asistencia/sabdom_pdf', compact('usuario','dia','desde','hasta','asistencia','fecha','dias_feriados','tipo','sess','hora'))->setPaper('A4','landscape')->setWarnings(false);
                        return $pdf->stream('_Resumen_de_asistencia.pdf');   
                    }
                    elseif ($dia =='0000-00-00') {

                        $asistencia = DB::table('asistencia')
                        ->join('carnet_us','asi_carus_id','=','carus_id')
                        ->join('horario_x_usuario','carus_hxu_id','=','hxu_id')
                        ->join('tipos_horarios','hxu_tiho_id','=','tiho_id')
                        ->join('usuarios','hxu_cedula','=','us_ced') 
                        ->join('departamentos','us_dp_id','=','dp_id')
                        ->join('tipo_usuarios','us_tdu_id','=','tdu_id')
                        ->whereBetween('asi_entrada', [$desde, $hasta])                  
                        ->where('dp_id',$departamento)
                        ->where('asi_status',1)
                        ->orderBy('us_nom','asc')
                        ->orderBy('asi_entrada','desc')
                        ->get();
                    }
                    else{
                        
                        $asistencia = DB::table('asistencia')
                        ->join('carnet_us','asi_carus_id','=','carus_id')
                        ->join ('horario_x_usuario','carus_hxu_id','=','hxu_id')
                        ->join('tipos_horarios','hxu_tiho_id','=','tiho_id')
                        ->join('usuarios','hxu_cedula','=','us_ced') 
                        ->join('departamentos','us_dp_id','=','dp_id')
                        ->join('tipo_usuarios','us_tdu_id','=','tdu_id')
                        ->where('asi_entrada', $dia)                    
                        ->where('dp_id',$departamento)
                        ->where('asi_status',1)
                        ->orderBy('us_nom','asc')
                        ->orderBy('asi_entrada','desc')
                        ->groupBy('us_ced','asi_id','carus_id','hxu_id','tiho_id','dp_id','tdu_id')
                        ->get();
                    }
                    
                }
                else{

                    $dp_acco = dp_x_us::where('dxu_us_ced',$_SESSION['id'])->pluck('dxu_dp_id');
                    //if ($_SESSION['rol'] =='SUPER ADMINISTRADOR') {

                    if ($sabdom == 'sabdom') {
                        $usuario = DB::table('usuarios')->where('us_ced', $_SESSION['id'])->join('departamentos','us_dp_id','=','dp_id')->join('horario_x_usuario','hxu_cedula','=','us_ced')->join('tipos_horarios','hxu_tiho_id','=','tiho_id')->paginate(1);
                        $tipo ='ASISTENCIA';
                        $panel ='panel-success';

                        $asistencia = DB::table('asistencia')
                        ->join('carnet_us','carus_id','=','asi_carus_id')
                        ->join('horario_x_usuario','hxu_id','=','carus_hxu_id')
                        ->join('tipos_horarios','tiho_id','=','hxu_tiho_id')
                        ->join('usuarios','us_ced','=','hxu_cedula') 
                        ->join('departamentos','dp_id','=','us_dp_id')
                        ->join('tipo_usuarios','tdu_id','=','us_tdu_id')
                        ->whereBetween('asi_entrada', [$desde, $hasta])
                        ->whereIn('dp_id',$dp_acco)
                        ->orderBy('us_nom','asc')
                        ->orderBy('asi_entrada','desc')
                        ->groupBy('us_ced','asi_id','carus_id','hxu_id','tiho_id','dp_id','tdu_id')
                        ->get();

                        $fecha = Carbon::now();
                        $fecha->toDateString();


                        if ($departamento != '') {
                            $dp = $departamento;
                        }
                        else{
                            $dp = $dp_acco;
                        }
                        if ($departamento == 'no') {
                            $dp = 'General';
                        }

                        $user_agent = $_SERVER['HTTP_USER_AGENT'];
                        $navegador = $this->getBrowser($user_agent);

                        //Registramos en la tabla de auditoría
                        $aud = new auditoria;
                        $audId ='aud_id';
                        $aud->aud_tipo = strtoupper('Impresion de reporte PDF');
                        $aud->aud_desc = strtoupper('Impresion de reporte en PDF sabados y domingos general:'.$dp);
                        $aud->aud_machine_ip = $_SERVER['REMOTE_ADDR'];
                        $aud->aud_machine_name = gethostname();
                        $aud->aud_machine_os =PHP_OS.'-'.php_uname();
                        $aud->aud_machine_explorer = $navegador;
                        $aud->aud_ced = $_SESSION['id'];
                        $aud->aud_fecha =$fecha;
                        $aud->save();

                        

                        $hora = $fecha->subHour(4)->toTimeString(); 
                        
                        $anio1 = substr($fecha,0,4);
                        $mes1 = substr($fecha,5,2);
                        $day1 = substr($fecha,8,2);

                        $fecha = $day1.'-'.$mes1.'-'.$anio1;
                        $pdf = PDF::loadView('Asistencia/sabdom_pdf', compact('usuario','dia','desde','hasta','asistencia','fecha','dias_feriados','tipo','sess','hora'))->setPaper('A4','landscape')->setWarnings(false);
                        return $pdf->stream('_Resumen_de_asistencia.pdf');   
                    }
                    elseif ($dia =='0000-00-00') {
                        
                        $asistencia = DB::table('asistencia')
                        ->join('carnet_us','asi_carus_id','=','carus_id')
                        ->join('horario_x_usuario','carus_hxu_id','=','hxu_id')
                        ->join('tipos_horarios','hxu_tiho_id','=','tiho_id')
                        ->join('usuarios','hxu_cedula','=','us_ced') 
                        ->join('departamentos','us_dp_id','=','dp_id')
                        ->join('tipo_usuarios','us_tdu_id','=','tdu_id')
                        ->whereBetween('asi_entrada', [$desde, $hasta])
                        ->whereIn('dp_id',$dp_acco)
                        ->where('asi_status',1)
                        ->orderBy('us_nom','asc')
                        ->orderBy('asi_entrada','desc')
                        ->get();
                    }
                    else{
                        $asistencia = DB::table('asistencia')
                        ->join('carnet_us','asi_carus_id','=','carus_id')
                        ->join('horario_x_usuario','carus_hxu_id','=','hxu_id')
                        ->join('tipos_horarios','hxu_tiho_id','=','tiho_id')
                        ->join('usuarios','hxu_cedula','=','us_ced') 
                        ->join('departamentos','us_dp_id','=','dp_id')
                        ->join('tipo_usuarios','us_tdu_id','=','tdu_id')
                        ->where('asi_entrada',$dia)
                        ->whereIn('dp_id',$dp_acco)
                        ->where('asi_status',1)
                        ->orderBy('us_nom','asc')
                        ->orderBy('asi_entrada','desc')
                        ->get();
                    }          
                }            
            }
            else{

                if ($departamento != 'no' ) {
                    if ($dia =='') {

                        
                    }
                    else{
                        $carnet = asistencia::select('asi_carus_id')->where('asi_entrada',$dia)->pluck('asi_carus_id');

                        $reposo = DB::table('ex_x_usuario')
                        ->join('usuarios','exu_ced','=','us_ced')
                        ->join('excepciones','exu_ex_id','=','ex_id')
                        ->join('reposo','ex_re_id','=','re_id')
                        ->join('tipo_reposo','re_tire_id','=','tire_id')
                        ->where('re_fecha_ini','<=',$dia)
                        ->where('re_fecha_fin','>=', $dia)
                        ->where('re_status', 1)
                        ->pluck('us_ced');
                        

                        
                        $permisos = DB::table('ex_x_usuario')
                        ->join('usuarios','exu_ced','=','us_ced')
                        ->join('departamentos','us_dp_id','=','dp_id')
                        ->join('roles','us_ro_id','=','ro_id')
                        ->join('excepciones','exu_ex_id','=','ex_id')
                        ->join('permiso as per','ex_per_id','=','per_id')
                        ->where('per_fecha_ini','<=',$dia)
                        ->where('per_fecha_fin','>=', $dia)
                        ->where('per_status', 1)
                        ->pluck('us_ced');

                        $vacaciones =  DB::table('ex_x_usuario')
                        ->join('usuarios','exu_ced','=','us_ced')
                        ->join('departamentos','us_dp_id','=','dp_id')
                        ->join('roles','us_ro_id','=','ro_id')
                        ->join('excepciones','exu_ex_id','=','ex_id')
                        ->join('vacaciones','ex_vac_id','=','vac_id')
                        ->where('vac_fecha_ini','<=',$dia)
                        ->where('vac_status',1)
                        ->where('vac_fecha_fin','>=', $dia)
                        ->pluck('us_ced');


                        $asistencia = carnet_us::join('horario_x_usuario','carus_hxu_id','=','hxu_id')
                        ->join('usuarios','hxu_cedula','=','us_ced')
                        ->join('departamentos','us_dp_id','=','dp_id')
                        ->whereNotIn('carus_id',$carnet)
                        ->whereNotIn('us_ced',$reposo)
                        ->whereNotIn('us_ced',$permisos)
                        ->whereNotIn('us_ced',$vacaciones)
                        ->where('dp_id',$departamento)
                        ->get();   
                    }                    
                }
                else{

                    if ($dia =='0000-00-00') {

                        $carnet = asistencia::select('asi_carus_id','asi_entrada')->whereBetween('asi_entrada',[$desde,$hasta])->pluck('asi_carus_id');

                        $reposo = DB::table('ex_x_usuario')
                        ->join('usuarios','exu_ced','=','us_ced')
                        ->join('excepciones','exu_ex_id','=','ex_id')
                        ->join('reposo','ex_re_id','=','re_id')
                        ->join('tipo_reposo','re_tire_id','=','tire_id')
                        ->whereBetween('re_fecha_ini',[$desde,$hasta])
                        ->whereBetween('re_fecha_fin',[$desde,$hasta])
                        ->where('re_status', 1)
                        ->pluck('us_ced');
                        

                        
                        $permisos = DB::table('ex_x_usuario')
                        ->join('usuarios','exu_ced','=','us_ced')
                        ->join('departamentos','us_dp_id','=','dp_id')
                        ->join('roles','us_ro_id','=','ro_id')
                        ->join('excepciones','exu_ex_id','=','ex_id')
                        ->join('permiso as per','ex_per_id','=','per_id')
                        ->whereBetween('per_fecha_ini',[$desde,$hasta])
                        ->whereBetween('per_fecha_fin',[$desde,$hasta])
                        ->where('per_status', 1)
                        ->pluck('us_ced');

                        $vacaciones =  DB::table('ex_x_usuario')
                        ->join('usuarios','exu_ced','=','us_ced')
                        ->join('departamentos','us_dp_id','=','dp_id')
                        ->join('roles','us_ro_id','=','ro_id')
                        ->join('excepciones','exu_ex_id','=','ex_id')
                        ->join('vacaciones','ex_vac_id','=','vac_id')
                        ->whereBetween('vac_fecha_ini',[$desde,$hasta])
                        ->where('vac_status',1)
                        ->whereBetween('vac_fecha_fin',[$desde,$hasta])
                        ->pluck('us_ced');


                        $asistencia = carnet_us::join('horario_x_usuario','carus_hxu_id','=','hxu_id')
                        ->join('usuarios','hxu_cedula','=','us_ced')
                        ->join('departamentos','us_dp_id','=','dp_id')
                        ->join('carnet_us','carus_hxu_id','=','hxu_id')
                        ->whereNotIn('carus_id',$carnet)
                        ->whereNotIn('us_ced',$reposo)
                        ->whereNotIn('us_ced',$permisos)
                        ->whereNotIn('us_ced',$vacaciones)
                        ->whereIn('dp_id',$dp_acco)
                        ->get();                            
                    }
                    else{
                        $carnet = asistencia::select('asi_carus_id')->where('asi_entrada',$dia)->pluck('asi_carus_id');

                        $reposo = DB::table('ex_x_usuario')
                        ->join('usuarios','exu_ced','=','us_ced')
                        ->join('excepciones','exu_ex_id','=','ex_id')
                        ->join('reposo','ex_re_id','=','re_id')
                        ->join('tipo_reposo','re_tire_id','=','tire_id')
                        ->where('re_fecha_ini','<=',$dia)
                        ->where('re_fecha_fin','>=', $dia)
                        ->where('re_status', 1)
                        ->pluck('us_ced');
                        

                        
                        $permisos = DB::table('ex_x_usuario')
                        ->join('usuarios','exu_ced','=','us_ced')
                        ->join('departamentos','us_dp_id','=','dp_id')
                        ->join('roles','us_ro_id','=','ro_id')
                        ->join('excepciones','exu_ex_id','=','ex_id')
                        ->join('permiso as per','ex_per_id','=','per_id')
                        ->where('per_fecha_ini','<=',$dia)
                        ->where('per_fecha_fin','>=', $dia)
                        ->where('per_status', 1)
                        ->pluck('us_ced');

                        $vacaciones =  DB::table('ex_x_usuario')
                        ->join('usuarios','exu_ced','=','us_ced')
                        ->join('departamentos','us_dp_id','=','dp_id')
                        ->join('roles','us_ro_id','=','ro_id')
                        ->join('excepciones','exu_ex_id','=','ex_id')
                        ->join('vacaciones','ex_vac_id','=','vac_id')
                        ->where('vac_fecha_ini','<=',$dia)
                        ->where('vac_status',1)
                        ->where('vac_fecha_fin','>=', $dia)
                        ->pluck('us_ced');


                        $asistencia = carnet_us::join('horario_x_usuario','carus_hxu_id','=','hxu_id')
                        ->join('usuarios','hxu_cedula','=','us_ced')
                        ->join('departamentos','us_dp_id','=','dp_id')
                        ->whereNotIn('carus_id',$carnet)
                        ->whereNotIn('us_ced',$reposo)
                        ->whereNotIn('us_ced',$permisos)
                        ->whereNotIn('us_ced',$vacaciones)
                        ->whereIn('dp_id',$dp_acco)
                        ->get();   
                    }  
                    
                }  
            }

            $fecha = Carbon::now();
            $fecha->toDateString();     

            if ($departamento != '') {
                $dp = $departamento;
            }
            else{
                $dp = $dp_acco;
            }
            if ($departamento == 'no') {
                $dp = 'General';
            }

            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            $navegador = $this->getBrowser($user_agent);

            //Registramos en la tabla de auditoría
            $aud = new auditoria;
            $audId ='aud_id';
            $aud->aud_tipo = strtoupper('Impresion de reporte PDF');
            $aud->aud_desc = strtoupper('Impresion de reporte en PDF general:'.$dp);
            $aud->aud_machine_ip = $_SERVER['REMOTE_ADDR'];
            $aud->aud_machine_name = gethostname();
            $aud->aud_machine_os =PHP_OS.'-'.php_uname();
            $aud->aud_machine_explorer = $navegador;
            $aud->aud_ced = $_SESSION['id'];
            $aud->aud_fecha =$fecha;
            $aud->save();


            $hora = $fecha->subHour(4)->toTimeString(); 
            
            $anio1 = substr($fecha,0,4);
            $mes1 = substr($fecha,5,2);
            $day1 = substr($fecha,8,2);

            $fecha = $day1.' / '.$mes1.' / '.$anio1;
            $pdf = PDF::loadView('Asistencia/pdfgeneral', compact('usuario','dia','desde','hasta','asistencia','fecha','dias_feriados','tipo','hora','sess'))->setPaper('A4','landscape')->setWarnings(false);
            return $pdf->stream('_Resumen_de_asistencia.pdf');         
        }
        else{ 
            //Si la sesion no existe...
            Session::flash('session','A expirado la sesión');
            return view('login');
        }  
        
    }
    public function generarexcelsabdom($ced,$dia,$desde,$hasta,$sabdom,$tipo)
    {
        session_start();
        Excel::create("Resumen de asistencia del empleado ".$ced, function ($excel) use ($ced,$dia,$desde,$hasta,$sabdom,$tipo) {
            $excel->sheet('usuarios', function($sheet) use ($ced,$dia,$desde,$hasta,$sabdom,$tipo)
            {   
                $usuario = DB::table('usuarios')->where('us_ced',$ced)->join('departamentos','us_dp_id','=','dp_id')->get();
                if ($tipo =='ASISTENCIA') {
                    $tipo ='ASISTENCIA';
                    $panel ='panel-success';

                    if ($dia =='0000-00-00') {
                        $asistencia = DB::table('asistencia')
                        ->join('carnet_us','carus_id','=','asi_carus_id')
                        ->join('horario_x_usuario','hxu_id','=','carus_hxu_id')
                        ->join('tipos_horarios','tiho_id','=','hxu_tiho_id')
                        ->join('usuarios','us_ced','=','hxu_cedula') 
                        ->join('departamentos','dp_id','=','us_dp_id')
                        ->join('tipo_usuarios','tdu_id','=','us_tdu_id')
                        ->where('us_ced',$ced)
                        ->where('asi_status',1)
                        ->whereBetween('asi_entrada', [$desde, $hasta])
                        ->orderBy('asi_entrada','desc')
                        ->get(); 
                    }
                    else{
                        $asistencia = DB::table('asistencia')
                        ->join('carnet_us','carus_id','=','asi_carus_id')
                        ->join('horario_x_usuario','hxu_id','=','carus_hxu_id')
                        ->join('tipos_horarios','tiho_id','=','hxu_tiho_id')
                        ->join('usuarios','us_ced','=','hxu_cedula') 
                        ->join('departamentos','dp_id','=','us_dp_id')
                        ->join('tipo_usuarios','tdu_id','=','us_tdu_id')
                        ->where('us_ced',$ced)
                        ->where('asi_entrada', $dia)
                        ->where('asi_status',1)
                        ->orderBy('asi_entrada','desc')
                        ->get();
                    }
                }
                else{
                    $tipo ='INASISTENCIA';
                    $panel ='panel-danger';
                     if ($dia =='0000-00-00') {
                        
                    }
                    else{
                        $carnet = asistencia::select('asi_carus_id')->where('asi_entrada',$dia)->where('asi_status',1)->pluck('asi_carus_id');
                                    
                        $reposo = DB::table('ex_x_usuario')
                        ->join('usuarios','exu_ced','=','us_ced')
                        ->join('excepciones','exu_ex_id','=','ex_id')
                        ->join('reposo','ex_re_id','=','re_id')
                        ->join('tipo_reposo','re_tire_id','=','tire_id')
                        ->where('re_fecha_ini','<=',$dia)
                        ->where('re_fecha_fin','>=', $dia)
                        ->where('us_ced',$ced)
                        ->where('re_status', 1)
                        ->pluck('us_ced');
                
                        
                        $permisos = DB::table('ex_x_usuario')
                        ->join('usuarios','exu_ced','=','us_ced')
                        ->join('departamentos','us_dp_id','=','dp_id')
                        ->join('roles','us_ro_id','=','ro_id')
                        ->join('excepciones','exu_ex_id','=','ex_id')
                        ->join('permiso as per','ex_per_id','=','per_id')
                        ->where('per_fecha_ini','<=',$dia)
                        ->where('per_fecha_fin','>=', $dia)
                        ->where('per_status', 1)
                        ->where('us_ced',$ced)
                        ->pluck('us_ced');

                        $vacaciones =  DB::table('ex_x_usuario')
                        ->join('usuarios','exu_ced','=','us_ced')
                        ->join('departamentos','us_dp_id','=','dp_id')
                        ->join('roles','us_ro_id','=','ro_id')
                        ->join('excepciones','exu_ex_id','=','ex_id')
                        ->join('vacaciones','ex_vac_id','=','vac_id')
                        ->where('vac_fecha_ini','<=',$dia)
                        ->where('vac_status',1)
                        ->where('us_ced',$ced)
                        ->where('vac_fecha_fin','>=', $dia)
                        ->pluck('us_ced');


                        $asistencia = carnet_us::join('horario_x_usuario','carus_hxu_id','=','hxu_id')
                        ->join('usuarios','hxu_cedula','=','us_ced')
                        ->join('departamentos','us_dp_id','=','dp_id')
                        ->where('us_ced',$ced)
                        ->whereNotIn('carus_id',$carnet)
                        ->whereNotIn('us_ced',$reposo)
                        ->whereNotIn('us_ced',$permisos)
                        ->whereNotIn('us_ced',$vacaciones) 
                        ->get();
                    }
                }
                $fecha = Carbon::now();
                $fecha->toDateString();


                $user_agent = $_SERVER['HTTP_USER_AGENT'];
                $navegador = $this->getBrowser($user_agent);

                //Registramos en la tabla de auditoría
                $aud = new auditoria;
                $audId ='aud_id';
                $aud->aud_tipo = strtoupper('Impresion de reporte EXCEL');
                $aud->aud_desc = strtoupper('Impresion de reporte en EXCEL de sabados y domingos del empleado:'.$ced);
                $aud->aud_machine_ip = $_SERVER['REMOTE_ADDR'];
                $aud->aud_machine_name = gethostname();
                $aud->aud_machine_os =PHP_OS.'-'.php_uname();
                $aud->aud_machine_explorer = $navegador;
                $aud->aud_ced = $_SESSION['id'];
                $aud->aud_fecha =$fecha;
                $aud->save();
                
                if ($dia =='0000-00-00') {
                    $anio1 = substr($desde,0,4);
                    $mes1 = substr($desde,5,2);
                    $day1 = substr($desde,8,2);
                    $anio2 = substr($hasta,0,4);
                    $mes2 = substr($hasta,5,2);
                    $day2 = substr($hasta,8,2);
                    $fecha = $day1.'-'.$mes1.'-'.$anio1.'/'.$day2.'-'.$mes2.'-'.$anio2;
                }
                else{ 
                    $anio1 = substr($dia,0,4);
                    $mes1 = substr($dia,5,2);
                    $day1 = substr($dia,8,2);

                    $fecha = $day1.'-'.$mes1.'-'.$anio1;
                }
                $departamento = 'no';

                $sheet->setFontFamily('Droid Sans Georgian');
                $sheet->setFontSize(12);
                $sheet->setFontBold(false);

                // Sets all borders
                $sheet->setAllBorders('thin');

                // Set border for cells
                $sheet->setBorder('A1', 'thin');

                // Set border for range
                $sheet->setBorder('A1:F10', 'thin');
                $sheet->mergeCells('E5:G5');
                $sheet->loadView('Asistencia/sabdom_excelp', compact('asistencia','usuario','fecha','dia','desde','hasta','panel','tipo','departamento'));
            });            
        })->export('xls');
    }

    public function generar_excel($ced,$dia,$desde,$hasta,$sabdom,$tipo)
    {
        session_start();
        Excel::create("Resumen de asistencia del empleado ".$ced, function ($excel) use ($ced,$dia,$desde,$hasta,$sabdom,$tipo) {
            $excel->sheet('usuarios', function($sheet) use ($ced,$dia,$desde,$hasta,$sabdom,$tipo)
            {   
                $usuario = DB::table('usuarios')->where('us_ced',$ced)->join('departamentos','us_dp_id','=','dp_id')->get();
                if ($tipo =='ASISTENCIA') {
                    $tipo ='ASISTENCIA';
                    $panel ='panel-success';
                    if ($dia =='0000-00-00') {
                        $asistencia = DB::table('asistencia')
                        ->join('carnet_us','carus_id','=','asi_carus_id')
                        ->join('horario_x_usuario','hxu_id','=','carus_hxu_id')
                        ->join('tipos_horarios','tiho_id','=','hxu_tiho_id')
                        ->join('usuarios','us_ced','=','hxu_cedula') 
                        ->join('departamentos','dp_id','=','us_dp_id')
                        ->join('tipo_usuarios','tdu_id','=','us_tdu_id')
                        ->where('us_ced',$ced)
                        ->where('asi_status',1)
                        ->whereBetween('asi_entrada', [$desde, $hasta])
                        ->orderBy('asi_entrada','desc')
                        ->get(); 
                    }
                    else{
                        $asistencia = DB::table('asistencia')
                        ->join('carnet_us','carus_id','=','asi_carus_id')
                        ->join('horario_x_usuario','hxu_id','=','carus_hxu_id')
                        ->join('tipos_horarios','tiho_id','=','hxu_tiho_id')
                        ->join('usuarios','us_ced','=','hxu_cedula') 
                        ->join('departamentos','dp_id','=','us_dp_id')
                        ->join('tipo_usuarios','tdu_id','=','us_tdu_id')
                        ->where('us_ced',$ced)
                        ->where('asi_entrada', $dia)
                        ->where('asi_status',1)
                        ->orderBy('asi_entrada','desc')
                        ->get();
                    }
                }
                else{
                    $tipo ='INASISTENCIA';
                    $panel ='panel-danger';
                     if ($dia =='0000-00-00') {
                        
                    }
                    else{
                        $carnet = asistencia::select('asi_carus_id')->where('asi_entrada',$dia)->where('asi_status',1)->pluck('asi_carus_id');
                                    
                        $reposo = DB::table('ex_x_usuario')
                        ->join('usuarios','exu_ced','=','us_ced')
                        ->join('excepciones','exu_ex_id','=','ex_id')
                        ->join('reposo','ex_re_id','=','re_id')
                        ->join('tipo_reposo','re_tire_id','=','tire_id')
                        ->where('re_fecha_ini','<=',$dia)
                        ->where('re_fecha_fin','>=', $dia)
                        ->where('us_ced',$ced)
                        ->where('re_status', 1)
                        ->pluck('us_ced');
                
                        
                        $permisos = DB::table('ex_x_usuario')
                        ->join('usuarios','exu_ced','=','us_ced')
                        ->join('departamentos','us_dp_id','=','dp_id')
                        ->join('roles','us_ro_id','=','ro_id')
                        ->join('excepciones','exu_ex_id','=','ex_id')
                        ->join('permiso as per','ex_per_id','=','per_id')
                        ->where('per_fecha_ini','<=',$dia)
                        ->where('per_fecha_fin','>=', $dia)
                        ->where('per_status', 1)
                        ->where('us_ced',$ced)
                        ->pluck('us_ced');

                        $vacaciones =  DB::table('ex_x_usuario')
                        ->join('usuarios','exu_ced','=','us_ced')
                        ->join('departamentos','us_dp_id','=','dp_id')
                        ->join('roles','us_ro_id','=','ro_id')
                        ->join('excepciones','exu_ex_id','=','ex_id')
                        ->join('vacaciones','ex_vac_id','=','vac_id')
                        ->where('vac_fecha_ini','<=',$dia)
                        ->where('vac_status',1)
                        ->where('us_ced',$ced)
                        ->where('vac_fecha_fin','>=', $dia)
                        ->pluck('us_ced');


                        $asistencia = carnet_us::join('horario_x_usuario','carus_hxu_id','=','hxu_id')
                        ->join('usuarios','hxu_cedula','=','us_ced')
                        ->join('departamentos','us_dp_id','=','dp_id')
                        ->where('us_ced',$ced)
                        ->whereNotIn('carus_id',$carnet)
                        ->whereNotIn('us_ced',$reposo)
                        ->whereNotIn('us_ced',$permisos)
                        ->whereNotIn('us_ced',$vacaciones) 
                        ->get();
                    }
                }
                $fecha = Carbon::now();
                $fecha->toDateString();

                $user_agent = $_SERVER['HTTP_USER_AGENT'];
                $navegador = $this->getBrowser($user_agent);

                //Registramos en la tabla de auditoría
                $aud = new auditoria;
                $audId ='aud_id';
                $aud->aud_tipo = strtoupper('Impresion de reporte PDF');
                $aud->aud_desc = strtoupper('Impresion de reporte en EXCEL del empleado:'.$ced);
                $aud->aud_machine_ip = $_SERVER['REMOTE_ADDR'];
                $aud->aud_machine_name = gethostname();
                $aud->aud_machine_os =PHP_OS.'-'.php_uname();
                $aud->aud_machine_explorer = $navegador;
                $aud->aud_ced = $_SESSION['id'];
                $aud->aud_fecha =$fecha;
                $aud->save();
                
                if ($dia =='0000-00-00') {
                    $anio1 = substr($desde,0,4);
                    $mes1 = substr($desde,5,2);
                    $day1 = substr($desde,8,2);
                    $anio2 = substr($hasta,0,4);
                    $mes2 = substr($hasta,5,2);
                    $day2 = substr($hasta,8,2);
                    $fecha = $day1.'-'.$mes1.'-'.$anio1.'/'.$day2.'-'.$mes2.'-'.$anio2;
                }
                else{ 
                    $anio1 = substr($dia,0,4);
                    $mes1 = substr($dia,5,2);
                    $day1 = substr($dia,8,2);

                    $fecha = $day1.'-'.$mes1.'-'.$anio1;
                }
                $departamento = 'no';

                $sheet->setFontFamily('Droid Sans Georgian');
                $sheet->setFontSize(12);
                $sheet->setFontBold(false);

                // Sets all borders
                $sheet->setAllBorders('thin');

                // Set border for cells
                $sheet->setBorder('A1', 'thin');

                // Set border for range
                $sheet->setBorder('A1:F10', 'thin');
                $sheet->mergeCells('E5:G5');
                $sheet->loadView('Asistencia/excel_empleado', compact('asistencia','usuario','fecha','dia','desde','hasta','panel','tipo','departamento'));
            });            
        })->export('xls');
    }

    public function generarexcelgsabdom($dia,$desde,$hasta,$departamento,$sabdom,$tipo)
    {
        session_start();
        Excel::create("Resumen de asistencia general", function ($excel) use ($dia,$desde,$hasta,$departamento,$sabdom,$tipo) {

            $excel->sheet('usuarios', function($sheet) use ($dia,$desde,$hasta,$departamento,$sabdom,$tipo)
            {
                $fecha = Carbon::now();
                $fecha->toDateString();
                $usuario = DB::table('usuarios')->where('us_ced',$_SESSION['id'])->join('departamentos','us_dp_id','=','dp_id')->get();
                
                if ($dia =='0000-00-00') {
                    $anio1 = substr($desde,0,4);
                    $mes1 = substr($desde,5,2);
                    $day1 = substr($desde,8,2);
                    $anio2 = substr($hasta,0,4);
                    $mes2 = substr($hasta,5,2);
                    $day2 = substr($hasta,8,2);
                    $fecha = $day1.'-'.$mes1.'-'.$anio1.'/'.$day2.'-'.$mes2.'-'.$anio2;
                }
                else{ 
                    $anio1 = substr($dia,0,4);
                    $mes1 = substr($dia,5,2);
                    $day1 = substr($dia,8,2);

                    $fecha = $day1.'-'.$mes1.'-'.$anio1;
                }
                if ($tipo =='ASISTENCIA') {
                
                    if ($departamento != 'no' ) {
                        if ($dia =='0000-00-00') {

                            $asistencia = DB::table('asistencia')
                            ->join('carnet_us','asi_carus_id','=','carus_id')
                            ->join('horario_x_usuario','carus_hxu_id','=','hxu_id')
                            ->join('tipos_horarios','hxu_tiho_id','=','tiho_id')
                            ->join('usuarios','hxu_cedula','=','us_ced') 
                            ->join('departamentos','us_dp_id','=','dp_id')
                            ->join('tipo_usuarios','us_tdu_id','=','tdu_id')
                            ->whereBetween('asi_entrada',[$desde,$hasta])
                            ->where('dp_id',$departamento)
                            ->where('asi_status',1)
                            ->orderBy('us_nom','asc')
                            ->orderBy('asi_entrada','asc')
                            ->groupBy('us_ced','asi_id','carus_id','hxu_id','tiho_id','dp_id','tdu_id')
                            ->get();
                        }
                        else{
                            
                            $asistencia = DB::table('asistencia')
                            ->join('carnet_us','asi_carus_id','=','carus_id')
                            ->join ('horario_x_usuario','carus_hxu_id','=','hxu_id')
                            ->join('tipos_horarios','hxu_tiho_id','=','tiho_id')
                            ->join('usuarios','hxu_cedula','=','us_ced') 
                            ->join('departamentos','us_dp_id','=','dp_id')
                            ->join('tipo_usuarios','us_tdu_id','=','tdu_id')
                            ->where('asi_entrada', $dia)                    
                            ->where('dp_id',$departamento)
                            ->where('asi_status',1)
                            ->orderBy('us_nom','asc')
                            ->orderBy('asi_entrada','asc')
                            ->groupBy('us_ced','asi_id','carus_id','hxu_id','tiho_id','dp_id','tdu_id')
                            ->get();
                        }
                        
                    }
                    else{

                        $dp_acco = dp_x_us::where('dxu_us_ced',$_SESSION['id'])->pluck('dxu_dp_id');

                        
                        if ($dia =='0000-00-00') {
                            $asistencia = DB::table('asistencia')
                            ->join('carnet_us','asi_carus_id','=','carus_id')
                            ->join('horario_x_usuario','carus_hxu_id','=','hxu_id')
                            ->join('tipos_horarios','hxu_tiho_id','=','tiho_id')
                            ->join('usuarios','hxu_cedula','=','us_ced') 
                            ->join('departamentos','us_dp_id','=','dp_id')
                            ->join('tipo_usuarios','us_tdu_id','=','tdu_id')
                            ->whereBetween('asi_entrada', [$desde, $hasta])
                            ->whereIn('dp_id',$dp_acco)
                            ->where('asi_status',1)
                            ->orderBy('us_nom','asc')
                            ->orderBy('asi_entrada','asc')
                            ->get();
                        }
                        else{
                            $asistencia = DB::table('asistencia')
                            ->join('carnet_us','asi_carus_id','=','carus_id')
                            ->join('horario_x_usuario','carus_hxu_id','=','hxu_id')
                            ->join('tipos_horarios','hxu_tiho_id','=','tiho_id')
                            ->join('usuarios','hxu_cedula','=','us_ced') 
                            ->join('departamentos','us_dp_id','=','dp_id')
                            ->join('tipo_usuarios','us_tdu_id','=','tdu_id')
                            ->where('asi_entrada',$dia)
                            ->whereIn('dp_id',$dp_acco)
                            ->where('asi_status',1)
                            ->orderBy('us_nom','asc')
                            ->orderBy('asi_entrada','asc')
                            ->get();
                        }           
                    }            
                }
                else{
                    $dp_acco = dp_x_us::where('dxu_us_ced',$_SESSION['id'])->pluck('dxu_dp_id');
                    if ($departamento != 'no' ) {
                        if ($dia =='0000-00-00') {

                            
                        }
                        else{
                            $carnet = asistencia::select('asi_carus_id')->where('asi_entrada',$dia)->where('asi_status',1)->pluck('asi_carus_id');

                            $reposo = DB::table('ex_x_usuario')
                            ->join('usuarios','exu_ced','=','us_ced')
                            ->join('excepciones','exu_ex_id','=','ex_id')
                            ->join('reposo','ex_re_id','=','re_id')
                            ->join('tipo_reposo','re_tire_id','=','tire_id')
                            ->where('re_fecha_ini','<=',$dia)
                            ->where('re_fecha_fin','>=', $dia)
                            ->where('re_status', 1)
                            ->pluck('us_ced');
                            

                            
                            $permisos = DB::table('ex_x_usuario')
                            ->join('usuarios','exu_ced','=','us_ced')
                            ->join('departamentos','us_dp_id','=','dp_id')
                            ->join('roles','us_ro_id','=','ro_id')
                            ->join('excepciones','exu_ex_id','=','ex_id')
                            ->join('permiso as per','ex_per_id','=','per_id')
                            ->where('per_fecha_ini','<=',$dia)
                            ->where('per_fecha_fin','>=', $dia)
                            ->where('per_status', 1)
                            ->pluck('us_ced');

                            $vacaciones =  DB::table('ex_x_usuario')
                            ->join('usuarios','exu_ced','=','us_ced')
                            ->join('departamentos','us_dp_id','=','dp_id')
                            ->join('roles','us_ro_id','=','ro_id')
                            ->join('excepciones','exu_ex_id','=','ex_id')
                            ->join('vacaciones','ex_vac_id','=','vac_id')
                            ->where('vac_fecha_ini','<=',$dia)
                            ->where('vac_status',1)
                            ->where('vac_fecha_fin','>=', $dia)
                            ->pluck('us_ced');


                            $asistencia = carnet_us::join('horario_x_usuario','carus_hxu_id','=','hxu_id')
                            ->join('usuarios','hxu_cedula','=','us_ced')
                            ->join('departamentos','us_dp_id','=','dp_id')
                            ->where('us_status',1)
                            ->whereNotIn('carus_id',$carnet)
                            ->whereNotIn('us_ced',$reposo)
                            ->whereNotIn('us_ced',$permisos)
                            ->whereNotIn('us_ced',$vacaciones)
                            ->where('dp_id',$departamento)
                            ->get();   
                        }                    
                    }
                    else{


                        if ($dia =='0000-00-00') {

                            $carnet = asistencia::select('asi_carus_id','asi_entrada')->where('asi_status',1)->whereBetween('asi_entrada',[$desde,$hasta])->pluck('asi_carus_id');

                            $reposo = DB::table('ex_x_usuario')
                            ->join('usuarios','exu_ced','=','us_ced')
                            ->join('excepciones','exu_ex_id','=','ex_id')
                            ->join('reposo','ex_re_id','=','re_id')
                            ->join('tipo_reposo','re_tire_id','=','tire_id')
                            ->whereBetween('re_fecha_ini',[$desde,$hasta])
                            ->whereBetween('re_fecha_fin',[$desde,$hasta])
                            ->where('re_status', 1)
                            ->pluck('us_ced');
                            

                            
                            $permisos = DB::table('ex_x_usuario')
                            ->join('usuarios','exu_ced','=','us_ced')
                            ->join('departamentos','us_dp_id','=','dp_id')
                            ->join('roles','us_ro_id','=','ro_id')
                            ->join('excepciones','exu_ex_id','=','ex_id')
                            ->join('permiso as per','ex_per_id','=','per_id')
                            ->whereBetween('per_fecha_ini',[$desde,$hasta])
                            ->whereBetween('per_fecha_fin',[$desde,$hasta])
                            ->where('per_status', 1)
                            ->pluck('us_ced');

                            $vacaciones =  DB::table('ex_x_usuario')
                            ->join('usuarios','exu_ced','=','us_ced')
                            ->join('departamentos','us_dp_id','=','dp_id')
                            ->join('roles','us_ro_id','=','ro_id')
                            ->join('excepciones','exu_ex_id','=','ex_id')
                            ->join('vacaciones','ex_vac_id','=','vac_id')
                            ->whereBetween('vac_fecha_ini',[$desde,$hasta])
                            ->where('vac_status',1)
                            ->whereBetween('vac_fecha_fin',[$desde,$hasta])
                            ->pluck('us_ced');


                            $asistencia = carnet_us::join('horario_x_usuario','carus_hxu_id','=','hxu_id')
                            ->join('usuarios','hxu_cedula','=','us_ced')
                            ->join('departamentos','us_dp_id','=','dp_id')
                            ->join('carnet_us','carus_hxu_id','=','hxu_id')
                            ->where('us_status',1)
                            ->whereNotIn('carus_id',$carnet)
                            ->whereNotIn('us_ced',$reposo)
                            ->whereNotIn('us_ced',$permisos)
                            ->whereNotIn('us_ced',$vacaciones)
                            ->whereIn('dp_id',$dp_acco)
                            ->get();                            
                        }
                        else{
                            $carnet = asistencia::select('asi_carus_id')->where('asi_status',1)->where('asi_entrada',$dia)->pluck('asi_carus_id');

                            $reposo = DB::table('ex_x_usuario')
                            ->join('usuarios','exu_ced','=','us_ced')
                            ->join('excepciones','exu_ex_id','=','ex_id')
                            ->join('reposo','ex_re_id','=','re_id')
                            ->join('tipo_reposo','re_tire_id','=','tire_id')
                            ->where('re_fecha_ini','<=',$dia)
                            ->where('re_fecha_fin','>=', $dia)
                            ->where('re_status', 1)
                            ->pluck('us_ced');
                            

                            
                            $permisos = DB::table('ex_x_usuario')
                            ->join('usuarios','exu_ced','=','us_ced')
                            ->join('departamentos','us_dp_id','=','dp_id')
                            ->join('roles','us_ro_id','=','ro_id')
                            ->join('excepciones','exu_ex_id','=','ex_id')
                            ->join('permiso as per','ex_per_id','=','per_id')
                            ->where('per_fecha_ini','<=',$dia)
                            ->where('per_fecha_fin','>=', $dia)
                            ->where('per_status', 1)
                            ->pluck('us_ced');

                            $vacaciones =  DB::table('ex_x_usuario')
                            ->join('usuarios','exu_ced','=','us_ced')
                            ->join('departamentos','us_dp_id','=','dp_id')
                            ->join('roles','us_ro_id','=','ro_id')
                            ->join('excepciones','exu_ex_id','=','ex_id')
                            ->join('vacaciones','ex_vac_id','=','vac_id')
                            ->where('vac_fecha_ini','<=',$dia)
                            ->where('vac_status',1)
                            ->where('vac_fecha_fin','>=', $dia)
                            ->pluck('us_ced');


                            $asistencia = carnet_us::join('horario_x_usuario','carus_hxu_id','=','hxu_id')
                            ->join('usuarios','hxu_cedula','=','us_ced')
                            ->join('departamentos','us_dp_id','=','dp_id')
                            ->where('us_status',1)
                            ->whereNotIn('carus_id',$carnet)
                            ->whereNotIn('us_ced',$reposo)
                            ->whereNotIn('us_ced',$permisos)
                            ->whereNotIn('us_ced',$vacaciones)
                            ->whereIn('dp_id',$dp_acco)
                            ->get();   
                        }  
                                 
                    }  
                }

                $fecha = Carbon::now();
                $fecha->toDateString();

                if ($departamento != '') {
                    $dp = $departamento;
                }
                else{
                    $dp = $dp_acco;
                }
                if ($departamento == 'no') {
                    $dp = 'General';
                }

                $user_agent = $_SERVER['HTTP_USER_AGENT'];
                $navegador = $this->getBrowser($user_agent);

                //Registramos en la tabla de auditoría
                $aud = new auditoria;
                $audId ='aud_id';
                $aud->aud_tipo = strtoupper('Impresion de reporte PDF');
                $aud->aud_desc = strtoupper('Impresion de reporte en EXCEL general de sabados y domingos:'.$dp);
                $aud->aud_machine_ip = $_SERVER['REMOTE_ADDR'];
                $aud->aud_machine_name = gethostname();
                $aud->aud_machine_os =PHP_OS.'-'.php_uname();
                $aud->aud_machine_explorer = $navegador;
                $aud->aud_ced = $_SESSION['id'];
                $aud->aud_fecha =$fecha;
                $aud->save();


                $usuario = DB::table('usuarios')->where('us_ced', $_SESSION['id'])->join('departamentos','us_dp_id','=','dp_id')->join('horario_x_usuario','hxu_cedula','=','us_ced')->join('tipos_horarios','hxu_tiho_id','=','tiho_id')->paginate(1);                

                $sheet->setFontFamily('Droid Sans Georgian');
                $sheet->setFontSize(12);
                $sheet->setFontBold(false);



                // Set border for range
                $sheet->setBorder('A1:A1', 'thin');
                #
                $sheet->loadView('Asistencia/sabdom_excelg', compact('asistencia','usuario','fecha','dia','desde','hasta','panel','tipo','departamento'));
            });            
        })->export('xls');
    }

    public function generar_excel_g($dia,$desde,$hasta,$departamento,$sabdom,$tipo)
    {   session_start();

        Excel::create("Resumen de asistencia general", function ($excel) use ($dia,$desde,$hasta,$departamento,$tipo) {

            $excel->sheet('usuarios', function($sheet) use ($dia,$desde,$hasta,$departamento,$tipo)
            {
                $fecha = Carbon::now();
                $fecha->toDateString();
                
                if ($dia =='0000-00-00') {
                    $anio1 = substr($desde,0,4);
                    $mes1 = substr($desde,5,2);
                    $day1 = substr($desde,8,2);
                    $anio2 = substr($hasta,0,4);
                    $mes2 = substr($hasta,5,2);
                    $day2 = substr($hasta,8,2);
                    $fecha = $day1.'-'.$mes1.'-'.$anio1.'/'.$day2.'-'.$mes2.'-'.$anio2;
                }
                else{ 
                    $anio1 = substr($dia,0,4);
                    $mes1 = substr($dia,5,2);
                    $day1 = substr($dia,8,2);

                    $fecha = $day1.'-'.$mes1.'-'.$anio1;
                }
                $dp_acco = dp_x_us::where('dxu_us_ced',$_SESSION['id'])->pluck('dxu_dp_id');
                
                if ($tipo =='ASISTENCIA') {
                
                    if ($departamento != 'no' ) {
                        if ($dia =='0000-00-00') {

                            $asistencia = DB::table('asistencia')
                            ->join('carnet_us','asi_carus_id','=','carus_id')
                            ->join('horario_x_usuario','carus_hxu_id','=','hxu_id')
                            ->join('tipos_horarios','hxu_tiho_id','=','tiho_id')
                            ->join('usuarios','hxu_cedula','=','us_ced') 
                            ->join('departamentos','us_dp_id','=','dp_id')
                            ->join('tipo_usuarios','us_tdu_id','=','tdu_id')
                            ->whereBetween('asi_entrada',[$desde,$hasta])
                            ->where('dp_id',$departamento)
                            ->where('asi_status',1)
                            ->orderBy('us_nom','asc')
                            ->orderBy('asi_entrada','asc')
                            ->groupBy('us_ced','asi_id','carus_id','hxu_id','tiho_id','dp_id','tdu_id')
                            ->get();
                        }
                        else{
                            
                            $asistencia = DB::table('asistencia')
                            ->join('carnet_us','asi_carus_id','=','carus_id')
                            ->join ('horario_x_usuario','carus_hxu_id','=','hxu_id')
                            ->join('tipos_horarios','hxu_tiho_id','=','tiho_id')
                            ->join('usuarios','hxu_cedula','=','us_ced') 
                            ->join('departamentos','us_dp_id','=','dp_id')
                            ->join('tipo_usuarios','us_tdu_id','=','tdu_id')
                            ->where('asi_entrada', $dia)                    
                            ->where('dp_id',$departamento)
                            ->where('asi_status',1)
                            ->orderBy('us_nom','asc')
                            ->orderBy('asi_entrada','asc')
                            ->groupBy('us_ced','asi_id','carus_id','hxu_id','tiho_id','dp_id','tdu_id')
                            ->get();
                        }
                        
                    }
                    else{                        

                        if ($dia =='0000-00-00') {
                            $sabdom = 'no';
                            $asistencia = DB::table('asistencia')
                            ->join('carnet_us','carus_id','=','asi_carus_id')
                            ->join('horario_x_usuario','hxu_id','=','carus_hxu_id')
                            ->join('tipos_horarios','tiho_id','=','hxu_tiho_id')
                            ->join('usuarios','us_ced','=','hxu_cedula') 
                            ->join('departamentos','dp_id','=','us_dp_id')
                            ->join('tipo_usuarios','tdu_id','=','us_tdu_id')
                            ->whereBetween('asi_entrada', [$desde, $hasta])
                            ->whereIn('dp_id',$dp_acco)
                            ->orderBy('asi_entrada','desc')
                            ->groupBy('us_ced','asi_id','carus_id','hxu_id','tiho_id','dp_id','tdu_id')
                            ->get();
                        }
                        else{
                            
                            $sabdom = 'no';
                            $asistencia =DB::table('asistencia')
                             ->join('carnet_us','carus_id','=','asi_carus_id')
                            ->join('horario_x_usuario','hxu_id','=','carus_hxu_id')
                            ->join('tipos_horarios','tiho_id','=','hxu_tiho_id')
                            ->join('usuarios','us_ced','=','hxu_cedula') 
                            ->join('departamentos','dp_id','=','us_dp_id')
                            ->join('tipo_usuarios','tdu_id','=','us_tdu_id')
                            ->where('asi_entrada',$dia)
                            ->whereIn('dp_id',$dp_acco)
                            ->orderBy('asi_entrada','desc')
                            ->groupBy('us_ced','asi_id','carus_id','hxu_id','tiho_id','dp_id','tdu_id')
                            ->get();
                        }  
                                   
                    }           
                }
                else{
                    if ($departamento != 'no' ) {
                        if ($dia =='') {

                            
                        }
                        else{
                            
                            $carnet = asistencia::select('asi_carus_id')->where('asi_entrada',$dia)->where('asi_status',1)->pluck('asi_carus_id');

                            $reposo = DB::table('ex_x_usuario')
                            ->join('usuarios','exu_ced','=','us_ced')
                            ->join('excepciones','exu_ex_id','=','ex_id')
                            ->join('reposo','ex_re_id','=','re_id')
                            ->join('tipo_reposo','re_tire_id','=','tire_id')
                            ->where('re_fecha_ini','<=',$dia)
                            ->where('re_fecha_fin','>=', $dia)
                            ->where('re_status', 1)
                            ->pluck('us_ced');
                            

                            
                            $permisos = DB::table('ex_x_usuario')
                            ->join('usuarios','exu_ced','=','us_ced')
                            ->join('departamentos','us_dp_id','=','dp_id')
                            ->join('roles','us_ro_id','=','ro_id')
                            ->join('excepciones','exu_ex_id','=','ex_id')
                            ->join('permiso as per','ex_per_id','=','per_id')
                            ->where('per_fecha_ini','<=',$dia)
                            ->where('per_fecha_fin','>=', $dia)
                            ->where('per_status', 1)
                            ->pluck('us_ced');

                            $vacaciones =  DB::table('ex_x_usuario')
                            ->join('usuarios','exu_ced','=','us_ced')
                            ->join('departamentos','us_dp_id','=','dp_id')
                            ->join('roles','us_ro_id','=','ro_id')
                            ->join('excepciones','exu_ex_id','=','ex_id')
                            ->join('vacaciones','ex_vac_id','=','vac_id')
                            ->where('vac_fecha_ini','<=',$dia)
                            ->where('vac_status',1)
                            ->where('vac_fecha_fin','>=', $dia)
                            ->pluck('us_ced');


                            $asistencia = carnet_us::join('horario_x_usuario','carus_hxu_id','=','hxu_id')
                            ->join('usuarios','hxu_cedula','=','us_ced')
                            ->join('departamentos','us_dp_id','=','dp_id')
                            ->where('us_status',1)
                            ->whereNotIn('carus_id',$carnet)
                            ->whereNotIn('us_ced',$reposo)
                            ->whereNotIn('us_ced',$permisos)
                            ->whereNotIn('us_ced',$vacaciones)
                            ->where('dp_id',$departamento)
                            ->get();   
                        }                    
                    }
                    else{

                        if ($dia =='0000-00-00') {

                            $carnet = asistencia::select('asi_carus_id','asi_entrada')->where('asi_status',1)->whereBetween('asi_entrada',[$desde,$hasta])->pluck('asi_carus_id');

                            $reposo = DB::table('ex_x_usuario')
                            ->join('usuarios','exu_ced','=','us_ced')
                            ->join('excepciones','exu_ex_id','=','ex_id')
                            ->join('reposo','ex_re_id','=','re_id')
                            ->join('tipo_reposo','re_tire_id','=','tire_id')
                            ->whereBetween('re_fecha_ini',[$desde,$hasta])
                            ->whereBetween('re_fecha_fin',[$desde,$hasta])
                            ->where('re_status', 1)
                            ->pluck('us_ced');
                            

                            
                            $permisos = DB::table('ex_x_usuario')
                            ->join('usuarios','exu_ced','=','us_ced')
                            ->join('departamentos','us_dp_id','=','dp_id')
                            ->join('roles','us_ro_id','=','ro_id')
                            ->join('excepciones','exu_ex_id','=','ex_id')
                            ->join('permiso as per','ex_per_id','=','per_id')
                            ->whereBetween('per_fecha_ini',[$desde,$hasta])
                            ->whereBetween('per_fecha_fin',[$desde,$hasta])
                            ->where('per_status', 1)
                            ->pluck('us_ced');

                            $vacaciones =  DB::table('ex_x_usuario')
                            ->join('usuarios','exu_ced','=','us_ced')
                            ->join('departamentos','us_dp_id','=','dp_id')
                            ->join('roles','us_ro_id','=','ro_id')
                            ->join('excepciones','exu_ex_id','=','ex_id')
                            ->join('vacaciones','ex_vac_id','=','vac_id')
                            ->whereBetween('vac_fecha_ini',[$desde,$hasta])
                            ->where('vac_status',1)
                            ->whereBetween('vac_fecha_fin',[$desde,$hasta])
                            ->pluck('us_ced');


                            $asistencia = carnet_us::join('horario_x_usuario','carus_hxu_id','=','hxu_id')
                            ->join('usuarios','hxu_cedula','=','us_ced')
                            ->join('departamentos','us_dp_id','=','dp_id')
                            ->join('carnet_us','carus_hxu_id','=','hxu_id')
                            ->where('us_status',1)
                            ->whereNotIn('carus_id',$carnet)
                            ->whereNotIn('us_ced',$reposo)
                            ->whereNotIn('us_ced',$permisos)
                            ->whereNotIn('us_ced',$vacaciones)
                            ->whereIn('dp_id',$dp_acco)
                            ->get();                            
                        }
                        else{
                            $carnet = asistencia::select('asi_carus_id')->where('asi_status',1)->where('asi_entrada',$dia)->pluck('asi_carus_id');

                            $reposo = DB::table('ex_x_usuario')
                            ->join('usuarios','exu_ced','=','us_ced')
                            ->join('excepciones','exu_ex_id','=','ex_id')
                            ->join('reposo','ex_re_id','=','re_id')
                            ->join('tipo_reposo','re_tire_id','=','tire_id')
                            ->where('re_fecha_ini','<=',$dia)
                            ->where('re_fecha_fin','>=', $dia)
                            ->where('re_status', 1)
                            ->pluck('us_ced');
                            

                            
                            $permisos = DB::table('ex_x_usuario')
                            ->join('usuarios','exu_ced','=','us_ced')
                            ->join('departamentos','us_dp_id','=','dp_id')
                            ->join('roles','us_ro_id','=','ro_id')
                            ->join('excepciones','exu_ex_id','=','ex_id')
                            ->join('permiso as per','ex_per_id','=','per_id')
                            ->where('per_fecha_ini','<=',$dia)
                            ->where('per_fecha_fin','>=', $dia)
                            ->where('per_status', 1)
                            ->pluck('us_ced');

                            $vacaciones =  DB::table('ex_x_usuario')
                            ->join('usuarios','exu_ced','=','us_ced')
                            ->join('departamentos','us_dp_id','=','dp_id')
                            ->join('roles','us_ro_id','=','ro_id')
                            ->join('excepciones','exu_ex_id','=','ex_id')
                            ->join('vacaciones','ex_vac_id','=','vac_id')
                            ->where('vac_fecha_ini','<=',$dia)
                            ->where('vac_status',1)
                            ->where('vac_fecha_fin','>=', $dia)
                            ->pluck('us_ced');


                            $asistencia = carnet_us::join('horario_x_usuario','carus_hxu_id','=','hxu_id')
                            ->join('usuarios','hxu_cedula','=','us_ced')
                            ->join('departamentos','us_dp_id','=','dp_id')
                            ->where('us_status',1)
                            ->whereNotIn('carus_id',$carnet)
                            ->whereNotIn('us_ced',$reposo)
                            ->whereNotIn('us_ced',$permisos)
                            ->whereNotIn('us_ced',$vacaciones)
                            ->whereIn('dp_id',$dp_acco)
                            ->get();   
                        }                                    
                    }  
                }

                $fecha = Carbon::now();
                $fecha->toDateString();

                if ($departamento != '') {
                    $dp = $departamento;
                }
                else{
                    $dp = $dp_acco;
                }
                if ($departamento == 'no') {
                    $dp = 'General';
                }

                $user_agent = $_SERVER['HTTP_USER_AGENT'];
                $navegador = $this->getBrowser($user_agent);

                //Registramos en la tabla de auditoría
                $aud = new auditoria;
                $audId ='aud_id';
                $aud->aud_tipo = strtoupper('Impresion de reporte PDF');
                $aud->aud_desc = strtoupper('Impresion de reporte en EXCEL general:'.$dp);
                $aud->aud_machine_ip = $_SERVER['REMOTE_ADDR'];
                $aud->aud_machine_name = gethostname();
                $aud->aud_machine_os =PHP_OS.'-'.php_uname();
                $aud->aud_machine_explorer = $navegador;
                $aud->aud_ced = $_SESSION['id'];
                $aud->aud_fecha =$fecha;
                $aud->save();

                $usuario = DB::table('usuarios')->where('us_ced', $_SESSION['id'])->join('departamentos','us_dp_id','=','dp_id')->join('horario_x_usuario','hxu_cedula','=','us_ced')->join('tipos_horarios','hxu_tiho_id','=','tiho_id')->paginate(1);


                $sheet->setFontFamily('Droid Sans Georgian');
                $sheet->setFontSize(12);
                $sheet->setFontBold(false);



                // Set border for range
                $sheet->setBorder('A1:A1', 'thin');
                
                $sheet->loadView('Asistencia/excel_general', compact('asistencia','usuario','fecha','dia','desde','hasta','panel','tipo','departamento'));
            });            
        })->export('xls');
    }

    /**
     * Busqueda General de asistencia.
     *
     * @return \Illuminate\Http\Response
     */
    public function buscar(Request $request)
    {
        if(isset($_POST['buscar'])){
            if (isset($request->status) && $request->status !='') {
                return'Ingresó';
            }
            $departamento = departamentos::pluck('dp_nombre','dp_id');
            //return view('Asistencia/resumen', compact('usuarios','departamento','sedes','vicepresidencia','coordinacion'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
