<?php

namespace Asistencias\Http\Controllers;

use Illuminate\Http\Request;
use Asistencias\Http\Requests;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Input;
use Asistencias\Http\Controllers\Controller;
use Asistencias\carnet_us;
use Asistencias\carnet_fondo;
use Asistencias\Usuarios;
use Asistencias\horario_x_usuario;
use Asistencias\carnet_tipo_reportes;
use Asistencias\carnet_historico;
use Asistencias\carnet_seriales_inutilizados;
use Asistencias\departamentos;
use Asistencias\carnet_provisionales;
use Asistencias\cargos;
use Asistencias\Support\Facades\Storage;
use Asistencias\roles;
use Asistencias\acceso;
use \Milon\Barcode\DNS1D;
use \Milon\Barcode\DNS2D;
use Session;
use DB;
use Validator;
use Image;
use PDF;
use PDO;
use Carbon\Carbon;
use Asistencias\config;

class CarnetController extends Controller {

    public function index(){
        session_start();
        if (isset($_SESSION['foto'])) {

          $date = Carbon::now(); //2015-01-01 00:00:00 fecha actual

          $fechaActual = $date->toDateString();
   
          $addDate = $date->addMonth(1); // sumamos 1 mes a la fecha actual

          $end = $addDate->toDateString(); // colocamos fecha en formato año/mes/dia sin hora (fecha actual con 1 mes agregado)

          $addDate1 = $date->addMonth(2); // sumamos 2 meses a la fecha actual

          $end1 = $addDate1->toDateString(); // colocamos fecha en formato año/mes/dia sin hora (fecha actual con 2 meses agregados)

          $porVencer = carnet_us::whereBetween('carus_fecha_vencimiento', array($fechaActual, $end1))
          ->where('carus_status', 1)
          ->where('carus_tipo_carnet', '1')        
          ->count();

          $robados = carnet_seriales_inutilizados::where('csi_tipo_reporte_id', 2)->where('csi_tipo_carnet', 1)->count(); // carnets robados

          $hurtados = carnet_seriales_inutilizados::where('csi_tipo_reporte_id', 3)->where('csi_tipo_carnet', 1)->count(); // hurtados

          $vencidos = carnet_us::where('carus_fecha_vencimiento', '<', $fechaActual)
          ->where('carus_status', 1)
          ->where('carus_tipo_carnet', '1')
          ->count(); // vencidos

          $extraviados = carnet_seriales_inutilizados::where('csi_tipo_reporte_id', 5)->where('csi_tipo_carnet', 1)->count(); // extraviados

       //   $empleadoC = carnet_us::lists('carus_ced');
       //   $empleadoH = horario_x_usuario::lists('hxu_cedula');

          $empleadoC = carnet_us::pluck('carus_ced');
          $empleadoH = horario_x_usuario::pluck('hxu_cedula');

          $sinCarnet = DB::connection('vive_2016')
                       ->table('sno_personal AS p')
                       ->select(DB::raw("cedper as cedula"))
                       ->where('estper',1)
                       ->whereNotIn('cedper', $empleadoC)
                       ->whereIn('cedper', $empleadoH)
                       ->count(); // sin carnet
          $rol = roles::where('ro_id',$_SESSION['acceso'])->get()->pluck('ro_id')->last();

          $aco_car = acceso::join('pantallas','aco_pnt_id','=','pnt_id')
            ->where('pnt_nombre','p_carnet')
            ->where('aco_ro_id',$rol)->get()->pluck('pnt_nombre')->last();
          return view ('Usuario/estadisticasCarnets', compact('porVencer', 'robados', 'vencidos', 'hurtados', 'extraviados', 'sinCarnet','aco_car'));
        }
        else{ 
          //Si la sesion no existe...
          Session::flash('session', 'A expirado la sesión');
          return view('login');
        }

    } // fin funcion index 

    public function porVencertotal(Request $request){
        if ($request->ajax()) {
            $date = Carbon::now(); //2015-01-01 00:00:00 fecha actual

            $fechaActual = $date->toDateString();
     
            $addDate = $date->addMonth(1); // sumamos 1 mes a la fecha actual

            $end = $addDate->toDateString(); // colocamos fecha en formato año/mes/dia sin hora (fecha actual con 1 mes agregado)

            $addDate1 = $date->addMonth(2); // sumamos 2 meses a la fecha actual

            $end1 = $addDate1->toDateString(); // colocamos fecha en formato año/mes/dia sin hora (fecha actual con 2 meses agregados)

            $porVencer = carnet_us::whereBetween('carus_fecha_vencimiento', array($fechaActual, $end1))
            ->where('carus_status', 1)
            ->where('carus_tipo_carnet', '1')        
            ->count();

            return response()->json($porVencer);
        }
    }

    public function robadosTotal(Request $request){

        if ($request->ajax()) {
            $robados = carnet_seriales_inutilizados::where('csi_tipo_reporte_id', 2)->where('csi_tipo_carnet', 1)->count(); // carnets robados

            return response()->json($robados);
        } 
    }

    public function hurtadosTotal(Request $request){

        if ($request->ajax()) {
            $hurtados = carnet_seriales_inutilizados::where('csi_tipo_reporte_id', 3)->where('csi_tipo_carnet', 1)->count(); // hurtados

            return response()->json($hurtados);
        } 
    }

    public function vencidosTotal(Request $request){

        if ($request->ajax()) {

            $date = Carbon::now(); //2015-01-01 00:00:00 fecha actual

            $fechaActual = $date->toDateString();

            $vencidos = carnet_us::where('carus_fecha_vencimiento', '<', $fechaActual)
                ->where('carus_status', 1)
                ->where('carus_tipo_carnet', '1')
                ->count();

            return response()->json($vencidos);
        } 
    }

    public function extraviadosTotal(Request $request){

        if ($request->ajax()) {
            $extraviados = carnet_seriales_inutilizados::where('csi_tipo_reporte_id', 5)->where('csi_tipo_carnet', 1)->count(); // extraviados

            return response()->json($extraviados);
        } 
    }

