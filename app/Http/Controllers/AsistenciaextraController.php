<?php

namespace Asistencias\Http\Controllers;

use Illuminate\Http\Request;

use Asistencias\Http\Requests;
use Asistencias\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Asistencias\tipos_horarios;
use Asistencias\roles;
use Asistencias\tipo_usuario;
use Asistencias\departamentos;
use Asistencias\Asistencia;
use Asistencias\unidadadmin;
use Asistencias\carnet_us;
use Asistencias\Usuarios;
use Asistencias\auditoria;
use Asistencias\dias_feriados;
use Asistencias\acceso;
use Asistencias\pantallas;
use Asistencias\dp_x_us;
use Carbon\Carbon;
use Session;
use DB;

class AsistenciaextraController extends Controller
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

            $us_dp = Usuarios::where('us_ced',$_SESSION['id'])->get()->pluck('us_dp_id')->last();
            $us_dep = departamentos::where('dp_id',$us_dp)->get()->pluck('dp_nombre')->last(); 
            $dp_acco = dp_x_us::where('dxu_us_ced',$_SESSION['id'])->pluck('dxu_dp_id');
            
            $departamento = DB::table('departamentos')
            ->whereIn('dp_id',$dp_acco)
            ->orderBy('dp_nombre','asc')
            ->get();
            $usuarios = DB::table('carnet_us')   
            ->join ('horario_x_usuario','carus_hxu_id','=','hxu_id')
            ->join ('usuarios','hxu_cedula','=','us_ced') 
            ->join('roles','us_ro_id','=','ro_id') 
            ->join('departamentos','us_dp_id','=','dp_id')
            ->join ('tipos_horarios','hxu_tiho_id','=','tiho_id')       
            ->join('tipo_usuarios','us_tdu_id','=','tdu_id')
            ->orderBy('us_nom','asc')
            ->whereIn('dp_id',$dp_acco)
            ->where('us_status',1)
            ->Paginate(5);            

            $rol = roles::where('ro_id',$_SESSION['acceso'])->get()->pluck('ro_id')->last();

            $aco_emp = acceso::join('pantallas','aco_pnt_id','=','pnt_id')
            ->where('aco_ro_id',$rol)->where('pnt_nombre','p_asistenciae')->get()->pluck('pnt_nombre')->last();

            return view('Asistencia/asistenciaextra',compact('departamento','usuarios','aco_emp'));
        }
        else{ 
            //Si la sesion no existe...
            Session::flash('session','A expirado la sesión');
            return view('login');
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

    public function getDatos(Request $request,$id){
        if($request->ajax()){
            $usuarios = carnet_us::where('us_ced',$id)
            ->join ('horario_x_usuario','carus_hxu_id','=','hxu_id')
            ->join ('usuarios','hxu_cedula','=','us_ced')
            ->join ('tipos_horarios','hxu_tiho_id','=','tiho_id')
            ->join('departamentos','us_dp_id','=','dp_id')
            ->where('us_status',1)
            ->get();
            return response()->json($usuarios);
        }
    }
    public function getDatos2(Request $request,$id){
        if($request->ajax()){
            $usuarios = asistencia::where('us_ced',$id)
            ->whereNull('asi_salida')
            ->join ('carnet_us','asi_carus_id','=','carus_id')
            ->join ('horario_x_usuario','carus_hxu_id','=','hxu_id')
            ->join ('usuarios','hxu_cedula','=','us_ced')
            ->join ('tipos_horarios','hxu_tiho_id','=','tiho_id')
            ->join('departamentos','us_dp_id','=','dp_id')
            ->orderBy('asi_entrada','desc')
            ->where('us_status',1)
            ->get();
            return response()->json($usuarios);
        }
    }
    public function getAsiDate(Request $request,$fecha,$id)
    {
       $usuarios = asistencia::where('us_ced',$id)
            ->where('asi_entrada',$fecha)
            ->whereNull('asi_salida')
            ->join ('carnet_us','asi_carus_id','=','carus_id')
            ->join ('horario_x_usuario','carus_hxu_id','=','hxu_id')
            ->join ('usuarios','hxu_cedula','=','us_ced')
            ->join ('tipos_horarios','hxu_tiho_id','=','tiho_id')
            ->join('departamentos','us_dp_id','=','dp_id')
            ->orderBy('asi_entrada','desc')
            ->where('us_status',1)
            ->get();
        return $usuarios;
    }
    public function getDatos3(Request $request,$id){
        if($request->ajax()){
            $usuarios = asistencia::where('us_ced',$id)
            ->whereNull('asi_entrada_hora')
            ->join ('carnet_us','asi_carus_id','=','carus_id')
            ->join ('horario_x_usuario','carus_hxu_id','=','hxu_id')
            ->join ('usuarios','hxu_cedula','=','us_ced')
            ->join ('tipos_horarios','hxu_tiho_id','=','tiho_id')
            ->join('departamentos','us_dp_id','=','dp_id')
            ->orderBy('asi_entrada','desc')
            ->where('us_status',1)
            ->get();
            return response()->json($usuarios);
        }
    }
     
    public function  registrarentrada(Request $request){
        
        //Tomamos los datos de el explorador
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        //y lo mandamos a la funcion para saber desde que explorador esta iniciando el usuario
        $navegador = $this->getBrowser($user_agent);
        if(isset($_POST['Registrar']))
        {
            //Verifica que los campos necesarios para registrar el reposo este llenos
            $v = \Validator::make($request->all(),[

           'horaentrada'=>'required',
           'codigo'=>'required',
            ]);
            if($v->fails()){
                return redirect()->back()->withErrors($v->errors());
            }
        }
        if ($request->salidasinentrada !='') {
            echo'salida sin hora entrada'.$request->salidasinentrada;


            $codigo2 = carnet_us::where('carus_codigo',$request->codigo)->get()->pluck('carus_id')->last();

            $insert = asistencia::where('asi_carus_id',$codigo2 )
            ->where('asi_entrada',$request->salidasinentrada)
            ->whereNull('asi_entrada_hora')
            ->update(['asi_entrada_hora'=> $request->horaentrada]);
            $cedula = carnet_us::where('carus_codigo',$request->codigo)->get()->pluck('carus_ced')->last();

            $fecha = Carbon::now();
            $fecha->toDateString(); 
            //Registramos el inicio de sesión en nuestra tabla de auditoría
            $aud = new auditoria;
            $audId ='aud_id';
            $aud->aud_tipo = strtoupper('Agregar');
            $aud->aud_desc = strtoupper('registro de entrada del empleado:'.$cedula);
            $aud->aud_machine_ip = $_SERVER['REMOTE_ADDR'];
            $aud->aud_machine_name = gethostname();
            $aud->aud_machine_os =PHP_OS.'-'.php_uname();
            $aud->aud_machine_explorer = $navegador;
            $aud->aud_ced = $_SESSION['id'];
            $aud->aud_fecha =$fecha;
            $aud->save();

        }
        else{
            $dia_fe = dias_feriados::where('diaf_feriado',Input::get('fechaentrada'))->get()->pluck('diaf_id')->last();
            $cedula = carnet_us::where('carus_codigo',$request->codigo)->get()->pluck('carus_ced')->last();

            if ($dia_fe =='') {
                $diaf = null;
            }
            else{
                $diaf = $dia_fe;
            }

            session_start();
           
            $asi = new Asistencia;
            $asiprimary ='asi_id';
            $asi->asi_entrada = Input::get('fechaentrada');
            $asi->asi_entrada_hora = Input::get('horaentrada');
            $asi->asi_carus_id = carnet_us::where('carus_codigo',Input::get('codigo'))->get()->pluck('carus_id')->last();
            $asi->asi_diaf_id = $diaf;
            $asi->asi_status = 1;
            $asi->save();
            $insert= $asi->save();
            
            $fecha = Carbon::now();

            $fecha->toDateString(); 
            //Registramos el inicio de sesión en nuestra tabla de auditoría
            $aud = new auditoria;
            $audId ='aud_id';
            $aud->aud_tipo = strtoupper('Agregar');
            $aud->aud_desc = strtoupper('registro de entrada del empleado:'.$cedula);
            $aud->aud_machine_ip = $_SERVER['REMOTE_ADDR'];
            $aud->aud_machine_name = gethostname();
            $aud->aud_machine_os =PHP_OS.'-'.php_uname();
            $aud->aud_machine_explorer = $navegador;
            $aud->aud_ced = $_SESSION['id'];
            $aud->aud_fecha =$fecha;
            $aud->save();
        
        }

        if ($insert == 1) {
            Session::flash('flash_message','Registro de entrada realizado con exito');
        }
        else{
            Session::flash('session','Error al realizar el registro');
        }
        
        return redirect()->back();
    }   


    public function  registrarsalida(Request $request){
        
        if(isset($_POST['Registrar']))
        {
            //Verifica que los campos necesarios para registrar el reposo este llenos
            $v = \Validator::make($request->all(),[

           'fechasalida'=>'required',
           'horasalida'=>'required',
           'codigo'=>'required',
           'entradasinsalida'=>'required',
            ]);
            if($v->fails()){
                return redirect()->back()->withErrors($v->errors());
            }
        }
        //donde la fecha tambien ¬¬
        $codigo2 = carnet_us::where('carus_codigo',$request->codigo)->get()->pluck('carus_id')->last();
        $cedula = carnet_us::where('carus_codigo',$request->codigo)->get()->pluck('carus_ced')->last();
       // $diaE = asistencia::where('asi_entrada',)->pluck('carus_id');
        $dia = Carbon::now();
        $dia->toDateString();

        $asi = asistencia::where('asi_entrada',$request->entradasinsalida)
        ->where('asi_id',$request->asi_idd)
        ->update(['asi_salida_hora'=> $request->horasalida,'asi_salida'=> $request->fechasalida,]);

        session_start();
        //Tomamos los datos de el explorador
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        //y lo mandamos a la funcion para saber desde que explorador esta iniciando el usuario
        $navegador = $this->getBrowser($user_agent);
        //Registramos el inicio de sesión en nuestra tabla de auditoría
        $fecha = Carbon::now();
        $fecha->toDateString(); 

        $aud = new auditoria;
        $audId ='aud_id';
        $aud->aud_tipo = strtoupper('agregar');
        $aud->aud_desc = strtoupper('registro de salida del empleado:'.$cedula);
        $aud->aud_machine_ip = $_SERVER['REMOTE_ADDR'];
        $aud->aud_machine_name = gethostname();
        $aud->aud_machine_os =PHP_OS.'-'.php_uname();
        $aud->aud_machine_explorer = $navegador;
        $aud->aud_ced = $_SESSION['id'];
        $aud->aud_fecha =$fecha;
        $aud->save();

        if ($asi == 1) {
            Session::flash('flash_message','Registro de salida realizado con exito');
        }
        else{
            Session::flash('session','Error al realizar el registro');
        }

        return redirect()->back();
    }

    public function LimpiarAsistencia ()
    {
        DB::table('asistencia')->truncate();
        return redirect()->back();
    }

    public function getUsuariosdp(Request $request,$id){
        if($request->ajax()){
            $usuarios = carnet_us::where('dp_id',$id)
            ->join ('horario_x_usuario','carus_hxu_id','=','hxu_id')
            ->join ('usuarios','hxu_cedula','=','us_ced')
            ->join ('tipos_horarios','hxu_tiho_id','=','tiho_id')
            ->join('departamentos','us_dp_id','=','dp_id')
            ->join('tipo_usuarios','us_tdu_id','=','tdu_id')
            ->where('us_status',1)
            ->get();
            return response()->json($usuarios);
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function buscar()
    {
        if(isset($_POST['buscar'])){
            if (isset($_POST['cedula'])) {
                $usuarios = DB::table('usuarios')
                ->select('us_id','us_nom','us_ape','us_ced','sex_sexo','dp_nombre','ro_nom','tdu_tipo')
                ->join('departamentos','us_dp_id','=','dp_id')
                ->join('sexo','us_sex_id','=','sex_id')
                ->join('roles','us_ro_id','=','ro_id')            
                ->join('tipo_usuarios','us_tdu_id','=','tdu_id')
                ->where('us_status',1)
                ->where('us_ced',Input::get('cedula'))
                ->Paginate(5);
            }
            $departamento = departamentos::pluck('dp_nombre','dp_id');
            return view('Asistencia/asistenciaextra',compact('usuarios','departamento'));
        }
    }
    public function getUsuarioc(Request $request,$ced){
        //if($request->ajax()){
            session_start();
             //if ($_SESSION['rol'] !='SUPER ADMINISTRADOR') {
            $us_dp = Usuarios::where('us_ced',$_SESSION['id'])->get()->pluck('us_dp_id')->last();
            $us_dep = departamentos::where('dp_id',$us_dp)->get()->pluck('dp_nombre')->last(); 

            $dp_acco = dp_x_us::where('dxu_us_ced',$_SESSION['id'])->pluck('dxu_dp_id');

            $usuarios =carnet_us::where('us_ced',$ced)
                ->join ('horario_x_usuario','carus_hxu_id','=','hxu_id')
                ->join ('usuarios','hxu_cedula','=','us_ced')
                ->join ('tipos_horarios','hxu_tiho_id','=','tiho_id')
                ->join('roles','us_ro_id','=','ro_id')            
                ->join('tipo_usuarios','us_tdu_id','=','tdu_id')
                ->join('departamentos','us_dp_id','=','dp_id')
                ->whereIn('dp_id',$dp_acco)
                ->where('us_status',1)
                ->get();                

            return $usuarios;
        //}
    }

}
