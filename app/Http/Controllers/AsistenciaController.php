<?php

namespace Asistencias\Http\Controllers;

use Illuminate\Http\Request;

use Asistencias\Http\Requests;
use Asistencias\Http\Controllers\Controller;
use Asistencias\carnet_us;
use Asistencias\Asistencia;
use Illuminate\Support\Facades\Input;
use Asistencias\notificaciones;
use Asistencias\carnet_seriales_inutilizados;
use Asistencias\horario_x_usuario;
use Asistencias\ex_x_usuario;
use Asistencias\Tipos_horarios;
use Asistencias\Acceso_cliente;
use Asistencias\departamentos;
use Asistencias\notificacion_coord;
use Asistencias\dias_feriados;
use Asistencias\autorizacion;
use Asistencias\Usuarios;
use Asistencias\Alertas;
use DB;
use Carbon\Carbon;
use Adldap\Laravel\Facades\Adldap;

class AsistenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        session_start();
        //$ip_maquina ='172.16.144.132/20';
        $ip_maquina = $_SERVER['REMOTE_ADDR'];
       // return $ip_maquina;

        $dia = date('Y-m-d');

        $usersent = Asistencia::where('asi_entrada', $dia)->where('asi_carus_id','10')->get();
        $usersal = Asistencia::where('asi_salida', $dia)->where('asi_carus_id','10')->get();

        $autip = Acceso_cliente::where('mcjacc_ip', $ip_maquina)->where('mcjacc_pantalla','Marcaje')->where('mcjacc_status','TRUE')->get();
        
        return view('Asistencia/asistenciag', compact('autip','usersent','usersal'));

    }

    public function marcar_entrada(Request $request, $cod)
    {

        $dias_lab = DB::table('carnet_us')->join('horario_x_usuario','carus_hxu_id','=','hxu_id')->join('tipos_horarios','hxu_tiho_id','=','tiho_id')->where('carus_codigo',$cod)->get()->pluck('tiho_dias')->last();

        $h_entrada = DB::table('carnet_us')->join('horario_x_usuario','carus_hxu_id','=','hxu_id')->join('tipos_horarios','hxu_tiho_id','=','tiho_id')->where('carus_codigo',$cod)->get()->pluck('tiho_hora_en')->last();

        $h_salida = DB::table('carnet_us')->join('horario_x_usuario','carus_hxu_id','=','hxu_id')->join('tipos_horarios','hxu_tiho_id','=','tiho_id')->where('carus_codigo',$cod)->get()->pluck('tiho_hora_sa')->last();

        $hh_entrada = DB::table('carnet_us')->join('horario_x_usuario','carus_hxu_id','=','hxu_id')->join('tipos_horarios','hxu_tiho_id','=','tiho_id')->where('carus_codigo',$cod)->get()->pluck('tiho_holgura_entrada')->last();

        $hh_salida = DB::table('carnet_us')->join('horario_x_usuario','carus_hxu_id','=','hxu_id')->join('tipos_horarios','hxu_tiho_id','=','tiho_id')->where('carus_codigo',$cod)->get()->pluck('tiho_holgura_salida')->last();

        $turno = DB::table('carnet_us')->join('horario_x_usuario','carus_hxu_id','=','hxu_id')->join('tipos_horarios','hxu_tiho_id','=','tiho_id')->where('carus_codigo',$cod)->get()->pluck('tiho_turno')->last();

        $media_jornada = $h_salida - $h_entrada;


        $fecha_a = new Carbon; 
        //Obtiene los días de semana
        $dias = array('Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado');
        //Captura que dia de la semana es la fecha
        $fecha = $dias[date('w', strtotime($fecha_a->toDateString()))];
        //Separa los dias de trabajo de la persona
        $explode = explode(",",$dias_lab);  

        $hora = $fecha_a->subHour(4)->toTimeString();

        $carnett = DB::table('carnet_us')->where('carus_codigo',$cod)->get()->pluck('carus_id')->last();
        if (isset($carnett)) {
            $carnet = $carnett;
        }
        else{
            $carnet = DB::table('carnet_us')->where('carus_ced',$cod)->where('carus_tipo_carnet',2)->get()->pluck('carus_id')->last();
        }

        $dia_fe = dias_feriados::whereIn('diaf_feriado',[$fecha_a->toDateString()])->get()->pluck('diaf_id')->last();

        $ip_maquina = $_SERVER['REMOTE_ADDR'];
        if ($dia_fe =='') {
            $diaf = null;
        }
        else{
            $diaf = $dia_fe;
        }

        $num = count($explode);
        $current = 0;
        if (isset($carnet)) {
                       
            foreach ($explode as $key => $value) {
                if ($current < $num){
                    if ($fecha == ltrim($value)) {
                        
                       $usu ='si';
                    }
                    else{
                        $user = 'no';
                    }
                }
                $current++;
            }

            if ($hora < '14:00:00') {
                
                if (isset($usu)) {
                    $cant_horas = substr(($media_jornada/2),0,1);
                    $media = substr($h_entrada,0,2) + $cant_horas.':00:00';
                    if ($hora <= $media) {


                        $fechaa = new Carbon;

                        $entrada= Asistencia::where('asi_entrada', $fechaa->subDay(1)->toDateString())->whereNull('asi_salida')->where('asi_carus_id', $carnet)->where('asi_entrada_hora','>','14:00:00')->get();

                        $e_anterior= Asistencia::where('asi_entrada', $fechaa->toDateString())->whereNull('asi_salida')->where('asi_carus_id', $carnet)->get()->pluck('asi_id')->last();
                        
                        if ($entrada != '[]') {

                            $asistencia = asistencia::where('asi_id',$e_anterior)->update(['asi_salida' =>  $fecha_a->toDateString() ,'asi_salida_hora' => $hora ]);

                            $usuario =asistencia::usuario_get($carnet,$fechaa->toDateString());
                        }
                        else{
                            $entrada= Asistencia::where('asi_entrada', $fecha_a->toDateString())->where('asi_carus_id', $carnet)->get(); 
                            if ($entrada != '[]') {
                                if (count($entrada)>=2) {
                                    $u_entrada= Asistencia::where('asi_entrada', $fecha_a->toDateString())->where('asi_carus_id', $carnet)->whereNull('asi_salida')->orderBy('asi_id','desc')->get()->pluck('asi_id')->first();
                                    if (!isset($u_entrada)) {
                                        $asistencia = new Asistencia;
                                        $asistencia->asi_entrada = $fecha_a->toDateString();
                                        $asistencia->asi_carus_id =  $carnet;
                                        $asistencia->asi_entrada_hora = $hora;
                                        $asistencia->asi_diaf_id = $diaf;
                                        $asistencia->asi_status = 1;
                                        $asistencia->save();
                                        $insert = $asistencia->save();

                                        //RETORNAR AL USUARIO//
                                        $usuario = asistencia::usuario_get($carnet,$fecha_a->toDateString());
                                    }
                                    else{
                                        $asistencia = asistencia::where('asi_id',$u_entrada)->update(['asi_salida' =>  $fecha_a->toDateString() ,'asi_salida_hora' => $hora ]);

                                        //RETORNAR AL USUARIO//
                                        $usuario = asistencia::usuario_get($carnet,$fecha_a->toDateString());
                                    }
                                    
                                }
                                else{
                                    $u_entrada= Asistencia::where('asi_entrada', $fecha_a->toDateString())->where('asi_carus_id', $carnet)->whereNull('asi_salida')->orderBy('asi_id','desc')->get()->pluck('asi_id')->first();

                                    if (!isset($u_entrada)) {
                                        $asistencia = new Asistencia;
                                        $asistencia->asi_entrada = $fecha_a->toDateString();
                                        $asistencia->asi_carus_id =  $carnet;
                                        $asistencia->asi_entrada_hora = $hora;
                                        $asistencia->asi_diaf_id = $diaf;
                                        $asistencia->asi_status = 1;
                                        $asistencia->save();
                                        $insert = $asistencia->save();

                                        //RETORNAR AL USUARIO//
                                        $usuario = asistencia::usuario_get($carnet,$fecha_a->toDateString());
                                    }
                                    else{
                                        $asistencia = asistencia::where('asi_id',$u_entrada)->update(['asi_salida' =>  $fecha_a->toDateString() ,'asi_salida_hora' => $hora ]);

                                        //RETORNAR AL USUARIO//
                                        $usuario = asistencia::usuario_get($carnet,$fecha_a->toDateString());
                                    }

                                }
                            }
                            else{
                                $asistencia = new Asistencia;
                                $asistencia->asi_entrada = $fecha_a->toDateString();
                                $asistencia->asi_carus_id =  $carnet;
                                $asistencia->asi_entrada_hora = $hora;
                                $asistencia->asi_diaf_id = $diaf;
                                $asistencia->asi_status = 1;
                                $asistencia->save();
                                $insert = $asistencia->save();

                                //RETORNAR AL USUARIO//
                                $usuario = asistencia::usuario_get($carnet,$fecha_a->toDateString());
                            }
                            
                        }
                    }
                    else{

                        $fechaa = new Carbon;

                        $entrada= Asistencia::where('asi_entrada', $fechaa->subDay(1)->toDateString())->whereNull('asi_salida')->where('asi_carus_id', $carnet)->where('asi_entrada_hora','>','14:00:00')->get();

                        $e_anterior= Asistencia::where('asi_entrada', $fechaa->toDateString())->whereNull('asi_salida')->where('asi_carus_id', $carnet)->get()->pluck('asi_id')->last();
                        
                        if ($entrada != '[]') {

                            $asistencia = asistencia::where('asi_id',$e_anterior)->update(['asi_salida' =>  $fecha_a->toDateString() ,'asi_salida_hora' => $hora ]);

                            $usuario =asistencia::usuario_get($carnet,$fechaa->toDateString());
                        }
                        else{
                            
                        $entrada= Asistencia::where('asi_entrada', $fecha_a->toDateString())->where('asi_carus_id', $carnet)->get();
                        if ($entrada != '[]') {
                            if (count($entrada)>=2) {
                                $u_entrada= Asistencia::where('asi_entrada', $fecha_a->toDateString())->where('asi_carus_id', $carnet)->whereNull('asi_salida')->orderBy('asi_id','desc')->get()->pluck('asi_id')->first();
                                if (!isset($u_entrada)) {
                                    $asistencia = new Asistencia;
                                    $asistencia->asi_entrada = $fecha_a->toDateString();
                                        $asistencia->asi_carus_id =  $carnet;
                                        $asistencia->asi_entrada_hora = $hora;
                                        $asistencia->asi_diaf_id = $diaf;
                                        $asistencia->asi_status = 1;
                                        $asistencia->save();
                                        $insert = $asistencia->save();

                                        //RETORNAR AL USUARIO//
                                        $usuario = asistencia::usuario_get($carnet,$fecha_a->toDateString());
                                    }
                                    else{
                                        $asistencia = asistencia::where('asi_id',$u_entrada)->update(['asi_salida' =>  $fecha_a->toDateString() ,'asi_salida_hora' => $hora ]);

                                        //RETORNAR AL USUARIO//
                                        $usuario = asistencia::usuario_get($carnet,$fecha_a->toDateString());
                                    }
                                    
                                }
                                else{
                                    $u_entrada= Asistencia::where('asi_entrada', $fecha_a->toDateString())->where('asi_carus_id', $carnet)->whereNull('asi_salida')->orderBy('asi_id','desc')->get()->pluck('asi_id')->first();

                                    if (!isset($u_entrada)) {
                                        $asistencia = new Asistencia;
                                        $asistencia->asi_entrada = $fecha_a->toDateString();
                                        $asistencia->asi_carus_id =  $carnet;
                                        $asistencia->asi_entrada_hora = $hora;
                                        $asistencia->asi_diaf_id = $diaf;
                                        $asistencia->asi_status = 1;
                                        $asistencia->save();
                                        $insert = $asistencia->save();

                                        //RETORNAR AL USUARIO//
                                        $usuario = asistencia::usuario_get($carnet,$fecha_a->toDateString());
                                    }
                                    else{
                                        $asistencia = asistencia::where('asi_id',$u_entrada)->update(['asi_salida' =>  $fecha_a->toDateString() ,'asi_salida_hora' => $hora ]);

                                        //RETORNAR AL USUARIO//
                                        $usuario = asistencia::usuario_get($carnet,$fecha_a->toDateString());
                                    }

                                }
                            }
                            else{
                                $asistencia = new Asistencia;
                                $asistencia->asi_entrada = $fecha_a->toDateString();
                                $asistencia->asi_carus_id =  $carnet;
                                $asistencia->asi_entrada_hora = $hora;
                                $asistencia->asi_diaf_id = $diaf;
                                $asistencia->asi_status = 1;
                                $asistencia->save();
                                $insert = $asistencia->save();

                                $dep = asistencia::join('carnet_us','carus_id','=','asi_carus_id')
                                ->join('horario_x_usuario','hxu_id','=','carus_hxu_id')
                                ->join('usuarios','hxu_cedula','=','us_ced')
                                ->join('departamentos','us_dp_id','=','dp_id')
                                ->where('asi_carus_id',$carnet)
                                ->where('asi_id',$asistencia->asi_id)
                                ->get()->pluck('dp_nombre')->last();

                                $ced = asistencia::join('carnet_us','carus_id','=','asi_carus_id')
                                ->join('horario_x_usuario','hxu_id','=','carus_hxu_id')
                                ->join('usuarios','hxu_cedula','=','us_ced')
                                ->join('departamentos','us_dp_id','=','dp_id')
                                ->where('asi_carus_id',$carnet)
                                ->where('asi_id',$asistencia->asi_id)
                                ->get()->pluck('us_ced')->last();

                                $motivo = 'Registro de salida sin hora de entrada registrada del empleado con cedula:'.$ced;

                                $notificacion = notificacion_coord::insert_not($dep,$ced,$motivo,$asistencia->asi_id);

                                //RETORNAR AL USUARIO//
                                $usuario = asistencia::usuario_get($carnet,$fecha_a->toDateString());
                            }
                        }
                    }
                }
                else{
                    $excepcion = ex_x_usuario::join('excepciones','exu_id','=','exu_ex_id')->join('autorizacion','au_id','=','ex_au_id')->where('au_permiso',$fecha_a->toDateString())->get();
                    if ($excepcion != '[]') {
                        $cant_horas = substr(($media_jornada/2),0,1);
                        $media = substr($h_entrada,0,2) + $cant_horas.':00:00';
                        if ($hora <= $media) {
                            $entrada= Asistencia::where('asi_entrada', $fecha_a->toDateString())->where('asi_carus_id', $carnet)->get(); 
                            if ($entrada != '[]') {
                                if (count($entrada)>=2) {
                                    $u_entrada= Asistencia::where('asi_entrada', $fecha_a->toDateString())->where('asi_carus_id', $carnet)->whereNull('asi_salida')->orderBy('asi_id','desc')->get()->pluck('asi_id')->first();
                                    if (!isset($u_entrada)) {
                                        $asistencia = new Asistencia;
                                        $asistencia->asi_entrada = $fecha_a->toDateString();
                                        $asistencia->asi_carus_id =  $carnet;
                                        $asistencia->asi_entrada_hora = $hora;
                                        $asistencia->asi_diaf_id = $diaf;
                                        $asistencia->asi_status = 1;
                                        $asistencia->save();
                                        $insert = $asistencia->save();

                                        //RETORNAR AL USUARIO//
                                        $usuario = asistencia::usuario_get($carnet,$fecha_a->toDateString());
                                    }
                                    else{
                                        $asistencia = asistencia::where('asi_id',$u_entrada)->update(['asi_salida' =>  $fecha_a->toDateString() ,'asi_salida_hora' => $hora ]);

                                        //RETORNAR AL USUARIO//
                                        $usuario = asistencia::usuario_get($carnet,$fecha_a->toDateString());
                                    }
                                    
                                }
                                else{
                                    $u_entrada= Asistencia::where('asi_entrada', $fecha_a->toDateString())->where('asi_carus_id', $carnet)->whereNull('asi_salida')->orderBy('asi_id','desc')->get()->pluck('asi_id')->first();

                                    if (!isset($u_entrada)) {
                                        $asistencia = new Asistencia;
                                        $asistencia->asi_entrada = $fecha_a->toDateString();
                                        $asistencia->asi_carus_id =  $carnet;
                                        $asistencia->asi_entrada_hora = $hora;
                                        $asistencia->asi_diaf_id = $diaf;
                                        $asistencia->asi_status = 1;
                                        $asistencia->save();
                                        $insert = $asistencia->save();

                                        //RETORNAR AL USUARIO//
                                        $usuario = asistencia::usuario_get($carnet,$fecha_a->toDateString());
                                    }
                                    else{
                                        $asistencia = asistencia::where('asi_id',$u_entrada)->update(['asi_salida' =>  $fecha_a->toDateString() ,'asi_salida_hora' => $hora ]);

                                        //RETORNAR AL USUARIO//
                                        $usuario = asistencia::usuario_get($carnet,$fecha_a->toDateString());
                                    }

                                }
                            }
                            else{
                                $asistencia = new Asistencia;
                                $asistencia->asi_entrada = $fecha_a->toDateString();
                                $asistencia->asi_carus_id =  $carnet;
                                $asistencia->asi_entrada_hora = $hora;
                                $asistencia->asi_diaf_id = $diaf;
                                $asistencia->asi_status = 1;
                                $asistencia->save();
                                $insert = $asistencia->save();

                                //RETORNAR AL USUARIO//
                                $usuario = asistencia::usuario_get($carnet,$fecha_a->toDateString());
                            }
                        }
                        else{
                            $entrada= Asistencia::where('asi_entrada', $fecha_a->toDateString())->where('asi_carus_id', $carnet)->get();
                            if ($entrada != '[]') {
                                if (count($entrada)>=2) {
                                    $u_entrada= Asistencia::where('asi_entrada', $fecha_a->toDateString())->where('asi_carus_id', $carnet)->whereNull('asi_salida')->orderBy('asi_id','desc')->get()->pluck('asi_id')->first();
                                    if (!isset($u_entrada)) {
                                        $asistencia = new Asistencia;
                                        $asistencia->asi_entrada = $fecha_a->toDateString();
                                        $asistencia->asi_carus_id =  $carnet;
                                        $asistencia->asi_entrada_hora = $hora;
                                        $asistencia->asi_diaf_id = $diaf;
                                        $asistencia->asi_status = 1;
                                        $asistencia->save();
                                        $insert = $asistencia->save();

                                        //RETORNAR AL USUARIO//
                                        $usuario = asistencia::usuario_get($carnet,$fecha_a->toDateString());
                                    }
                                    else{
                                        $asistencia = asistencia::where('asi_id',$u_entrada)->update(['asi_salida' =>  $fecha_a->toDateString() ,'asi_salida_hora' => $hora ]);

                                        //RETORNAR AL USUARIO//
                                        $usuario = asistencia::usuario_get($carnet,$fecha_a->toDateString());
                                    }
                                    
                                }
                                else{
                                    $u_entrada= Asistencia::where('asi_entrada', $fecha_a->toDateString())->where('asi_carus_id', $carnet)->whereNull('asi_salida')->orderBy('asi_id','desc')->get()->pluck('asi_id')->first();

                                    if (!isset($u_entrada)) {
                                        $asistencia = new Asistencia;
                                        $asistencia->asi_entrada = $fecha_a->toDateString();
                                        $asistencia->asi_carus_id =  $carnet;
                                        $asistencia->asi_entrada_hora = $hora;
                                        $asistencia->asi_diaf_id = $diaf;
                                        $asistencia->asi_status = 1;
                                        $asistencia->save();
                                        $insert = $asistencia->save();

                                        //RETORNAR AL USUARIO//
                                        $usuario = asistencia::usuario_get($carnet,$fecha_a->toDateString());
                                    }
                                    else{
                                        $asistencia = asistencia::where('asi_id',$u_entrada)->update(['asi_salida' =>  $fecha_a->toDateString() ,'asi_salida_hora' => $hora ]);

                                        //RETORNAR AL USUARIO//
                                        $usuario = asistencia::usuario_get($carnet,$fecha_a->toDateString());
                                    }

                                }
                            }
                            else{
                                $asistencia = new Asistencia;
                                $asistencia->asi_entrada = $fecha_a->toDateString();
                                $asistencia->asi_carus_id =  $carnet;
                                $asistencia->asi_entrada_hora = $hora;
                                $asistencia->asi_diaf_id = $diaf;
                                $asistencia->asi_status = 1;
                                $asistencia->save();
                                $insert = $asistencia->save();

                                $dep = asistencia::join('carnet_us','carus_id','=','asi_carus_id')
                                ->join('horario_x_usuario','hxu_id','=','carus_hxu_id')
                                ->join('usuarios','hxu_cedula','=','us_ced')
                                ->join('departamentos','us_dp_id','=','dp_id')
                                ->where('asi_carus_id',$carnet)
                                ->where('asi_id',$asistencia->asi_id)
                                ->get()->pluck('dp_nombre')->last();

                                $ced = asistencia::join('carnet_us','carus_id','=','asi_carus_id')
                                ->join('horario_x_usuario','hxu_id','=','carus_hxu_id')
                                ->join('usuarios','hxu_cedula','=','us_ced')
                                ->join('departamentos','us_dp_id','=','dp_id')
                                ->where('asi_carus_id',$carnet)
                                ->where('asi_id',$asistencia->asi_id)
                                ->get()->pluck('us_ced')->last();

                                $motivo = 'Registro de salida sin hora de entrada registrada del empleado con cedula:'.$ced;

                                $notificacion = notificacion_coord::insert_not($dep,$ced,$motivo,$asistencia->asi_id);

                                //RETORNAR AL USUARIO//
                                $usuario = asistencia::usuario_get($carnet,$fecha_a->toDateString());
                            }
                        }
                    }
                    else{
                        $cant_horas = substr(($media_jornada/2),0,1);
                        $media = substr($h_entrada,0,2) + $cant_horas.':00:00';
                        if ($hora <= $media) {
                            $entrada= Asistencia::where('asi_entrada', $fecha_a->toDateString())->where('asi_carus_id', $carnet)->get(); 
                            if ($entrada != '[]') {
                                if (count($entrada)>=2) {
                                    $u_entrada= Asistencia::where('asi_entrada',$fecha_a->toDateString())->where('asi_carus_id', $carnet)->whereNull('asi_salida')->orderBy('asi_id','desc')->get()->pluck('asi_id')->first();
                                    if (!isset($u_entrada)) {
                                        $asistencia = new Asistencia;
                                        $asistencia->asi_entrada = $fecha_a->toDateString();
                                        $asistencia->asi_carus_id =  $carnet;
                                        $asistencia->asi_entrada_hora = $hora;
                                        $asistencia->asi_diaf_id = $diaf;
                                        $asistencia->asi_status = 1;
                                        $asistencia->save();
                                        $insert = $asistencia->save();

                                        $dep = asistencia::b_dep($carnet,$asistencia->asi_id);

                                        $ced = asistencia::b_usuario($carnet,$asistencia->asi_id);

                                        $motivo = 'Registro de entrada fuera de jornada laboral y sin autorizacion del empleado con cedula'.$ced;

                                        $notificacion = notificacion_coord::insert_not($dep,$ced,$motivo,$asistencia->asi_id);

                                        $Notf = new Notificaciones;
                                        $Notf->notmcj_fecha = $fecha_a->toDateString();
                                        $Notf->notmcj_motivo = "Ingreso al canal";
                                        $Notf->notmcj_descripcion =  "Ingreso al canal fuera de su horario laboral del empleado con cedula;".$ced;
                                        $Notf->notmcj_ipmaquina = $ip_maquina;
                                        $Notf->notmcj_codigo = $cod;
                                        $Notf->notmcj_hora = $hora;
                                        $Notf->save();

                                        $alt = new Alertas;
                                        $alt->alert_alerta = $Notf->notmcj_descripcion;
                                        $alt->alert_fecha = $fecha_a->toDateString();
                                        $alt->alert_hora = $hora;
                                        $alt->save();

                                        //RETORNAR AL USUARIO//
                                        $usuario = asistencia::usuario_get($carnet,$fecha_a->toDateString());
                                    }
                                    else{
                                        $asistencia = asistencia::where('asi_id',$u_entrada)->update(['asi_salida' =>  $fecha_a->toDateString() ,'asi_salida_hora' => $hora ]);

                                        //RETORNAR AL USUARIO//
                                        $usuario = asistencia::usuario_get($carnet,$fecha_a->toDateString());
                                    }
                                    
                                }
                                else{
                                    $u_entrada= Asistencia::where('asi_entrada', $fecha_a->toDateString())->where('asi_carus_id', $carnet)->whereNull('asi_salida')->orderBy('asi_id','desc')->get()->pluck('asi_id')->first();

                                    if (!isset($u_entrada)) {
                                        $asistencia = new Asistencia;
                                        $asistencia->asi_entrada = $fecha_a->toDateString();
                                        $asistencia->asi_carus_id =  $carnet;
                                        $asistencia->asi_entrada_hora = $hora;
                                        $asistencia->asi_diaf_id = $diaf;
                                        $asistencia->asi_status = 1;
                                        $asistencia->save();
                                        $insert = $asistencia->save();
                                        
                                        $dep = asistencia::b_dep($carnet,$asistencia->asi_id);

                                        $ced = asistencia::b_usuario($carnet,$asistencia->asi_id);

                                        $motivo = 'Registro de entrada fuera de jornada laboral y sin autorizacion del empleado con cedula'.$ced;

                                        $notificacion = notificacion_coord::insert_not($dep,$ced,$motivo,$asistencia->asi_id);

                                        $Notf = new Notificaciones;
                                        $Notf->notmcj_fecha = $fecha_a->toDateString();
                                        $Notf->notmcj_motivo = "Ingreso al canal";
                                        $Notf->notmcj_descripcion =  "Ingreso al canal fuera de su horario laboral del empleado con cedula;".$ced;
                                        $Notf->notmcj_ipmaquina = $ip_maquina;
                                        $Notf->notmcj_codigo = $cod;
                                        $Notf->notmcj_hora = $hora;
                                        $Notf->save();

                                        $alt = new Alertas;
                                        $alt->alert_alerta = $Notf->notmcj_descripcion;
                                        $alt->alert_fecha = $fecha_a->toDateString();
                                        $alt->alert_hora = $hora;
                                        $alt->save();

                                        //RETORNAR AL USUARIO//
                                        $usuario = asistencia::usuario_get($carnet,$fecha_a->toDateString());
                                    }
                                    else{
                                        $asistencia = asistencia::where('asi_id',$u_entrada)->update(['asi_salida' =>  $fecha_a->toDateString() ,'asi_salida_hora' => $hora ]);

                                        //RETORNAR AL USUARIO//
                                        $usuario = asistencia::usuario_get($carnet,$fecha_a->toDateString());
                                    }

                                }
                            }
                            else{
                                $asistencia = new Asistencia;
                                $asistencia->asi_entrada = $fecha_a->toDateString();
                                $asistencia->asi_carus_id =  $carnet;
                                $asistencia->asi_entrada_hora = $hora;
                                $asistencia->asi_diaf_id = $diaf;
                                $asistencia->asi_status = 1;
                                $asistencia->save();
                                $insert = $asistencia->save();
                                        
                                $dep = asistencia::b_dep($carnet,$asistencia->asi_id);

                                $ced = asistencia::b_usuario($carnet,$asistencia->asi_id);

                                $motivo = 'Registro de entrada fuera de jornada laboral y sin autorizacion del empleado con cedula'.$ced;

                                $notificacion = notificacion_coord::insert_not($dep,$ced,$motivo,$asistencia->asi_id);

                                $Notf = new Notificaciones;
                                $Notf->notmcj_fecha = $fecha_a->toDateString();
                                $Notf->notmcj_motivo = "Ingreso al canal";
                                $Notf->notmcj_descripcion =  "Ingreso al canal fuera de su horario laboral del empleado con cedula;".$ced;
                                $Notf->notmcj_ipmaquina = $ip_maquina;
                                $Notf->notmcj_codigo = $cod;
                                $Notf->notmcj_hora = $hora;
                                $Notf->save();

                                $alt = new Alertas;
                                $alt->alert_alerta = $Notf->notmcj_descripcion;
                                $alt->alert_fecha = $fecha_a->toDateString();
                                $alt->alert_hora = $hora;
                                $alt->save();

                                //RETORNAR AL USUARIO//
                                $usuario = asistencia::usuario_get($carnet,$fecha_a->toDateString());
                            }
                        }
                        else{
                            $entrada= Asistencia::where('asi_entrada', $fecha_a->toDateString())->where('asi_carus_id', $carnet)->get();
                            if ($entrada != '[]') {
                                if (count($entrada)>=2) {
                                    $u_entrada= Asistencia::where('asi_entrada', $fecha_a->toDateString())->where('asi_carus_id', $carnet)->whereNull('asi_salida')->orderBy('asi_id','desc')->get()->pluck('asi_id')->first();
                                    if (!isset($u_entrada)) {
                                        $asistencia = new Asistencia;
                                        $asistencia->asi_entrada = $fecha_a->toDateString();
                                        $asistencia->asi_carus_id =  $carnet;
                                        $asistencia->asi_entrada_hora = $hora;
                                        $asistencia->asi_diaf_id = $diaf;
                                        $asistencia->asi_status = 1;
                                        $asistencia->save();
                                        $insert = $asistencia->save();
                                        
                                        $dep = asistencia::b_dep($carnet,$asistencia->asi_id);

                                        $ced = asistencia::b_usuario($carnet,$asistencia->asi_id);

                                        $motivo = 'Registro de entrada fuera de jornada laboral y sin autorizacion del empleado con cedula'.$ced;

                                        $notificacion = notificacion_coord::insert_not($dep,$ced,$motivo,$asistencia->asi_id);

                                        $Notf = new Notificaciones;
                                        $Notf->notmcj_fecha = $fecha_a->toDateString();
                                        $Notf->notmcj_motivo = "Ingreso al canal";
                                        $Notf->notmcj_descripcion =  "Ingreso al canal fuera de su horario laboral del empleado con cedula;".$ced;
                                        $Notf->notmcj_ipmaquina = $ip_maquina;
                                        $Notf->notmcj_codigo = $cod;
                                        $Notf->notmcj_hora = $hora;
                                        $Notf->save();

                                        $alt = new Alertas;
                                        $alt->alert_alerta = $Notf->notmcj_descripcion;
                                        $alt->alert_fecha = $fecha_a->toDateString();
                                        $alt->alert_hora = $hora;
                                        $alt->save();

                                        //RETORNAR AL USUARIO//
                                        $usuario = asistencia::usuario_get($carnet,$fecha_a->toDateString());
                                    }
                                    else{
                                        $asistencia = asistencia::where('asi_id',$u_entrada)->update(['asi_salida' =>  $fecha_a->toDateString() ,'asi_salida_hora' => $hora ]);

                                        //RETORNAR AL USUARIO//
                                        $usuario = asistencia::usuario_get($carnet,$fecha_a->toDateString());
                                    }
                                    
                                }
                                else{
                                    $u_entrada= Asistencia::where('asi_entrada', $fecha_a->toDateString())->where('asi_carus_id', $carnet)->whereNull('asi_salida')->orderBy('asi_id','desc')->get()->pluck('asi_id')->first();

                                    if (!isset($u_entrada)) {
                                        $asistencia = new Asistencia;
                                        $asistencia->asi_entrada = $fecha_a->toDateString();
                                        $asistencia->asi_carus_id =  $carnet;
                                        $asistencia->asi_entrada_hora = $hora;
                                        $asistencia->asi_diaf_id = $diaf;
                                        $asistencia->asi_status = 1;
                                        $asistencia->save();
                                        $insert = $asistencia->save();
                                        
                                        $dep = asistencia::b_dep($carnet,$asistencia->asi_id);

                                        $ced = asistencia::b_usuario($carnet,$asistencia->asi_id);

                                        $motivo = 'Registro de entrada fuera de jornada laboral y sin autorizacion del empleado con cedula'.$ced;

                                        $notificacion = notificacion_coord::insert_not($dep,$ced,$motivo,$asistencia->asi_id);

                                        $Notf = new Notificaciones;
                                        $Notf->notmcj_fecha = $fecha_a->toDateString();
                                        $Notf->notmcj_motivo = "Ingreso al canal";
                                        $Notf->notmcj_descripcion =  "Ingreso al canal fuera de su horario laboral del empleado con cedula;".$ced;
                                        $Notf->notmcj_ipmaquina = $ip_maquina;
                                        $Notf->notmcj_codigo = $cod;
                                        $Notf->notmcj_hora = $hora;
                                        $Notf->save();

                                        $alt = new Alertas;
                                        $alt->alert_alerta = $Notf->notmcj_descripcion;
                                        $alt->alert_fecha = $fecha_a->toDateString();
                                        $alt->alert_hora = $hora;
                                        $alt->save();

                                        //RETORNAR AL USUARIO//
                                        $usuario = asistencia::usuario_get($carnet,$fecha_a->toDateString());
                                    }
                                    else{
                                        $asistencia = asistencia::where('asi_id',$u_entrada)->update(['asi_salida' =>  $fecha_a->toDateString() ,'asi_salida_hora' => $hora ]);

                                        //RETORNAR AL USUARIO//
                                        $usuario = asistencia::usuario_get($carnet,$fecha_a->toDateString());
                                    }

                                }
                            }
                            else{
                                $asistencia = new Asistencia;
                                $asistencia->asi_entrada = $fecha_a->toDateString();
                                $asistencia->asi_carus_id =  $carnet;
                                $asistencia->asi_entrada_hora = $hora;
                                $asistencia->asi_diaf_id = $diaf;
                                $asistencia->asi_status = 1;
                                $asistencia->save();
                                $insert = $asistencia->save();
                                        
                                $dep = asistencia::b_dep($carnet,$asistencia->asi_id);

                                $ced = asistencia::b_usuario($carnet,$asistencia->asi_id);

                                $motivo = 'Registro de salida sin hora de entrada registrada del empleado con cedula:'.$ced;

                                $notificacion = notificacion_coord::insert_not($dep,$ced,$motivo,$asistencia->asi_id);

                                $Notf = new Notificaciones;
                                $Notf->notmcj_fecha = $fecha_a->toDateString();
                                $Notf->notmcj_motivo = "Ingreso al canal";
                                $Notf->notmcj_descripcion =  "Ingreso al canal fuera de su horario laboral del empleado con cedula;".$ced;
                                $Notf->notmcj_ipmaquina = $ip_maquina;
                                $Notf->notmcj_codigo = $cod;
                                $Notf->notmcj_hora = $hora;
                                $Notf->save();

                                $alt = new Alertas;
                                $alt->alert_alerta = $Notf->notmcj_descripcion;
                                $alt->alert_fecha = $fecha_a->toDateString();
                                $alt->alert_hora = $hora;
                                $alt->save();

                                //RETORNAR AL USUARIO//
                                $usuario = asistencia::usuario_get($carnet,$fecha_a->toDateString());
                            }
                        }
                    }
                }
            }
            else{
                $entrada= Asistencia::where('asi_entrada', $fecha_a->toDateString())->where('asi_carus_id', $carnet)->get(); 
                if ($entrada != '[]') {
                        if (count($entrada)>=2) {
                            $u_entrada= Asistencia::where('asi_entrada', $fecha_a->toDateString())->where('asi_carus_id', $carnet)->whereNull('asi_salida')->orderBy('asi_id','desc')->get()->pluck('asi_id')->first();
                            if (!isset($u_entrada)) {
                                $asistencia = new Asistencia;
                                $asistencia->asi_entrada = $fecha_a->toDateString();
                                $asistencia->asi_carus_id =  $carnet;
                                $asistencia->asi_entrada_hora = $hora;
                                $asistencia->asi_diaf_id = $diaf;
                                $asistencia->asi_status = 1;
                                $asistencia->save();
                                $insert = $asistencia->save();

                                //RETORNAR AL USUARIO//
                                $usuario = asistencia::usuario_get($carnet,$fecha_a->toDateString());
                            }
                            else{
                                $asistencia = asistencia::where('asi_id',$u_entrada)->update(['asi_salida' =>  $fecha_a->toDateString() ,'asi_salida_hora' => $hora ]);

                                //RETORNAR AL USUARIO//
                                $usuario = asistencia::usuario_get($carnet,$fecha_a->toDateString());
                            }
                            
                        }
                        else{
                            $u_entrada= Asistencia::where('asi_entrada', $fecha_a->toDateString())->where('asi_carus_id', $carnet)->whereNull('asi_salida')->orderBy('asi_id','desc')->get()->pluck('asi_id')->first();

                            if (!isset($u_entrada)) {
                                $asistencia = new Asistencia;
                                $asistencia->asi_entrada = $fecha_a->toDateString();
                                $asistencia->asi_carus_id =  $carnet;
                                $asistencia->asi_entrada_hora = $hora;
                                $asistencia->asi_diaf_id = $diaf;
                                $asistencia->asi_status = 1;
                                $asistencia->save();
                                $insert = $asistencia->save();

                                //RETORNAR AL USUARIO//
                                $usuario = asistencia::usuario_get($carnet,$fecha_a->toDateString());
                            }
                            else{
                                $asistencia = asistencia::where('asi_id',$u_entrada)->update(['asi_salida' =>  $fecha_a->toDateString() ,'asi_salida_hora' => $hora ]);

                                //RETORNAR AL USUARIO//
                                $usuario = asistencia::usuario_get($carnet,$fecha_a->toDateString());
                            }

                        }
                    }
                    else{
                        $asistencia = new Asistencia;
                        $asistencia->asi_entrada = $fecha_a->toDateString();
                        $asistencia->asi_carus_id =  $carnet;
                        $asistencia->asi_entrada_hora = $hora;
                        $asistencia->asi_diaf_id = $diaf;
                        $asistencia->asi_status = 1;
                        $asistencia->save();
                        $insert = $asistencia->save();

                        //RETORNAR AL USUARIO//
                        $usuario = asistencia::usuario_get($carnet,$fecha_a->toDateString());
                    }
                    
                
            }
        

        }
        else{
            #Si el codigo de barras no esta en la tabla carnets, valido en la de seriales inutilizados
           
            $cnt_inunt= Carnet_seriales_inutilizados::where('csi_cod_barra',$cod)->get();

            if (count($cnt_inunt)>0){
                // si el codigo se encuentra registrado en esta tabla debo lanzar alerta con los datos especificados al área de seguridad.
                $Notf = new Notificaciones;
                $Notf->notmcj_fecha = $fecha_a->toDateString();
                $Notf->notmcj_motivo = "Carnet Inutilizado";
                $Notf->notmcj_descripcion =  "Intento de ingreso con un carnet inutilizado.";
                $Notf->notmcj_ipmaquina = $ip_maquina;
                $Notf->notmcj_codigo = $request->codigo;
                $Notf->notmcj_hora = $hora;
                $Notf->save();

                $alt = new Alertas;
                $alt->alert_alerta = $Notf->notmcj_descripcion;
                $alt->alert_fecha = $fecha_a->toDateString();
                $alt->alert_hora = $hora;
                $alt->save();

                $usuario ='inutilizado';

           
            }

            else{
                // Si el código marcado no está registrado, Guardo y envío una notificación 
                $usuario ='null';

                $Notf = new Notificaciones;
                $Notf->notmcj_fecha = $fecha_a->toDateString();
                $Notf->notmcj_motivo = "Carnet Inválido";
                $Notf->notmcj_descripcion =  "Intento de ingreso con un carnet inválido. Código: ".$cod;
                $Notf->notmcj_ipmaquina = $ip_maquina;
                $Notf->notmcj_codigo = $request->codigo;
                $Notf->notmcj_hora = $hora;
                $Notf->save();

                $alt = new Alertas;
                $alt->alert_alerta = $Notf->notmcj_descripcion;
                $alt->alert_fecha = $fecha_a->toDateString();
                $alt->alert_hora = $hora;
                $alt->save();
            
                $mensaje=$Notf->notmcj_descripcion;
            }
        }
        return $usuario;
    }
    public function guardar_status(Request $request)
    {
       return 'guardar_status';
    }


    
    /*public function marcar_entrada(Request $request, $cod)
    {
            
        $carnet = carnet_us::where('carus_codigo',$cod)->get()->pluck('carus_id')->last();
        $cedula = carnet_us::where('carus_codigo',$cod)->get()->pluck('carus_ced')->last();
        $ip_maquina = $_SERVER['REMOTE_ADDR'];

        $dia = date('Y-m-d'); 
        $hora = new Carbon;
        $hora->toTimeString();

        //Carbon::now();
        //$hora = $hora->toTimeString();


        $users = carnet_us::where('carus_codigo', $cod)->get();

        $carnet=carnet_us::where('carus_codigo', $cod)->get()->pluck('carus_id')->last();
  

       if (count($users)>0) 
            /// Si el codigo de barras se 

       {

            $huser=carnet_us::where('carus_id', $carnet)->get()->pluck('carus_hxu_id')->last();

            $thuser=horario_x_usuario::where('hxu_id',$huser)->get()->pluck('hxu_tiho_id')->last();

            $hdias=tipos_horarios::where('tiho_id',$thuser)->get()->pluck('tiho_dias')->last();  

            $hentrada=tipos_horarios::where('tiho_id',$thuser)->get()->pluck('tiho_hora_en')->last();                

            $hsalida=tipos_horarios::where('tiho_id',$thuser)->get()->pluck('tiho_hora_sa')->last();

            $hent = Carbon::parse($hentrada);
            $hsal = Carbon::parse($hsalida);

            $hlab = $hsal->diffInHours($hent);

            $hlab =  $hlab / 2 ;

            $hlab = sprintf('%02d:%02d', (int) $hlab, fmod($hlab, 1) * 60);
            $hlab = date($hlab);

            $hlimen = $hentrada + date($hlab);

            $hlimen= date($hlimen.":00:00");

            $par= explode(':', $hlab);
            $m=$hlab[3]."".$hlab[4];

        
            $hlimen = strtotime ('+'.$m.' minute' , strtotime ( $hlimen ) ) ;
            $hlimen = date ('H:i:s' , $hlimen );

            

            if (($hora >= $hentrada) && ($hora<= $hlimen)) {
                
                $entrada= Asistencia::where('asi_entrada', $dia)->where('asi_carus_id', $carnet)->get(); 

                if (count($entrada) > 0 ) {
                    
                    $usuario = "si entrada";

                }
                else {

                    $dia_fe = dias_feriados::whereIn('diaf_feriado',[$request->fechaentrada])->get()->pluck('diaf_id')->last();

                    if ($dia_fe =='') {
                        $diaf = null;
                    }
                    else{
                        $diaf = $dia_fe;
                    }

                    $hora->subHour(4);

                    $asistencia = new Asistencia;
                    $asistencia->asi_entrada = $dia;
                    $asistencia->asi_carus_id =  $carnet;
                    $asistencia->asi_entrada_hora = $hora;
                    $asistencia->asi_diaf_id = $diaf;
                    $asistencia->save();

                    $usuario = asistencia::join('carnet_us','carus_id','=','asi_carus_id')
                    ->join('horario_x_usuario','hxu_id','=','carus_hxu_id')
                    ->join('usuarios','hxu_cedula','=','us_ced')
                    ->join('departamentos','us_dp_id','=','dp_id')
                    ->where('asi_carus_id',$carnet)
                    ->where('asi_entrada',$dia)
                    ->get();
                }
            }

            elseif (($hora > $hlimen) && ($hora<= $hsalida)) {

                $asisdia=Asistencia::where('asi_entrada', $dia)->where('asi_salida',$dia)->where('asi_carus_id', $carnet)->get(); 

                if (count($asisdia) > 0) {
                    
                    $usuario= "si salida";
                }
                else{

                    ///Verifico si tiene alguna entrda del dia que no se registre salida

                    $entrada= Asistencia::where('asi_entrada', $dia)->whereNull('asi_salida')->where('asi_carus_id', $carnet)->get(); 

                    if (count($entrada)>0) {
                        
                        #Si es así voy a actualizar el registro marcando la salida

                        $asi_id= Asistencia::where('asi_entrada', $dia)->whereNull('asi_salida')->where('asi_carus_id', $carnet)->get()->pluck('asi_id')->last();
                        $hora->subHour(4);
                        $asi = asistencia::where('asi_id', $asi_id)
                        ->update(['asi_salida' => $dia ,'asi_salida_hora' => $hora , ]);
                        $usuario = usuarios::where('us_ced',$cedula)
                        ->join('departamentos','us_dp_id','=','dp_id')
                        ->get();
                    }

                    else {

                        #Si no tiene entrada registrada en sistema voy a marcar la salida dejando la entrada vacia 
                        $dia_fe = dias_feriados::whereIn('diaf_feriado',[$request->fechaentrada])->get()->pluck('diaf_id')->last();

                        if ($dia_fe =='') {
                            $diaf = null;
                        }
                        else{
                            $diaf = $dia_fe;
                        }

                        $hora->subHour(4);
                        $asistencia = new Asistencia;
                        $asistencia->asi_entrada = $dia;
                        $asistencia->asi_salida = $dia;
                        $asistencia->asi_carus_id =  $carnet;
                        $asistencia->asi_salida_hora = $hora;
                        $asistencia->asi_diaf_id = $diaf;
                        $asistencia->save();

                        $usuario = asistencia::join('carnet_us','carus_id','=','asi_carus_id')
                        ->join('horario_x_usuario','hxu_id','=','carus_hxu_id')
                        ->join('usuarios','hxu_cedula','=','us_ced')
                        ->join('departamentos','us_dp_id','=','dp_id')
                        ->where('asi_carus_id',$carnet)
                        ->where('asi_entrada',$dia)
                        ->get();
                    }
                }
            }
            else{
                $entrada= Asistencia::where('asi_entrada', $dia)->where('asi_carus_id', $carnet)->get(); 

                if (count($entrada) > 0 ) {
                    
                    $usuario = "si entrada";

                }
                else {

                    $dia_fe = dias_feriados::whereIn('diaf_feriado',[$request->fechaentrada])->get()->pluck('diaf_id')->last();

                    if ($dia_fe =='') {
                        $diaf = null;
                    }
                    else{
                        $diaf = $dia_fe;
                    }
                    $hora->subHour(4);
                    $asistencia = new Asistencia;
                    $asistencia->asi_entrada = $dia;
                    $asistencia->asi_carus_id =  $carnet;
                    $asistencia->asi_entrada_hora = $hora;
                    $asistencia->asi_diaf_id = $diaf;
                    $asistencia->save();

                    $usuario = asistencia::join('carnet_us','carus_id','=','asi_carus_id')
                    ->join('horario_x_usuario','hxu_id','=','carus_hxu_id')
                    ->join('usuarios','hxu_cedula','=','us_ced')
                    ->join('departamentos','us_dp_id','=','dp_id')
                    ->where('asi_carus_id',$carnet)
                    ->where('asi_entrada',$dia)
                    ->get();
                }
                ///Verifico si tiene alguna entrda del dia que no se registre salida

                    $entrada= Asistencia::where('asi_entrada', $dia)->whereNull('asi_salida')->where('asi_carus_id', $carnet)->get(); 

                    if (count($entrada)>0) {
                        
                        #Si es así voy a actualizar el registro marcando la salida

                        $asi_id= Asistencia::where('asi_entrada', $dia)->whereNull('asi_salida')->where('asi_carus_id', $carnet)->get()->pluck('asi_id')->last();
                        $hora->subHour(4);
                        $asi = asistencia::where('asi_id', $asi_id)
                        ->update(['asi_salida' => $dia ,'asi_salida_hora' => $hora , ]);
                        $usuario = usuarios::where('us_ced',$cedula)
                        ->join('departamentos','us_dp_id','=','dp_id')
                        ->get();
                    }

                    else {

                        #Si no tiene entrada registrada en sistema voy a marcar la salida dejando la entrada vacia 
                        $dia_fe = dias_feriados::whereIn('diaf_feriado',[$request->fechaentrada])->get()->pluck('diaf_id')->last();

                        if ($dia_fe =='') {
                            $diaf = null;
                        }
                        else{
                            $diaf = $dia_fe;
                        }

                        $hora->subHour(4);
                        $asistencia = new Asistencia;
                        $asistencia->asi_entrada = $dia;
                        $asistencia->asi_salida = $dia;
                        $asistencia->asi_carus_id =  $carnet;
                        $asistencia->asi_salida_hora = $hora;
                        $asistencia->asi_diaf_id = $diaf;
                        $asistencia->save();

                        $usuario = asistencia::join('carnet_us','carus_id','=','asi_carus_id')
                        ->join('horario_x_usuario','hxu_id','=','carus_hxu_id')
                        ->join('usuarios','hxu_cedula','=','us_ced')
                        ->join('departamentos','us_dp_id','=','dp_id')
                        ->where('asi_carus_id',$carnet)
                        ->where('asi_entrada',$dia)
                        ->get();
                    }
            }




        }  
        else{  

            #Si el codigo de barras no esta en la tabla carnets, valido en la de seriales inutilizados
           
            $cnt_inunt= Carnet_seriales_inutilizados::where('csi_cod_barra',$cod)->get();

            if (count($cnt_inunt)>0){
                // si el codigo se encuentra registrado en esta tabla debo lanzar alerta con los datos especificados al área de seguridad.
                $hora->subHour(4);
                $Notf = new Notificaciones;
                $Notf->notmcj_fecha = $dia;
                $Notf->notmcj_motivo = "Carnet Inutilizado";
                $Notf->notmcj_descripcion =  "Intento de ingreso con un carnet inutilizado.";
                $Notf->notmcj_ipmaquina = $ip_maquina;
                $Notf->notmcj_codigo = $request->codigo;
                $Notf->notmcj_hora = $hora;
                $Notf->save();

                $alt = new Alertas;
                $alt->alert_alerta = $Notf->notmcj_descripcion;
                $alt->alert_fecha = $dia;
                $alt->alert_hora = $hora;
                $alt->save();

                $usuario ='inutilizado';

           
            }

            else{
                $hora->subHour(4);
                // Si el código marcado no está registrado, Guardo y envío una notificación 
                $usuario ='null';

                $Notf = new Notificaciones;
                $Notf->notmcj_fecha = $dia;
                $Notf->notmcj_motivo = "Carnet Inválido";
                $Notf->notmcj_descripcion =  "Intento de ingreso con un carnet inválido.";
                $Notf->notmcj_ipmaquina = $ip_maquina;
                $Notf->notmcj_codigo = $request->codigo;
                $Notf->notmcj_hora = $hora;
                $Notf->save();

                $alt = new Alertas;
                $alt->alert_alerta = $Notf->notmcj_descripcion;
                $alt->alert_fecha = $dia;
                $alt->alert_hora = $hora;
                $alt->save();
            
                $mensaje=$Notf->notmcj_descripcion;
            }


        }
        
        return response()->json($usuario);
        
    }*/
        /*$user=Adldap::search()->where('employeeNumber','=','17388014') -> get ();
         return $user;*/
        

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getCodigo(Request $request, $codigo){
        if($request->ajax()){
            $users = carnet_us::where('carus_codigo', $codigo)
            ->get();
            return response()->json($users);
        }
    }
    public function store(request $request)
    {
        $ip_maquina = $_SERVER['REMOTE_ADDR'];

         $dia = date('Y-m-d'); 

         $hora = date('H:i');

         //Carbon::now();
         //$hora = $hora->toTimeString();


        $v = \Validator::make($request->all(),[

            'codigo' =>'required',
        ]);

        if($v->fails())
        {
            return redirect()->back()->withErrors($v->errors());
        }

       $users = carnet_us::where('carus_codigo', $request->codigo)->get();

       $carus_id=carnet_us::where('carus_codigo', $request->codigo)->get()->pluck('carus_id')->last();
  

           if (count($users)>0) 
                /// Si el codigo de barras se 

           {
                $huser=carnet_us::where('carus_id', $carus_id)->get()->pluck('carus_hxu_id')->last();

                $thuser=horario_x_usuario::where('hxu_id',$huser)->get()->pluck('hxu_tiho_id')->last();

                $hdias=tipos_horarios::where('tiho_id',$thuser)->get()->pluck('tiho_dias')->last();  

                $hentrada=tipos_horarios::where('tiho_id',$thuser)->get()->pluck('tiho_hora_en')->last();                

                $hsalida=tipos_horarios::where('tiho_id',$thuser)->get()->pluck('tiho_hora_sa')->last();

                $hent = Carbon::parse($hentrada);
                $hsal = Carbon::parse($hsalida);

                $hlab = $hsal->diffInHours($hent);

                $hlab =  $hlab / 2 ;

                $hlab = sprintf('%02d:%02d', (int) $hlab, fmod($hlab, 1) * 60);
                $hlab = date($hlab);

                $hlimen = $hentrada + date($hlab);

                $hlimen= date($hlimen.":00:00");

                $par= explode(':', $hlab);
                $m=$hlab[3]."".$hlab[4];

            
                $hlimen = strtotime ('+'.$m.' minute' , strtotime ( $hlimen ) ) ;
                $hlimen = date ('H:i:s' , $hlimen );

                

                if (($hora >= $hentrada) && ($hora<= $hlimen))

                {
                    
                    $entrada= Asistencia::where('asi_entrada', $dia)->where('asi_carus_id', $carus_id)->get(); 

                    if (count($entrada) > 0 ) {
                        
                        $mensaje = "Usted ya marcó su entrada";

                    }else
                    {

                        $asistencia = new Asistencia;
                        $asistencia->asi_entrada = $dia;
                        $asistencia->asi_carus_id =  $carus_id;
                        $asistencia->asi_entrada_hora = $hora;
                        $asistencia->save();

                        $mensaje="Entrada a las".$hora."";

                    }

                    

                    
                }elseif (($hora > $hlimen) && ($hora<= $hsalida)) 

                {
                    $asisdia=Asistencia::where('asi_entrada', $dia)->where('asi_salida',$dia)->where('asi_carus_id', $carus_id)->get(); 

                    if (count($asisdia) > 0) {
                        
                        $mensaje= "Usted ya marcó salida";



                    }else

                    {
                        ///Verifico si tiene alguna entrda del dia que no se registre salida

                        $entrada= Asistencia::where('asi_entrada', $dia)->whereNull('asi_salida')->where('asi_carus_id', $carus_id)->get(); 

                        if (count($entrada)>0) {
                            
                            #Si es así voy a actualizar el registro marcando la salida

                            $asi_id= Asistencia::where('asi_entrada', $dia)->whereNull('asi_salida')->where('asi_carus_id', $carus_id)->get()->pluck('asi_id')->last();

                            $asi = asistencia::where('asi_id', $asi_id)
                            ->update(['asi_salida' => $dia ,'asi_salida_hora' => $hora , ]);

                            $mensaje="Salida a las".$hora."";


                        }else
                        {

                            #Si no tiene entrada registrada en sistema voy a marcar la salida dejando la entrada vacia 


                                    $asistencia = new Asistencia;
                                    $asistencia->asi_entrada = $dia;
                                    $asistencia->asi_salida = $dia;
                                    $asistencia->asi_carus_id =  $carus_id;
                                    $asistencia->asi_salida_hora = $hora;
                                    $asistencia->save();
                        }



                    }


                }

           }  
           else

            {  

                #Si el codigo de barras no esta en la tabla carnets, valido en la de seriales inutilizados


               
                $cnt_inunt= Carnet_seriales_inutilizados::where('csi_cod_barra',$request->codigo)->get();

                if (count($cnt_inunt)>0) 
                {
                    // si el codigo se encuentra registrado en esta tabla debo lanzar alerta con los datos especificados al área de seguridad.




               
                }

                else

                {


             /// Si el código marcado no está registrado, Guardo y envío una notificación 

                    $Notf = new Notificaciones;
                    $Notf->notmcj_fecha = $dia;
                    $Notf->notmcj_motivo = "Carnet Inválido";
                    $Notf->notmcj_descripcion =  "codigo de barras no corresponde con los registrados en sistema Contacte al área encargada";
                    $Notf->notmcj_ipmaquina = $ip_maquina;
                    $Notf->notmcj_codigo = $request->codigo;
                    $Notf->notmcj_hora = $hora;
                    $Notf->save();
                
                    $mensaje=$Notf->notmcj_descripcion;

                  // return $mensaje;
                }


            }
 
        return view('Marcaje.marcar', compact('users','asisdia','mensaje','asi_id','dia','hora'));



    }

   /* public function asistencia(){
            
           // $users = carnet_us::all();


    return view('Asistencia/marcar');

    }*/

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
