<?php

namespace Asistencias\Http\Controllers;

use Illuminate\Http\Request;

use Asistencias\Http\Requests;
use Asistencias\Http\Controllers\Controller;
use Asistencias\auditoria;
use Illuminate\Support\Facades\Input;
use Asistencias\roles;
use Asistencias\acceso;
use Session;
use DB;
use Carbon\Carbon;
use PDF;

class AudController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fecha1 = new Carbon;
        $desde = $fecha1->subDay(5)->toDateString();
        $fecha2 = new Carbon;
        $hasta = $fecha2->toDateString();
        session_start();
        if (isset($_SESSION['foto'])) {

            if ($_SESSION['rol'] !='EMPLEADO') {
                $auditoria = auditoria::join('usuarios','us_ced','=','aud_ced')->orderBy('aud_fecha','desc')->where('aud_fecha','>=',$desde)->where('aud_fecha','<=',$hasta)->paginate(15);
                $user = DB::table('usuarios')->get();
                $ip = DB::table('auditoria')->select('aud_machine_ip')->groupBy('aud_machine_ip')->get();
                $name_machine = DB::table('auditoria')->select('aud_machine_name')->groupBy('aud_machine_name')->get();
                $explorer_machine = DB::table('auditoria')->select('aud_machine_explorer')->groupBy('aud_machine_explorer')->get();

                $rol = roles::where('ro_id',$_SESSION['acceso'])->get()->pluck('ro_id')->last();

                $aco_aud = acceso::join('pantallas','aco_pnt_id','=','pnt_id')
                ->where('pnt_nombre','p_audit')
                ->where('aco_ro_id',$rol)->get()->pluck('pnt_nombre')->last();

                //return $ip;
                return view('Config/auditoria', compact('auditoria','user','ip','name_machine','explorer_machine','aco_aud'));
                              
            }                        
            //Si no coincide la contraseña,
            Session::flash('session','Disculpe, no tiene acceso a este sistema');
            return view('login');
        }
        else{ 
            //Si la sesion no existe...
            Session::flash('session','A expirado la sesión');
            return view('login');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function auditoria_pdf()
    {

        $fecha1 = new Carbon;
        $desde = $fecha1->subDay(5)->toDateString();
        $fecha2 = new Carbon;
        $hasta = $fecha2->toDateString();
        session_start();
        $usuario = DB::table('usuarios')
            ->where('us_ced', $_SESSION['id'])
            ->join('departamentos','us_dp_id','=','dp_id')
            ->join('horario_x_usuario','hxu_cedula','=','us_ced')->join('tipos_horarios','hxu_tiho_id','=','tiho_id')
            ->paginate(1);
        $auditoria = auditoria::join('usuarios','us_ced','=','aud_ced')->where('aud_fecha','>=',$desde)->where('aud_fecha','<=',$hasta)->get();
        $fecha = Carbon::now();
        $fecha->toDateString();
        //return View('Asistencia/pdf', compact('usuario','dia','desde','hasta','asistencia','fecha'));
        $pdf = PDF::loadView('Config/auditoria_pdf', compact('usuario','auditoria','fecha'))->setPaper('A4','landscape')->setWarnings(false);
        return $pdf->stream('resumen_de_auditoria.pdf');
    }


    public function auditoria_pdf_b($desde,$hasta,$dia,$usuario,$ipb,$name_m,$explorer_m)
    {
        session_start();
        if ($dia =='0000-00-00') {
            if ( $usuario !='sin' || $ipb !='sin' || $name_m !='sin' || $explorer_m !='sin') {
                //Si tiene contenido en usuario
                if ( $usuario  !='sin') {
                    $auditoria = DB::table('auditoria')->join('usuarios','us_ced','=','aud_ced')
                        ->where('us_ced',$usuario)
                        ->where('aud_fecha','>=',$desde)
                        ->where('aud_fecha','<=',$hasta)
                        ->get();
                }
                elseif ( $ipb  !='sin') {
                    $auditoria = DB::table('auditoria')->join('usuarios','us_ced','=','aud_ced')
                        ->where('aud_machine_ip',$ipb)
                        ->where('aud_fecha','>=',$desde)
                        ->where('aud_fecha','<=',$hasta)
                        ->get();
                }
                elseif ( $name_m  !='sin') {
                    $auditoria = DB::table('auditoria')->join('usuarios','us_ced','=','aud_ced')
                        ->where('aud_machine_name',$name_m)
                        ->where('aud_fecha','>=',$desde)
                        ->where('aud_fecha','<=',$hasta)
                        ->get();
                }
                else{
                    $auditoria = DB::table('auditoria')->join('usuarios','us_ced','=','aud_ced')
                        ->where('aud_machine_explorer',$explorer_m)
                        ->where('aud_fecha','>=',$desde)
                        ->where('aud_fecha','<=',$hasta)
                        ->get();
                }
            }
            else{
                $auditoria = DB::table('auditoria')->join('usuarios','us_ced','=','aud_ced')
                    ->where('aud_fecha','>=',$desde)
                    ->where('aud_fecha','<=',$hasta)
                    ->get();
            }
        }
        else{
            if ( $usuario !='sin' || $ipb !='sin' || $name_m !='sin' || $explorer_m !='sin') {
                if ( $usuario  !='sin') {
                    $auditoria = DB::table('auditoria')->join('usuarios','us_ced','=','aud_ced')
                        ->where('us_ced',$usuario)
                        ->where('aud_fecha',$dia)
                        ->get();
                }
                elseif ( $ipb  !='sin') {
                    $auditoria = DB::table('auditoria')->join('usuarios','us_ced','=','aud_ced')
                        ->where('aud_machine_ip',$ipb)
                        ->where('aud_fecha',$dia)
                        ->get();
                }
                elseif ( $name_m  !='sin') {
                    $auditoria = DB::table('auditoria')->join('usuarios','us_ced','=','aud_ced')
                        ->where('aud_machine_name',$name_m)
                        ->where('aud_fecha',$dia)
                        ->get();
                }
                else{
                    $auditoria = DB::table('auditoria')->join('usuarios','us_ced','=','aud_ced')
                        ->where('aud_machine_explorer',$explorer_m)
                        ->where('aud_fecha',$dia)
                        ->get();
                }
            }
            else{
                $auditoria = DB::table('auditoria')->join('usuarios','us_ced','=','aud_ced')
                    ->where('aud_fecha',$dia)
                    ->get();
            }
        }
        $fecha = Carbon::now();
        $fecha->toDateString();
        $usuario = DB::table('usuarios')
            ->where('us_ced', $_SESSION['id'])
            ->join('departamentos','us_dp_id','=','dp_id')
            ->join('horario_x_usuario','hxu_cedula','=','us_ced')->join('tipos_horarios','hxu_tiho_id','=','tiho_id')
            ->paginate(1);
        //return View('Asistencia/pdf', compact('usuario','dia','desde','hasta','asistencia','fecha'));
        $pdf = PDF::loadView('Config/auditoria_pdf', compact('usuario','auditoria','fecha'))->setPaper('A4','landscape')->setWarnings(false);
        return $pdf->stream('resumen_de_auditoria.pdf');
    }
    public function aud_search(Request $request)
    {
        session_start();
        $user = DB::table('usuarios')->get();
        $ip = DB::table('auditoria')->select('aud_machine_ip')->groupBy('aud_machine_ip')->get();
        $name_machine = DB::table('auditoria')->select('aud_machine_name')->groupBy('aud_machine_name')->get();
        $explorer_machine = DB::table('auditoria')->select('aud_machine_explorer')->groupBy('aud_machine_explorer')->get();
        
        //Variables para la busqueda
        $desde = $request->desde;
        $hasta = $request->hasta;
        $dia = $request->dia;
        $usuario = $request->usuario;
        $ipb = $request->ip;
        $acc = $request->acc;
        $name_m = $request->name_machine;
        $explorer_m = $request->explorer;

        if ($dia !='' || $desde !='') {
            if ($dia =='') {
                if (isset($usuario) || isset($ipb) || isset($name_m) || isset($explorer_m)) {
                    //Si tiene contenido en usuario
                    if (isset($usuario)) {
                        $auditoria = DB::table('auditoria')->join('usuarios','us_ced','=','aud_ced')
                            ->where('us_ced',$usuario)
                            ->where('aud_fecha','>=',$desde)
                            ->where('aud_fecha','<=',$hasta)
                            ->get();
                    }
                    elseif (isset($ipb)) {
                        $auditoria = DB::table('auditoria')->join('usuarios','us_ced','=','aud_ced')
                            ->where('aud_machine_ip',$ipb)
                            ->where('aud_fecha','>=',$desde)
                            ->where('aud_fecha','<=',$hasta)
                            ->get();
                    }
                    elseif (isset($name_m)) {
                        $auditoria = DB::table('auditoria')->join('usuarios','us_ced','=','aud_ced')
                            ->where('aud_machine_name',$name_m)
                            ->where('aud_fecha','>=',$desde)
                            ->where('aud_fecha','<=',$hasta)
                            ->get();
                    }
                    else{
                        $auditoria = DB::table('auditoria')->join('usuarios','us_ced','=','aud_ced')
                            ->where('aud_machine_explorer',$explorer_m)
                            ->where('aud_fecha','>=',$desde)
                            ->where('aud_fecha','<=',$hasta)
                            ->get();
                    }
                }
                else{
                    $auditoria = DB::table('auditoria')->join('usuarios','us_ced','=','aud_ced')
                        ->where('aud_fecha','>=',$desde)
                        ->where('aud_fecha','<=',$hasta)
                        ->get();
                }
            }
            else{
                if (isset($usuario) || isset($ipb) || isset($name_m) || isset($explorer_m)) {
                    if (isset($usuario)) {
                        $auditoria = DB::table('auditoria')->join('usuarios','us_ced','=','aud_ced')
                            ->where('us_ced',$usuario)
                            ->where('aud_fecha',$dia)
                            ->get();
                    }
                    elseif (isset($ipb)) {
                        $auditoria = DB::table('auditoria')->join('usuarios','us_ced','=','aud_ced')
                            ->where('aud_machine_ip',$ipb)
                            ->where('aud_fecha',$dia)
                            ->get();
                    }
                    elseif (isset($name_m)) {
                        $auditoria = DB::table('auditoria')->join('usuarios','us_ced','=','aud_ced')
                            ->where('aud_machine_name',$name_m)
                            ->where('aud_fecha',$dia)
                            ->get();
                    }
                    else{
                        $auditoria = DB::table('auditoria')->join('usuarios','us_ced','=','aud_ced')
                            ->where('aud_machine_explorer',$explorer_m)
                            ->where('aud_fecha',$dia)
                            ->get();
                    }
                }
                else{
                    $auditoria = DB::table('auditoria')->join('usuarios','us_ced','=','aud_ced')
                        ->where('aud_fecha',$dia)
                        ->get();
                }
            }
            return view('Config/auditoria_b', compact('auditoria','user','ip','name_machine','explorer_machine','desde','hasta','dia','usuario','ipb','name_m','explorer_m'));
        }
        else{
            Session::flash('session','Debe especificar una fecha para poder realizar la busqueda.');
            return redirect()->back();
        }
    }
}