    public function sinCarnetTotal(Request $request){

        if ($request->ajax()) {
          //  $empleadoC = carnet_us::lists('carus_ced');
          //  $empleadoH = horario_x_usuario::lists('hxu_cedula');

          $empleadoC = carnet_us::pluck('carus_ced');
          $empleadoH = horario_x_usuario::pluck('hxu_cedula');


            $sinCarnet = DB::connection('vive_2016')
                     ->table('sno_personal AS p')
                     ->select(DB::raw("cedper as cedula"))
                     ->where('estper',1)
                     ->whereNotIn('cedper', $empleadoC)
                     ->whereIn('cedper', $empleadoH)
                     ->count(); // sin carnet

            return response()->json($sinCarnet);
        } 
    }
    




//--- vista carnets por vencerse en modal--------------------------
public function porVencerse(Request $request){ // captura datos en modal para ver empleados con carnets por vencerse (limit 5) 

    if ($request->ajax()) {

        $date = Carbon::now(); //2015-01-01 00:00:00 fecha actual

        $fechaActual = $date->toDateString();
                
        $addDate1 = $date->addMonth(2); // sumamos 2 meses a la fecha actual

        $end1 = $addDate1->toDateString(); // colocamos fecha en formato año/mes/dia sin hora (fecha actual con 2 meses agregados)

        $empleados = DB::table('carnet_us')
                     ->select(DB::raw("us_nom as nombres, us_ape as apellidos, us_ced as cedula, dp_nombre as des_uni"))
                    ->join('usuarios', 'us_ced', '=', 'carus_ced')
                    ->join('departamentos', 'dp_id', '=', 'us_dp_id') 
                    ->whereBetween('carus_fecha_vencimiento', array($fechaActual, $end1))
                    ->where('carus_status', 1)
                    ->where('carus_tipo_carnet', '1')
                    ->take(5)
                    ->get(); 

       /* $porVencer = DB::table('carnet_us')
                    ->select('carus_ced', 'carus_fecha_vencimiento')
                    ->whereBetween('carus_fecha_vencimiento', array($fechaActual, $end1))
                    ->where('carus_status', 1)
                    ->where('carus_tipo_carnet', '1') 
                    ->lists('carus_ced');

        $empleados = DB::connection('vive_2016')
                     ->table('sno_personalnomina AS pn')
                     ->select(DB::raw("pn.codper, cedper as cedula, nomper as nombres, apeper as apellidos, pn.minorguniadm || '-' || pn.ofiuniadm || '-' || pn.uniuniadm || '-' || pn.depuniadm || '-' || pn.prouniadm as cod_uni, sc.codcar as cod_car,  sc.descar as des_car, max(pn.fecingper) as fecha 
                        "))
                     ->join('sno_personal as p', 'p.codper', '=','pn.codper')
                     ->leftjoin('sno_cargo as sc', 'sc.codcar','=','pn.codcar')
                     ->join('sno_nomina as n', 'n.codnom', '=', 'pn.codnom')
                     ->where('staper',1)
                     ->whereBetween('n.codnom', array('0001', '0008'))
                     ->whereIn('cedper', $porVencer)
                     ->groupBy('cedper','nomper','apeper','pn.minorguniadm', 'pn.ofiuniadm', 'pn.uniuniadm', 'pn.depuniadm', 'pn.prouniadm', 'sc.codcar', 'sc.descar', 'pn.fecingper', 'pn.codper')
                     ->distinct('pn.codper')
                     ->take(5)
                     ->get();*/

        return response()->json($empleados);
      }

}

// index vista carnets por vencerse
public function vistaPorVencerse(){
    session_start();
    if (isset($_SESSION['foto'])) {

    $sigesp = config::where('cof_tipo', 'SIGESP')->get()->pluck('cof_value')->last();
    $local = config::where('cof_tipo', 'LOCAL')->get()->pluck('cof_value')->last();

   /* $date = Carbon::now(); //2015-01-01 00:00:00 fecha actual

    $fechaActual = $date->toDateString();
                
    $addDate1 = $date->addMonth(2); // sumamos 2 meses a la fecha actual

    $end1 = $addDate1->toDateString(); // colocamos fecha en formato año/mes/dia sin hora (fecha actual con 2 meses agregados)

    $porVencer = DB::table('carnet_us')
                    ->select('carus_ced', 'carus_fecha_vencimiento')
                    ->whereBetween('carus_fecha_vencimiento', array($fechaActual, $end1))
                    ->where('carus_status', 1)
                    ->where('carus_tipo_carnet', '1') 
                    ->lists('carus_ced');

    $empleados = DB::connection('vive_2016')
                     ->table('sno_personalnomina AS pn')
                     ->select(DB::raw("pn.codper, cedper as cedula, nomper as nombres, apeper as apellidos, pn.minorguniadm || '-' || pn.ofiuniadm || '-' || pn.uniuniadm || '-' || pn.depuniadm || '-' || pn.prouniadm as cod_uni, sc.codcar as cod_car,  sc.descar as des_car, max(pn.fecingper) as fecha 
                        "))
                     ->join('sno_personal as p', 'p.codper', '=','pn.codper')
                     ->leftjoin('sno_cargo as sc', 'sc.codcar','=','pn.codcar')
                     ->join('sno_nomina as n', 'n.codnom', '=', 'pn.codnom')
                     ->where('staper',1)
                     ->whereBetween('n.codnom', array('0001', '0008'))
                     ->whereIn('cedper', $porVencer)
                     ->groupBy('cedper','nomper','apeper','pn.minorguniadm', 'pn.ofiuniadm', 'pn.uniuniadm', 'pn.depuniadm', 'pn.prouniadm', 'sc.codcar', 'sc.descar', 'pn.fecingper', 'pn.codper')
                     ->distinct('pn.codper')
                     ->paginate(15);
     */                
                     
    $date = Carbon::now(); //2015-01-01 00:00:00 fecha actual

    $fechaActual = $date->toDateString();
                
    $addDate1 = $date->addMonth(2); // sumamos 2 meses a la fecha actual

    $end1 = $addDate1->toDateString(); // colocamos fecha en formato año/mes/dia sin hora (fecha actual con 2 meses agregados)

    $empleados = DB::table('carnet_us')
                     ->select(DB::raw("us_nom as nombres, us_ape as apellidos, us_ced as cedula, dp_nombre as des_uni, c.car_nombre AS des_car, carus_fecha_vencimiento as fecha 
                        "))
                    ->join('usuarios','us_ced','=','carus_ced')
                    ->join('departamentos', 'dp_id','=','us_dp_id')
                    ->join('cargos AS c', 'car_id', '=', 'us_car_cod_id')                     
                    ->whereBetween('carus_fecha_vencimiento', array($fechaActual, $end1))
                    ->where('carus_status', 1)
                    ->where('carus_tipo_carnet', '1') 
                    ->paginate(15);

    $tipo_reportes = DB::table('carnet_tipo_reportes')
                                ->select('ctr_id', 'ctr_descripcion')
                                ->where('ctr_id' , '=', 4)
                                ->get();
    $rol = roles::where('ro_id',$_SESSION['acceso'])->get()->pluck('ro_id')->last();

    $aco_cpv = acceso::join('pantallas','aco_pnt_id','=','pnt_id')
            ->where('aco_ro_id',$rol)->where('pnt_nombre','p_cvencer')->get()->pluck('pnt_nombre')->last();
    return view('Usuario/Por_vencerse', compact('empleados','tipo_reportes','aco_cpv'));

    }else{ 
          //Si la sesion no existe...
          Session::flash('session', 'A expirado la sesión');
          return view('login');
        }

}

// captura datos de modal para carnets que estan por vencerse

public function modalPorVencerse (Request $request, $id){

    if ($request->ajax()) {

        $empleados = DB::table('carnet_us')
                     ->select(DB::raw("us_nom as nombres, us_ape as apellidos, us_ced as cedula, dp_nombre as des_uni, c.car_nombre AS des_car, carus_fecha_vencimiento as fecha, c.car_cod as cod_car, dp_codigo as cod_uni 
                        "))
                    ->join('usuarios','us_ced','=','carus_ced')
                    ->join('departamentos', 'dp_id','=','us_dp_id')
                    ->join('cargos AS c', 'car_id', '=', 'us_car_cod_id')                     
                    ->where('carus_ced', $id)
                    ->get();

        return response()->json($empleados);

    }
}

public function modCarnetPorVencerse (){
      session_start();

 if (isset($_POST['registrar'])) {

        $cedulaEmpleado = $_POST['cedulaEmpleado'];
        $empleadoFechaVec = $_POST['empleadoFechaVec'];
        $apellidoEmpleado = $_POST['apellidoEmpleado'];
        $ipmaquina = $_SERVER["REMOTE_ADDR"];
        $areaEmpleado = $_POST['areaEmpleado'];
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $empleadoMotivo = $_POST['carnet_usu_motivo'];
        $nombreEmpleado = $_POST['nombreEmpleado'];
        $areaEmpleado = $_POST['areaEmpleado'];
        $selloPrensa =$_POST['selloPrensa'];

     }

    // if($empleadoMotivo == 4){ // reportar carnet como vencido no genera nuevo codigo

        $date = Carbon::now()->toDateTimeString(); //Fecha actual

                   /* $rules = ['carus_fecha_vencimiento' => 'required',];
                        $messages = [
                            'carus_fecha_vencimiento.required' => 'La fecha de vencimiento es requerida',
                        ];
                        $validator = Validator::make($request->all(), $rules, $messages);
                        
                        if ($validator->fails()){
                            return redirect('carnet')->withErrors($validator);
                        }else{*/

                    $carnet_us = DB::table('carnet_us')
                    ->where('carus_ced', $cedulaEmpleado)
                    ->update(['carus_motivo' => $empleadoMotivo,
                              'carus_fecha_vencimiento' => $empleadoFechaVec,
                              'updated_at'   => $date,
                              'carus_selloprensa' => $selloPrensa
                        ]);

                       // }

                    if ($carnet_us == true) {

                        $carnetCodigo = carnet_us::where('carus_ced', $cedulaEmpleado)->get()
                            ->pluck('carus_codigo')->last();

                            $navegador = $this->getBrowser($user_agent);
                            $areaEm = Usuarios::where('us_ced', $_SESSION['id'])->get()->pluck('us_dp_id')->last();
                            $usuario = Usuarios::where('us_ced', $_SESSION['id'])->get()->pluck('us_login')->last();

                            $historico = new carnet_historico;
                            $id_hist = 'carth_id';
                            $historico->carth_ip_maquina = $ipmaquina;
                            $historico->carth_usuario = $usuario;
                            $historico->carth_departamento = $areaEm;
                            $historico->carth_so = $user_agent;
                            $historico->carth_navegador = $navegador;
                            $historico->carth_serial_carnet = $carnetCodigo;
                            $historico->carth_cedulaempleado = $cedulaEmpleado;
                            $historico->carth_motivo = $empleadoMotivo;
                            $historico->carth_tipo_carnet = 1;
                                
                            $historico->save();

                            
                            Session::flash('flash_message_Venc', 'Se ha renovado el carnet del empleado C.I '.$cedulaEmpleado.'');

                            //debe mostrar pdf del carnet

                            }else{
                                Session::flash('flash_message', 'Error, no se han modificado dichos datos');
                            }

                        return redirect()->back();

                  //  }
}

public function busPorVencerse (Request $request, $cedula) { 

    if ($request->ajax()) {

    $date = Carbon::now(); //2015-01-01 00:00:00 fecha actual

    $fechaActual = $date->toDateString();
                
    $addDate1 = $date->addMonth(2); // sumamos 2 meses a la fecha actual

    $end1 = $addDate1->toDateString(); // colocamos fecha en formato año/mes/dia sin hora (fecha actual con 2 meses agregados)

    $empleados = DB::table('carnet_us')
                     ->select(DB::raw("us_nom as nombres, us_ape as apellidos, us_ced as cedula, dp_nombre as des_uni, c.car_nombre AS des_car, carus_fecha_vencimiento as fecha, c.car_cod as cod_car, dp_codigo as cod_uni 
                        "))
                    ->join('usuarios','us_ced','=','carus_ced')
                    ->join('departamentos', 'dp_id','=','us_dp_id')
                    ->join('cargos AS c', 'car_id', '=', 'us_car_cod_id')                     
                    ->whereBetween('carus_fecha_vencimiento', array($fechaActual, $end1))
                    ->where('carus_status', 1)
                    ->where('carus_tipo_carnet', '1')
                    ->where('carus_ced', $cedula) 
                    ->get();

    return response()->json($empleados);
}

}

// index vista carnets robados

public function vistaRobados(){

    session_start();

    if (isset($_SESSION['foto'])) {       

    $robados = DB::table('carnet_seriales_inutilizados')
                    ->select('csi_cedula as cedula', 'csi_nombre as nombres', 'csi_apellido as apellidos', 'csi_cod_barra as codigo', 'csi_fecha as fecha', 'dp_nombre as des_uni')
                    ->join('usuarios', 'us_ced', '=', 'csi_cedula')
                    ->join('departamentos', 'dp_id', '=', 'us_dp_id')
                    ->where('csi_tipo_reporte_id', 2)
                    ->where('csi_tipo_carnet', 1) 
                    ->paginate(15);

    $rol = roles::where('ro_id',$_SESSION['acceso'])->get()->pluck('ro_id')->last();

    $aco_cro = acceso::join('pantallas','aco_pnt_id','=','pnt_id')
            ->where('aco_ro_id',$rol)->where('pnt_nombre','p_crobados')->get()->pluck('pnt_nombre')->last();
    return view('Usuario/Carnets_Robados', compact('robados','aco_cro'));

    }else{ 
            //Si la sesion no existe...
            Session::flash('session', 'A expirado la sesión');
            return view('login');
        }

}

// vista de modal de carnets robados 
public function robados_car(Request $request){

    if ($request->ajax()) {

        $empleados = DB::table('carnet_seriales_inutilizados')
                    ->select('csi_cedula as cedula', 'csi_nombre as nombres', 'csi_apellido as apellidos', 'csi_cod_barra as codigo', 'csi_fecha as fecha', 'dp_nombre as des_uni')
                    ->join('usuarios', 'us_ced', '=', 'csi_cedula')
                    ->join('departamentos', 'dp_id', '=', 'us_dp_id')
                    ->where('csi_tipo_reporte_id', 2)
                    ->where('csi_tipo_carnet', 1) 
                    ->take(5)
                     ->get();

        return response()->json($empleados);
      }

}

// busqueda de carnets robados
public function buskRobados(Request $request, $cedula){

    if ($request->ajax()) {

        $robados = DB::table('carnet_seriales_inutilizados')
                    ->select('csi_cedula as cedula', 'csi_nombre as nombres', 'csi_apellido as apellidos', 'csi_cod_barra as codigo', 'csi_fecha as fecha')
                    ->where('csi_tipo_reporte_id', 2)
                    ->where('csi_tipo_carnet', 1) 
                    ->where('csi_cedula', $cedula)
                    ->orwhere('csi_cod_barra', $cedula)
                    ->orderBy('csi_id', 'ASC')
                    ->get();

        return response()->json($robados);
      }

}

// ----------- index Vista carnets hurtados-------

public function vistaHurtados (){

    session_start();

    if (isset($_SESSION['foto'])) {  

    $hurtados = DB::table('carnet_seriales_inutilizados')
                    ->select('csi_cedula as cedula', 'csi_nombre as nombres', 'csi_apellido as apellidos', 'csi_cod_barra as codigo', 'csi_fecha as fecha', 'dp_nombre as des_uni')
                    ->join('usuarios', 'us_ced', '=', 'csi_cedula')
                    ->join('departamentos', 'dp_id', '=', 'us_dp_id')
                    ->where('csi_tipo_reporte_id', 3)
                    ->where('csi_tipo_carnet', 1) 
                    ->paginate(15);
    $rol = roles::where('ro_id',$_SESSION['acceso'])->get()->pluck('ro_id')->last();

    $aco_chu = acceso::join('pantallas','aco_pnt_id','=','pnt_id')
            ->where('aco_ro_id',$rol)->where('pnt_nombre','p_churtados')->get()->pluck('pnt_nombre')->last();
    return view('Usuario/Carnets_Hurtados', compact('hurtados','aco_chu'));

    }else{ 
            //Si la sesion no existe...
            Session::flash('session', 'A expirado la sesión');
            return view('login');
        }
}

// vista de modal de carnets hurtados 
public function hurtados_car(Request $request){

    if ($request->ajax()) {

        $empleados = DB::table('carnet_seriales_inutilizados')
                    ->select('csi_cedula as cedula', 'csi_nombre as nombres', 'csi_apellido as apellidos', 'dp_nombre as des_uni')
                    ->join('usuarios', 'us_ced', '=', 'csi_cedula')
                    ->join('departamentos', 'dp_id', '=', 'us_dp_id')
                    ->where('csi_tipo_reporte_id', 3)
                    ->where('csi_tipo_carnet', 1) 
                    ->take(5)
                     ->get();

        return response()->json($empleados);
      }

}


// busqueda de carnets hurtados
public function buskHurtados(Request $request, $cedula){

    if ($request->ajax()) {

      $hurtados = DB::table('carnet_seriales_inutilizados')
                    ->select('csi_cedula as cedula', 'csi_nombre as nombres', 'csi_apellido as apellidos', 'csi_cod_barra as codigo', 'csi_fecha as fecha')
                    ->where('csi_tipo_reporte_id', 3)
                    ->where('csi_tipo_carnet', 1)
                    ->where('csi_cedula', $cedula)
                    ->orwhere('csi_cod_barra', $cedula)
                    ->orderBy('csi_id', 'ASC')
                    ->get();

        return response()->json($hurtados);
      }

}

// ----------- index Vista carnets extraviados-------

public function vistaExtraviados (){

    session_start();

    if (isset($_SESSION['foto'])) { 

    $extraviado = DB::table('carnet_seriales_inutilizados')
                    ->select('csi_cedula as cedula', 'csi_nombre as nombres', 'csi_apellido as apellidos', 'csi_cod_barra as codigo', 'csi_fecha as fecha', 'dp_nombre as des_uni')
                    ->join('usuarios', 'us_ced', '=', 'csi_cedula')
                    ->join('departamentos', 'dp_id', '=', 'us_dp_id')
                    ->where('csi_tipo_reporte_id', 5)
                    ->where('csi_tipo_carnet', 1) 
                    ->paginate(15);
    $rol = roles::where('ro_id',$_SESSION['acceso'])->get()->pluck('ro_id')->last();

    $aco_cex = acceso::join('pantallas','aco_pnt_id','=','pnt_id')
            ->where('aco_ro_id',$rol)->where('pnt_nombre','p_cextraviados')->get()->pluck('pnt_nombre')->last();

    return view('Usuario/Carnets_Extraviados', compact('extraviado','aco_cex'));

    }else{ 
            //Si la sesion no existe...
            Session::flash('session', 'A expirado la sesión');
            return view('login');
        }
}

// vista de modal de carnets extraviados 
public function extraviado_car(Request $request){

    if ($request->ajax()) {

        $empleados = DB::table('carnet_seriales_inutilizados')
                    ->select('csi_cedula as cedula', 'csi_nombre as nombres', 'csi_apellido as apellidos', 'dp_nombre as des_uni')
                    ->join('usuarios', 'us_ced', '=', 'csi_cedula')
                    ->join('departamentos', 'dp_id', '=', 'us_dp_id')
                    ->where('csi_tipo_reporte_id', 5)
                    ->where('csi_tipo_carnet', 1) 
                    ->take(5)
                     ->get();

        return response()->json($empleados);
      }

}

// busqueda de carnets robados
public function buskExtraviado(Request $request, $cedula){

    if ($request->ajax()) {

      $extraviado = DB::table('carnet_seriales_inutilizados')
                    ->select('csi_cedula as cedula', 'csi_nombre as nombres', 'csi_apellido as apellidos', 'csi_cod_barra as codigo', 'csi_fecha as fecha')
                    ->where('csi_tipo_reporte_id', 5)
                    ->where('csi_tipo_carnet', 1) 
                    ->where('csi_cedula', $cedula)
                    ->orwhere('csi_cod_barra', $cedula)
                    ->orderBy('csi_id', 'ASC')
                    ->get();

        return response()->json($extraviado);
      }

}

// index vista carnets vencidos
public function vistaVencidos(){

    session_start();

     if (isset($_SESSION['foto'])) {

    $sigesp = config::where('cof_tipo', 'SIGESP')->get()->pluck('cof_value')->last();
    $local = config::where('cof_tipo', 'LOCAL')->get()->pluck('cof_value')->last();

    $date = Carbon::now(); //2015-01-01 00:00:00 fecha actual

    $fechaActual = $date->toDateString();

  /*  $vencidos = carnet_us::where('carus_fecha_vencimiento', '<', $fechaActual)
        ->where('carus_status', 1)
        ->where('carus_tipo_carnet', '1') 
        ->lists('carus_ced');

    $empleados = DB::connection('vive_2016')
                     ->table('sno_personalnomina AS pn')
                     ->select(DB::raw("pn.codper, cedper as cedula, nomper as nombres, apeper as apellidos, pn.minorguniadm || '-' || pn.ofiuniadm || '-' || pn.uniuniadm || '-' || pn.depuniadm || '-' || pn.prouniadm as cod_uni, sc.codcar as cod_car,  sc.descar as des_car, max(pn.fecingper) as fecha 
                        "))
                     ->join('sno_personal as p', 'p.codper', '=','pn.codper')
                     ->leftjoin('sno_cargo as sc', 'sc.codcar','=','pn.codcar')
                     ->join('sno_nomina as n', 'n.codnom', '=', 'pn.codnom')
                     ->where('staper',1)
                     ->whereBetween('n.codnom', array('0001', '0008'))
                     ->whereIn('cedper', $vencidos)
                     ->groupBy('cedper','nomper','apeper','pn.minorguniadm', 'pn.ofiuniadm', 'pn.uniuniadm', 'pn.depuniadm', 'pn.prouniadm', 'sc.codcar', 'sc.descar', 'pn.fecingper', 'pn.codper')
                     ->distinct('pn.codper')
                     ->paginate(15); */

    $empleados = DB::table('carnet_us')
                     ->select(DB::raw("us_nom as nombres, us_ape as apellidos, us_ced as cedula, dp_nombre as des_uni, c.car_nombre AS des_car, carus_fecha_vencimiento as fecha, c.car_cod as cod_car, dp_codigo as cod_uni, dp_nombre as des_uni
                        "))
                    ->join('usuarios','us_ced','=','carus_ced')
                    ->join('departamentos', 'dp_id','=','us_dp_id')
                    ->join('cargos AS c', 'car_id', '=', 'us_car_cod_id')                     
                    ->where('carus_fecha_vencimiento', '<', $fechaActual)
                    ->where('carus_status', 1)
                    ->where('carus_tipo_carnet', '1')
                    ->paginate(15);

    $tipo_reportes = DB::table('carnet_tipo_reportes')
                                ->select('ctr_id', 'ctr_descripcion')
                                ->where('ctr_id' , '=', 4)
                                ->get();

    $rol = roles::where('ro_id',$_SESSION['acceso'])->get()->pluck('ro_id')->last();

    $aco_cven = acceso::join('pantallas','aco_pnt_id','=','pnt_id')
            ->where('aco_ro_id',$rol)->where('pnt_nombre','p_cvencidos')->get()->pluck('pnt_nombre')->last();
    return view('Usuario/Carnets_Vencidos', compact('empleados','tipo_reportes','aco_cven'));

    }else{ 
            //Si la sesion no existe...
            Session::flash('session', 'A expirado la sesión');
            return view('login');
        }

}

// vista de modal de carnets vencidos 
public function vencido_car(Request $request){

    if ($request->ajax()) {

        $date = Carbon::now(); //2015-01-01 00:00:00 fecha actual

        $fechaActual = $date->toDateString();

     /*   $vencidos = carnet_us::where('carus_fecha_vencimiento', '<', $fechaActual)
        ->where('carus_status', 1)
        ->where('carus_tipo_carnet', '1') 
        ->lists('carus_ced');

        $empleados = DB::connection('vive_2016')
                     ->table('sno_personalnomina AS pn')
                     ->select(DB::raw("pn.codper, cedper as cedula, nomper as nombres, apeper as apellidos, pn.minorguniadm || '-' || pn.ofiuniadm || '-' || pn.uniuniadm || '-' || pn.depuniadm || '-' || pn.prouniadm as cod_uni, sc.codcar as cod_car,  sc.descar as des_car, max(pn.fecingper) as fecha 
                        "))
                     ->join('sno_personal as p', 'p.codper', '=','pn.codper')
                     ->leftjoin('sno_cargo as sc', 'sc.codcar','=','pn.codcar')
                     ->join('sno_nomina as n', 'n.codnom', '=', 'pn.codnom')
                     ->where('staper',1)
                     ->whereBetween('n.codnom', array('0001', '0008'))
                     ->whereIn('cedper', $vencidos)
                     ->groupBy('cedper','nomper','apeper','pn.minorguniadm', 'pn.ofiuniadm', 'pn.uniuniadm', 'pn.depuniadm', 'pn.prouniadm', 'sc.codcar', 'sc.descar', 'pn.fecingper', 'pn.codper')
                     ->distinct('pn.codper')
                     ->take(5)
                     ->get();*/

      $empleados = DB::table('carnet_us')
                     ->select(DB::raw("us_nom as nombres, us_ape as apellidos, us_ced as cedula, dp_nombre as des_uni, c.car_nombre AS des_car, carus_fecha_vencimiento as fecha, c.car_cod as cod_car, dp_codigo as cod_uni, dp_nombre as des_uni
                        "))
                    ->join('usuarios','us_ced','=','carus_ced')
                    ->join('departamentos', 'dp_id','=','us_dp_id')
                    ->join('cargos AS c', 'car_id', '=', 'us_car_cod_id')                     
                    ->where('carus_fecha_vencimiento', '<', $fechaActual)
                    ->where('carus_status', 1)
                    ->where('carus_tipo_carnet', '1')
                    ->take(5)
                     ->get();

        return response()->json($empleados);
      }

}

// buscar vencidos---------

public function buskVencidos (Request $request, $cedula) { 

    if ($request->ajax()) {

        $date = Carbon::now(); //2015-01-01 00:00:00 fecha actual

        $fechaActual = $date->toDateString();

        $tipo_reportes = DB::table('carnet_tipo_reportes')
                                ->select('ctr_id', 'ctr_descripcion')
                                ->where('ctr_id' , '=', 4)
                                ->get();


        $empleados = DB::table('carnet_us')
                   ->select('u.us_nom AS nombres', 'u.us_ape AS apellidos', 'u.us_ced AS cedula', 'd.dp_nombre AS des_uni', 'c.car_nombre as cod_car', 'c.car_nombre AS des_car','d.dp_codigo AS cod_uni', 'carus_fecha_vencimiento as fecha', 'carus_status as status', 'carus_motivo as motivoRe', 'ctr_descripcion as descripcion')
                   ->join('usuarios as u','us_ced','=','carus_ced')
                   ->join('departamentos AS d','us_dp_id', '=', 'dp_id')
                   ->join('cargos AS c', 'car_id', '=', 'us_car_cod_id')
                   ->join('carnet_tipo_reportes', 'ctr_id', '=', 'carus_motivo')
                   ->where('us_ced', $cedula)
                   ->where('carus_fecha_vencimiento', '<', $fechaActual)
                   ->where('carus_status', 1)
                   ->where('carus_tipo_carnet', '1') 
                   ->get();

          return response()->json($empleados);
}

}



// pdf carnets por vencerse
public function PdfPorVencerse (){

    session_start();

     $usuario = DB::table('usuarios')->where('us_ced', $_SESSION['id'])->join('departamentos', 'us_dp_id', '=', 'dp_id')->join('horario_x_usuario', 'hxu_cedula', '=', 'us_ced')->join('tipos_horarios', 'hxu_tiho_id', '=', 'tiho_id')->paginate(1);

    $fecha = Carbon::now();
    $fecha->toDateString();

    $date = Carbon::now(); //2015-01-01 00:00:00 fecha actual

    $fechaActual = $date->toDateString();
                
    $addDate1 = $date->addMonth(2); // sumamos 2 meses a la fecha actual

    $end1 = $addDate1->toDateString(); // colocamos fecha en formato año/mes/dia sin hora (fecha actual con 2 meses agregados)

    $empleados = DB::table('carnet_us')
                     ->select(DB::raw("us_nom as nombres, us_ape as apellidos, us_ced as cedula, dp_nombre as des_uni, c.car_nombre AS des_car, carus_fecha_vencimiento as fecha 
                        "))
                    ->join('usuarios','us_ced','=','carus_ced')
                    ->join('departamentos', 'dp_id','=','us_dp_id')
                    ->join('cargos AS c', 'car_id', '=', 'us_car_cod_id')                     
                    ->whereBetween('carus_fecha_vencimiento', array($fechaActual, $end1))
                    ->where('carus_status', 1)
                    ->where('carus_tipo_carnet', '1') 
                    ->get();

  /*  $porVencer = DB::table('carnet_us')
                    ->select('carus_ced', 'carus_fecha_vencimiento')
                    ->whereBetween('carus_fecha_vencimiento', array($fechaActual, $end1))
                    ->where('carus_status', 1)
                    ->where('carus_tipo_carnet', '1') 
                    ->lists('carus_ced');
*/
  /*  $empleados = DB::connection('vive_2016')
                     ->table('sno_personalnomina AS pn')
                     ->select(DB::raw("pn.codper, cedper as cedula, nomper as nombres, apeper as apellidos, pn.minorguniadm || '-' || pn.ofiuniadm || '-' || pn.uniuniadm || '-' || pn.depuniadm || '-' || pn.prouniadm as cod_uni, sc.codcar as cod_car,  sc.descar as des_car, max(pn.fecingper) as fecha 
                        "))
                     ->join('sno_personal as p', 'p.codper', '=','pn.codper')
                     ->leftjoin('sno_cargo as sc', 'sc.codcar','=','pn.codcar')
                     ->join('sno_nomina as n', 'n.codnom', '=', 'pn.codnom')
                     ->where('staper',1)
                     ->whereBetween('n.codnom', array('0001', '0008'))
                     ->whereIn('cedper', $porVencer)
                     ->groupBy('cedper','nomper','apeper','pn.minorguniadm', 'pn.ofiuniadm', 'pn.uniuniadm', 'pn.depuniadm', 'pn.prouniadm', 'sc.codcar', 'sc.descar', 'pn.fecingper', 'pn.codper')
                     ->distinct('pn.codper')
                     ->paginate(15);
*/
    

        //return view('Usuario/Carnets_Por_Vencerse', compact('empleados','porVencer'));

        $pdf =PDF::setOptions (['images'=>true])->loadView('Usuario/PDF_Por_Vencerse',compact('empleados', 'usuario', 'fecha'));

            return $pdf->stream('Carnets-Por-Vencerse.pdf');
}

// pdf carnets por vencerse
public function PdfPorVencerseFechas (Request $request){

    session_start();
      
  $desde = $request->fechadesdeV;
  $hasta = $request->fechahastaV;

    $usuario = DB::table('usuarios')->where('us_ced', $_SESSION['id'])->join('departamentos', 'us_dp_id', '=', 'dp_id')->join('horario_x_usuario', 'hxu_cedula', '=', 'us_ced')->join('tipos_horarios', 'hxu_tiho_id', '=', 'tiho_id')->paginate(1);

    $fecha = Carbon::now();
    $fecha->toDateString();

    $date = Carbon::now(); //2015-01-01 00:00:00 fecha actual

    $fechaActual = $date->toDateString();
                
    $addDate1 = $date->addMonth(2); // sumamos 2 meses a la fecha actual

    $end1 = $addDate1->toDateString(); // colocamos fecha en formato año/mes/dia sin hora (fecha actual con 2 meses agregados)

    $empleados = DB::table('carnet_us')
                     ->select(DB::raw("us_nom as nombres, us_ape as apellidos, us_ced as cedula, dp_nombre as des_uni, c.car_nombre AS des_car, carus_fecha_vencimiento as fecha 
                        "))
                    ->join('usuarios','us_ced','=','carus_ced')
                    ->join('departamentos', 'dp_id','=','us_dp_id')
                    ->join('cargos AS c', 'car_id', '=', 'us_car_cod_id')                                         
                    ->whereBetween('carus_fecha_vencimiento', array($desde, $hasta))
                    ->whereBetween('carus_fecha_vencimiento', array($fechaActual, $end1))
                    ->where('carus_status', 1)
                    ->where('carus_tipo_carnet', '1')
                    ->get();

        $pdf =PDF::setOptions (['images'=>true])->loadView('Usuario/PDF_Por_Vencerse_Fechas',compact('empleados', 'usuario', 'fecha', 'desde', 'hasta'));

            return $pdf->stream('Carnets-Por-Vencerse.pdf');
 
}

// pdf carnets robados
public function PdfRobados (){
    
    session_start();

    $usuario = DB::table('usuarios')->where('us_ced', $_SESSION['id'])->join('departamentos', 'us_dp_id', '=', 'dp_id')->join('horario_x_usuario', 'hxu_cedula', '=', 'us_ced')->join('tipos_horarios', 'hxu_tiho_id', '=', 'tiho_id')->paginate(1);

    $fecha = Carbon::now();
    $fecha->toDateString();

    $robados = DB::table('carnet_seriales_inutilizados')
                    ->select('csi_cedula as cedula', 'csi_nombre as nombres', 'csi_apellido as apellidos', 'csi_cod_barra as codigo', 'csi_fecha as fecha', 'dp_nombre as des_uni')
                    ->join('usuarios', 'us_ced', '=', 'csi_cedula')
                    ->join('departamentos', 'dp_id', '=', 'us_dp_id')
                    ->where('csi_tipo_reporte_id', 2)
                    ->where('csi_tipo_carnet', 1) 
                    ->get();

    $pdf =PDF::setOptions (['images'=>true])->loadView('Usuario/PDF_Carnet_Robados',compact('robados', 'usuario', 'fecha'));

    return $pdf->stream('Carnets-Robados.pdf');

}

public function PdfRobadosFechas(Request $request){

  session_start();
      
  $desde = $request->fechadesdeR;
  $hasta = $request->fechahastaR;

  $usuario = DB::table('usuarios')->where('us_ced', $_SESSION['id'])->join('departamentos', 'us_dp_id', '=', 'dp_id')->join('horario_x_usuario', 'hxu_cedula', '=', 'us_ced')->join('tipos_horarios', 'hxu_tiho_id', '=', 'tiho_id')->paginate(1);

  $fecha = Carbon::now();
  $fecha->toDateString();

  $robados = DB::table('carnet_seriales_inutilizados')
                    ->select('csi_cedula as cedula', 'csi_nombre as nombres', 'csi_apellido as apellidos', 'csi_cod_barra as codigo', 'csi_fecha as fecha', 'dp_nombre as des_uni')
                    ->join('usuarios', 'us_ced', '=', 'csi_cedula')
                    ->join('departamentos', 'dp_id', '=', 'us_dp_id')
                    ->whereBetween('csi_fecha', array($desde, $hasta))
                    ->where('csi_tipo_reporte_id', 2)
                    ->where('csi_tipo_carnet', 1) 
                    ->get();

  $pdf =PDF::setOptions (['images'=>true])->loadView('Usuario/PDF_Carnet_Robados_Fechas',compact('robados', 'usuario', 'fecha','desde', 'hasta'));

  return $pdf->stream('Carnets-Robados.pdf');

}

// pdf carnets hurtados (general)
public function PdfHurtados (){
    
    session_start();

    $usuario = DB::table('usuarios')->where('us_ced', $_SESSION['id'])->join('departamentos', 'us_dp_id', '=', 'dp_id')->join('horario_x_usuario', 'hxu_cedula', '=', 'us_ced')->join('tipos_horarios', 'hxu_tiho_id', '=', 'tiho_id')->paginate(1);

    $fecha = Carbon::now();
    $fecha->toDateString();

    $hurtados = DB::table('carnet_seriales_inutilizados')
                    ->select('csi_cedula as cedula', 'csi_nombre as nombres', 'csi_apellido as apellidos', 'csi_cod_barra as codigo', 'csi_fecha as fecha', 'dp_nombre as des_uni')
                    ->join('usuarios', 'us_ced', '=', 'csi_cedula')
                    ->join('departamentos', 'dp_id', '=', 'us_dp_id')
                    ->where('csi_tipo_reporte_id', 3)
                    ->where('csi_tipo_carnet', 1) 
                    ->get();

    $pdf =PDF::setOptions (['images'=>true])->loadView('Usuario/PDF_Carnet_Hurtado',compact('hurtados', 'usuario', 'fecha'));

            return $pdf->stream('Carnets-Robados.pdf');

}

//pdf hurtados por rango de fecha
public function PdfHurtadosFechas(Request $request){

  session_start();
      
  $desde = $request->fechadesdeH;
  $hasta = $request->fechahastaH;

  $usuario = DB::table('usuarios')->where('us_ced', $_SESSION['id'])->join('departamentos', 'us_dp_id', '=', 'dp_id')->join('horario_x_usuario', 'hxu_cedula', '=', 'us_ced')->join('tipos_horarios', 'hxu_tiho_id', '=', 'tiho_id')->paginate(1);

  $fecha = Carbon::now();
  $fecha->toDateString();

  $hurtados = DB::table('carnet_seriales_inutilizados')
                    ->select('csi_cedula as cedula', 'csi_nombre as nombres', 'csi_apellido as apellidos', 'csi_cod_barra as codigo', 'csi_fecha as fecha', 'dp_nombre as des_uni')
                    ->join('usuarios', 'us_ced', '=', 'csi_cedula')
                    ->join('departamentos', 'dp_id', '=', 'us_dp_id')
                    ->whereBetween('csi_fecha', array($desde, $hasta))
                    ->where('csi_tipo_reporte_id', 3)
                    ->where('csi_tipo_carnet', 1) 
                    ->get();

  $pdf =PDF::setOptions (['images'=>true])->loadView('Usuario/PDF_Carnet_Hurtados_Fechas',compact('hurtados', 'usuario', 'fecha','desde', 'hasta'));

  return $pdf->stream('Carnets-Hurtados.pdf');
  
}

// pdf carnets extraviados
public function PdfExtraviados (){
    
    session_start();

    $usuario = DB::table('usuarios')->where('us_ced', $_SESSION['id'])->join('departamentos', 'us_dp_id', '=', 'dp_id')->join('horario_x_usuario', 'hxu_cedula', '=', 'us_ced')->join('tipos_horarios', 'hxu_tiho_id', '=', 'tiho_id')->paginate(1);

    $fecha = Carbon::now();
    $fecha->toDateString();

     $extraviado = DB::table('carnet_seriales_inutilizados')
                    ->select('csi_cedula as cedula', 'csi_nombre as nombres', 'csi_apellido as apellidos', 'csi_cod_barra as codigo', 'csi_fecha as fecha', 'dp_nombre as des_uni')
                    ->join('usuarios', 'us_ced', '=', 'csi_cedula')
                    ->join('departamentos', 'dp_id', '=', 'us_dp_id')
                    ->where('csi_tipo_reporte_id', 5)
                    ->where('csi_tipo_carnet', 1) 
                    ->get();

    $pdf =PDF::setOptions (['images'=>true])->loadView('Usuario/PDF_Carnet_Extraviado',compact('extraviado', 'usuario', 'fecha'));

            return $pdf->stream('Carnets-Extraviados.pdf');

}

//pdf extraviados por rango de fecha
public function PdfExtraviadosFechas(Request $request){

  session_start();
      
  $desde = $request->fechadesdeE;
  $hasta = $request->fechahastaE;

  $usuario = DB::table('usuarios')->where('us_ced', $_SESSION['id'])->join('departamentos', 'us_dp_id', '=', 'dp_id')->join('horario_x_usuario', 'hxu_cedula', '=', 'us_ced')->join('tipos_horarios', 'hxu_tiho_id', '=', 'tiho_id')->paginate(1);

  $fecha = Carbon::now();
  $fecha->toDateString();

  $extraviado = DB::table('carnet_seriales_inutilizados')
                    ->select('csi_cedula as cedula', 'csi_nombre as nombres', 'csi_apellido as apellidos', 'csi_cod_barra as codigo', 'csi_fecha as fecha', 'dp_nombre as des_uni')
                    ->join('usuarios', 'us_ced', '=', 'csi_cedula')
                    ->join('departamentos', 'dp_id', '=', 'us_dp_id')
                    ->whereBetween('csi_fecha', array($desde, $hasta))
                    ->where('csi_tipo_reporte_id', 5)
                    ->where('csi_tipo_carnet', 1) 
                    ->get();

  $pdf =PDF::setOptions (['images'=>true])->loadView('Usuario/PDF_Carnet_Extraviado_Fechas',compact('extraviado', 'usuario', 'fecha','desde', 'hasta'));

  return $pdf->stream('Carnets-Extraviados.pdf');
  
}


// pdf carnets vencidos
public function PdfVencidos (){
    
    session_start();

    $usuario = DB::table('usuarios')->where('us_ced', $_SESSION['id'])->join('departamentos', 'us_dp_id', '=', 'dp_id')->join('horario_x_usuario', 'hxu_cedula', '=', 'us_ced')->join('tipos_horarios', 'hxu_tiho_id', '=', 'tiho_id')->paginate(1);

    $fecha = Carbon::now();
    $fecha->toDateString();

    $date = Carbon::now(); //2015-01-01 00:00:00 fecha actual

    $fechaActual = $date->toDateString();

    $vencidos = DB::table('carnet_us')
                     ->select(DB::raw("us_nom as nombres, us_ape as apellidos, us_ced as cedula, dp_nombre as des_uni, c.car_nombre AS des_car, carus_fecha_vencimiento as fecha 
                        "))
                    ->join('usuarios','us_ced','=','carus_ced')
                    ->join('departamentos', 'dp_id','=','us_dp_id')
                    ->join('cargos AS c', 'car_id', '=', 'us_car_cod_id')                     
                    ->where('carus_fecha_vencimiento', '<', $fechaActual)
                    ->where('carus_status', 1)
                    ->where('carus_tipo_carnet', '1') 
                    ->get();

     $pdf =PDF::setOptions (['images'=>true])->loadView('Usuario/PDF_Carnet_Vencido',compact('vencidos', 'usuario', 'fecha'));

     return $pdf->stream('Carnets-Vencidos.pdf');                

}


//pdf vencidos por rango de fecha
public function PdfVencidosFechas(Request $request){

  session_start();
      
  $desde = $request->fechadesdeV;
  $hasta = $request->fechahastaV;

  $usuario = DB::table('usuarios')->where('us_ced', $_SESSION['id'])->join('departamentos', 'us_dp_id', '=', 'dp_id')->join('horario_x_usuario', 'hxu_cedula', '=', 'us_ced')->join('tipos_horarios', 'hxu_tiho_id', '=', 'tiho_id')->paginate(1);

  $fecha = Carbon::now();
  $fecha->toDateString();

  $date = Carbon::now(); //2015-01-01 00:00:00 fecha actual

  $fechaActual = $date->toDateString();

  $vencidos = DB::table('carnet_us')
                     ->select(DB::raw("us_nom as nombres, us_ape as apellidos, us_ced as cedula, dp_nombre as des_uni, c.car_nombre AS des_car, carus_fecha_vencimiento as fecha 
                        "))
                    ->join('usuarios','us_ced','=','carus_ced')
                    ->join('departamentos', 'dp_id','=','us_dp_id')
                    ->join('cargos AS c', 'car_id', '=', 'us_car_cod_id')                     
                    ->where('carus_fecha_vencimiento', '<', $fechaActual)
                    ->whereBetween('carus_fecha_vencimiento', array($desde, $hasta))
                    ->where('carus_status', 1)
                    ->where('carus_tipo_carnet', '1') 
                    ->get();

  $pdf =PDF::setOptions (['images'=>true])->loadView('Usuario/PDF_Carnet_Vencido_Fechas',compact('vencidos', 'usuario', 'fecha','desde', 'hasta'));

  return $pdf->stream('Carnets-Vencidos.pdf');
  
}




// pdf carnets sin carnets
public function PdfSinCarnets (){
    
    session_start();

    $usuario = DB::table('usuarios')->where('us_ced', $_SESSION['id'])->join('departamentos', 'us_dp_id', '=', 'dp_id')->join('horario_x_usuario', 'hxu_cedula', '=', 'us_ced')->join('tipos_horarios', 'hxu_tiho_id', '=', 'tiho_id')->paginate(1);

    $fecha = Carbon::now();
    $fecha->toDateString(); 


    $pdf =PDF::setOptions (['images'=>true])->loadView('Usuario/PDF_Sin_Carnet',compact('vencidos', 'usuario', 'fecha'));

     return $pdf->stream('Sin-Carnets.pdf');                

}


// vista modal sin carnets ---------------
public function noCarnet(Request $request){

    if ($request->ajax()) {

      //  $empleadoC = carnet_us::lists('carus_ced');
      //  $empleadoH = horario_x_usuario::lists('hxu_cedula');

          $empleadoC = carnet_us::pluck('carus_ced');
          $empleadoH = horario_x_usuario::pluck('hxu_cedula');

      /*  $empleados = DB::table('usuarios AS u')
                     ->select(DB::raw("us_nom as nombres, us_ape as apellidos, us_ced as cedula, dp_nombre as des_uni, max(hxu_fecha_created) as fecha 
                        "))
                      ->join('horario_x_usuario', 'hxu_cedula','=','us_ced')
                      ->join('departamentos AS d', 'dp_id','=','us_dp_id')
                      ->where('us_status', 1)
                      ->where('us_tdu_id', 1)
                      ->whereNotIn('us_ced', $empleadoC)
                      ->whereIn('us_ced', $empleadoH)
                      ->groupBy('us_ced','d.dp_nombre')
                      ->take(5)
                     ->get();*/

          $empleados = DB::connection('vive_2016')
                     ->table('sno_personalnomina AS pn')
                     ->select(DB::raw("pn.codper, cedper as cedula, nomper as nombres, apeper as apellidos, pn.minorguniadm || '-' || pn.ofiuniadm || '-' || pn.uniuniadm || '-' || pn.depuniadm || '-' || pn.prouniadm as cod_uni, sc.codcar as cod_car,  sc.descar as des_car, max(pn.fecingper) as fecha 
                        "))
                     ->join('sno_personal as p', 'p.codper', '=','pn.codper')
                     ->leftjoin('sno_cargo as sc', 'sc.codcar','=','pn.codcar')
                     ->join('sno_nomina as n', 'n.codnom', '=', 'pn.codnom')
                     ->where('staper',1)
                     ->whereBetween('n.codnom', array('0001', '0008'))
                     ->whereNotIn('cedper', $empleadoC)
                     ->whereIn('cedper', $empleadoH)
                     ->groupBy('cedper','nomper','apeper','pn.minorguniadm', 'pn.ofiuniadm', 'pn.uniuniadm', 'pn.depuniadm', 'pn.prouniadm', 'sc.codcar', 'sc.descar', 'pn.fecingper', 'pn.codper')
                     ->distinct('pn.codper')
                     ->take(5)
                     ->get();

        return response()->json($empleados);
      }

}


// index empleados con carnets

    public function conCarnets(){

        session_start();

        if (isset($_SESSION['foto'])) {

        $sigesp = config::where('cof_tipo', 'SIGESP')->get()->pluck('cof_value')->last();
        $local = config::where('cof_tipo', 'LOCAL')->get()->pluck('cof_value')->last();

     /*   if ($sigesp == 1 ) {

          $empleadoC = carnet_us::lists('carus_ced');
          $empleadoH = horario_x_usuario::lists('hxu_cedula');

          $empleados = DB::connection('vive_2016')
                     ->table('sno_personalnomina AS pn')
                     ->select(DB::raw("pn.codper, cedper as cedula, nomper as nombres, apeper as apellidos, pn.minorguniadm || '-' || pn.ofiuniadm || '-' || pn.uniuniadm || '-' || pn.depuniadm || '-' || pn.prouniadm as cod_uni, sc.codcar as cod_car,  sc.descar as des_car, max(pn.fecingper) as fecha 
                        "))
                     ->join('sno_personal as p', 'p.codper', '=','pn.codper')
                     ->leftjoin('sno_cargo as sc', 'sc.codcar','=','pn.codcar')
                     ->join('sno_nomina as n', 'n.codnom', '=', 'pn.codnom')
                     ->where('staper',1)
                     ->whereBetween('n.codnom', array('0001', '0008'))
                     ->whereIn('cedper', $empleadoC)
                     ->whereIn('cedper', $empleadoH)
                     ->groupBy('cedper','nomper','apeper','pn.minorguniadm', 'pn.ofiuniadm', 'pn.uniuniadm', 'pn.depuniadm', 'pn.prouniadm', 'sc.codcar', 'sc.descar', 'pn.fecingper', 'pn.codper')
                     ->distinct('pn.codper')
                     ->paginate(15);
      
             $tipo_reportes = DB::table('carnet_tipo_reportes')
                                ->select('ctr_id', 'ctr_descripcion')
                                ->where('ctr_id' , '>', 1)
                                ->where('ctr_id', '<', 6)
                                ->orderBy('ctr_id', 'asc')
                                ->get();

            $tipo_reportes2 = DB::table('carnet_tipo_reportes')
                                ->select('ctr_id', 'ctr_descripcion')
                                ->where('ctr_id' , '>', 1)
                                ->orderBy('ctr_id', 'asc')
                                ->get();
            $rol = roles::where('ro_id',$_SESSION['acceso'])get()->pluck('ro_id')->last();

            $aco_cr = acceso::join('pantallas','aco_pnt_id','=','pnt_id')
            ->where('aco_ro_id',$rol)->where('pnt_nombre','p_cnreporte')get()->pluck('pnt_nombre')->last();

            return view('Usuario/carnet', compact('empleados','tipo_reportes', 'tipo_reportes2','aco_cr'));

        }elseif ($local == 1 ) {*/

            $empleados = DB::table('usuarios AS u')
                     ->select(DB::raw("us_nom as nombres, us_ape as apellidos, us_ced as cedula, dp_nombre as des_uni, max(hxu_fecha_created) as fecha, c.car_nombre AS des_car 
                        "))
                      ->join('horario_x_usuario', 'hxu_cedula','=','us_ced')
                      ->join('departamentos AS d', 'dp_id','=','us_dp_id')
                      ->join('cargos AS c', 'car_id', '=', 'us_car_cod_id')
                      ->join('carnet_us','carus_ced','=','us_ced')
                      ->where('us_status', 1)
                      ->where('us_tdu_id', 1)
                      ->groupBy('us_ced','d.dp_nombre', 'c.car_nombre')
                      ->paginate(10);


           $tipo_reportes = DB::table('carnet_tipo_reportes')
                                ->select('ctr_id', 'ctr_descripcion')
                                ->where('ctr_id' , '>', 1)
                                ->where('ctr_id', '<', 6)
                                ->orderBy('ctr_id', 'asc')
                                ->get();

            /*$tipo_reportes2 = DB::table('carnet_tipo_reportes')
                                ->select('ctr_id', 'ctr_descripcion')
                                ->where('ctr_id' , '>', 5)
                                ->orderBy('ctr_id', 'asc')
                                ->get();*/

            $tipo_reportes2 = DB::table('carnet_tipo_reportes')
                                ->select('ctr_id', 'ctr_descripcion')
                                ->where('ctr_id' ,'>', 1)
                                ->orderBy('ctr_id', 'asc')
                                ->get();

            $rol = roles::where('ro_id',$_SESSION['acceso'])->get()->pluck('ro_id')->last();

            $aco_cr = acceso::join('pantallas','aco_pnt_id','=','pnt_id')
            ->where('aco_ro_id',$rol)->where('pnt_nombre','p_cnreporte')->get()->pluck('pnt_nombre')->last();

            return View('Usuario/carnet', compact('empleados', 'tipo_reportes', 'tipo_reportes2','aco_cr'));
        

      //  }

        }else{ 
          //Si la sesion no existe...
          Session::flash('session', 'A expirado la sesión');
          return view('login');
        }

    } // fin funcion index 
//----------------HASTA AQUI--------------------------
//-------------------Datos de Empleado con Carnet ---------------------------------

public function datosEmpleado(Request $request, $id){ // modal

  

   $sigesp = config::where('cof_tipo', 'SIGESP')->get()->pluck('cof_value')->last();
    $local = config::where('cof_tipo', 'LOCAL')->get()->pluck('cof_value')->last();

        if ($request->ajax()) {

           /* if ($sigesp == 1 ) {
             $db = DB::connection('vive_2016');
             $stmt = $db->getPdo()->prepare("SELECT * FROM fn_usuarios_carnets(?,?)");
             $stmt->execute(['cedula', $id]);
             $empleados = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');

             return response()->json($empleados);


            }elseif ($local == 1) { */

             $db = DB::connection('vive_2016');
             $stmt = $db->getPdo()->prepare("SELECT * FROM fn_usuarios_carnets(?,?)");
             $stmt->execute(['cedula', $id]);
             $empleados = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');

             return response()->json($empleados);

               /* $empleados = DB::table('usuarios AS u')
                   ->select('u.us_nom AS nombres', 'u.us_ape AS apellidos', 'u.us_ced AS cedula', 'd.dp_nombre AS des_uni', 'c.car_nombre as cod_car', 'c.car_nombre AS des_car','d.dp_codigo AS cod_uni', 'carus_fecha_vencimiento as fecha', 'carus_status as status', 'carus_motivo as motivoRe', 'ctr_descripcion as descripcion')
                   ->join('departamentos AS d','us_dp_id', '=', 'dp_id')
                   ->join('cargos AS c', 'car_id', '=', 'us_car_cod_id')
                   ->join('carnet_us', 'carus_ced', '=', 'us_ced')
                   ->join('carnet_tipo_reportes', 'ctr_id', '=', 'carus_motivo')
                   ->where('us_ced', $id)
                   ->get();

              return response()->json($empleados);

              */
         // }
  
        }
    }    

    public function getDatoEmpleado(Request $request, $id){

       $empleados = DB::table('carnet_us AS c')
         ->select('carus_fecha_vencimiento as fecha', 'carus_status as status', 'carus_motivo as motivoRe', 'ctr_descripcion as descripcion')
         ->join('carnet_tipo_reportes', 'ctr_id', '=', 'carus_motivo')
         ->where('carus_ced', $id)
         ->get();

     return response()->json($empleados);

    }
//-------------------Datos de Empleado con Carnet modal FIN --------------------------------

// ------------------ cargar datos modal de vista previa (reportar carnets)-------------------------

public function datosVista(Request $request, $id){
  

    $sigesp = config::where('cof_tipo', 'SIGESP')->get()->pluck('cof_value')->last();
    $local = config::where('cof_tipo', 'LOCAL')->get()->pluck('cof_value')->last();

        if ($request->ajax()) {

          /*  if ($sigesp == 1 ) {
             $db = DB::connection('vive_2016');
             $stmt = $db->getPdo()->prepare("SELECT * FROM fn_usuarios_carnets(?,?)");
             $stmt->execute(['cedula', $id]);
             $empleados = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
             
           //  return response()->json($empleados);


          //  }elseif ($local == 1) { 

           /* $empleados = DB::table('usuarios AS u')
           ->select('u.us_nom AS nombres', 'u.us_ape AS apellidos', 'u.us_ced AS cedula', 'd.dp_nombre AS des_uni')
           ->join('departamentos AS d','us_dp_id', '=', 'dp_id')
           ->where('us_ced', $id)
           ->get();

           $empleados = DB::select(DB::raw("
                SELECT u.us_nom AS nombres, u.us_ape AS apellidos, u.us_ced AS cedula, d.dp_codigo AS cod_uni,
     d.dp_nombre AS des_uni, c.car_nombre AS des_car, c.car_cod AS cod_car, u.us_foto, cU.carus_fecha_vencimiento AS fecha, cU.carus_selloprensa AS sello from usuarios AS u
                INNER JOIN departamentos AS d on u.us_dp_id = d.dp_id
                INNER JOIN carnet_us AS cU on u.us_ced = cU.carus_ced
                INNER JOIN cargos AS c on u.us_car_cod_id = c.car_id
                WHERE us_ced =  ".$id." ")); */

             $db = DB::connection('vive_2016');
             $stmt = $db->getPdo()->prepare("SELECT * FROM fn_usuarios_carnets(?,?)");
             $stmt->execute(['cedula', $id]);
             $empleados = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');

          /* $empleados = DB::table('usuarios AS u')
                   ->select('u.us_nom AS nombres', 'u.us_ape AS apellidos', 'u.us_ced AS cedula', 'd.dp_nombre AS des_uni', 'c.car_nombre as cod_car', 'c.car_nombre AS des_car','d.dp_codigo AS cod_uni', 'carus_fecha_vencimiento as fecha', 'carus_status as status', 'carus_selloprensa as sello', 'carus_motivo as motivoR')
                   ->join('departamentos AS d','us_dp_id', '=', 'dp_id')
                   ->join('cargos AS c', 'car_id', '=', 'us_car_cod_id')
                   ->join('carnet_us', 'carus_ced', '=', 'us_ced')
                   ->where('us_ced', $id)
                   ->get();*/

            return response()->json($empleados);
      //  }
    }

    }

    public function datosVista2(Request $request, $id){

      $empleados = DB::table('carnet_us AS c')
                   ->select('carus_fecha_vencimiento as fecha', 'carus_status as status', 'carus_selloprensa as sello', 'carus_motivo as motivoR')
                   ->where('carus_ced', $id)
                   ->get();

            return response()->json($empleados);

    }

// ------------------ cargar datos modal de vista previa FIN -------------------------


//--------- Modifica datos del empleado para reportar carnet ---------------

public function modEmpleado(Request $request){ // Modificar datos para carnet de empleados

  session_start();

 if (isset($_POST['registrar'])) {

        $cedulaEmpleado = $_POST['cedulaEmpleado'];
        $empleadoFechaVec = $_POST['empleadoFechaVec'];
        $apellidoEmpleado = $_POST['apellidoEmpleado'];
        $ipmaquina = $_SERVER["REMOTE_ADDR"];
        $areaEmpleado = $_POST['areaEmpleado'];
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $empleadoMotivo = $_POST['carnet_usu_motivo'];
        $nombreEmpleado = $_POST['nombreEmpleado'];
        $areaEmpleado = $_POST['areaEmpleado'];
        $selloPrensa =$_POST['selloPrensaC'];
        $statusE = $_POST['status'];
        $motivoRe = $_POST['motivoRp'];

        $empleadoFechaVecim = $_POST['empleadoFechaVecim'];

     }

    /* if ($imprimirReportar == 'Si') {

        //Toma id del ultimo horario de la persona --------
        $tbl_horario = DB::table('horario_x_usuario')
        ->where('hxu_cedula', $cedulaEmpleado)
        ->orderby('hxu_fecha_created','DESC')
        ->get()
        ->pluck('hxu_id')
        ->last();

        $th = count($tbl_horario);
        $date = Carbon::now()->toDateTimeString(); //Fecha actual

        if ($th == 0) {
            Session::flash('flash_message', 'Disculpe el empleado con C.I '.$cedulaEmpleado.' no posee un horario debe asignarle uno para poder reportar su carnet');
            return redirect()->back();
        }else{
 //verifica si persona tiene carnet y actualiza datos (para reportar los carnets)----------------
           $tbl_carnet = carnet_us::where('carus_ced', $cedulaEmpleado)->get();
           $c_n = count($tbl_carnet);

           if ($c_n > 0) { // si encuentro empleado en tabla carnet_us

            if($empleadoMotivo == 4){ // reportar carnet como vencido no genera nuevo codigo

                    $carnet_us = DB::table('carnet_us')
                    ->where('carus_ced', $cedulaEmpleado)
                    ->update(['carus_motivo' => $empleadoMotivo,
                                'updated_at' => $date
                        ]);

                    if ($carnet_us == true) {
                            Session::flash('flash_message', 'Se ha reportado el carnet como vencido del empleado C.I '.$cedulaEmpleado.'');
                            }else{
                                Session::flash('flash_message', 'Error, no se han modificado dichos datos');
                            }

                        return redirect()->back();

            }elseif ($empleadoMotivo == 7 ) { // para reimprimir un carnet 

                Session::flash('flash_message', 'No puede reimprimir un carnet si solo se reportará');

                return redirect()->back();
                            
            }else{ // reporto carnet como extraviado, robado, hurtado, retirado

                $carnets_us = DB::table('carnet_us')
                        ->where('carus_ced', $cedulaEmpleado)
                        ->update(['carus_motivo' => $empleadoMotivo,
                                  'carus_status' => 2,
                                  'updated_at'   => $date
                        ]);

                        if ($carnets_us == true) {
                            $carnetLast = carnet_us::where('carus_ced', $cedulaEmpleado)
                            ->get()->pluck('carus_codigo')->last(); // tomo cod de carnet vigente para reportarlo

                            $inutilizados = new carnet_seriales_inutilizados;

                            $id_int = 'csi_id';
                            $inutilizados->csi_fecha = Carbon::now()->toDateString();
                            $inutilizados->csi_hora = Carbon::now()->toTimeString();
                            $inutilizados->csi_cedula = $cedulaEmpleado;
                            $inutilizados->csi_nombre = $nombreEmpleado;
                            $inutilizados->csi_apellido = $apellidoEmpleado;
                            $inutilizados->csi_cod_barra = $carnetLast;
                            $inutilizados->csi_tipo_reporte_id = $empleadoMotivo;
                            $inutilizados->csi_carnet_user_id = 1;
                            $inutilizados->csi_tipo_carnet = 1;

                            $inutilizados->save();

                            Session::flash('flash_message', 'Se ha reportado el carnet del empleado C.I '.$cedulaEmpleado.' y este no podrá usarse más');
                            }else{
                                Session::flash('flash_message', 'Error, no se han modificado dichos datos');
                            }
            }

                        return redirect()->back();
            }
         
        }

        }*/
       // else{ //Fin Reportar SI 

        //Toma id del ultimo horario de la persona --------
        $tbl_horario = DB::table('horario_x_usuario')
        ->where('hxu_cedula', $cedulaEmpleado)
        ->orderby('hxu_fecha_created','DESC')->get()
        ->pluck('hxu_id')->last();

        $th = count($tbl_horario);
        if ($th == 0) {
            Session::flash('flash_message', 'Disculpe el empleado con C.I '.$cedulaEmpleado.' no posee un horario debe asignarle uno');
            return redirect()->back();
        }else{

//verifica si persona tiene carnet y actualiza datos (para reportar los carnets)----------------

        $tbl_carnet = carnet_us::where('carus_ced', $cedulaEmpleado)->get();
        $c_n = count($tbl_carnet);
        $date = Carbon::now()->toDateTimeString(); //Fecha actual

        if ($c_n > 0) { // si encuentro empleado en tabla carnet_us

            if ( ($empleadoMotivo != $motivoRe) && ($motivoRe != 7) && ($motivoRe != 1) ){
                Session::flash('flash_message', 'Disculpe este carnet ya tiene otro tipo de reporte');
            return redirect()->back();
            }elseif( ($motivoRe == 7) || ($empleadoMotivo == $motivoRe) || ($motivoRe == 1)){

            if($empleadoMotivo == 4){ // reportar carnet como vencido no genera nuevo codigo

                    $date = Carbon::now(); //2015-01-01 00:00:00 fecha actual

                    $fechaActual = $date->toDateString();

                    if ( ($empleadoFechaVec < $fechaActual) || ($empleadoFechaVec < $empleadoFechaVecim )) {
                        Session::flash('flash_error_Fecha','La fecha de Vencimiento no puede ser menor a la fecha actual o fecha de vencimiento anterior');
                        return redirect()->back();
                    }else{

                      if ($request->file('image') != "") {

                      $name = $cedulaEmpleado.'.jpg';
                      $request->file('image')->move('imagenes2', $name);

                    }

                    $carnet_us = DB::table('carnet_us')
                    ->where('carus_ced', $cedulaEmpleado)
                    ->update(['carus_motivo' => 1,
                              'carus_fecha_vencimiento' => $empleadoFechaVec,
                              'updated_at'   => $date,
                              'carus_selloprensa' => $selloPrensa
                        ]);
                  }

                      //  }

                    if ($carnet_us == true) {

                        $carnetCodigo = carnet_us::where('carus_ced', $cedulaEmpleado)
                            ->get()->pluck('carus_codigo')->last();

                            $navegador = $this->getBrowser($user_agent);
                            $areaEm = Usuarios::where('us_ced', $_SESSION['id'])->get()->pluck('us_dp_id')->last();
                            $usuario = Usuarios::where('us_ced', $_SESSION['id'])->get()->pluck('us_login')->last();

                            $historico = new carnet_historico;
                            $id_hist = 'carth_id';
                            $historico->carth_ip_maquina = $ipmaquina;
                            $historico->carth_usuario = $usuario;
                            $historico->carth_departamento = $areaEm;
                            $historico->carth_so = PHP_OS.' - '.php_uname();
                            $historico->carth_navegador = $navegador;
                            $historico->carth_serial_carnet = $carnetCodigo;
                            $historico->carth_cedulaempleado = $cedulaEmpleado;
                            $historico->carth_motivo = $empleadoMotivo;
                            $historico->carth_tipo_carnet = 1;
                                
                            $historico->save();

                            
                            Session::flash('flash_message_Venc', 'Se ha renovado el carnet del empleado C.I '.$cedulaEmpleado.'');

                            //debe mostrar pdf del carnet

                            }else{
                                Session::flash('flash_message_Venc', 'Error, no se han modificado dichos datos');
                            }

                        return redirect()->back();

                    }/*elseif ($empleadoMotivo == 6) { // motivo Retirado empleado (no genera nuevo codigo)

                        $carnets_us = DB::table('carnet_us')
                        ->where('carus_ced', $cedulaEmpleado)
                        ->update(['carus_motivo' => $empleadoMotivo,
                                  'carus_status' => 2,
                                  'updated_at'   => $date
                        ]);

                        if ($carnets_us == true) {
                            $carnetLast = carnet_us::where('carus_ced', $cedulaEmpleado)
                            ->pluck('carus_codigo'); // tomo cod de carnet vigente para reportarlo

                            $inutilizados = new carnet_seriales_inutilizados;

                                $id_int = 'csi_id';
                                $inutilizados->csi_fecha = Carbon::now()->toDateString();
                                $inutilizados->csi_hora = Carbon::now()->toTimeString();
                                $inutilizados->csi_cedula = $cedulaEmpleado;
                                $inutilizados->csi_nombre = $nombreEmpleado;
                                $inutilizados->csi_apellido = $apellidoEmpleado;
                                $inutilizados->csi_cod_barra = $carnetLast;
                                $inutilizados->csi_tipo_reporte_id = $empleadoMotivo;
                                $inutilizados->csi_carnet_user_id = 1;
                                $inutilizados->csi_tipo_carnet = 1;

                                $inutilizados->save();
                            Session::flash('flash_message', 'Se ha reportado el carnet del empleado C.I '.$cedulaEmpleado.' no se generará otro carnet ');
                            }else{
                                Session::flash('flash_message', 'Error, no se han modificado dichos datos');
                            }

                        return redirect()->back();

                        }elseif ($empleadoMotivo == 7 ) { // para reimprimir un carnet 

                          $carnets_us = DB::table('carnet_us')
                                        ->where('carus_ced', $cedulaEmpleado)
                                        ->update(['carus_motivo' => $empleadoMotivo,
                                                  'updated_at'   => $date
                                        ]);

                            if ($carnets_us == true) {

                            $carnetCodigo = carnet_us::where('carus_ced', $cedulaEmpleado)
                            ->pluck('carus_codigo');

                            $navegador = $this->getBrowser($user_agent);
                            $areaEm = Usuarios::where('us_ced', $_SESSION['id'])->pluck('us_dp_id');

                            $historico = new carnet_historico;
                                $id_hist = 'carth_id';
                                $historico->carth_ip_maquina = $ipmaquina;
                                $historico->carth_usuario = $usuario;
                                $historico->carth_departamento = $areaEm;
                                $historico->carth_so = $navegador;
                                $historico->carth_navegador = $navegador;
                                $historico->carth_serial_carnet = $carnetCodigo;
                                $historico->carth_cedulaempleado = $cedulaEmpleado;
                                $historico->carth_motivo = $empleadoMotivo;
                                
                                $historico->save();

                                if($historico == true){
                                    Session::flash('flash_message_Cod', 'Ha sido impreso nuevamente el código de barras del empleado C.I '.$cedulaEmpleado.' ');

                                    //mostrar pdf

                                    }else{
                                Session::flash('flash_message_Cod', 'Error, no se ha podido imprimir este carnet');
                                }
                            }
                        }*/else{ // reporto carnet como extraviado, robado, hurtado (genera nuevo codigo)

                          $date = Carbon::now(); //2015-01-01 00:00:00 fecha actual

                          $fechaActual = $date->toDateString();

                          if ( ($empleadoFechaVec < $fechaActual) || ($empleadoFechaVec < $empleadoFechaVecim )) {
                              Session::flash('flash_error_Fecha','La fecha de Vencimiento no puede ser menor a la fecha actual o fecha de vencimiento anterior');
                              return redirect()->back();
                          }else{

                             if ($request->file('image') != "") {

                               $name = $cedulaEmpleado.'.jpg';
                               $request->file('image')->move('imagenes2', $name);

                             }

                            $carnetLast = carnet_us::where('carus_ced', $cedulaEmpleado)->get()
                            ->pluck('carus_codigo')->last(); // tomo cod de carnet vigente para reportarlo
                            $usuarioCI = $_SESSION['id'];

                            $Codigo = carnet_seriales_inutilizados::where('csi_cod_barra', $carnetLast)
                            ->get()->pluck('csi_cod_barra')->last();

                            $c = count($Codigo);

                            if ($c == 0) {

                              $inutilizados = new carnet_seriales_inutilizados;

                              $id_int = 'csi_id';
                              $inutilizados->csi_fecha = Carbon::now()->toDateString();
                              $inutilizados->csi_hora = Carbon::now()->toTimeString();
                              $inutilizados->csi_cedula = $cedulaEmpleado;
                              $inutilizados->csi_nombre = $nombreEmpleado;
                              $inutilizados->csi_apellido = $apellidoEmpleado;
                              $inutilizados->csi_cod_barra = $carnetLast;
                              $inutilizados->csi_tipo_reporte_id = $empleadoMotivo;
                              $inutilizados->csi_carnet_user_id = $usuarioCI;
                              $inutilizados->csi_tipo_carnet = 1;

                              $inutilizados->save();
                              
                            }

                            $carnetCodigo = rand(300000,900000); // Creo cod de carnet nuevo

                            $verifico = carnet_us::where('carus_codigo', $carnetCodigo)->get();
                            $cod = count($verifico);
                            if ($cod == 0) { // verifico que no este el codigo registrado 

                            $carnets_us = DB::table('carnet_us')
                            ->where('carus_ced', $cedulaEmpleado)
                            ->update(['carus_motivo' => 1,
                                      'updated_at' => $date,
                                      'carus_fecha_vencimiento' => $empleadoFechaVec,
                                      'carus_codigo' => $carnetCodigo,
                                      'carus_selloprensa' => $selloPrensa,
                                      'carus_status' => 1
                            ]);
                        
                        if ($carnets_us == true) {

                            $navegador = $this->getBrowser($user_agent);
                            $areaEm = Usuarios::where('us_ced', $_SESSION['id'])->get()->pluck('us_dp_id')->last();
                            $usuario = Usuarios::where('us_ced', $_SESSION['id'])->get()->pluck('us_login')->last();

                                $historico = new carnet_historico;
                                $id_hist = 'carth_id';
                                $historico->carth_ip_maquina = $ipmaquina;
                                $historico->carth_usuario = $usuario;
                                $historico->carth_departamento = $areaEm;
                                $historico->carth_so = PHP_OS.' - '.php_uname();
                                $historico->carth_navegador = $navegador;
                                $historico->carth_serial_carnet = $carnetCodigo;
                                $historico->carth_cedulaempleado = $cedulaEmpleado;
                                $historico->carth_motivo = $empleadoMotivo;
                                $historico->carth_tipo_carnet = 1;
                                
                                $historico->save();

                                Session::flash('flash_message_Rep', 'Se ha reportado el carnet del empleado C.I '.$cedulaEmpleado.' y se ha creado uno nuevo ');

                                 //debe mostrar pdf del carnet
                              

                            }else{
                                Session::flash('flash_message_Rep', 'Error, no se han modificado dichos datos');
                    }
                
                  }  
              }

            }

        }/*else{
            Session::flash('flash_message', 'El carnet del empleado C.I '.$cedulaEmpleado.' esta inactivo');

            return redirect()->back();
        }*/
    }
    }
        
//}
                return redirect()->back();

}


public function modEmpleadoReportar(Request $request){ // Modificar datos para carnet de empleados (solo reportes)

  session_start();

 if (isset($_POST['reportar'])) {

        $cedulaEmpleadoR = $_POST['cedulaEmpleado'];
        $nombreEmpleadoR = $_POST['nombreEmpleado'];
        $apellidoEmpleadoR = $_POST['apellidoEmpleado'];
        $empleadoFechaVecR = $_POST['empleadoFechaVecim'];        
        $ipmaquinaR = $_SERVER["REMOTE_ADDR"];
        $user_agentR = $_SERVER['HTTP_USER_AGENT'];
        $empleadoMotivoR = $_POST['carnet_motivo_report'];       
        $tipoVistaR = $_POST['VistaCod'];
        $statusR = $_POST['statusR'];
     }

        //Toma id del ultimo horario de la persona --------
        $tbl_horario = DB::table('horario_x_usuario')
        ->where('hxu_cedula', $cedulaEmpleadoR)
        ->orderby('hxu_fecha_created','DESC')
        ->get()
        ->pluck('hxu_id')
        ->last();

        $th = count($tbl_horario);
        $date = Carbon::now()->toDateTimeString(); //Fecha actual

        if ($th == 0) {
            Session::flash('flash_message', 'Disculpe el empleado con C.I '.$cedulaEmpleadoR.' no posee un horario debe asignarle uno para poder reportar su carnet');
            return redirect()->back();
        }else{
 //verifica si persona tiene carnet y actualiza datos (para reportar los carnets)----------------
           $tbl_carnet = carnet_us::where('carus_ced', $cedulaEmpleadoR)->get();
           $c_n = count($tbl_carnet);

           if ($c_n > 0) { // si encuentro empleado en tabla carnet_us

            if ($statusR == "Inactivo") {
                    Session::flash('flash_message', 'Este carnet ya ha sido reportado, no podrá reimprimirlo');

                }elseif($empleadoMotivoR == 4){ // reportar carnet como vencido no genera nuevo codigo

                    $carnet_us = DB::table('carnet_us')
                    ->where('carus_ced', $cedulaEmpleadoR)
                    ->update(['carus_motivo' => $empleadoMotivoR,
                                'updated_at' => $date
                    ]);

                    if ($carnet_us == true) {
                            Session::flash('flash_message', 'Se ha reportado el carnet como vencido del empleado C.I '.$cedulaEmpleadoR.'');
                            }else{
                                Session::flash('flash_message', 'Error, no se han modificado dichos datos');
                            }

                        return redirect()->back();

            }elseif (($empleadoMotivoR == 7 ) && ($statusR == "Activo")) { // para reimprimir un carnet 

                 $carnet_us = DB::table('carnet_us')
                    ->where('carus_ced', $cedulaEmpleadoR)
                    ->update(['carus_motivo' => $empleadoMotivoR,
                                'updated_at' => $date
                    ]);

                    if ($carnet_us == true) {
                            
                            $carnetCodigo = carnet_us::where('carus_ced', $cedulaEmpleadoR)->get()
                            ->pluck('carus_codigo')->last(); // tomo cod de carnet vigente para reportarlo

                            $navegador = $this->getBrowser($user_agentR);
                            $areaEm = Usuarios::where('us_ced', $_SESSION['id'])->get()->pluck('us_dp_id')->last();
                            $usuario = Usuarios::where('us_ced', $_SESSION['id'])->get()->pluck('us_login')->last();

                            $historico = new carnet_historico;
                            $id_hist = 'carth_id';
                            $historico->carth_ip_maquina = $ipmaquinaR;
                            $historico->carth_usuario = $usuario;
                            $historico->carth_departamento = $areaEm;
                            $historico->carth_so = PHP_OS.' - '.php_uname();
                            $historico->carth_navegador = $navegador;
                            $historico->carth_serial_carnet = $carnetCodigo;
                            $historico->carth_cedulaempleado = $cedulaEmpleadoR;
                            $historico->carth_motivo = $empleadoMotivoR;
                            $historico->carth_tipo_carnet = 1;
                                            
                            $historico->save();

                            if ($historico == true) {

                                if ($tipoVistaR == "QR") {

                                     return redirect()->back()->with('flash_message_QR','Se ha impreso nuevamente el código QR del empleado '.$cedulaEmpleadoR.' ');

                                }elseif ($tipoVistaR == "Barra") {

                                    return redirect()->back()->with('flash_message_Barra','Se ha impreso nuevamente el código de Barras del empleado '.$cedulaEmpleadoR.'');

                                }elseif ($tipoVistaR == "Completo") {

                                    return redirect()->back()->with('flash_message_Carnet','Se ha impreso nuevamente el carnet del empleado '.$cedulaEmpleadoR.'');
                                }
                            }
                            }else{
                                Session::flash('flash_message', 'Error, no se han modificado dichos datos');
                            }

                return redirect()->back();
                            
            }elseif (($empleadoMotivoR == 7 ) && ($statusR == "Inactivo")) {
               
               return redirect()->back()->with('flash_message','El carnet del empleado '.$cedulaEmpleadoR.' no puede ser impreso nuevamente');

            }else{ // reporto carnet como extraviado, robado, hurtado, retirado

                if ( ($empleadoMotivoR != 4) || ($empleadoMotivoR != 7)  ) {

                    $carnets_us = DB::table('carnet_us')
                        ->where('carus_ced', $cedulaEmpleadoR)
                        ->update(['carus_motivo' => $empleadoMotivoR,
                                  'carus_status' => 2,
                                  'updated_at'   => $date
                        ]);


                        if ($carnets_us == true) {
                            $carnetLast = carnet_us::where('carus_ced', $cedulaEmpleadoR)->get()
                            ->pluck('carus_codigo')->last(); // tomo cod de carnet vigente para reportarlo

                            $usuarioCI = $_SESSION['id'];

                            $inutilizados = new carnet_seriales_inutilizados;

                            $id_int = 'csi_id';
                            $inutilizados->csi_fecha = Carbon::now()->toDateString();
                            $inutilizados->csi_hora = Carbon::now()->toTimeString();
                            $inutilizados->csi_cedula = $cedulaEmpleadoR;
                            $inutilizados->csi_nombre = $nombreEmpleadoR;
                            $inutilizados->csi_apellido = $apellidoEmpleadoR;
                            $inutilizados->csi_cod_barra = $carnetLast;
                            $inutilizados->csi_tipo_reporte_id = $empleadoMotivoR;
                            $inutilizados->csi_carnet_user_id = $usuarioCI;
                            $inutilizados->csi_tipo_carnet = 1;

                            $inutilizados->save();

                            Session::flash('flash_message', 'Se ha reportado el carnet del empleado C.I '.$cedulaEmpleadoR.' y este no podrá usarse más');
                            }else{
                                Session::flash('flash_message', 'Error, no se han modificado dichos datos');
                            }
                    
                }

                
            }

                        return redirect()->back();
            }
         
        }

        }
 //Fin Reportar SI 



// PDF Carnets reportados FIN ---------------

// Genera PDF CARNETS REPORTADOS 

public function getPDF(){

    $sigesp = config::where('cof_tipo', 'SIGESP')->get()->pluck('cof_value')->last();
    $local = config::where('cof_tipo', 'LOCAL')->get()->pluck('cof_value')->last();

      //  if ($sigesp == 1 ) {

       /* $datosC = DB::table('carnet_us')
        ->select(DB::raw("carus_ced, max(updated_at) as fecha , carus_selloprensa as sello, carus_fecha_vencimiento, carus_codigo"))
        ->orderBy('updated_at', 'DESC')
        ->groupBy('carus_ced', 'updated_at', 'carus_selloprensa', 'carus_fecha_vencimiento', 'carus_codigo')
        ->take(1)
        ->get();

          $empleadosC = carnet_us::orderBy('updated_at', 'DESC')->get()->pluck('carus_ced')->last();*/

          $datosC = DB::table('carnet_us')
        ->select('carus_selloprensa as sello','carus_fecha_vencimiento','carus_codigo','carus_ced')
        ->orderBy('updated_at', 'desc')->take(1)->get();
          
          $empleadosC = carnet_us::orderBy('updated_at', 'asc')->get()->pluck('carus_ced')->last();
 
            $db = DB::connection('vive_2016');
            $stmt = $db->getPdo()->prepare("SELECT * FROM fn_usuarios_carnets(?,?)");
            $stmt->execute(['cedula', $empleadosC]);
            $empleados = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');

            $encabezado = DB::table('configs')
            ->select('cof_nombre as encabezado')
            ->orderBy('cof_id', 'desc')
            ->where('cof_tipo', 'Encabezado')
            ->take(1)
            ->get();

            $empresa = DB::table('configs')
            ->select('cof_nombre as empresa')
            ->orderBy('cof_id', 'desc')
            ->where('cof_tipo', 'Empresa')
            ->take(1)
            ->get();

            $pagina = DB::table('configs')
            ->select('cof_nombre as pagina')
            ->orderBy('cof_id', 'desc')
            ->where('cof_tipo', 'Pagina')
            ->take(1)
            ->get();

            $agradecimiento= DB::table('configs')
            ->select('cof_nombre as agradecimiento')
            ->orderBy('cof_id', 'desc')
            ->where('cof_tipo', 'Agradecimiento')
            ->take(1)
            ->get();

            $descripcion = DB::table('configs')
            ->select('cof_nombre as descripcion')
            ->orderBy('cof_id', 'desc')
            ->where('cof_tipo', 'Descripcion')
            ->take(1)
            ->get();

            $telefono = DB::table('configs')
            ->select('cof_nombre as telefono')
            ->orderBy('cof_id', 'desc')
            ->where('cof_tipo', 'telefono')
            ->take(1)
            ->get();

            $presidente = DB::table('configs')
            ->select('cof_nombre as presidente', 'cof_alias as foto')
            ->orderBy('cof_id', 'desc')
            ->where('cof_tipo', 'FIRMA')
            ->take(1)
            ->get();

            $codigo = DB::table('carnet_fondos')
            ->select('descripcion as tipoCodigo')
            ->orderBy('id', 'desc')
            ->where('fondo_carnet', 'codigo')
            ->take(1)
            ->get();


            $barra = new DNS1D();
            $barraC = new DNS2D();

            $pdf =PDF::setOptions (['images'=>true])
            ->setPaper(array(0, 0, 578, 918)) //204*324
            ->loadView('Usuario/disenoCarnet',compact('barra', 'empleados','datosC','barraC', 'encabezado', 'empresa', 'pagina', 'agradecimiento', 'descripcion', 'telefono', 'presidente', 'codigo'));
              return $pdf->stream('Carnet.pdf');


     /*   }elseif ($local == 1 ) {

         /* $datosC = DB::table('carnet_us')
            ->select(DB::raw("carus_ced, max(updated_at) as fecha , carus_selloprensa as sello, carus_fecha_vencimiento, carus_codigo"))
            ->orderBy('updated_at', 'DESC')
            ->groupBy('carus_ced', 'updated_at', 'carus_selloprensa', 'carus_fecha_vencimiento', 'carus_codigo')
            ->take(1)
            ->get();

          $empleadosC = carnet_us::orderBy('updated_at', 'DESC')->get()->pluck('carus_ced')->last();*/

      /*    $datosC = DB::table('carnet_us')
        ->select('carus_selloprensa as sello','carus_fecha_vencimiento','carus_codigo','carus_ced')
        ->orderBy('updated_at', 'desc')->take(1)->get();
          
          $empleadosC = carnet_us::orderBy('updated_at', 'asc')->get()->pluck('carus_ced')->last();



          $empleados = DB::table('usuarios')
                      ->select('us_nom as nombres', 'us_ape as apellidos', 'us_ced as cedula', 'dp_nombre as des_uni', 'c.car_cod AS cod_car','c.car_nombre as des_car','d.dp_codigo AS cod_uni')              
                      ->join('departamentos AS d', 'dp_id','=','us_dp_id')
                      ->join('cargos AS c', 'car_id', '=', 'us_car_cod_id')
                      ->where('us_ced', $empleadosC)
                      ->get();

          $barra = new DNS1D();
          $barraC = new DNS2D();

          $pdf =PDF::setOptions (['images'=>true])->loadView('Usuario/disenoCarnet',compact('barra', 'empleados','datosC','barraC', 'encabezado', 'empresa', 'pagina', 'agradecimiento', 'descripcion', 'telefono'));

                return $pdf->stream('Carnet.pdf');
  }*/
}

// GENERA PDF CARNETS IMPRESOS NUEVAMENTE (COD DE BARRAS) 

public function PDFCodBarras(){

  session_start();

    $usuario = DB::table('usuarios')->where('us_ced', $_SESSION['id'])->join('departamentos', 'us_dp_id', '=', 'dp_id')->join('horario_x_usuario', 'hxu_cedula', '=', 'us_ced')->join('tipos_horarios', 'hxu_tiho_id', '=', 'tiho_id')->paginate(1);

    $fecha = Carbon::now();
    $fecha->toDateString(); 

    $sigesp = config::where('cof_tipo', 'SIGESP')->get()->pluck('cof_value')->last();
    $local = config::where('cof_tipo', 'LOCAL')->get()->pluck('cof_value')->last();

      //  if ($sigesp == 1 ) {

       /* $datosC = DB::table('carnet_us')
        ->select(DB::raw("carus_ced, max(updated_at) as fecha , carus_codigo"))
        ->orderBy('updated_at', 'DESC')
        ->groupBy('carus_ced', 'updated_at', 'carus_codigo')
        ->take(1)
        ->get();

          $empleadosC = carnet_us::orderBy('updated_at', 'DESC')->get()->pluck('carus_ced')->last();*/

          $datosC = DB::table('carnet_us')
        ->select('carus_selloprensa as sello','carus_fecha_vencimiento','carus_codigo','carus_ced')
        ->orderBy('updated_at', 'desc')->take(1)->get();
          
          $empleadosC = carnet_us::orderBy('updated_at', 'asc')->get()->pluck('carus_ced')->last();
 
            $db = DB::connection('vive_2016');
            $stmt = $db->getPdo()->prepare("SELECT * FROM fn_usuarios_carnets(?,?)");
            $stmt->execute(['cedula', $empleadosC]);
            $empleados = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');


            $barra = new DNS1D();
            $barraC = new DNS2D();

            $pdf =PDF::setOptions (['images'=>true])->loadView('Usuario/CodBarras',compact('barra', 'empleados','datosC','barraC','usuario', 'fecha'));

            return $pdf->stream('Carnet.pdf');


       /* }elseif ($local == 1 ) {

        /*  $datosC = DB::table('carnet_us')
            ->select(DB::raw("carus_ced, max(updated_at) as fecha , carus_codigo"))
            ->orderBy('updated_at', 'DESC')
            ->groupBy('carus_ced', 'updated_at', 'carus_codigo')
            ->take(1)
            ->get();

          $empleadosC = carnet_us::orderBy('updated_at', 'DESC')->get()->pluck('carus_ced')->last();*/

    /*      $datosC = DB::table('carnet_us')
        ->select('carus_selloprensa as sello','carus_fecha_vencimiento','carus_codigo','carus_ced')
        ->orderBy('updated_at', 'desc')->take(1)->get();
          
          $empleadosC = carnet_us::orderBy('updated_at', 'asc')->get()->pluck('carus_ced')->last();

          $empleados = DB::table('usuarios')
                      ->select('us_nom as nombres', 'us_ape as apellidos', 'us_ced as cedula', 'dp_nombre as des_uni', 'c.car_cod AS cod_car','c.car_nombre as des_car','d.dp_codigo AS cod_uni')              
                      ->join('departamentos AS d', 'dp_id','=','us_dp_id')
                      ->join('cargos AS c', 'car_id', '=', 'us_car_cod_id')
                      ->where('us_ced', $empleadosC)
                      ->get();

          $barra = new DNS1D();
          $barraC = new DNS2D();

          $pdf =PDF::setOptions (['images'=>true])->loadView('Usuario/CodBarras',compact('barra', 'empleados','datosC','barraC','usuario','fecha'));

                return $pdf->stream('Carnet.pdf');
  }*/
}

public function getPDFCodQR (){

  session_start();

    $usuario = DB::table('usuarios')->where('us_ced', $_SESSION['id'])->join('departamentos', 'us_dp_id', '=', 'dp_id')->join('horario_x_usuario', 'hxu_cedula', '=', 'us_ced')->join('tipos_horarios', 'hxu_tiho_id', '=', 'tiho_id')->paginate(1);

    $fecha = Carbon::now();
    $fecha->toDateString(); 

  /*  $datosCod = DB::table('carnet_us')
            ->select(DB::raw("carus_ced as cedula, max(carnet_us.updated_at) as fecha , carus_codigo, us_nom as nombres, us_ced as cedula, us_ape as apellidos, dp_nombre as des_uni, c.car_nombre as des_car"))
            ->join('usuarios', 'carus_ced', '=', 'us_ced')
            ->join('departamentos AS d', 'dp_id','=','us_dp_id')
            ->join('cargos AS c', 'car_id', '=', 'us_car_cod_id')
            ->orderBy('carnet_us.updated_at', 'DESC')
            ->groupBy('carus_ced', 'carnet_us.updated_at', 'carus_codigo' , 'us_ced', 'us_nom', 'us_ape', 'dp_nombre','car_nombre')
            ->take(1)
            ->get();

          $empleadosC = carnet_us::orderBy('updated_at', 'DESC')->get()->pluck('carus_ced')->last();



          $empleados = DB::table('usuarios')
                      ->select('us_nom as nombres', 'us_ape as apellidos', 'us_ced as cedula', 'dp_nombre as des_uni', 'c.car_cod AS cod_car','c.car_nombre as des_car','d.dp_codigo AS cod_uni')              
                      ->join('departamentos AS d', 'dp_id','=','us_dp_id')
                      ->join('cargos AS c', 'car_id', '=', 'us_car_cod_id')
                      ->where('us_ced', $empleadosC)
                      ->get();

          $barra = new DNS1D();
          $barraC = new DNS2D();

          $pdf =PDF::setOptions (['images'=>true])->loadView('Usuario/CodQR',compact('barra','datosCod','barraC','usuario', 'fecha'));

                return $pdf->stream('Carnet.pdf');*/

           $datosCod = DB::table('carnet_us')
        ->select('carus_selloprensa as sello','carus_fecha_vencimiento','carus_codigo','carus_ced')
        ->orderBy('updated_at', 'desc')->take(1)->get();
          
          $empleadosC = carnet_us::orderBy('updated_at', 'asc')->get()->pluck('carus_ced')->last();
 
            $db = DB::connection('vive_2016');
            $stmt = $db->getPdo()->prepare("SELECT * FROM fn_usuarios_carnets(?,?)");
            $stmt->execute(['cedula', $empleadosC]);
            $empleados = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');


          $barra = new DNS1D();
          $barraC = new DNS2D();

          $pdf =PDF::setOptions (['images'=>true])->loadView('Usuario/CodQR',compact('barra','datosCod','barraC','usuario', 'fecha'));

                return $pdf->stream('Carnet.pdf');

}

// BUSQUEDA EMPLEADOS CON CARNETS 

public function empleadoConCarnetBusca(Request $request, $id) { 

  $sigesp = config::where('cof_tipo', 'SIGESP')->get()->pluck('cof_value')->last();
  $local = config::where('cof_tipo', 'LOCAL')->get()->pluck('cof_value')->last();

  if ($request->ajax()) {

     // if ($sigesp == 1 ) {

      //  $empleadoC = carnet_us::lists('carus_ced');
       // $empleadoH = horario_x_usuario::lists('hxu_cedula');

      /*  $empleado = DB::connection('vive_2016')
                     ->table('sno_personalnomina AS pn')
                     ->select(DB::raw("pn.codper, cedper as cedula, nomper as nombres, apeper as apellidos, pn.minorguniadm || '-' || pn.ofiuniadm || '-' || pn.uniuniadm || '-' || pn.depuniadm || '-' || pn.prouniadm as cod_uni, sc.codcar as cod_car,  sc.descar as des_car, max(pn.fecingper) as fecha 
                        "))
                     ->join('sno_personal as p', 'p.codper', '=','pn.codper')
                     ->leftjoin('sno_cargo as sc', 'sc.codcar','=','pn.codcar')
                     ->join('sno_nomina as n', 'n.codnom', '=', 'pn.codnom')
                     ->where('staper',1)
                     ->where('cedper' , $cedula)
                     ->whereBetween('n.codnom', array('0001', '0008'))
                     ->whereIn('cedper', $empleadoC)
                     ->whereIn('cedper', $empleadoH)                     
                     ->groupBy('cedper','nomper','apeper','pn.minorguniadm', 'pn.ofiuniadm', 'pn.uniuniadm', 'pn.depuniadm', 'pn.prouniadm', 'sc.codcar', 'sc.descar', 'pn.fecingper', 'pn.codper')
                     ->distinct('pn.codper')
                     ->pluck('cedper');

        $db = DB::connection('vive_2016');
        $stmt = $db->getPdo()->prepare("SELECT * FROM fn_usuarios_carnets(?,?)");
        $stmt->execute(['cedula', $empleado]);
        $empleados = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass'); 

             return response()->json($empleados);

        }elseif ($local == 1 ) {

            $empleadoC = carnet_us::lists('carus_ced');
            $empleadoH = horario_x_usuario::lists('hxu_cedula');*/

           /* $empleados = DB::table('usuarios AS u')
                     ->select(DB::raw("us_nom as nombres, us_ape as apellidos, us_ced as cedula, dp_nombre as des_uni, max(hxu_fecha_created) as fechaH, c.car_nombre AS des_car, carus_fecha_vencimiento as fecha 
                        "))
                      ->join('horario_x_usuario', 'hxu_cedula','=','us_ced')
                      ->join('departamentos AS d', 'dp_id','=','us_dp_id')
                      ->join('cargos AS c', 'car_id', '=', 'us_car_cod_id')
                      ->join('carnet_us', 'us_ced', '=', 'carus_ced')
                      ->whereIn('us_ced', $empleadoC)
                      ->whereIn('us_ced', $empleadoH)
                      ->where('us_status', 1)
                      ->where('us_tdu_id', 1)
                      ->where('us_ced', $cedula)
                      ->groupBy('us_ced','d.dp_nombre', 'c.car_nombre')
                      ->get();*/

          /*  $empleados = DB::table('usuarios AS u')
                   ->select('u.us_nom AS nombres', 'u.us_ape AS apellidos', 'u.us_ced AS cedula', 'd.dp_nombre AS des_uni', 'c.car_nombre as cod_car', 'c.car_nombre AS des_car','d.dp_codigo AS cod_uni', 'carus_fecha_vencimiento as fecha', 'carus_status as status', 'ctr_descripcion as descripcion', 'carus_motivo as motivoRe')
                   ->join('departamentos AS d','us_dp_id', '=', 'dp_id')
                   ->join('cargos AS c', 'car_id', '=', 'us_car_cod_id')
                   ->join('carnet_us', 'carus_ced', '=', 'us_ced')
                   ->join('carnet_tipo_reportes', 'ctr_id', '=', 'carus_motivo')
                   ->where('us_ced', $cedula)
                   ->get();



                      return response()->json($empleados); */

          $empleadoC = carnet_us::pluck('carus_ced');
          $empleadoH = horario_x_usuario::pluck('hxu_cedula');

          $empleado = DB::table('usuarios')
                      ->select('us_ced')
                      ->whereIn('us_ced', $empleadoC)
                      ->whereIn('us_ced', $empleadoH)
                      ->where('us_ced',$id)
                      ->get()
                      ->pluck('us_ced')->last();
                      

        $db = DB::connection('vive_2016');
        $stmt = $db->getPdo()->prepare("SELECT * FROM fn_usuarios_carnets(?,?)");
        $stmt->execute(['cedula', $empleado]);
        $empleados = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass'); 


           /*  $db = DB::connection('vive_2016');
             $stmt = $db->getPdo()->prepare("SELECT * FROM fn_usuarios_carnets(?,?)");
             $stmt->execute(['cedula', $id]);
             $empleados = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');*/

             return response()->json($empleados);

     //  }
                
        }
  }

  public function empleadoConCarnetBuscaVista2 (Request $request, $id){

    if ($request->ajax()) {
        $empleados = DB::table('carnet_us AS c')
         ->select('carus_fecha_vencimiento as fecha', 'carus_status as status', 'carus_motivo as motivoRe', 'ctr_descripcion as descripcion')
         ->join('carnet_tipo_reportes', 'ctr_id', '=', 'carus_motivo')
         ->where('carus_ced', $id)
         ->get();

     return response()->json($empleados);
          }
  }


// Genera pdf de listado de personas con carnets
public function PdfConCarnet(){

   session_start();

    $usuario = DB::table('usuarios')->where('us_ced', $_SESSION['id'])->join('departamentos', 'us_dp_id', '=', 'dp_id')->join('horario_x_usuario', 'hxu_cedula', '=', 'us_ced')->join('tipos_horarios', 'hxu_tiho_id', '=', 'tiho_id')->paginate(1);

    $fecha = Carbon::now();
    $fecha->toDateString();

    $empleados = DB::table('usuarios AS u')
                     ->select(DB::raw("us_nom as nombres, us_ape as apellidos, us_ced as cedula, dp_nombre as des_uni, max(hxu_fecha_created) as fecha, c.car_nombre AS des_car 
                        "))
                      ->join('horario_x_usuario', 'hxu_cedula','=','us_ced')
                      ->join('departamentos AS d', 'dp_id','=','us_dp_id')
                      ->join('cargos AS c', 'car_id', '=', 'us_car_cod_id')
                      ->join('carnet_us','carus_ced','=','us_ced')
                      ->where('us_status', 1)
                      ->where('us_tdu_id', 1)
                      ->where('carus_tipo_carnet', 1)
                      ->groupBy('us_ced','d.dp_nombre', 'c.car_nombre')
                      ->get();

    $pdf =PDF::setOptions (['images'=>true])->loadView('Usuario/PDF_Con-Carnet',compact('usuario', 'fecha', 'empleados'));

    return $pdf->stream('Con-Carnets.pdf');


}















// ---------------Creación de nuevos carnets ------------------

public function carnetNuevo (Request $request) {  // vista de empleados sin carnets

    session_start();

        if (isset($_SESSION['foto'])) {   
 

    $sigesp = config::where('cof_tipo', 'SIGESP')->get()->pluck('cof_value')->last();
    $local = config::where('cof_tipo', 'LOCAL')->get()->pluck('cof_value')->last();

      //  if ($sigesp == 1 ) {

        //  $empleadoC = carnet_us::lists('carus_ced');
        //  $empleadoH = horario_x_usuario::lists('hxu_cedula');

          $empleadoC = carnet_us::pluck('carus_ced');
          $empleadoH = horario_x_usuario::pluck('hxu_cedula');

         $empleados = DB::connection('vive_2016')
         ->table('sno_personalnomina AS pn')
         ->select(DB::raw("pn.codper, cedper as cedula, nomper as nombres, apeper as apellidos, pn.minorguniadm || '-' || pn.ofiuniadm || '-' || pn.uniuniadm || '-' || pn.depuniadm || '-' || pn.prouniadm as cod_uni, sc.codcar as cod_car,  sc.descar as des_car, max(pn.fecingper) as fecha 
            "))
         ->join('sno_personal as p', 'p.codper', '=','pn.codper')
         ->leftjoin('sno_cargo as sc', 'sc.codcar','=','pn.codcar')
         ->join('sno_nomina as n', 'n.codnom', '=', 'pn.codnom')
         ->where('staper',1)
         ->whereBetween('n.codnom', array('0001', '0008'))
         ->whereNotIn('cedper', $empleadoC)
         ->whereIn('cedper', $empleadoH)
         ->groupBy('cedper','nomper','apeper','pn.minorguniadm', 'pn.ofiuniadm', 'pn.uniuniadm', 'pn.depuniadm', 'pn.prouniadm', 'sc.codcar', 'sc.descar', 'pn.fecingper', 'pn.codper')
         ->distinct('pn.codper')
         ->paginate(10);

          $nuevo = DB::table('carnet_tipo_reportes')
                ->select('ctr_id', 'ctr_descripcion')
                ->where('ctr_id','=', 1)
                ->get();

          $motivos = DB::table('carnet_tipo_reportes')
              ->select('ctr_id', 'ctr_descripcion')
              ->where('ctr_id','>', 1)
              ->where('ctr_id', '<', 7)
              ->where('ctr_id', '!=', 4)
              ->orderBy('ctr_id', 'asc')
              ->get();

          $empleadoCC = carnet_provisionales::pluck('car_prov_ced');
          $empleadoHH = horario_x_usuario::pluck('hxu_cedula');

          $empleadoss = DB::connection('vive_2016')
           ->table('sno_personalnomina AS pn')
           ->select(DB::raw("pn.codper, cedper as cedula, nomper as nombres, apeper as apellidos, pn.minorguniadm || '-' || pn.ofiuniadm || '-' || pn.uniuniadm || '-' || pn.depuniadm || '-' || pn.prouniadm as cod_uni, sc.codcar as cod_car,  sc.descar as des_car, max(pn.fecingper) as fecha 
              "))
           ->join('sno_personal as p', 'p.codper', '=','pn.codper')
           ->leftjoin('sno_cargo as sc', 'sc.codcar','=','pn.codcar')
           ->join('sno_nomina as n', 'n.codnom', '=', 'pn.codnom')
           ->where('staper',1)
           ->whereBetween('n.codnom', array('0001', '0008'))
           ->whereIn('cedper', $empleadoCC)
           ->whereIn('cedper', $empleadoHH)
           ->groupBy('cedper','nomper','apeper','pn.minorguniadm', 'pn.ofiuniadm', 'pn.uniuniadm', 'pn.depuniadm', 'pn.prouniadm', 'sc.codcar', 'sc.descar', 'pn.fecingper', 'pn.codper')
           ->distinct('pn.codper')
           ->paginate(10);
          $rol = roles::where('ro_id',$_SESSION['acceso'])->get()->pluck('ro_id')->last();

          $aco_cn = acceso::join('pantallas','aco_pnt_id','=','pnt_id')
          ->where('aco_ro_id',$rol)->where('pnt_nombre','p_cnempleado')->get()->pluck('pnt_nombre')->last();
          return view('Usuario/new_carnet', compact('empleados','aco_cn','empleadoss','nuevo','motivos'));

        /*  }elseif ($local == 1 ) {

          //  $empleadoC = carnet_us::lists('carus_ced');
            $empleadoC = carnet_us::pluck('carus_ced');

            $empleados = DB::table('usuarios AS u')
                    /* ->select(DB::raw("us_nom as nombres, us_ape as apellidos, us_ced as cedula, dp_nombre as des_uni, max(hxu_fecha_created) as fecha, c.car_nombre AS des_car 
                        "))*/
      /*               ->select(DB::raw("us_nom as nombres, us_ape as apellidos, us_ced as cedula, dp_nombre as des_uni, max(hxu_fecha_created) as fecha 
                        "))
                      ->join('horario_x_usuario', 'hxu_cedula','=','us_ced')
                      ->join('departamentos AS d', 'dp_id','=','us_dp_id')
                     // ->join('cargos AS c', 'car_id', '=', 'us_car_cod_id')
                      ->where('us_status', 1)
                      ->where('us_tdu_id', 1)
                      ->whereNotIn('us_ced', $empleadoC)
                      //->groupBy('us_ced','d.dp_nombre','c.car_nombre')
                      ->groupBy('us_ced','d.dp_nombre')
                      ->paginate(10);
                      $rol = roles::where('ro_id',$_SESSION['acceso'])->get()->pluck('ro_id')->last();

                      $aco_cn = acceso::join('pantallas','aco_pnt_id','=','pnt_id')
                      ->where('aco_ro_id',$rol)->where('pnt_nombre','p_cnempleado')->get()->pluck('pnt_nombre')->last();
        return view('Usuario/new_carnet', compact('empleados','aco_cn'));
    }*/

    }else{ 
            //Si la sesion no existe...
            Session::flash('session', 'A expirado la sesión');
            return view('login');
        }
}

public function datosEmpleadoNewCarnet(Request $request, $id){  // carga datos en modal para insertar registros 
  

    $sigesp = config::where('cof_tipo', 'SIGESP')->get()->pluck('cof_value')->last();
    $local = config::where('cof_tipo', 'LOCAL')->get()->pluck('cof_value')->last();

        if ($request->ajax()) {

          //  if ($sigesp == 1 ) {
             $db = DB::connection('vive_2016');
             $stmt = $db->getPdo()->prepare("SELECT * FROM fn_usuarios_carnets(?,?)");
             $stmt->execute(['cedula', $id]);
             $empleados = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');

             return response()->json($empleados);


          /*  }elseif ($local == 1) {

                $empleados = DB::table('usuarios AS u')
                   ->select('u.us_nom AS nombres', 'u.us_ape AS apellidos', 'u.us_ced AS cedula', 'd.dp_codigo AS cod_uni', 'd.dp_nombre AS des_uni')
                   ->join('departamentos AS d','us_dp_id', '=', 'dp_id')
                   ->join('cargos as c', 'u.us_car_cod_id', '=', 'c.car_id')
                   ->where('us_ced', $id)
                   ->get();

                    return response()->json($empleados);
         }*/
  
        }
    }

    public function registrarNewCarnet(Request $request){
        session_start();

    if (isset($_POST['registrar'])) {

        $cedulaEmpleadoN = $_POST['cedulaEmpleadoN'];
        $nombreEmpleadoN = $_POST['nombreEmpleadoN'];
        $apellidoEmpleadoN = $_POST['apellidoEmpleadoN'];
        $areaEmpleadoN =$_POST['areaEmpleadoN'];
        $empleadoFechaVecN = $_POST['empleadoFechaVecN'];
        $ipmaquina = $_SERVER["REMOTE_ADDR"];
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $usuario = $_SESSION['id']; // cedula usuario
        $selloPrensa =$_POST['selloPrensaN'];
        $cargoCodigo = $_POST['cargoCodigo'];

     }

     // $empleadoFechaVecN = date("Y/m/d", strtotime($empleadoFechaVecN));
     // selecciono el ultimo horario 
        $tbl_horario = DB::table('horario_x_usuario')
        ->where('hxu_cedula', $cedulaEmpleadoN)
        ->orderby('hxu_fecha_created','DESC')->get()
        ->pluck('hxu_id')->last();

         $carnetCodigo = rand(300000,900000); // Creo cod de carnet 

        $verifico = carnet_us::where('carus_codigo', $carnetCodigo)->get();
        $cod = count($verifico);

        

                    if ($cod == 0) { // verifico que no este el codigo registrado 

                        $rules = ['image' => 'required|image|mimes:jpeg,jpg,png',];
                        $messages = [
                            'image.required' => 'La imagen es requerida',
                            'image.image' => 'Formato no permitido',
                            'image.max' => 'El máximo permitido es 1 MB',
                        ];
                        $validator = Validator::make($request->all(), $rules, $messages);
                        
                        if ($validator->fails()){
                            return redirect('new_carnet')->withErrors($validator);
                        }else{

                            $date = Carbon::now(); //2015-01-01 00:00:00 fecha actual

                            $fechaActual = $date->toDateString();

                            if ($empleadoFechaVecN < $fechaActual) {
                                Session::flash('flash_error_Fecha','La fecha de Vencimiento no puede ser menor a la fecha actual');
                                return redirect()->back();
                            }else{

                            $name = $cedulaEmpleadoN.'.jpg';
                            $request->file('image')->move('imagenes2', $name);
                           // chmod('imagenes/'.$cedulaEmpleadoN.".jpg",0777);
                            
                            $carus = new carnet_us;

                            $id = 'carus_id';
                            $carus->carus_ced = $cedulaEmpleadoN;
                            $carus->carus_codigo = $carnetCodigo;
                            $carus->carus_hxu_id = $tbl_horario;
                            $carus->carus_status = 1;
                            $carus->carus_fecha_vencimiento =  $empleadoFechaVecN;
                            $carus->carus_tipo_carnet = 1;
                            $carus->carus_motivo = 1;
                            $carus->carus_selloprensa = $selloPrensa;
                    
                        }
                      }

                        $carus->save();

                    $alerta = $carus->save();

                    if ($alerta == true) {

                        $navegador = $this->getBrowser($user_agent);
                        $areaEm = Usuarios::where('us_ced', $_SESSION['id'])->get()->pluck('us_dp_id')->last();
                        $usuario = Usuarios::where('us_ced', $_SESSION['id'])->get()->pluck('us_login')->last();

                        $historico = new carnet_historico;
                        $id_hist = 'carth_id';
                        $historico->carth_ip_maquina = $ipmaquina;
                        $historico->carth_usuario = $usuario;
                        $historico->carth_departamento = $areaEm;
                        $historico->carth_so = PHP_OS.' - '.php_uname();
                        $historico->carth_navegador = $navegador;
                        $historico->carth_serial_carnet = $carnetCodigo;
                        $historico->carth_cedulaempleado = $cedulaEmpleadoN;
                        $historico->carth_motivo = 1;
                        $historico->carth_tipo_carnet = 1;
                        
                        $historico->save();

                        $history = $historico->save();

                        if ($history == true) {

                          $cargosEmp = DB::connection('vive_2016')
                                       ->table('sno_cargo')
                                       ->where('codcar', $cargoCodigo)
                                       ->get()->pluck('codcar')->last();

                          $cargosNomb = DB::connection('vive_2016')
                                       ->table('sno_cargo')
                                       ->where('codcar', $cargoCodigo)
                                       ->get()->pluck('descar')->last();

                        $cargoID = cargos::where('car_cod', $cargosEmp)->get()->pluck('car_id')->last();

                        $n = count($cargoID);

                        if ($n > 0) {

                          $usuario = DB::table('usuarios')
                            ->where('us_ced', $cedulaEmpleadoN)
                            ->update(['us_car_cod_id' => $cargoID
                            ]);

                            if ($usuario == true) {
                              Session::flash('flash_message','Se ha creado un nuevo carnet');
                            }else{
                              Session::flash('flash_message','Falló');
                            }
                          
                        }else{

                          $cargo = new cargos;
                          $cargo_id = 'car_id';
                          $cargo->car_cod = $cargosEmp;
                          $cargo->car_nombre = $cargosNomb;
                        
                          $cargo->save();

                          $cargoR = $cargo->save();

                          if ($cargoR == true) {

                            $carID = cargos::where('car_cod', $cargosEmp)->get()->pluck('car_id')->last();

                            $usuarioC = DB::table('usuarios')
                            ->where('us_ced', $cedulaEmpleadoN)
                            ->update(['us_car_cod_id' => $carID
                            ]);

                            Session::flash('flash_message','Se ha creado un nuevo carnet');
                            }else{
                              Session::flash('flash_message','Falló');
                            }

                        }
                      }

                        

                    }else{
                        Session::flash('flash_message','Falló');
                    }

                }
    return redirect()->back();


}

public function empleadoSinCarnetBusca(Request $request, $cedula) { 

    $sigesp = config::where('cof_tipo', 'SIGESP')->get()->pluck('cof_value')->last();
    $local = config::where('cof_tipo', 'LOCAL')->get()->pluck('cof_value')->last();

    if ($request->ajax()) {

      //  if ($sigesp == 1 ) {

          //  $empleadoC = carnet_us::lists('carus_ced');
          //  $empleadoH = horario_x_usuario::lists('hxu_cedula');

      /*   $empleadoC = carnet_us::pluck('carus_ced');
          $empleadoH = horario_x_usuario::pluck('hxu_cedula');

            $empleado = DB::connection('vive_2016')
                     ->table('sno_personalnomina AS pn')
                     ->select(DB::raw("pn.codper, cedper as cedula, nomper as nombres, apeper as apellidos, pn.minorguniadm || '-' || pn.ofiuniadm || '-' || pn.uniuniadm || '-' || pn.depuniadm || '-' || pn.prouniadm as cod_uni, sc.codcar as cod_car,  sc.descar as des_car, max(pn.fecingper) as fecha 
                        "))
                     ->join('sno_personal as p', 'p.codper', '=','pn.codper')
                     ->leftjoin('sno_cargo as sc', 'sc.codcar','=','pn.codcar')
                     ->join('sno_nomina as n', 'n.codnom', '=', 'pn.codnom')
                     ->where('staper',1)
                     ->whereBetween('n.codnom', array('0001', '0008'))
                     ->whereNotIn('cedper', $empleadoC)
                     ->whereIn('cedper', $empleadoH)
                     ->where('cedper', $cedula)
                     ->groupBy('cedper','nomper','apeper','pn.minorguniadm', 'pn.ofiuniadm', 'pn.uniuniadm', 'pn.depuniadm', 'pn.prouniadm', 'sc.codcar', 'sc.descar', 'pn.fecingper', 'pn.codper')
                     ->distinct('pn.codper')->get()
                     ->pluck('cedper')->last();*/

    /*    $db = DB::connection('vive_2016');
        $stmt = $db->getPdo()->prepare("SELECT * FROM fn_usuarios_carnets(?,?)");
        $stmt->execute(['cedula', $cedula]);
        $empleados = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass'); 

             return response()->json($empleados);*/

          // }elseif ($local == 1 ) {

          $empleadoC = carnet_us::pluck('carus_ced');
          $empleadoH = horario_x_usuario::pluck('hxu_cedula');

          $empleado = DB::table('usuarios')
                      ->select('us_ced')
                      ->whereNotIn('us_ced', $empleadoC)
                      ->whereIn('us_ced', $empleadoH)
                      ->where('us_ced',$cedula)
                      ->get()
                      ->pluck('us_ced')->last();
                      

        $db = DB::connection('vive_2016');
        $stmt = $db->getPdo()->prepare("SELECT * FROM fn_usuarios_carnets(?,?)");
        $stmt->execute(['cedula', $empleado]);
        $empleados = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass'); 


     /*   $empleados = DB::select(DB::raw("
                 SELECT us_nom as nombres, us_ape as apellidos, us_ced as cedula, dp_nombre as des_uni, dp_codigo AS cod_uni, car_nombre AS des_car, car_cod AS cod_car, max(hxu_fecha_created) as fecha 
                FROM usuarios T1
                INNER JOIN horario_x_usuario on T1.us_ced = horario_x_usuario.hxu_cedula
                INNER JOIN departamentos on T1.us_dp_id = departamentos.dp_id
                INNER JOIN cargos on T1.us_car_cod_id = cargos.car_id
                WHERE NOT EXISTS (SELECT carus_ced FROM carnet_us T2 WHERE T1.us_ced = T2.carus_ced) 
                AND us_status = 1 
                AND us_tdu_id = 1
                AND us_ced = ".$cedula."
                GROUP BY nombres, apellidos, cedula, des_uni, cod_uni, des_car, cod_car"));

        */

        /*    $empleados = DB::select(DB::raw("
                 SELECT us_nom as nombres, us_ape as apellidos, us_ced as cedula, dp_nombre as des_uni, dp_codigo AS cod_uni, car_nombre AS des_car, car_cod AS cod_car, max(hxu_fecha_created) as fecha 
                FROM usuarios T1
                INNER JOIN horario_x_usuario on T1.us_ced = horario_x_usuario.hxu_cedula
                INNER JOIN departamentos on T1.us_dp_id = departamentos.dp_id
                INNER JOIN cargos on T1.us_car_cod_id = cargos.car_id
                WHERE NOT EXISTS (SELECT carus_ced FROM carnet_us T2 WHERE T1.us_ced = T2.carus_ced) 
                AND us_status = 1 
                AND us_tdu_id = 1
                AND us_ced = ".$cedula."
                GROUP BY nombres, apellidos, cedula, des_uni, cod_uni, des_car, cod_car"));*/
            
            return response()->json($empleados);
        }
   // }
}



//----------------busqueda empleados con carnet provisional--------------------


public function empleadoCarnetProvBusca(Request $request, $cedula) { 

    $sigesp = config::where('cof_tipo', 'SIGESP')->get()->pluck('cof_value')->last();
    $local = config::where('cof_tipo', 'LOCAL')->get()->pluck('cof_value')->last();

    if ($request->ajax()) {


          $empleadoC = carnet_us::pluck('carus_ced');
          $empleadoH = horario_x_usuario::pluck('hxu_cedula');

          $empleado = DB::table('usuarios')
                      ->select('us_ced')
                      ->whereNotIn('us_ced', $empleadoC)
                      ->whereIn('us_ced', $empleadoH)
                      ->where('us_ced',$cedula)
                      ->get()
                      ->pluck('us_ced')->last();
                      

        $db = DB::connection('vive_2016');
        $stmt = $db->getPdo()->prepare("SELECT * FROM fn_usuarios_carnets(?,?)");
        $stmt->execute(['cedula', $empleado]);
        $empleados = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass'); 

        
            
            return response()->json($empleados);
        }

}



// PDF carnets nuevos-----------
public function pdfNewCarnet (){

    $sigesp = config::where('cof_tipo', 'SIGESP')->get()->pluck('cof_value')->last();
    $local = config::where('cof_tipo', 'LOCAL')->get()->pluck('cof_value')->last();

      //  if ($sigesp == 1 ) {

        /*  $datosC = DB::table('carnet_us')
        ->select(DB::raw("carus_ced, max(created_at) as fecha , carus_selloprensa as sello, carus_fecha_vencimiento, carus_codigo"))
        ->orderBy('created_at', 'asc')
        ->groupBy('carus_ced', 'created_at', 'carus_selloprensa', 'carus_fecha_vencimiento', 'carus_codigo')
        ->take(1)
        ->get();*/

        $datosC = DB::table('carnet_us')
        ->select('carus_selloprensa as sello','carus_fecha_vencimiento','carus_codigo','carus_ced')
        ->orderBy('updated_at', 'desc')->take(1)->get();
          
          $empleadosC = carnet_us::orderBy('updated_at', 'asc')->get()->pluck('carus_ced')->last();
 
            $db = DB::connection('vive_2016');
            $stmt = $db->getPdo()->prepare("SELECT * FROM fn_usuarios_carnets(?,?)");
            $stmt->execute(['cedula', $empleadosC]);
            $empleados = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');

          /*  $empleados = DB::select(DB::raw("
                 SELECT us_nom as nombres, us_ape as apellidos, us_ced as cedula, car_nombre as des_car, car_cod as cod_car, dp_codigo as cod_uni, dp_nombre as des_uni
                FROM usuarios
                INNER JOIN cargos on usuarios.us_car_cod_id = cargos.car_id 
                INNER JOIN departamentos on usuarios.us_dp_id  = departamentos.dp_id 
                WHERE us_ced = ".$empleadosC."
                GROUP BY nombres, apellidos, cedula, des_uni, cod_uni, des_car, cod_car"));*/

            $encabezado = DB::table('configs')
            ->select('cof_nombre as encabezado')
            ->orderBy('cof_id', 'desc')
            ->where('cof_tipo', 'Encabezado')
            ->take(1)
            ->get();

            $empresa = DB::table('configs')
            ->select('cof_nombre as empresa')
            ->orderBy('cof_id', 'desc')
            ->where('cof_tipo', 'Empresa')
            ->take(1)
            ->get();

            $pagina = DB::table('configs')
            ->select('cof_nombre as pagina')
            ->orderBy('cof_id', 'desc')
            ->where('cof_tipo', 'Pagina')
            ->take(1)
            ->get();

            $agradecimiento= DB::table('configs')
            ->select('cof_nombre as agradecimiento')
            ->orderBy('cof_id', 'desc')
            ->where('cof_tipo', 'Agradecimiento')
            ->take(1)
            ->get();

            $descripcion = DB::table('configs')
            ->select('cof_nombre as descripcion')
            ->orderBy('cof_id', 'desc')
            ->where('cof_tipo', 'Descripcion')
            ->take(1)
            ->get();

            $telefono = DB::table('configs')
            ->select('cof_nombre as telefono')
            ->orderBy('cof_id', 'desc')
            ->where('cof_tipo', 'telefono')
            ->take(1)
            ->get();

            $presidente = DB::table('configs')
            ->select('cof_nombre as presidente', 'cof_alias as foto')
            ->orderBy('cof_id', 'desc')
            ->where('cof_tipo', 'FIRMA')
            ->take(1)
            ->get();

            $codigo = DB::table('carnet_fondos')
            ->select('descripcion as tipoCodigo')
            ->orderBy('id', 'desc')
            ->where('fondo_carnet', 'codigo')
            ->take(1)
            ->get();


            $barra = new DNS1D();
            $barraC = new DNS2D();

            $pdf =PDF::setOptions (['images'=>true])
            ->setPaper(array(0, 0, 578, 918)) //204*324
            ->loadView('Usuario/disenoCarnetNew',compact('barra', 'empleados','datosC','barraC', 'encabezado', 'empresa', 'pagina', 'agradecimiento', 'descripcion', 'telefono', 'presidente', 'codigo'));
              return $pdf->stream('Carnet.pdf');

       /* $datosC = DB::table('carnet_us')
        ->select(DB::raw("carus_ced, max(updated_at) as fecha , carus_selloprensa as sello, carus_fecha_vencimiento, carus_codigo"))
        ->orderBy('updated_at', 'DESC')
        ->groupBy('carus_ced', 'updated_at', 'carus_selloprensa', 'carus_fecha_vencimiento', 'carus_codigo')
        ->take(1)
        ->get();

          $empleadosC = carnet_us::orderBy('updated_at', 'DESC')->pluck('carus_ced');
 
            $db = DB::connection('vive_2016');
            $stmt = $db->getPdo()->prepare("SELECT * FROM fn_usuarios_carnets(?,?)");
            $stmt->execute(['cedula', $empleadosC]);
            $empleados = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');


            $barra = new DNS1D();
            $barraC = new DNS2D();

            $pdf =PDF::setOptions (['images'=>true])->loadView('Usuario/disenoCarnetNew',compact('barra', 'empleados','datosC','barraC'));
              return $pdf->stream('Carnet.pdf');
*/

       // }elseif ($local == 1 ) {

        /*  $datosC = DB::table('carnet_us')
            ->select(DB::raw("carus_ced, max(updated_at) as fecha , carus_selloprensa as sello, carus_fecha_vencimiento, carus_codigo"))
            ->orderBy('updated_at', 'DESC')
            ->groupBy('carus_ced', 'updated_at', 'carus_selloprensa', 'carus_fecha_vencimiento', 'carus_codigo')
            ->take(1)
            ->get();

          $empleadosC = carnet_us::orderBy('updated_at', 'DESC')->get()->pluck('carus_ced')->last();



          $empleados = DB::table('usuarios')
                      ->select('us_nom as nombres', 'us_ape as apellidos', 'us_ced as cedula', 'dp_nombre as des_uni', 'c.car_cod AS cod_car','c.car_nombre as des_car','d.dp_codigo AS cod_uni')              
                      ->join('departamentos AS d', 'dp_id','=','us_dp_id')
                      ->join('cargos AS c', 'car_id', '=', 'us_car_cod_id')
                      ->where('us_ced', $empleadosC)
                      ->get();

          $barra = new DNS1D();
          $barraC = new DNS2D();

          $pdf =PDF::setOptions (['images'=>true])->loadView('Usuario/disenoCarnetNew',compact('barra', 'empleados','datosC','barraC'));

                return $pdf->stream('Carnet.pdf');*/
 // }


}


// Genera pdf de listado de personas sin carnets
public function PdfSinCarnet(){

   session_start();

    $usuario = DB::table('usuarios')->where('us_ced', $_SESSION['id'])->join('departamentos', 'us_dp_id', '=', 'dp_id')->join('horario_x_usuario', 'hxu_cedula', '=', 'us_ced')->join('tipos_horarios', 'hxu_tiho_id', '=', 'tiho_id')->paginate(1);

    $fecha = Carbon::now();
    $fecha->toDateString();

   // $empleadoC = carnet_us::lists('carus_ced');

  //  $empleadoH = horario_x_usuario::lists('hxu_cedula');

    $empleadoC = carnet_us::pluck('carus_ced');
    $empleadoH = horario_x_usuario::pluck('hxu_cedula');



     $empleados = DB::connection('vive_2016')
     ->table('sno_personalnomina as pn')
     ->select(DB::raw("pn.codper, cedper, max(pn.fecingper) as fecha"))
     ->join('sno_personal as p', 'p.codper', '=','pn.codper')
     ->join('sno_nomina as n', 'n.codnom', '=', 'pn.codnom')
     ->where('staper',1)
     ->whereBetween('n.codnom', array('0001', '0008'))
     ->distinct('pn.codper')
     ->groupBy('cedper', 'pn.fecingper', 'pn.codper')
     ->pluck('cedper');

    $empleadosU = DB::table('usuarios')
                      ->select('us_nom as nombres', 'us_ape as apellidos', 'us_ced as cedula', 'dp_nombre as des_uni', 'c.car_cod AS cod_car','c.car_nombre as des_car','d.dp_codigo AS cod_uni')    
                      ->join('departamentos AS d', 'dp_id','=','us_dp_id')
                      ->join('cargos AS c', 'car_id', '=', 'us_car_cod_id')
                      ->where('us_status', 1)
                      ->whereNotIn('us_ced', $empleadoC)
                      ->whereIn('us_ced', $empleadoH)
                      ->whereIn('us_ced', $empleados)
                      ->get();

    $pdf =PDF::setOptions (['images'=>true])->loadView('Usuario/PDF_Sin-Carnet',compact('usuario', 'fecha', 'empleadosU'));

    return $pdf->stream('Sin-Carnets.pdf');


}





// ----------------Fin creación de carnets ----------------------

//CARNETS PROVISIONALES ----------

    public function provisionales() { // index lista de pasantes con carnets
      session_start();

     /* $sigesp = config::where('cof_tipo', 'SIGESP')->pluck('cof_value');
      $local = config::where('cof_tipo', 'LOCAL')->pluck('cof_value');

        if ($sigesp == 1 ) {






          }elseif ($local == 1 ) {
          



          }*/

            //$pasantesC = carnet_us::where('carus_status', 1)->lists('carus_ced');

            $pasantes = DB::table('usuarios')
                ->select(DB::raw("us_nom as nombres, us_ape as apellidos, us_ced as cedula, dp_nombre as des_uni, max(hxu_fecha_created) as fecha"))
                ->join('horario_x_usuario', 'hxu_cedula', '=', 'us_ced')
                ->join('departamentos', 'dp_id', '=', 'us_dp_id')
                ->join('carnet_us', 'carus_ced', '=', 'us_ced')
                ->where('us_status', 1)
                ->where('us_tdu_id', 2)
                //->whereIn('us_ced', $pasantesC)
                ->groupBy('us_nom', 'us_ape',  'us_ced', 'dp_nombre', 'carus_status')
                ->paginate(10);

            $motivos = DB::table('carnet_tipo_reportes')
                ->select('ctr_id', 'ctr_descripcion')
                ->where('ctr_id','>', 1)
                ->where('ctr_id', '<', 7)
                ->where('ctr_id', '!=', 4)
                ->orderBy('ctr_id', 'asc')
                ->get();

            $nuevo = DB::table('carnet_tipo_reportes')
                ->select('ctr_id', 'ctr_descripcion')
                ->where('ctr_id','=', 1)
                ->get();

            $sigesp = config::where('cof_tipo', 'SIGESP')->get()->pluck('cof_value')->last();
            $local = config::where('cof_tipo', 'LOCAL')->get()->pluck('cof_value')->last();

      

            $empleadoC = carnet_provisionales::pluck('car_prov_ced');
            $empleadoH = horario_x_usuario::pluck('hxu_cedula');

            $empleados = DB::connection('vive_2016')
             ->table('sno_personalnomina AS pn')
             ->select(DB::raw("pn.codper, cedper as cedula, nomper as nombres, apeper as apellidos, pn.minorguniadm || '-' || pn.ofiuniadm || '-' || pn.uniuniadm || '-' || pn.depuniadm || '-' || pn.prouniadm as cod_uni, sc.codcar as cod_car,  sc.descar as des_car, max(pn.fecingper) as fecha 
                "))
             ->join('sno_personal as p', 'p.codper', '=','pn.codper')
             ->leftjoin('sno_cargo as sc', 'sc.codcar','=','pn.codcar')
             ->join('sno_nomina as n', 'n.codnom', '=', 'pn.codnom')
             ->where('staper',1)
             ->whereBetween('n.codnom', array('0001', '0008'))
             ->whereIn('cedper', $empleadoC)
             ->whereIn('cedper', $empleadoH)
             ->groupBy('cedper','nomper','apeper','pn.minorguniadm', 'pn.ofiuniadm', 'pn.uniuniadm', 'pn.depuniadm', 'pn.prouniadm', 'sc.codcar', 'sc.descar', 'pn.fecingper', 'pn.codper')
             ->distinct('pn.codper')
             ->paginate(10);

            $rol = roles::where('ro_id',$_SESSION['acceso'])->get()->pluck('ro_id')->last();

            $aco_rp = acceso::join('pantallas','aco_pnt_id','=','pnt_id')
            ->where('aco_ro_id',$rol)->where('pnt_nombre','p_cnpreportes')->get()->pluck('pnt_nombre')->last();
              return view ('Usuario/carnet_provisionales', compact('pasantes', 'motivos','aco_rp', 'nuevo','empleados'));

        
    }

    public function datosProv(Request $request, $id){ // captura datos en modal para reportar carnets
      $pasante = DB::table('usuarios')
        ->select(DB::raw("us_nom as nombres, us_ape as apellidos, us_ced as cedula, dp_nombre as des_uni, max(hxu_fecha_created) as fecha, carus_status as status, carus_fecha_vencimiento"))
        ->join('horario_x_usuario', 'hxu_cedula', '=', 'us_ced')
        ->join('departamentos', 'dp_id', '=', 'us_dp_id')
        ->join('carnet_us', 'carus_ced', '=', 'us_ced')
        ->where('us_status', 1)
        ->where('us_tdu_id', 1)
        ->where('us_ced', $id)
        ->groupBy('us_nom', 'us_ape',  'us_ced', 'dp_nombre', 'carus_status', 'carus_fecha_vencimiento')
        ->get();
      return $pasante;
    }

    public function datosPasante(Request $request, $id){ // captura datos en modal para reportar carnets

        if ($request->ajax()) {

            $pasante = DB::table('usuarios')
                ->select(DB::raw("us_nom as nombres, us_ape as apellidos, us_ced as cedula, dp_nombre as des_uni, max(hxu_fecha_created) as fecha, carus_status as status, carus_fecha_vencimiento"))
                ->join('horario_x_usuario', 'hxu_cedula', '=', 'us_ced')
                ->join('departamentos', 'dp_id', '=', 'us_dp_id')
                ->join('carnet_us', 'carus_ced', '=', 'us_ced')
                ->where('us_status', 1)
                ->where('us_tdu_id', 2)
                ->where('us_ced', $id)
                ->groupBy('us_nom', 'us_ape',  'us_ced', 'dp_nombre', 'carus_status', 'carus_fecha_vencimiento')
                ->get();
            return response()->json($pasante);
        }
    }

// modifica datos de carnet provisional (REPORTES-RETIROS)
public function modPasante(Request $request){

    session_start();

     if (isset($_POST['registrar'])) {

        $cedulaPasante = $_POST['cedulaPasante'];
        $nombrePasante = $_POST['nombrePasante'];
        $apellidoPasante = $_POST['apellidoPasante'];
        $areaPasante =$_POST['areaPasante'];
        $pasanteMotivo = $_POST['pasanteMotivo'];
        $ipmaquina = $_SERVER["REMOTE_ADDR"];
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $usuario = $_SESSION['id'];

     }

     if ($request->ReportaImprimes == "Si") { // solo reporta, no imprime pdf

        $date = Carbon::now()->toDateTimeString(); //Fecha actual

        $tbl_carnet = carnet_us::where('carus_ced', $request->cedulaPasante )->get(); // verifico si tiene un carnet 
        $c_n = count($tbl_carnet);

        if ($c_n > 0) {

            if ($request->pasanteMotivo == 6) { // fin de pasantias, pasante retirado
               
               /* $carnets_us = DB::table('carnet_us')
                        ->where('carus_ced', $cedulaPasante)
                        ->update(['carus_motivo' => $pasanteMotivo,
                                  'carus_status' => 2,
                                  'updated_at'   => $date
                        ]);

                        if ($carnets_us == true) {*/

                            $carnetLast = carnet_us::where('carus_ced', $cedulaPasante)->get()
                            ->pluck('carus_codigo')->last(); // seleccionamos cod para buscar en tabla provisionales

                            $provisional = DB::table('carnet_provisionales')
                            ->where('car_prov_cod', $carnetLast)
                            ->update(['car_prov_status' => 1,
                                      'car_prov_ced'    => 0,
                                      'updated_at'   => $date
                            ]);

                              if ($provisional == true) {

                              $carnet_us =  DB::table('carnet_us')->where('carus_ced', $cedulaPasante)->delete();

                            Session::flash('flash_message', 'Se ha reportado el carnet del pasante C.I '.$cedulaPasante.' y este podrá ser usado por otra persona');
                            }else{
                                Session::flash('flash_message', 'Error, no se han modificado dichos datos');
                            }

                            return redirect()->back();
                }else{ // reporto carnet como extraviado, robado, hurtado
                   
                   /* $carnet_us = DB::table('carnet_us')
                        ->where('carus_ced', $cedulaPasante)
                        ->update(['carus_motivo' => $pasanteMotivo,
                                  'carus_status' => 2,
                                  'updated_at'   => $date
                        ]);*/

                       // if ($carnet_us == true) {

                            $carnetsLast = carnet_us::where('carus_ced', $request->cedulaPasante)->get()
                            ->pluck('carus_codigo')->last(); // tomo cod de carnet vigente para reportarlo
                            
                            $inutilizados = new carnet_seriales_inutilizados;

                            $id_int = 'csi_id';
                            $inutilizados->csi_fecha = Carbon::now()->toDateString();
                            $inutilizados->csi_hora = Carbon::now()->toTimeString();
                            $inutilizados->csi_cedula = $request->cedulaPasante;
                            $inutilizados->csi_nombre = $request->nombrePasante;
                            $inutilizados->csi_apellido = $request->apellidoPasante;
                            $inutilizados->csi_cod_barra = $carnetsLast;
                            $inutilizados->csi_tipo_reporte_id = $request->pasanteMotivo;
                            $inutilizados->csi_carnet_user_id = 1;
                            $inutilizados->csi_tipo_carnet = 2;

                            $inutilizados->save();

                            $alerta = $inutilizados->save();

                              if ($alerta == true) {

                                $provisional = DB::table('carnet_provisionales')
                                ->where('car_prov_cod', $carnetsLast)
                                ->update(['car_prov_status' => 3,
                                          'updated_at'   => $date
                                ]);

                              /*
                              $carnet_us =  DB::table('carnet_us')->where('carus_ced', $request->cedulaPasante)->delete();
                              */
                              $carnet_us = DB::table('carnet_us')
                            ->where('carus_ced', $request->cedulaPasante)
                            ->update(['carus_motivo' => $request->pasanteMotivo,
                                      'updated_at' => $date,
                                      'carus_status' => 2
                            ]);

                              if ($carnet_us == true) {
                                Session::flash('flash_message', 'Se ha reportado el carnet del pasante C.I '.$request->cedulaPasante.' y este no podrá usarse más');
                              }else{
                                Session::flash('flash_message', 'Error, no se han modificado dichos datos');
                            }

                            }                            
                }

        } 

      return redirect()->back();
         
     }else{ // no se desea reportar solamente, se crea otro carnet
        $date = Carbon::now()->toDateTimeString(); //Fecha actual

        $tbl_carnet = carnet_us::where('carus_ced', $cedulaPasante)->get(); // verifico si tiene un carnet 
        $c_n = count($tbl_carnet);

        if ($c_n > 0) {

            if ($pasanteMotivo == 6) { // fin de pasantias, pasante retirado

              $carnetLast = carnet_us::where('carus_ced', $cedulaPasante)->get()
                            ->pluck('carus_codigo')->last(); // seleccionamos cod para buscar en tabla provisionales

                            $provisional = DB::table('carnet_provisionales')
                            ->where('car_prov_cod', $carnetLast)
                            ->update(['car_prov_status' => 1,
                                      'car_prov_ced'    => 0,
                                      'updated_at'   => $date
                            ]);

                              if ($provisional == true) {

                              //$carnet_us =  DB::table('carnet_us')->where('carus_ced', $cedulaPasante)->delete();



                          $carnet = DB::table('carnet_us')
                            ->where('carus_codigo', $carnetLast)
                            ->update(['carus_status' => 2]);


               /* $carnets_us = DB::table('carnet_us')
                        ->where('carus_ced', $cedulaPasante)
                        ->update(['carus_motivo' => $pasanteMotivo,
                                  'carus_status' => 2,
                                  'updated_at'   => $date
                        ]);

                        if ($carnets_us == true) {

                            $carnetLast = carnet_us::where('carus_ced', $cedulaPasante)
                            ->pluck('carus_codigo'); // seleccionamos cod para buscar en tabla provisionales

                            $provisional = DB::table('carnet_provisionales')
                            ->where('car_prov_cod', $carnetLast)
                            ->update(['car_prov_status' => 1,
                                      'car_prov_ced'    => 0,
                                      'updated_at'   => $date
                        ]);*/

                            Session::flash('flash_message', 'Se ha reportado el carnet del pasante C.I '.$cedulaPasante.' y este podrá ser usado por otra persona');
                            }else{
                                Session::flash('flash_message', 'Error, no se han modificado dichos datos');
                            }

                            return redirect()->back();
                }else{ // reporto carnet como extraviado, robado, hurtado, se asigna nuevo carnet

                    $date = Carbon::now()->toDateTimeString(); //Fecha actual

                    $carntLast = carnet_us::where('carus_ced', $cedulaPasante)->get()
                            ->pluck('carus_codigo')->last(); // tomo cod de carnet vigente para reportarlo
                    
                    $inutilizados = new carnet_seriales_inutilizados;

                    $id_int = 'csi_id';
                    $inutilizados->csi_fecha = Carbon::now()->toDateString();
                    $inutilizados->csi_hora = Carbon::now()->toTimeString();
                    $inutilizados->csi_cedula = $cedulaPasante;
                    $inutilizados->csi_nombre = $nombrePasante;
                    $inutilizados->csi_apellido = $apellidoPasante;
                    $inutilizados->csi_cod_barra = $carntLast;
                    $inutilizados->csi_tipo_reporte_id = $pasanteMotivo;
                    $inutilizados->csi_carnet_user_id = $usuario;
                    $inutilizados->csi_tipo_carnet = 2;

                    $inutilizados->save();

                    // asigno nuevo codigo

                    $tbl_provis = DB::table('carnet_provisionales')
                    ->where('car_prov_status', 1)
                    ->orderby('car_prov_id','ASC')->get()
                    ->pluck('car_prov_cod')->last(); // verifico codigos disponibles y tomo 1 

                    $totalP = count($tbl_provis);

                    if ($totalP > 0) {

                      $carnets_us = DB::table('carnet_us')
                            ->where('carus_ced', $cedulaPasante)
                            ->update(['carus_motivo' => $pasanteMotivo,
                                      'updated_at' => $date,
                                      'carus_codigo' => $tbl_provis,
                                      'carus_status' => 1
                            ]);

                    if ($carnets_us == true) {

                        $car_provisional = DB::table('carnet_provisionales')
                            ->where('car_prov_cod', $tbl_provis)
                            ->update(['car_prov_ced' => $cedulaPasante,
                                      'updated_at' => $date,
                                      'car_prov_status' => 2
                            ]);

                            if ($car_provisional == true) {

                            $navegador = $this->getBrowser($user_agent);
                            $areaEm = Usuarios::where('us_ced', $_SESSION['id'])->get()->pluck('us_dp_id')->last();
                            $login = Usuarios::where('us_ced', $_SESSION['id'])->get()->pluck('us_login')->last();

                            $historico = new carnet_historico;
                            $id_hist = 'carth_id';
                            $historico->carth_ip_maquina = $ipmaquina;
                            $historico->carth_usuario = $login;
                            $historico->carth_departamento = $areaEm;
                            $historico->carth_so = PHP_OS.' - '.php_uname();
                            $historico->carth_navegador = $navegador;
                            $historico->carth_serial_carnet = $tbl_provis;
                            $historico->carth_cedulaempleado = $cedulaPasante;
                            $historico->carth_motivo = 1;
                                    
                            $historico->save();

                        Session::flash('flash_message_Prov', 'Se ha asignado nuevo carnet al pasante C.I '.$cedulaPasante.' ');

                        }else{

                            Session::flash('flash_message_Prov', 'Error, no se han modificado dichos datos');
                        }
                    }

                        return redirect()->back();
                      
                    }else{
                      Session::flash('flash_message', 'Disculpe, no hay carnets disponibles, no se puede asignar uno nuevo');
                    }

                   return redirect()->back(); 
                }

        }

     }
 }

// busqueda de pasantes con Carnet

    public function pasanteConCarnetBusca(Request $request, $cedula) { 

            if ($request->ajax()) {

                $pasante = DB::select(DB::raw("
                 SELECT us_nom as nombres, us_ape as apellidos, us_ced as cedula, dp_nombre as des_uni, max(hxu_fecha_created) as fecha, carus_status as status, carus_fecha_vencimiento 
                FROM usuarios T1
                INNER JOIN horario_x_usuario on T1.us_ced = horario_x_usuario.hxu_cedula
                INNER JOIN departamentos on T1.us_dp_id = departamentos.dp_id
                INNER JOIN carnet_us on T1.us_ced = carnet_us.carus_ced
                WHERE EXISTS (SELECT carus_ced FROM carnet_us T2 WHERE T1.us_ced = T2.carus_ced) 
                AND us_status = 1 
                AND us_tdu_id = 2
                AND us_ced = ".$cedula."
                GROUP BY nombres, apellidos, cedula, des_uni, status, carus_fecha_vencimiento"));

            return response()->json($pasante);
        }

}

// index pasantes sin carnet
    public function provisionales_new() { //index 

        session_start();

       // $pasantesC = carnet_us::lists('carus_ced');
        $pasantesC = carnet_us::pluck('carus_ced');

        $pasantes = DB::table('usuarios')
        ->select(DB::raw("us_nom as nombres, us_ape as apellidos, us_ced as cedula, dp_nombre as des_uni, max(hxu_fecha_created) as fecha"))
        ->join('horario_x_usuario', 'hxu_cedula', '=', 'us_ced')
        ->join('departamentos', 'dp_id', '=', 'us_dp_id')
        ->where('us_status', 1)
        ->where('us_tdu_id', 2)
        ->whereNotIn('us_ced', $pasantesC)
        ->groupBy('us_nom', 'us_ape',  'us_ced', 'dp_nombre')
        ->paginate(10);

        $provisional = DB::table('carnet_provisionales')
        ->where('car_prov_status', 1)
        ->get();

        $cp = count($provisional);

        $serials = DB::table('carnet_provisionales')
        ->join('carnet_us', 'carus_ced','=','car_prov_ced')
        ->where('car_prov_status', 2)
        ->where('carus_motivo', 6)
        ->where('carus_status', 2)
        ->get();

        $empleadoC = carnet_us::pluck('carus_ced');
        $empleadoH = horario_x_usuario::pluck('hxu_cedula');

        $empleados = DB::connection('vive_2016')
         ->table('sno_personalnomina AS pn')
         ->select(DB::raw("pn.codper, cedper as cedula, nomper as nombres, apeper as apellidos, pn.minorguniadm || '-' || pn.ofiuniadm || '-' || pn.uniuniadm || '-' || pn.depuniadm || '-' || pn.prouniadm as cod_uni, sc.codcar as cod_car,  sc.descar as des_car, max(pn.fecingper) as fecha 
            "))
         ->join('sno_personal as p', 'p.codper', '=','pn.codper')
         ->leftjoin('sno_cargo as sc', 'sc.codcar','=','pn.codcar')
         ->join('sno_nomina as n', 'n.codnom', '=', 'pn.codnom')
         ->where('staper',1)
         ->whereBetween('n.codnom', array('0001', '0008'))
         ->whereNotIn('cedper', $empleadoC)
         ->whereIn('cedper', $empleadoH)
         ->groupBy('cedper','nomper','apeper','pn.minorguniadm', 'pn.ofiuniadm', 'pn.uniuniadm', 'pn.depuniadm', 'pn.prouniadm', 'sc.codcar', 'sc.descar', 'pn.fecingper', 'pn.codper')
         ->distinct('pn.codper')
         ->paginate(10);

        $rol = roles::where('ro_id',$_SESSION['acceso'])->get()->pluck('ro_id')->last();

        $aco_cpn = acceso::join('pantallas','aco_pnt_id','=','pnt_id')
        ->where('aco_ro_id',$rol)->where('pnt_nombre','p_cnprovisional')->get()->pluck('pnt_nombre')->last();

        return view ('Usuario/carnet_provisionales_new', compact('pasantes','cp','serials','aco_cpn','empleados'));

}


public function seriales(Request $request){

    if (isset($_POST['registrar'])) {

        $newSerial = $_POST['newSerial'];
        $cantidadSerial = $_POST['cantidadSerial'];
        

     }

     if ($newSerial == "SI") {


    $carnetCodigo = rand(300000,900000); //Genereamos aleatorio

    for ($i = 0; $i <=$cantidadSerial; $i++) {

        $carnetCodigo = rand(300000,900000); //Generamos aleatorio
        for ($j = 0; $j <$i; $j++) { 
        /*buscamos que no este repetido,
         si esta repetido genero otro aleatorio y empiezo de 0 
        previniendo el $j++ */

        $codCarnet = carnet_us::where('carus_codigo', $usados[$j])->get();

        $total = count($codCarnet);

        if ($total == 0) {

          $codCarnetP = carnet_provisionales::where('car_prov_cod', $usados[$j])->get();

          $totales = count($codCarnetP);

          if ($totales == 0) {

            if($usados[$j] == $carnetCodigo){ 
                $carnetCodigo = rand(300000,900000);
                $j=-1;

            }else{

              $carus = new carnet_provisionales;

              $id = 'car_prov_id';
              $carus->car_prov_cod = $usados[$j];
              $carus->car_prov_status = 1;

              $carus->save();

            }
            
          }

        }

    }

    $usados[] = $carnetCodigo;    //No esta repetido, luego guardamos el aleatorio

}

    $alerta = $carus->save();

    if ($alerta == true) {
        Session::flash('flash_message','Se han creado nuevos seriales para carnets provisionales');
      return redirect()->back();  
    }else{
        Session::flash('flash_message','Falló');
    } 

    }else{
      Session::flash('flash_message','No se han creado nuevos seriales para carnets provisionales');
      return redirect()->back(); 
    }
    
}


// captura datos en modal para registrar nuevo carnet de pasante----
public function datosPasanteNew(Request $request, $id){ // datos modal

  if ($request->ajax()) {

            $pasante = DB::select(DB::raw("
                 SELECT us_nom as nombres, us_ape as apellidos, us_ced as cedula, dp_nombre as des_uni, max(hxu_fecha_created) as fecha 
                FROM usuarios T1
                INNER JOIN horario_x_usuario on T1.us_ced = horario_x_usuario.hxu_cedula
                INNER JOIN departamentos on T1.us_dp_id = departamentos.dp_id
                WHERE NOT EXISTS (SELECT carus_ced FROM carnet_us T2 WHERE T1.us_ced = T2.carus_ced) 
                AND us_status = 1 
                AND us_ced = ".$id."
                GROUP BY nombres, apellidos, cedula, des_uni"));

            return response()->json($pasante);
        }

      /*  if ($request->ajax()) {

            $pasante = DB::table('usuarios')
            ->select(DB::raw("us_nom as nombres, us_ape as apellidos, us_ced as cedula, dp_nombre as des_uni, max(hxu_fecha_created) as fecha"))
            ->join('horario_x_usuario', 'hxu_cedula', '=', 'us_ced')
            ->join('departamentos', 'dp_id', '=', 'us_dp_id')
            ->where('us_status', 1)
            ->where('us_ced', $id)
            ->groupBy('us_nom', 'us_ape',  'us_ced', 'dp_nombre')
            ->get();
            return response()->json($pasante);
        }*/
    }
// captura datos en modal para registrar nuevo carnet de pasante (FIN) ----


// crea nuevo carnet pasante --------------------

     public function modPasantesNew(Request $request){   
      
        session_start();
    
    if (isset($_POST['registrar'])) {

        $cedulaPasante = $_POST['cedulaPasante'];
        $nombrePasante = $_POST['nombrePasante'];
        $apellidoPasante = $_POST['apellidoPasante'];
        $areaPasante =$_POST['areaPasante'];
        $pasanteFechaVec = $_POST['pasanteFechaVec'];
        $ipmaquina = $_SERVER["REMOTE_ADDR"];
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $usuario = $_SESSION['id']; // cedula usuario

     }

     $date = Carbon::now()->toDateTimeString(); //Fecha actual

     //verifico que tenga horario y selecciono el ultimo 
        $tbl_horario = DB::table('horario_x_usuario')
        ->where('hxu_cedula', $cedulaPasante)
        ->orderby('hxu_fecha_created','DESC')->get()
        ->pluck('hxu_id')->last();

        $tbl_carnet = carnet_us::where('carus_ced', $cedulaPasante)->get(); // verifico si tiene un carnet 
        $c_n = count($tbl_carnet);

        $carnet_Provisional = carnet_provisionales::where('car_prov_status', 1)->get(); 
        $c_p = count($carnet_Provisional);

        if ($c_p == 0) {
            Session::flash('flash_message', 'Disculpe No puede asignar un carnet provisional para el pasante C.I '.$cedulaPasante.', verifique que hayan seriales disponibles');
                return redirect()->back();

        }else{

            $tbl_provis = DB::table('carnet_provisionales')
            ->where('car_prov_status', 1)
            ->orderby('car_prov_id','ASC')->get()
            ->pluck('car_prov_cod')->last(); // verifico codigos disponibles y tomo 1 

            /* $codCarnet = carnet_us::where('carus_codigo', $tbl_provis)->get();

            $total = count($codCarnet);

            if ($total == 0) { */ // verifico que no este el codigo registrado 
              $rules = ['image' => 'required|image|mimes:jpeg,jpg,png',];
              $messages = [
                  'image.required' => 'La imagen es requerida',
                  'image.image' => 'Formato no permitido',
                  'image.max' => 'El máximo permitido es 1 MB',
              ];
              $validator = Validator::make($request->all(), $rules, $messages);
              
              if ($validator->fails()){

                  return redirect()->back()->withErrors($validator);

              }
              else{

                  $date = Carbon::now();

                  $fechaActual = $date->toDateString();

                  if ($pasanteFechaVec < $fechaActual) {
                      Session::flash('flash_error_Fecha','La fecha de Vencimiento no puede ser menor a la fecha actual');
                      return redirect()->back();
                  }
                  else{

                  $name = $cedulaPasante.'.jpg';
                  $request->file('image')->move('imagenes2', $name);
                  
                }
              }
                $carus = new carnet_us;

                $id = 'carus_id';
                $carus->carus_ced = $cedulaPasante;
                $carus->carus_codigo = $tbl_provis;
                $carus->carus_hxu_id = $tbl_horario;
                $carus->carus_status = 1;
                $carus->carus_fecha_vencimiento = $pasanteFechaVec;
                $carus->carus_tipo_carnet = 2;
                $carus->carus_motivo = 1;

                $carus->save();

                $alerta = $carus->save();

                if ($alerta == true) {

                    $navegador = $this->getBrowser($user_agent);
                    $areaEm = Usuarios::where('us_ced', $_SESSION['id'])->get()->pluck('us_dp_id')->last();
                    $login = Usuarios::where('us_ced', $_SESSION['id'])->get()->pluck('us_login')->last();

                    $historico = new carnet_historico;
                    $id_hist = 'carth_id';
                    $historico->carth_ip_maquina = $ipmaquina;
                    $historico->carth_usuario = $login;
                    $historico->carth_departamento = $areaEm;
                    $historico->carth_so = PHP_OS.' - '.php_uname();
                    $historico->carth_navegador = $navegador;
                    $historico->carth_serial_carnet = $tbl_provis;
                    $historico->carth_cedulaempleado = $cedulaPasante;
                    $historico->carth_motivo = 1;
                    
                    $historico->save();

                    $carnets_prov = DB::table('carnet_provisionales')
                            ->where('car_prov_cod', $tbl_provis)
                            ->update(['car_prov_status' => 2,
                                      'updated_at' => $date,
                                      'car_prov_ced' => $cedulaPasante
                            ]);

                    Session::flash('flash_message_Prov','Se ha creado un nuevo carnet provisional');

                }else{
                    Session::flash('flash_message_Prov','Falló');
                }
           // }

        }

       return redirect()->back(); 
}
// crea nuevo carnet pasante (FIN)--------------------

// genera pdf de nuevo carnet provisional y reimprime 

public function carnetProvisionalNEW() {

   /* $car_pasante = carnet_us::orderBy('carus_id', 'DESC')
    ->where('carus_tipo_carnet', '2')
    //->where('carus_motivo', 1)
    ->get()->pluck('carus_ced')->last();*/

    $car_pasante = carnet_provisionales::orderBy('updated_at', 'asc')->get()->pluck('car_prov_ced')->last();

    $datosC = DB::table('carnet_us')
            ->select('carus_codigo', 'car_prov_id')
            ->join('carnet_provisionales', 'car_prov_cod' ,'=', 'carus_codigo')
            ->where('carus_ced', $car_pasante)
            ->get();

    $barra = new DNS1D();

          $pdf =PDF::setOptions (['images'=>true])->loadView('Usuario/carnetProvisional',compact('barra', 'datosC', 'car_pasante'));

                return $pdf->stream('Carnet.pdf');


}

// buscador pasantes sin carnets-----------


public function pasanteSinCarnetBusca(Request $request, $cedula) { 

     if ($request->ajax()) {

            $pasante = DB::select(DB::raw("
                 SELECT us_nom as nombres, us_ape as apellidos, us_ced as cedula, dp_nombre as des_uni, max(hxu_fecha_created) as fecha 
                FROM usuarios T1
                INNER JOIN horario_x_usuario on T1.us_ced = horario_x_usuario.hxu_cedula
                INNER JOIN departamentos on T1.us_dp_id = departamentos.dp_id
                WHERE NOT EXISTS (SELECT carus_ced FROM carnet_us T2 WHERE T1.us_ced = T2.carus_ced) 
                AND us_status = 1 
                AND us_ced = ".$cedula."
                GROUP BY nombres, apellidos, cedula, des_uni"));

            return response()->json($pasante);
        }

    }

// seriales carnet provisionales

    public function seriales_carnet(){ // index vista carnet_seriales

        session_start();

        $disponibles = DB::table('carnet_provisionales')
        ->where('car_prov_status', 1)
        ->get();

        $cp = count($disponibles);

      //  $seriales = carnet_provisionales::lists('car_prov_cod');

        $provisional = DB::table('carnet_provisionales')
            ->select('car_prov_cod as codigo', 'car_prov_status as status', 'car_prov_ced as cedula', 'car_prov_id as id')
            ->orderby('car_prov_id', 'ASC')
            ->paginate(15);
        $rol = roles::where('ro_id',$_SESSION['acceso'])->get()->pluck('ro_id')->last();

        $aco_ser = acceso::join('pantallas','aco_pnt_id','=','pnt_id')
        ->where('aco_ro_id',$rol)->where('pnt_nombre','p_cnpseriales')->get()->pluck('pnt_nombre')->last();

         return view ('Usuario/carnet_seriales', compact('provisional', 'cp','aco_ser'));
    }

    public function provisional_historico(Request $request, $codigo){ // vista modal pasantes carnets, historicos

        if($request->ajax()){
            $datosProv = DB::table('carnet_provisionales')
            ->select('car_prov_id as nro', 'car_prov_cod as codigo', 'car_prov_ced as cedula', 'us_nom as nombres', 'us_ape as apellidos', 'h.created_at as fecha', 'dp_nombre as des_uni')
            ->join('carnet_historico as h', 'carth_serial_carnet', '=', 'car_prov_cod')
            ->join('usuarios', 'us_ced', '=', 'car_prov_ced')
            ->join('carnet_tipo_reportes AS t', 'ctr_id','=','carth_motivo')
            ->join('departamentos', 'us_dp_id', '=', 'dp_id')
            ->join('carnet_us', 'carus_ced', '=', 'car_prov_ced')
            ->where('car_prov_cod', $codigo)
           // ->take(3)
            ->get();

            return response()->json($datosProv);

            }
    }



// seriales carnet provisionales (FIN)
    
//-----------Historico de Impresiones --------------------------

public function historico_carnet(){ // carga datos en la tabla 

session_start();
        if (isset($_SESSION['foto'])) {


  /*  $sigesp = config::where('cof_tipo', 'SIGESP')->pluck('cof_value');
    $local = config::where('cof_tipo', 'LOCAL')->pluck('cof_value');

        if ($sigesp == 1 ) {






          }elseif ($local == 1 ) {
          



          }*/



    $historicoCarnets = DB::table('carnet_historico AS h')
    ->select('u.us_nom AS nombres', 'u.us_ape AS apellidos', 'u.us_ced AS cedula', 'h.carth_serial_carnet AS codigo', 't.ctr_descripcion AS tipoReporte', 'h.created_at AS fecha', 'd.dp_nombre AS des_uni', 'h.carth_usuario AS responsable')
    ->join('usuarios AS u', 'us_ced','=','carth_cedulaempleado')
    ->join('departamentos AS d','us_dp_id', '=', 'dp_id')
    ->join('carnet_tipo_reportes AS t', 'ctr_id','=','carth_motivo')
    ->where('carth_tipo_carnet', 1)
    ->paginate(10);
    $rol = roles::where('ro_id',$_SESSION['acceso'])->get()->pluck('ro_id')->last();

    $aco_che = acceso::join('pantallas','aco_pnt_id','=','pnt_id')
            ->where('aco_ro_id',$rol)->where('pnt_nombre','p_chistoricoe')->get()->pluck('pnt_nombre')->last();
        
    return view ('Usuario/carnet_historico', compact('historicoCarnets','aco_che'));

    }else{ 
          //Si la sesion no existe...
          Session::flash('session', 'A expirado la sesión');
          return view('login');
        }
}


// buscador de la tabla historico de impresiones de carnets ---------

public function busHistory(Request $request ,$cedula){

    $sigesp = config::where('cof_tipo', 'SIGESP')->get()->pluck('cof_value')->last();
    $local = config::where('cof_tipo', 'LOCAL')->get()->pluck('cof_value')->last();

    if($request->ajax()){

       /* if ($sigesp == 1 ) {

            return response()->json($bus_historico);

            }elseif ($local == 1 ) {*/

                $bus_historico = DB::table('carnet_historico AS h')
                ->select('u.us_nom AS nombres', 'u.us_ape AS apellidos', 'u.us_ced AS cedula', 'h.carth_serial_carnet AS codigo', 't.ctr_descripcion AS tipoReporte', 'h.created_at AS fecha', 'd.dp_nombre AS des_uni', 'h.carth_usuario AS responsable')
                ->join('usuarios AS u', 'us_ced','=','carth_cedulaempleado')
                ->join('departamentos AS d','us_dp_id', '=', 'dp_id')
                ->join('carnet_tipo_reportes AS t', 'ctr_id','=','carth_motivo')
                ->where('carth_cedulaempleado', $cedula)
                ->where('carth_tipo_carnet', 1)
                ->orwhere('carth_serial_carnet', $cedula)
                ->orwhere('us_login', $cedula)
                ->orwhere('ctr_descripcion', $cedula)
                ->orderBy('carth_id', 'desc')
                ->get();

                return response()->json($bus_historico);
         //   }  
        }
    }


    public function PdfHistoricoEmpleado(){

      session_start();

     $usuario = DB::table('usuarios')->where('us_ced', $_SESSION['id'])->join('departamentos', 'us_dp_id', '=', 'dp_id')->join('horario_x_usuario', 'hxu_cedula', '=', 'us_ced')->join('tipos_horarios', 'hxu_tiho_id', '=', 'tiho_id')->paginate(1);

    $fecha = Carbon::now();
    $fecha->toDateString();

      $historicoCarnet = DB::table('carnet_historico AS h')
    ->select('u.us_nom AS nombres', 'u.us_ape AS apellidos', 'u.us_ced AS cedula', 'h.carth_serial_carnet AS codigo', 't.ctr_descripcion AS tipoReporte', 'h.created_at AS fecha', 'd.dp_nombre AS des_uni', 'h.carth_usuario AS responsable')
    ->join('usuarios AS u', 'us_ced','=','carth_cedulaempleado')
    ->join('departamentos AS d','us_dp_id', '=', 'dp_id')
    ->join('carnet_tipo_reportes AS t', 'ctr_id','=','carth_motivo')
    ->where('carth_tipo_carnet', 1)
    ->get();



    $pdf =PDF::setOptions (['images'=>true])->loadView('Usuario/PDF_Carnet_Historico',compact('historicoCarnet', 'usuario', 'fecha'));

    return $pdf->stream('Carnet_Historico.pdf');

    }

    // index historico carnets provisionales

    public function Provisional_historico_carnet(){

      session_start();

      $historicoProvisional = DB::table('carnet_historico AS h')
    ->select('u.us_nom AS nombres', 'u.us_ape AS apellidos', 'u.us_ced AS cedula', 'h.carth_serial_carnet AS codigo', 't.ctr_descripcion AS tipoReporte', 'h.created_at AS fecha', 'd.dp_nombre AS des_uni', 'h.carth_usuario AS responsable')
    ->join('usuarios AS u', 'us_ced','=','carth_cedulaempleado')
    ->join('departamentos AS d','us_dp_id', '=', 'dp_id')
    ->join('carnet_tipo_reportes AS t', 'ctr_id','=','carth_motivo')
    ->where('carth_tipo_carnet', 2)
    ->paginate(10);

    $rol = roles::where('ro_id',$_SESSION['acceso'])->get()->pluck('ro_id')->last();

    $aco_chp = acceso::join('pantallas','aco_pnt_id','=','pnt_id')
            ->where('aco_ro_id',$rol)->where('pnt_nombre','p_chistoricop')->get()->pluck('pnt_nombre')->last();
        
    return view ('Usuario/historico_carnet_provisional', compact('historicoProvisional','aco_chp'));

    }

public function busHistoryProv(Request $request ,$cedula){

    if($request->ajax()){

                $bus_historicoProv = DB::table('carnet_historico AS h')
                ->select('u.us_nom AS nombres', 'u.us_ape AS apellidos', 'u.us_ced AS cedula', 'h.carth_serial_carnet AS codigo', 't.ctr_descripcion AS tipoReporte', 'h.created_at AS fecha', 'd.dp_nombre AS des_uni', 'h.carth_usuario AS responsable')
                ->join('usuarios AS u', 'us_ced','=','carth_cedulaempleado')
                ->join('departamentos AS d','us_dp_id', '=', 'dp_id')
                ->join('carnet_tipo_reportes AS t', 'ctr_id','=','carth_motivo')
                ->where('carth_cedulaempleado', $cedula)
                ->where('carth_tipo_carnet', 2)
                ->orwhere('carth_serial_carnet', $cedula)
                ->orwhere('carth_usuario', $cedula)
                ->orwhere('ctr_descripcion', $cedula)
                ->orderBy('carth_id', 'desc')
                ->get();

                return response()->json($bus_historicoProv);
         
        }
    }


    public function PdfHistoricoProvisional(){

      session_start();

      $usuario = DB::table('usuarios')->where('us_ced', $_SESSION['id'])->join('departamentos', 'us_dp_id', '=', 'dp_id')->join('horario_x_usuario', 'hxu_cedula', '=', 'us_ced')->join('tipos_horarios', 'hxu_tiho_id', '=', 'tiho_id')->paginate(1);

    $fecha = Carbon::now();
    $fecha->toDateString();

      $historicoProvisional = DB::table('carnet_historico AS h')
    ->select('u.us_nom AS nombres', 'u.us_ape AS apellidos', 'u.us_ced AS cedula', 'h.carth_serial_carnet AS codigo', 't.ctr_descripcion AS tipoReporte', 'h.created_at AS fecha', 'd.dp_nombre AS des_uni', 'h.carth_usuario AS responsable')
    ->join('usuarios AS u', 'us_ced','=','carth_cedulaempleado')
    ->join('departamentos AS d','us_dp_id', '=', 'dp_id')
    ->join('carnet_tipo_reportes AS t', 'ctr_id','=','carth_motivo')
    ->where('carth_tipo_carnet', 2)
    ->get();

    $pdf =PDF::setOptions (['images'=>true])->loadView('Usuario/PDF_Carnet_Provisional',compact('historicoProvisional', 'usuario', 'fecha'));

    return $pdf->stream('Carnet_Historico.pdf');
      
    }





    //-----------Historico de Impresiones (FIN) --------------------------



//-------------Configuración de diseño de los carnets ---------------------

public function fondo_carnets(){

    session_start();

    if (isset($_SESSION['foto'])) {

    $fondo = DB::table('carnet_fondos')
        ->select('fondo_carnet')
        ->orderBy('id', 'desc')
        ->where('descripcion','fondo')
        ->take(1)
        ->get();

    $encabezado = DB::table('configs')
    ->select('cof_nombre as encabezado')
    ->orderBy('cof_id', 'desc')
    ->where('cof_tipo', 'Encabezado')
    ->take(1)
    ->get();

    $empresa = DB::table('configs')
    ->select('cof_nombre as empresa')
    ->orderBy('cof_id', 'desc')
    ->where('cof_tipo', 'Empresa')
    ->take(1)
    ->get();

    $pagina = DB::table('configs')
    ->select('cof_nombre as pagina')
    ->orderBy('cof_id', 'desc')
    ->where('cof_tipo', 'Pagina')
    ->take(1)
    ->get();

    $agradecimiento= DB::table('configs')
    ->select('cof_nombre as agradecimiento')
    ->orderBy('cof_id', 'desc')
    ->where('cof_tipo', 'Agradecimiento')
    ->take(1)
    ->get();

    $descripcion = DB::table('configs')
    ->select('cof_nombre as descripcion')
    ->orderBy('cof_id', 'desc')
    ->where('cof_tipo', 'Descripcion')
    ->take(1)
    ->get();

    $telefono = DB::table('configs')
    ->select('cof_nombre as telefono')
    ->orderBy('cof_id', 'desc')
    ->where('cof_tipo', 'telefono')
    ->take(1)
    ->get();

  $rol = roles::where('ro_id',$_SESSION['acceso'])->get()->pluck('ro_id')->last();

    $aco_ccd = acceso::join('pantallas','aco_pnt_id','=','pnt_id')
            ->where('aco_ro_id',$rol)->where('pnt_nombre','p_ccof_diseño')->get()->pluck('pnt_nombre')->last();
    return view('Usuario/conf_diseno_carnet' , compact('fondo', 'encabezado', 'empresa', 'pagina', 'agradecimiento', 'telefono','aco_ccd', 'descripcion'));


    }else{ 
          //Si la sesion no existe...
          Session::flash('session', 'A expirado la sesión');
          return view('login');
        }

}

public function saveFondo(Request $request){ // inserta fotos ---------

    if (isset($_POST['saveFondoCarnet'])) {
        $CodDepartamento = $_POST['carnetArea'];
       // $nombre = $_POST['presidente'];
    }

    $rules = ['image' => 'required|image|mimes:jpeg,jpg,png',];
    $messages = [
            'image.required' => 'La imagen es requerida',
            'image.image' => 'Formato no permitido',
            'image.max' => 'El máximo permitido es 1 MB',
                ];
    $validator = Validator::make($request->all(), $rules, $messages);
        
        if ($validator->fails()){
            return redirect('conf_diseno_carnet')->withErrors($validator);
        }else{

            $name = $CodDepartamento.'.jpg';
            $request->file('image')->move('imagenes', $name);
          //  chmod('imagenes/'.$CodDepartamento.".jpg",0777);
            
            $carnet_fondos = new carnet_fondo;
            $primaryKey = 'id';
            $carnet_fondos->fondo_carnet = $name;
            $carnet_fondos->descripcion = "fondo";

        }
        $carnet_fondos->save();
        return redirect('conf_diseno_carnet')->with('flash_message', 'Su imagen de carnet ha sido registrada con éxito');


    }

        public function carnetsPosteriore(Request $request){  // modifica datos del fondo de carnets
      
        if($request->ajax()){

          $tipos = $request->tipo;
          $encabezado = $request->enkbezado;
          $empresa = $request->empresas;
          $pagina = $request->paginaC;
          $agradecimiento = $request->agradecimiento;
          $telefono = $request->telephone;
          $tlfDescripcion = $request->telfDescrip;
          $codigoCarnet = $request->tipoCodigo;
          $presidente = $request->president;
          $nombreFoto = $request->fotoNombre;

          if ($tipos == "FIRMA") {

            if ($request->fotoFirma != "") {

              $firmas = config::where('cof_tipo', 'FIRMA')->get()->pluck('cof_id')->last();

              $n = count($firmas);

              if ($n > 0) {

                $file = $request->file('fotoFirma');
                $names = $nombreFoto.".jpg";
                $path = "imagenes";
                $request->file('fotoFirma')->move($path, $names);

                $configs = DB::table('configs')
                    ->where('cof_id', $firmas)
                    ->update(['cof_tipo' => $tipos,
                              'cof_nombre' => $presidente,
                              'cof_alias'   => $names,
                              'cof_value' => 1,
                              'cof_desc' => "Firma y nombre del Presidente"
                        ]);

                    
            return response()->json([
              "mensaje" => 'Registro Actualizado'
            ]);

              }else{
                      

              $file = $request->file('fotoFirma');
              $name = $nombreFoto.".jpg";
              $path = "imagenes";
              $request->file('fotoFirma')->move($path, $name);
              // chmod('imagenes/'.$nombreFoto.".jpg",0777);
                      

              $configs = new config;
              $primaryKey = 'cof_id'; 
              $configs->cof_tipo = $tipos;
              $configs->cof_nombre = $presidente;
              $configs->cof_alias =$name;
              $configs->cof_value = 1;
              $configs->cof_desc ="Firma y nombre del Presidente";

              $configs->save();
                  
                return response()->json([
                    "mensaje" => 'Registro Creado'
                 ]);
              }

            }else{
                return response()->json([
                    "mensaje" => 'Error'
                ]);
              }

          }elseif ($tipos == "Encabezado") {
            
            $encabezados = config::where('cof_tipo', 'Encabezado')->get()->pluck('cof_id')->last();

          $n = count($encabezados);

          if ($n > 0) {

            $configs = DB::table('configs')
                    ->where('cof_id', $encabezados)
                    ->update(['cof_tipo' => $tipos,
                              'cof_nombre' => $encabezado,
                              'cof_alias'   => $tipos,
                              'cof_value' => 1,
                              'cof_desc' => $encabezado
                        ]);

                    
            return response()->json([
              "mensaje" => 'Registro Actualizado'
            ]);

                    
            
          }else{

            $configs = new config;
            $primaryKey = 'cof_id'; 
            $configs->cof_tipo = $tipos;
            $configs->cof_nombre = $encabezado;
            $configs->cof_alias =$tipos;
            $configs->cof_value = 1;
            $configs->cof_desc =$encabezado;

            $configs->save();

            return response()->json([
                "mensaje" => 'Registro Creado'
            ]);

          }

          }elseif ($tipos == "Empresa") {

            $empr = config::where('cof_tipo', 'Empresa')->get()->pluck('cof_id')->last();

            $e = count($empr);

            if ($e > 0) {

              $configs = DB::table('configs')
                    ->where('cof_id', $empr)
                    ->update(['cof_tipo' => $tipos,
                              'cof_nombre' => $empresa,
                              'cof_alias'   => $tipos,
                              'cof_value' => 1,
                              'cof_desc' => $empresa
                        ]);

                    return response()->json([
                      "mensaje" => 'Registro Actualizado'
                    ]);
              
            }else{

            $configs = new config;
            $primaryKey = 'cof_id'; 
            $configs->cof_tipo = $tipos;
            $configs->cof_nombre = $empresa;
            $configs->cof_alias = $tipos;
            $configs->cof_value = 1;
            $configs->cof_desc = "Nombre de la empresa";

            $configs->save();

            return response()->json([
                      "mensaje" => 'Registro Creado'
                    ]);

            }

          }elseif ($tipos == "Pagina") {

            $pag = config::where('cof_tipo', 'Pagina')->get()->pluck('cof_id')->last();

            $p = count($pag);

            if ($p > 0) {

              $configs = DB::table('configs')
                    ->where('cof_id', $pag)
                    ->update(['cof_tipo' => $tipos,
                              'cof_nombre' => $pagina,
                              'cof_alias'   => $tipos,
                              'cof_value' => 1,
                              'cof_desc' => "Pagina de la empresa"
                        ]);

                    return response()->json([
                      "mensaje" => 'Registro Actualizado'
                    ]);
              
            }else{


            $configs = new config;
            $primaryKey = 'cof_id'; 
            $configs->cof_tipo = $tipos;
            $configs->cof_nombre = $pagina;
            $configs->cof_alias = $tipos;
            $configs->cof_value = 1;
            $configs->cof_desc = "Pagina de la empresa";

            $configs->save();

            return response()->json([
                      "mensaje" => 'Registro Creado'
                    ]);

          }

          }elseif ($tipos == "Agradecimiento") {

            $agr = config::where('cof_tipo', 'Agradecimiento')->get()->pluck('cof_id')->last();

            $ag = count($agr);

            if ($ag > 0) {

              $configs = DB::table('configs')
                    ->where('cof_id', $agr)
                    ->update(['cof_tipo' => $tipos,
                              'cof_nombre' => $agradecimiento,
                              'cof_alias'   => $tipos,
                              'cof_value' => 1,
                              'cof_desc' => $agradecimiento
                        ]);

                    return response()->json([
                      "mensaje" => 'Registro Actualizado'
                    ]);
              
            }else{


            $configs = new config;
            $primaryKey = 'cof_id'; 
            $configs->cof_tipo = $tipos;
            $configs->cof_nombre = $agradecimiento;
            $configs->cof_alias = $tipos;
            $configs->cof_value = 1;
            $configs->cof_desc = $agradecimiento;

            $configs->save();

          }

          }elseif ($tipos == "telefono") {

            $telf = config::where('cof_tipo', 'telefono')->get()->pluck('cof_id')->last();

            $t = count($telf);

            $descr = config::where('cof_tipo', 'Descripcion')->get()->pluck('cof_id')->last();

            $d = count($descr);

            if ( ($t > 0) && ($d > 0) ) {

              $configs = DB::table('configs')
                    ->where('cof_id', $telf)
                    ->update(['cof_tipo' => $tipos,
                              'cof_nombre' => $telefono,
                              'cof_alias'   => $tipos,
                              'cof_value' => 1,
                              'cof_desc' => "Telefono de la empresa"
                        ]);

                    $configs = DB::table('configs')
                    ->where('cof_id', $descr)
                    ->update(['cof_tipo' => 'Descripcion',
                              'cof_nombre' => $tlfDescripcion,
                              'cof_alias'   => $tipos,
                              'cof_value' => 1,
                              'cof_desc' => "Descripcion telefono a reportar"
                        ]);
                   
            return response()->json([
              "mensaje" => 'Registro Actualizado'
            ]);

          }else{

            $configs = new config;
            $primaryKey = 'cof_id'; 
            $configs->cof_tipo = $tipos;
            $configs->cof_nombre = $telefono;
            $configs->cof_alias = $tipos;
            $configs->cof_value = 1;
            $configs->cof_desc = "Telefono de la empresa";

            $configs->save();


            $configs = new config;
            $primaryKey = 'cof_id'; 
            $configs->cof_tipo = 'Descripcion';
            $configs->cof_nombre = $tlfDescripcion;
            $configs->cof_alias = $tipos;
            $configs->cof_value = 1;
            $configs->cof_desc = "Descripcion telefono a reportar";

            $configs->save();

            return response()->json([
              "mensaje" => 'Registro Creado'
            ]);

          }


          }elseif ($tipos == "codigo") {

             $cod = carnet_fondo::where('fondo_carnet', 'codigo')->get()->pluck('id')->last();

              $c = count($cod);

              if ($c > 0) {
                
                 $carnet_fondos = DB::table('carnet_fondos')
                    ->where('id', $cod)
                    ->update(['fondo_carnet' => $tipos,
                              'descripcion' => $codigoCarnet
                        ]);

                    return response()->json([
              "mensaje" => 'Registro Actualizado'
            ]);
            
              }else{

                $carnet_fondos = new carnet_fondo;
                $primaryKey = 'id';
                $carnet_fondos->fondo_carnet = $tipo;
                $carnet_fondos->descripcion = $request->codigoCarnet;

                $carnet_fondos->save();

                return response()->json([
              "mensaje" => 'Registro Creado'
            ]);
          }
        }

        }
    }

    public function carnetPosteriore (Request $request){

      if (isset($_POST['saveCarnetFondo'])) {
        $tipo = $_POST['tipoCarnet'];
        $nombreFoto = $_POST['nombreFoto'];
        $presidente = $_POST['presidente'];
        $encabezado = $_POST['encabezado'];
        $empresa = $_POST['empresa'];
        $pagina = $_POST['pagina'];
        $agradecimiento = $_POST['agradecimiento'];
        $telefono = $_POST['telefono'];
        //$codigoCarnet = $_POST['codigoCarnet'];
        $tlfDescripcion = $_POST['tlfDescripcion'];
    }


    if ($tipo == "FIRMA") {

    $rules = ['image' => 'required|image|mimes:jpeg,jpg,png',];
    $messages = [
            'image.required' => 'La imagen es requerida',
            'image.image' => 'Formato no permitido',
            'image.max' => 'El máximo permitido es 1 MB',
                ];
    $validator = Validator::make($request->all(), $rules, $messages);
        
        if ($validator->fails()){
            return redirect('conf_diseno_carnet')->withErrors($validator);
        }else{

            $name = $nombreFoto.".jpg";
            $request->file('image')->move('imagenes', $name);
           // chmod('imagenes/'.$nombreFoto.".jpg",0777);

            $configs = new config;
            $primaryKey = 'cof_id'; 
            $configs->cof_tipo = $tipo;
            $configs->cof_nombre = $presidente;
            $configs->cof_alias = $name;
            $configs->cof_value = 1;
            $configs->cof_desc = "Firma y nombre del Presidente";
           
          }

          $configs->save();
        Session::flash('flash_message', 'Se han modificado los datos del presidente con éxito');
        return redirect()->back();

        }elseif ($tipo == "Encabezado") {

          $encabezados = config::where('cof_tipo', 'Encabezado')->get()->pluck('cof_id')->last();

          $n = count($encabezado);

          if ($n > 0) {

            $configs = DB::table('configs')
                    ->where('cof_id', $encabezados)
                    ->update(['cof_tipo' => $tipo,
                              'cof_nombre' => $encabezado,
                              'cof_alias'   => $tipo,
                              'cof_value' => 1,
                              'cof_desc' => $encabezado
                        ]);

                    if ($configs == true) {
                      Session::flash('flash_message', 'Se han modificado los datos con éxito');
                      return redirect()->back();
                    }else{
                      Session::flash('flash_message', 'No se han modificado los datos con éxito');
                      return redirect()->back();
                    }
            
          }else{

            $configs = new config;
            $primaryKey = 'cof_id'; 
            $configs->cof_tipo = $tipo;
            $configs->cof_nombre = $encabezado;
            $configs->cof_alias =$tipo;
            $configs->cof_value = 1;
            $configs->cof_desc =$encabezado;

            $configs->save();

          Session::flash('flash_message', 'Se han modificado los datos con éxito');
          return redirect()->back();
          }

          
          
        }elseif ($tipo == "Empresa") {
            $configs = new config;
            $primaryKey = 'cof_id'; 
            $configs->cof_tipo = $tipo;
            $configs->cof_nombre = $empresa;
            $configs->cof_alias = $tipo;
            $configs->cof_value = 1;
            $configs->cof_desc = "Nombre de la empresa";

            $configs->save();
        Session::flash('flash_message', 'Se han modificado los datos con éxito');
         return redirect()->back();

          }elseif ($tipo == "Pagina") {
            $configs = new config;
            $primaryKey = 'cof_id'; 
            $configs->cof_tipo = $tipo;
            $configs->cof_nombre = $pagina;
            $configs->cof_alias = $tipo;
            $configs->cof_value = 1;
            $configs->cof_desc = "Pagina de la empresa";

            $configs->save();
        Session::flash('flash_message', 'Se han modificado los datos con éxito');
        return redirect()->back();

          }elseif ($tipo == "Agradecimiento") {
            $configs = new config;
            $primaryKey = 'cof_id'; 
            $configs->cof_tipo = $tipo;
            $configs->cof_nombre = $agradecimiento;
            $configs->cof_alias = $tipo;
            $configs->cof_value = 1;
            $configs->cof_desc = $agradecimiento;

            $configs->save();

        Session::flash('flash_message', 'Se han modificado los datos con éxito');
        return redirect()->back();

          }elseif ($tipo == "telefono") {

            $configs = new config;
            $primaryKey = 'cof_id'; 
            $configs->cof_tipo = $tipo;
            $configs->cof_nombre = $telefono;
            $configs->cof_alias = $tipo;
            $configs->cof_value = 1;
            $configs->cof_desc = "Telefono de la empresa";

            $configs->save();


            $configs = new config;
            $primaryKey = 'cof_id'; 
            $configs->cof_tipo = 'Descripcion';
            $configs->cof_nombre = $tlfDescripcion;
            $configs->cof_alias = $tipo;
            $configs->cof_value = 1;
            $configs->cof_desc = "Descripcion telefono a reportar";

            $configs->save();

        Session::flash('flash_message', 'Se han modificado los datos con éxito');
        return redirect()->back();

          }elseif ($tipo == "codigo") {
            
            $carnet_fondos = new carnet_fondo;
            $primaryKey = 'id';
            $carnet_fondos->fondo_carnet = $tipo;
            $carnet_fondos->descripcion = $request->codigoCarnet;

            $carnet_fondos->save();

        Session::flash('flash_message', 'Se han modificado los datos con éxito');
        return redirect()->back();
          }

    }

//----------------- Configuración de diseño de los carnets (FIN)---------------------



// ----------- Captura navegador ------------

    public function getBrowser($user_agent){

        if(strpos($user_agent, 'MSIE') !== FALSE)
            return 'Internet explorer';
        elseif(strpos($user_agent, 'Edge') !== FALSE) //Microsoft Edge
            return 'Microsoft Edge';
        elseif(strpos($user_agent, 'Trident') !== FALSE) //IE 11
            return 'Internet explorer';
        elseif(strpos($user_agent, 'Opera Mini') !== FALSE)
            return "Opera Mini";
        elseif(strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR') !== FALSE)
            return "Opera";
        elseif(strpos($user_agent, 'Firefox') !== FALSE)
            return 'Mozilla Firefox';
        elseif(strpos($user_agent, 'Chrome') !== FALSE)
            return 'Google Chrome';
        elseif(strpos($user_agent, 'Safari') !== FALSE)
            return "Safari";
        else
            return 'No hemos podido detectar su navegador';
    }



    public function store(Request $request){

        if($request->ajax()){

          $prueba = $request->caja;
          $pruebas = $request->cajados;

            $configs = new config;
            $primaryKey = 'cof_id'; 
            $configs->cof_tipo = $prueba;
            $configs->cof_nombre = $pruebas;
            $configs->cof_alias =1;
            $configs->cof_value = 1;
            $configs->cof_desc =1;

            $configs->save();
           // config::create($request->all());
            return response()->json([
                "mensaje" => 'Registro Creado'
            ]);
        }
    }


    public function cambio_prov_carem(Request $request)
    {
      session_start();

      if (isset($_POST['registrar'])) {

          $cedulaEmpleadoN = $_POST['cedulaEmpleadoN'];
          $nombreEmpleadoN = $_POST['nombreEmpleadoN'];
          $apellidoEmpleadoN = $_POST['apellidoEmpleadoN'];
          $areaEmpleadoN =$_POST['areaEmpleadoN'];
          $empleadoFechaVecN = $_POST['empleadoFechaVecN'];
          $ipmaquina = $_SERVER["REMOTE_ADDR"];
          $user_agent = $_SERVER['HTTP_USER_AGENT'];
          $usuario = $_SESSION['id']; // cedula usuario
          $selloPrensa =$_POST['selloPrensaN'];
          $cargoCodigo = $_POST['cargoCodigo'];

       }

       // $empleadoFechaVecN = date("Y/m/d", strtotime($empleadoFechaVecN));
       // selecciono el ultimo horario 
          $tbl_horario = DB::table('horario_x_usuario')
          ->where('hxu_cedula', $cedulaEmpleadoN)
          ->orderby('hxu_fecha_created','DESC')->get()
          ->pluck('hxu_id')->last();

           $carnetCodigo = rand(300000,900000); // Creo cod de carnet 

          $verifico = carnet_us::where('carus_codigo', $carnetCodigo)->get();
          $cod = count($verifico);

          

                      if ($cod == 0) { // verifico que no este el codigo registrado 

                          $rules = ['image' => 'required|image|mimes:jpeg,jpg,png',];
                          $messages = [
                              'image.required' => 'La imagen es requerida',
                              'image.image' => 'Formato no permitido',
                              'image.max' => 'El máximo permitido es 1 MB',
                          ];
                          $validator = Validator::make($request->all(), $rules, $messages);
                          
                          if ($validator->fails()){
                              return redirect('new_carnet')->withErrors($validator);
                          }else{

                              $date = Carbon::now(); //2015-01-01 00:00:00 fecha actual

                              $fechaActual = $date->toDateString();

                              if ($empleadoFechaVecN < $fechaActual) {
                                  Session::flash('flash_error_Fecha','La fecha de Vencimiento no puede ser menor a la fecha actual');
                                  return redirect()->back();
                              }
                              else{

                              $name = $cedulaEmpleadoN.'.jpg';
                              $request->file('image')->move('imagenes2', $name);
                             // chmod('imagenes/'.$cedulaEmpleadoN.".jpg",0777);

                              $carnetLast = carnet_us::where('carus_ced', $cedulaEmpleadoN)->get()
                              ->pluck('carus_codigo')->last(); // seleccionamos cod para buscar en tabla provisionales

                              $provisional = DB::table('carnet_provisionales')
                              ->where('car_prov_ced', $cedulaEmpleadoN)
                              ->update(['car_prov_status' => 1, 'car_prov_ced' => 0 ]);
                              
                              $carus = carnet_us::where('carus_ced', $cedulaEmpleadoN)->update(['carus_codigo' => $carnetCodigo, 'carus_tipo_carnet' => 1, 'carus_fecha_vencimiento' => $empleadoFechaVecN, 'carus_selloprensa' => $selloPrensa, 'carus_status' => 1]);


                              
                      
                          }
                        }


                      $alerta = $carus;

                      if ($alerta == true) {

                          $navegador = $this->getBrowser($user_agent);
                          $areaEm = Usuarios::where('us_ced', $_SESSION['id'])->get()->pluck('us_dp_id')->last();
                          $usuario = Usuarios::where('us_ced', $_SESSION['id'])->get()->pluck('us_login')->last();

                          $historico = new carnet_historico;
                          $id_hist = 'carth_id';
                          $historico->carth_ip_maquina = $ipmaquina;
                          $historico->carth_usuario = $usuario;
                          $historico->carth_departamento = $areaEm;
                          $historico->carth_so = PHP_OS.' - '.php_uname();
                          $historico->carth_navegador = $navegador;
                          $historico->carth_serial_carnet = $carnetCodigo;
                          $historico->carth_cedulaempleado = $cedulaEmpleadoN;
                          $historico->carth_motivo = 1;
                          $historico->carth_tipo_carnet = 1;
                          
                          $historico->save();

                          $history = $historico->save();

                          if ($history == true) {

                            $cargosEmp = DB::connection('vive_2016')
                                         ->table('sno_cargo')
                                         ->where('codcar', $cargoCodigo)
                                         ->get()->pluck('codcar')->last();

                            $cargosNomb = DB::connection('vive_2016')
                                         ->table('sno_cargo')
                                         ->where('codcar', $cargoCodigo)
                                         ->get()->pluck('descar')->last();

                          $cargoID = cargos::where('car_cod', $cargosEmp)->get()->pluck('car_id')->last();

                          $n = count($cargoID);

                          if ($n > 0) {

                            $usuario = DB::table('usuarios')
                              ->where('us_ced', $cedulaEmpleadoN)
                              ->update(['us_car_cod_id' => $cargoID
                              ]);

                              if ($usuario == true) {
                                Session::flash('flash_message','Se ha creado un nuevo carnet');
                              }else{
                                Session::flash('flash_message','Falló');
                              }
                            
                          }else{

                            $cargo = new cargos;
                            $cargo_id = 'car_id';
                            $cargo->car_cod = $cargosEmp;
                            $cargo->car_nombre = $cargosNomb;
                          
                            $cargo->save();

                            $cargoR = $cargo->save();

                            if ($cargoR == true) {

                              $carID = cargos::where('car_cod', $cargosEmp)->get()->pluck('car_id')->last();

                              $usuarioC = DB::table('usuarios')
                              ->where('us_ced', $cedulaEmpleadoN)
                              ->update(['us_car_cod_id' => $carID
                              ]);

                              Session::flash('flash_message','Se ha creado un nuevo carnet');
                              }else{
                                Session::flash('flash_message','Falló');
                              }

                          }
                        }

                          

                      }else{
                          Session::flash('flash_message','Falló');
                      }

                  }
        return redirect()->back();
      }
    



}
