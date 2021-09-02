<?php

namespace Asistencias\Http\Controllers;

use Illuminate\Http\Request;
use Asistencias\Tipos_horarios;
use Asistencias\Http\Requests;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Input;
use Asistencias\Http\Controllers\Controller;
use Asistencias\departamentos;
use Asistencias\Usuarios;
use Asistencias\auditoria;
use Asistencias\horario_x_usuario;
use Asistencias\horario_historico;
use Asistencias\dp_x_us;
use Asistencias\acceso;
use Asistencias\roles;
use Carbon\Carbon;
use Session;
use DB;
class HorariosController extends Controller 
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
             
                
            $departamentos = DB::table('departamentos')->whereIn('dp_id',$dp_acco)->orderBy('dp_nombre','asc')->get();

                
            
            $horarios = DB::table('tipos_horarios')
            ->where('tiho_status',1)
            ->orderBy('tiho_id','asc')
            ->paginate(11);
            $usuarios = usuarios::all();

             $rol = roles::where('ro_id',$_SESSION['acceso'])->get()->pluck('ro_id')->last();

            $aco_hora = acceso::join('pantallas','aco_pnt_id','=','pnt_id')
            ->where('pnt_nombre','p_horarios')
            ->where('aco_ro_id',$rol)->get()->pluck('pnt_nombre')->last();

            return view('Usuario/horarios', compact('horarios','departamentos','usuarios','aco_hora'));
        }
        else{ 
            //Si la sesion no existe...
            Session::flash('session','A expirado la sesión');
            return view('login');
        } 
    }

    //Busca Los empleados del departamento para la asignacion de horarios en masa
    
    public function dptoHorario(Request $request, $dpto)
    {
        if ($request->ajax()) {
            $usuarios = DB::table('horario_x_usuario')->join('usuarios','hxu_cedula','=','us_ced')->join('departamentos','us_dp_id','=','dp_id')->where('us_dp_id', $dpto)->get();

            return response()->json($usuarios);
        }
    }
    //Asignacion de horarios a un grupo de empleados

    public function asigmasa(Request $request)
    {
        $v = \Validator::make($request->all(),[

       'empleados'=>'required',
       'horario'=>'required',
       'departamento'=>'required'
        ]);
        if($v->fails()){
            return redirect()->back()->withErrors($v->errors());
        }

        $date = Carbon::now();
        $date->toDateString();
        session_start();
        if (isset($_POST['empleados'])){
            $asignacion ='';
            $num = count($_POST['empleados']);
            $current = 0;
            foreach ($request->empleados as $key => $value) {
                if ($current < $num){

                    $hora = horario_historico::where('hh_cedula', $value)
                    ->orderBy('hh_time_reg','desc')
                    ->take(1)
                    ->get()
                    ->pluck('hh_tiho_id')
                    ->last();

                    $insert = DB::table('horario_x_usuario')
                        ->where('hxu_cedula', $value)
                        ->update(['hxu_tiho_id'=> $request->horario]);
                        
                    if ($request->horario != $hora) {
                        $h_x_h = new horario_historico;
                        $h_x_h->hh_cedula = $value;
                        $h_x_h->hh_tiho_id = $request->horario;
                        $h_x_h->hh_us_reg = $_SESSION['id'];
                        $h_x_h->hh_time_reg = $date;
                        $h_x_h->save();
                        $h_x_h_s = $h_x_h->save();
                    }
                }
                $current++;
            }

            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            $navegador = $this->getBrowser($user_agent);
            
            //Registramos en la tabla de auditoría
            $aud = new auditoria;
            $audId ='aud_id';
            $aud->aud_tipo = strtoupper('AGREGAR');
            $aud->aud_desc = strtoupper('asignacion en masa');
            $aud->aud_machine_ip = $_SERVER['REMOTE_ADDR'];
            $aud->aud_machine_name = gethostname();
            $aud->aud_machine_os =PHP_OS.'-'.php_uname();
            $aud->aud_machine_explorer = $navegador;
            $aud->aud_ced = $_SESSION['id'];
            $aud->aud_fecha = $date;
            $aud->save();

            if ($insert == 1) {
                   Session::flash('flash_message','Asignación realizada con exito');
                
            }
            else{
                Session::flash('session','Error al realizar la asignación');
            }
            return redirect()->back();
        }
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
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $v = \Validator::make($request->all(),[

       'dias'=>'required',
       'time1'=>'required',
       'time2'=>'required'
        ]);
        if($v->fails()){
            return redirect()->back()->withErrors($v->errors());
        }
        if (isset($_POST['registrar'])) {
            if (isset($_POST['dias'])){
                $selected ='';
                $num_dias = count($_POST['dias']);
                $current = 0;
                foreach ($_POST['dias'] as $key => $value) {
                    if ($current != $num_dias-1)
                        $selected.=$value.',';
                    else
                        $selected.=$value;
                    $current++;
                }
            }
            else {
                $selected ='Debe seleccionar un dia'; 
                return redirect()->back()->with('selected') ;
            }
            $hora_en = $_POST['time1'];
            $hora_sa = $_POST['time2'];
            if ($hora_en <='18:00'){
                $tur = "Diurno";
            }
            else{
                $tur = "Nocturno";
            }
        }
            $horario = new Tipos_horarios;
            $primaryKey ='tiho_id';
            $horario->tiho_dias =$selected;
            $horario->tiho_hora_en = $hora_en;
            $horario->tiho_hora_sa = $hora_sa;
            $horario->tiho_turno = $tur;
            $horario->tiho_status = 1;
            $horario->tiho_holgura_entrada = $request->holgura_en;
            $horario->tiho_holgura_salida = $request->holgura_sa;
            $horario->save();
            $insertar = $horario->save();

            session_start();

            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            $navegador = $this->getBrowser($user_agent);
            
            $fecha = Carbon::now();
            $fecha->toDateString(); 
            //Registramos en la tabla de auditoría
            $aud = new auditoria;
            $audId ='aud_id';
            $aud->aud_tipo = strtoupper('agregar');
            $aud->aud_desc = strtoupper('registro de horario');
            $aud->aud_machine_ip = $_SERVER['REMOTE_ADDR'];
            $aud->aud_machine_name = gethostname();
            $aud->aud_machine_os =PHP_OS.'-'.php_uname();
            $aud->aud_machine_explorer = $navegador;
            $aud->aud_ced = $_SESSION['id'];
            $aud->aud_fecha =$fecha;
            $aud->save();

            if ($insertar == 1 ) {
            Session::flash('flash_message','Horario registrado con exito');
            }
            else{
                Session::flash('session','Error al realizar el registro');
            }
            return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {        
        if (isset($_POST['modificar'])) {
            if (isset($_POST['dias'])){
                $selected ='';
                $num_dias = count($_POST['dias']);
                $current = 0;
                foreach ($_POST['dias'] as $key => $value) {
                    if ($current != $num_dias-1)
                        $selected .= $value.',';
                    else
                        $selected .= $value;
                    $current++;
                }
            }
            else
                $selected = DB::table('tipos_horarios')->where('tiho_id', Input::get('id'))->get()->pluck('tiho_dias')->last();

            $hora_en = $_POST['time1'];
            $hora_sa = $_POST['time2'];


            if ($hora_en <='18:00'){
                $tur = "Diurno";
            }
            else{
                $tur = "Nocturno";
            }
            $horario = DB::table('tipos_horarios')
            ->where('tiho_id', Input::get('id'))
            ->update(['tiho_dias'=> $selected,'tiho_hora_en'=>$hora_en ,'tiho_hora_sa'=> $hora_sa,'tiho_holgura_entrada'=> $_POST['holgura_en'],'tiho_holgura_salida'=> $_POST['holgura_sa'],'tiho_turno'=>$tur,'tiho_status'=>1]);

            session_start();

            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            $navegador = $this->getBrowser($user_agent);

            $fecha = Carbon::now();
            $fecha->toDateString(); 

            //Registramos en la tabla de auditoría
            $aud = new auditoria;
            $audId ='aud_id';
            $aud->aud_tipo = strtoupper('EDITAR');
            $aud->aud_desc = strtoupper('MODIFICACION de horario');
            $aud->aud_machine_ip = $_SERVER['REMOTE_ADDR'];
            $aud->aud_machine_name = gethostname();
            $aud->aud_machine_os =PHP_OS.'-'.php_uname();
            $aud->aud_machine_explorer = $navegador;
            $aud->aud_ced = $_SESSION['id'];
            $aud->aud_fecha =$fecha;
            $aud->save();
            if ($horario == 1 ) {
                Session::flash('flash_message','Horario actualizado con exito');
            }
            else{
                Session::flash('session','Error al realizar el actualización');
            }
            return redirect()->back();
        }
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
        if ($request->ajax()) {
            $horario = tipos_horarios::where('tiho_id', $id)->get();
            return response()->json($horario);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        //Modifica horario
        if ($request->ajax()) {

            $horario = DB::table('tipos_horarios')
            ->where('tiho_id', $id)
            ->update(['tiho_status'=>0]);
            session_start();

            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            $navegador = $this->getBrowser($user_agent);
            $fecha = Carbon::now();
            $fecha->toDateString(); 
            //Registramos en la tabla de auditoría
            $aud = new auditoria;
            $audId ='aud_id';
            $aud->aud_tipo = strtoupper('eliminar');
            $aud->aud_desc = strtoupper('MODIFICACION de estatus de horario - Desactivado');
            $aud->aud_machine_ip = $_SERVER['REMOTE_ADDR'];
            $aud->aud_machine_name = gethostname();
            $aud->aud_machine_os =PHP_OS.'-'.php_uname();
            $aud->aud_machine_explorer = $navegador;
            $aud->aud_ced = $_SESSION['id'];
            $aud->aud_fecha =$fecha;
            $aud->save();
            return redirect()->back();
        }

    }

    public function horaActive(Request $request, $id)
    {
        //Modifica horario
        if ($request->ajax()) {

            $horario = DB::table('tipos_horarios')
            ->where('tiho_id', $id)
            ->update(['tiho_status'=>1]);
            session_start();

            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            $navegador = $this->getBrowser($user_agent);
            $fecha = Carbon::now();
            $fecha->toDateString(); 
            //Registramos en la tabla de auditoría
            $aud = new auditoria;
            $audId ='aud_id';
            $aud->aud_tipo = strtoupper('editar');
            $aud->aud_desc = strtoupper('MODIFICACION de estatus de horario - Activado');
            $aud->aud_machine_ip = $_SERVER['REMOTE_ADDR'];
            $aud->aud_machine_name = gethostname();
            $aud->aud_machine_os =PHP_OS.'-'.php_uname();
            $aud->aud_machine_explorer = $navegador;
            $aud->aud_ced = $_SESSION['id'];
            $aud->aud_fecha =$fecha;
            $aud->save();

            return response()->json($horario);
        }

    }



}