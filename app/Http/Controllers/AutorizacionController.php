<?php

namespace Asistencias\Http\Controllers;

use Illuminate\Http\Request;

use Asistencias\Http\Requests;
use Asistencias\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Asistencias\tipo_autorizacion;
use Asistencias\departamentos;
use Asistencias\ex_x_usuario;
use Asistencias\autorizacion;
use Asistencias\excepciones;
use Asistencias\auditoria;
use Asistencias\Usuarios; 
use Asistencias\acceso;
use Asistencias\roles;
use Asistencias\dp_x_us;
use Carbon\Carbon;
use Session;
use DB;

class AutorizacionController extends Controller
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

            $usuarios =DB::table('ex_x_usuario')
                ->where('au_status',1)
                ->join('usuarios','exu_ced','=','us_ced')
                ->join('departamentos','us_dp_id','=','dp_id')
                ->join('roles','us_ro_id','=','ro_id')
                ->join('excepciones','exu_ex_id','=','ex_id')
                ->join('autorizacion','ex_au_id','=','au_id')
                ->whereIn('dp_id',$dp_acco)
                ->Paginate(5);
                
            $tipo_autorizacion = tipo_autorizacion::all();

            $rol = roles::where('ro_id',$_SESSION['acceso'])->get()->pluck('ro_id')->last();

            $aco_auto = acceso::join('pantallas','aco_pnt_id','=','pnt_id')
            ->where('pnt_nombre','p_autorizacion')
            ->where('aco_ro_id',$rol)->get()->pluck('pnt_nombre')->last();

            return view('Asistencia/Excepciones/autorizacion',compact('departamento','tipo_autorizacion','usuarios','aco_auto'));
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
    public function Regauto(Request $request,$idd)
    {
        if ($request->ajax()) {
            session_start();
            if ($_SESSION['rol'] !='SUPER ADMINISTRADOR') {

                $us_dp = Usuarios::where('us_ced',$_SESSION['id'])->get()->pluck('us_dp_id')->last();
                
                $us_dep = departamentos::where('dp_id',$us_dp)->get()->pluck('dp_nombre')->last(); 
                $dp_acco = dp_x_us::where('dxu_us_ced',$_SESSION['id'])->pluck('dxu_dp_id');

                $usuarios = Usuarios::join('departamentos','us_dp_id','=','dp_id')
                ->join('roles','us_ro_id','=','ro_id')            
                ->join('tipo_usuarios','us_tdu_id','=','tdu_id')
                ->whereIn('dp_id',$dp_acco)
                ->where('us_ced',$idd)
                ->get();
            }
            else{
                $usuarios = Usuarios::where('us_ced',$idd)
                ->join('departamentos','us_dp_id','=','dp_id')
                ->join('roles','us_ro_id','=','ro_id')            
                ->join('tipo_usuarios','us_tdu_id','=','tdu_id')->get();
            }
            
            return response()->json($usuarios);
        }
    }
    public function Bdia(Request $request,$fecha)
    {        
        session_start();

        $dp_acco = dp_x_us::where('dxu_us_ced',$_SESSION['id'])->pluck('dxu_dp_id');

        $usuarios =DB::table('ex_x_usuario')
            ->join('usuarios','exu_ced','=','us_ced')
            ->join('departamentos','us_dp_id','=','dp_id')
            ->join('roles','us_ro_id','=','ro_id')
            ->join('excepciones','exu_ex_id','=','ex_id')
            ->join('autorizacion','ex_au_id','=','au_id')
            ->join('tipo_autorizacion','au_tiau_id','=','tiau_id')
            ->where('au_permiso',$fecha)
            ->whereIn('dp_id',$dp_acco)
            ->get();
        
        return $usuarios;
    }
    public function Brango(Request $request,$desde,$hasta)
    {
        if ($request->ajax()) {
            session_start();

            $dp_acco = dp_x_us::where('dxu_us_ced',$_SESSION['id'])->pluck('dxu_dp_id');

            $usuarios =DB::table('ex_x_usuario')
                ->join('usuarios','exu_ced','=','us_ced')
                ->join('departamentos','us_dp_id','=','dp_id')
                ->join('roles','us_ro_id','=','ro_id')
                ->join('excepciones','exu_ex_id','=','ex_id')
                ->join('autorizacion','ex_au_id','=','au_id')
                ->join('tipo_autorizacion','au_tiau_id','=','tiau_id')
                ->where('au_status',1)
                ->whereBetween('au_permiso',[$desde,$hasta])
                ->whereIn('dp_id',$dp_acco)
                ->get();
            return response()->json($usuarios);
        }
    }
    public function Bcedula(Request $request,$ced)
    {
        if ($request->ajax()) {
            session_start();

            $dp_acco = dp_x_us::where('dxu_us_ced',$_SESSION['id'])->pluck('dxu_dp_id');

            $usuarios =DB::table('ex_x_usuario')
                ->join('usuarios','exu_ced','=','us_ced')
                ->join('departamentos','us_dp_id','=','dp_id')
                ->join('roles','us_ro_id','=','ro_id')
                ->join('excepciones','exu_ex_id','=','ex_id')
                ->join('autorizacion','ex_au_id','=','au_id')
                ->join('tipo_autorizacion','au_tiau_id','=','tiau_id')
                ->where('au_status',1)
                ->where('us_ced',$ced)
                ->whereIn('dp_id',$dp_acco)
                ->get();
            
            return response()->json($usuarios);
        }
    }
    public function BCedulaRango(Request $request,$desde,$hasta,$ced)
    {
        if ($request->ajax()) {
            session_start();
            if ($_SESSION['rol'] !='SUPER ADMINISTRADOR') {

                $dp_acco = dp_x_us::where('dxu_us_ced',$_SESSION['id'])->pluck('dxu_dp_id');
                $usuarios =DB::table('ex_x_usuario')
                ->join('usuarios','exu_ced','=','us_ced')
                ->join('departamentos','us_dp_id','=','dp_id')
                ->join('roles','us_ro_id','=','ro_id')
                ->join('excepciones','exu_ex_id','=','ex_id')
                ->join('autorizacion','ex_au_id','=','au_id')
                ->join('tipo_autorizacion','au_tiau_id','=','tiau_id')
                ->whereBetween('au_permiso',[$desde,$hasta])
                ->where('au_status',1)
                ->whereIn('dp_id',$dp_acco)
                ->where('us_ced',$ced)
                ->get();
            }
            else{

                $usuarios =DB::table('ex_x_usuario')
                ->join('usuarios','exu_ced','=','us_ced')
                ->join('departamentos','us_dp_id','=','dp_id')
                ->join('roles','us_ro_id','=','ro_id')
                ->join('excepciones','exu_ex_id','=','ex_id')
                ->join('autorizacion','ex_au_id','=','au_id')
                ->join('tipo_autorizacion','au_tiau_id','=','tiau_id')
                ->whereBetween('au_permiso',[$desde,$hasta])
                ->where('au_status',1)
                ->where('us_ced',$ced)
                ->get();
            }
            return response()->json($usuarios);
        }
    }
    public function getDepAut(Request $request,$id)
    {
        if ($request->ajax()) {

            $usuarios =DB::table('ex_x_usuario')
            ->join('usuarios','exu_ced','=','us_ced')
            ->join('departamentos','us_dp_id','=','dp_id')
            ->join('roles','us_ro_id','=','ro_id')
            ->join('excepciones','exu_ex_id','=','ex_id')
            ->join('autorizacion','ex_au_id','=','au_id')
            ->join('tipo_autorizacion','au_tiau_id','=','tiau_id')
            ->where('dp_id',$id)
            ->where('au_status',1)
            ->get();
            return response()->json($usuarios);
        }
    }
    public function BCedulaDia(Request $request,$dia,$ced)
    {
        if ($request->ajax()) {
            session_start();
            if ($_SESSION['rol'] !='SUPER ADMINISTRADOR') {

                $dp_acco = dp_x_us::where('dxu_us_ced',$_SESSION['id'])->pluck('dxu_dp_id');
                $usuarios =DB::table('ex_x_usuario')
                ->join('usuarios','exu_ced','=','us_ced')
                ->join('departamentos','us_dp_id','=','dp_id')
                ->join('roles','us_ro_id','=','ro_id')
                ->join('excepciones','exu_ex_id','=','ex_id')
                ->join('autorizacion','ex_au_id','=','au_id')
                ->join('tipo_autorizacion','au_tiau_id','=','tiau_id')
                ->where('au_permiso',$dia)
                ->where('au_status',1)
                ->where('us_ced',$ced)
                ->whereIn('dp_id',$dp_acco)
                ->get();
            }
            else{
                $usuarios =DB::table('ex_x_usuario')
                ->join('usuarios','exu_ced','=','us_ced')
                ->join('departamentos','us_dp_id','=','dp_id')
                ->join('roles','us_ro_id','=','ro_id')
                ->join('excepciones','exu_ex_id','=','ex_id')
                ->join('autorizacion','ex_au_id','=','au_id')
                ->join('tipo_autorizacion','au_tiau_id','=','tiau_id')
                ->where('au_permiso',$dia)
                ->where('au_status',1)
                ->where('us_ced',$ced)
                ->get();
            }
            
            return response()->json($usuarios);
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
        if(isset($_POST['Registrar']))
        {
            //Verifica que los campos necesarios para registrar el reposo este llenos
            $v = \Validator::make($request->all(),[

           'autorizacion'=>'required',
           'fecha'=>'required',
           'motivo'=>'required',
           'empleado'=>'required',
            ]);
            if($v->fails()){
                return redirect()->back()->withErrors($v->errors());
            }
        }

        //Registra la nueva autorización
        $auto = new autorizacion;
        //Genera el id del registro
        
        session_start();
        $id ='au_id';
        $auto->au_permiso = Input::get('fecha');
        $auto->au_desc = Input::get('motivo');
        $auto->au_tiau_id = Input::get('autorizacion');
        $auto->au_status = 1;
        $auto->au_us_reg = $_SESSION['id'];
        $auto->save();
        $insertA = $auto->save();
        //Registra la Excepcion
        $excep = new excepciones;
        $excepprimary ='ex_id';
        $excep->ex_au_id = $auto->au_id;
        $excep->ex_status = 1;
        $excep->save();
        $insertEx = $excep->save();
        //Asigna la excepcion al usuario
        $exu = new ex_x_usuario;
        $exuprimary ='exu_id';
        $exu->exu_ex_id = $excep->ex_id;
        $exu->exu_ced = $request->empleado;
        $exu->save();
        $insertExu = $exu->save();

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
        $aud->aud_desc = strtoupper('registro de autorización');
        $aud->aud_machine_ip = $_SERVER['REMOTE_ADDR'];
        $aud->aud_machine_name = gethostname();
        $aud->aud_machine_os =PHP_OS.'-'.php_uname();
        $aud->aud_machine_explorer = $navegador;
        $aud->aud_ced = $_SESSION['id'];
        $aud->aud_fecha =$fecha;
        $aud->save();

        if ($insertA == 1 && $insertEx == 1 && $insertExu == 1) {
            Session::flash('flash_message','Registro realizado de manera exitosa');
        }
        else{
            Session::flash('session','Error al realizar registro');
        }
        return redirect()->back();


    }
    public function buscaTipoaut(Request $request)
    {
        if ($request->ajax()) {
            $tipo = DB::table('tipo_autorizacion')->get();
            return response()->json($tipo);
        }
    }
    public function refrescara(Request $request)
    {
        
        if ($request->ajax()) {
             session_start();
            if ($_SESSION['rol'] !='SUPER ADMINISTRADOR') {

                $dp_acco = dp_x_us::where('dxu_us_ced',$_SESSION['id'])->pluck('dxu_dp_id');
                $usuarios =DB::table('ex_x_usuario')
                ->where('au_status',1)
                ->whereIn('dp_id',$dp_acco)
                ->join('usuarios','exu_ced','=','us_ced')
                ->join('departamentos','us_dp_id','=','dp_id')
                ->join('roles','us_ro_id','=','ro_id')
                ->join('excepciones','exu_ex_id','=','ex_id')
                ->join('autorizacion','ex_au_id','=','au_id')
                ->join('tipo_autorizacion','au_tiau_id','=','tiau_id')
                ->get();
            }
            else{
                $usuarios =DB::table('ex_x_usuario')
                ->where('au_status',1)
                ->join('usuarios','exu_ced','=','us_ced')
                ->join('departamentos','us_dp_id','=','dp_id')
                ->join('roles','us_ro_id','=','ro_id')
                ->join('excepciones','exu_ex_id','=','ex_id')
                ->join('autorizacion','ex_au_id','=','au_id')
                ->join('tipo_autorizacion','au_tiau_id','=','tiau_id')
                ->get();
            }
            
            return response()->json($usuarios);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$idAu,$idUs)
    {
        if ($request->ajax()) {
            $usuario= DB::table('ex_x_usuario')
            ->join('usuarios','exu_ced','=','us_ced')
            ->join('excepciones','exu_ex_id','=','ex_id')
            ->join('autorizacion','ex_au_id','=','au_id')
            ->join('tipo_autorizacion','au_tiau_id','=','tiau_id')
            ->join('departamentos','us_dp_id','=','dp_id')
            ->join('tipo_usuarios','us_tdu_id','=','tdu_id')
            ->join('roles','us_ro_id','=','ro_id')          
            ->where('au_id',$idAu)
            ->where('us_ced',$idUs)->get();
            return response()->json($usuario);
        }
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
    public function update(Request $request)
    {

        $autorizacion = DB::table('autorizacion')
        ->where('au_id',$request->idAu)
        ->update(['au_permiso'=> $request->fecha,'au_desc'=> $request->motivo,'au_tiau_id'=> $request->autorizacion]);

        session_start();
        $fecha = Carbon::now();
        $fecha->toDateString(); 

        //Tomamos los datos de el explorador
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        //y lo mandamos a la funcion para saber desde que explorador esta iniciando el usuario
        $navegador = $this->getBrowser($user_agent);
        //Registramos el inicio de sesión en nuestra tabla de auditoría
        $aud = new auditoria;
        $audId ='aud_id';
        $aud->aud_tipo = strtoupper('editar');
        $aud->aud_desc = strtoupper('modificacion de autorización');
        $aud->aud_machine_ip = $_SERVER['REMOTE_ADDR'];
        $aud->aud_machine_name = gethostname();
        $aud->aud_machine_os =PHP_OS.'-'.php_uname();
        $aud->aud_machine_explorer = $navegador;
        $aud->aud_ced = $_SESSION['id'];
        $aud->aud_fecha =$fecha;
        $aud->save();
        if ($autorizacion == 1) {
            Session::flash('flash_message','Actualización realizada de manera exitosa');
        }
        else{
            Session::flash('session','Error al realizar actualizaciónmm');
        }
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function delete(Request $request,$id)
    {
        //Modifica estatus horario
        if ($request->ajax()) {
            
            $autorizacion = DB::table('autorizacion')
            ->where('au_id',$id)
            ->update(['au_status'=>0]);

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
            $aud->aud_tipo = strtoupper('eliminar');
            $aud->aud_desc = strtoupper('modificacion de estatus de autorización - Desactivado');
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

    public function autoActive(Request $request,$id)
    {
        //Modifica estatus horario
        if ($request->ajax()) {
            
            $autorizacion = DB::table('autorizacion')
            ->where('au_id',$id)
            ->update(['au_status'=> 1]);

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
            $aud->aud_tipo = strtoupper('editar');
            $aud->aud_desc = strtoupper('modificacion de estatus de autorización - Activado');
            $aud->aud_machine_ip = $_SERVER['REMOTE_ADDR'];
            $aud->aud_machine_name = gethostname();
            $aud->aud_machine_os =PHP_OS.'-'.php_uname();
            $aud->aud_machine_explorer = $navegador;
            $aud->aud_ced = $_SESSION['id'];
            $aud->aud_fecha =$fecha;
            $aud->save();

            return response()->json($autorizacion);
        }
    }
}
