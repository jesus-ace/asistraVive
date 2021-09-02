<?php

namespace Asistencias\Http\Controllers;

use Illuminate\Http\Request;

use Asistencias\Http\Requests;
use Asistencias\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Asistencias\reposo;
use Asistencias\permiso;
use Asistencias\autorizacion;
use Asistencias\vacaciones;
use Asistencias\excepciones;
use Asistencias\ex_x_usuario;
use Asistencias\tipo_reposo;
use Asistencias\Usuarios;
use Asistencias\departamentos;
use Asistencias\auditoria;
use Carbon\Carbon;
use Asistencias\roles;
use Asistencias\acceso;
use Asistencias\dp_x_us;
use Session;
use DB;
class RepososController extends Controller
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

            $departamento = DB::table('departamentos')
            ->whereIn('dp_id',$dp_acco)
            ->orderBy('dp_nombre','asc')
            ->get();

            $excepcion =DB::table('ex_x_usuario')
            ->join('usuarios','exu_ced','=','us_ced')
            ->join('departamentos','us_dp_id','=','dp_id')
            ->join('excepciones','exu_ex_id','=','ex_id')
            ->join('reposo','ex_re_id','=','re_id')
            ->join('tipo_reposo','re_tire_id','=','tire_id')
            ->whereIn('dp_id',$dp_acco)
            ->where('re_status',1)
            ->orderBy('re_fecha_ini','desc')
            ->paginate(5);
           
            $tiporeposo = tipo_reposo::all();

            $rol = roles::where('ro_id',$_SESSION['acceso'])->get()->pluck('ro_id')->last();

            $aco_rep = acceso::join('pantallas','aco_pnt_id','=','pnt_id')
            ->where('pnt_nombre','p_reposos')
            ->where('aco_ro_id',$rol)->get()->pluck('pnt_nombre')->last();
            return view('Asistencia/Excepciones/reposos',compact('tiporeposo','excepcion','departamento','aco_rep'));
        }
        else{ 
            //Si la sesion no existe...
            Session::flash('session','A expirado la sesión');
            return view('login');
        }

    }
    public function repo_act()
    {
        session_start();

        //Toma fecha actual del servidor
        $fecha = Carbon::now();
        //y la ajustamos al formato que vamos a utilizar
        $fecha->toDateString(); 
        if (isset($_SESSION['foto'])) {

            $dp_acco = dp_x_us::where('dxu_us_ced',$_SESSION['id'])->pluck('dxu_dp_id');

            $departamento = DB::table('departamentos')
            ->whereIn('dp_id',$dp_acco)
            ->orderBy('dp_nombre','asc')
            ->get();

            $excepcion = DB::table('ex_x_usuario')
            ->join('usuarios','exu_ced','=','us_ced')
            ->join('excepciones','exu_ex_id','=','ex_id')
            ->join('reposo','ex_re_id','=','re_id')
            ->join('tipo_reposo','re_tire_id','=','tire_id')
            ->join('departamentos','us_dp_id','=','dp_id')
            ->whereIn('dp_id',$dp_acco)
            ->where('re_fecha_ini','<=',$fecha)
            ->where('re_fecha_fin','>=',$fecha)
            ->where('re_status',1)
            ->paginate(5);

            $tiporeposo = tipo_reposo::all();

            $rol = roles::where('ro_id',$_SESSION['acceso'])->get()->pluck('ro_id')->last();

            $aco_rep = acceso::join('pantallas','aco_pnt_id','=','pnt_id')
            ->where('pnt_nombre','p_reposos')
            ->where('aco_ro_id',$rol)->get()->pluck('pnt_nombre')->last();

            return view('Asistencia/Excepciones/reposos',compact('tiporeposo','excepcion','departamento','aco_rep'));
        }
        else{ 
            //Si la sesion no existe...
            Session::flash('session','A expirado la sesión');
            return view('login');
        }
    }
    public function buscaReposo(Request $request,$idR,$idUs)
    {
        if($request->ajax()){           

                $usuario = DB::table('ex_x_usuario')
                ->join('usuarios','exu_ced','=','us_ced')
                ->join('excepciones','exu_ex_id','=','ex_id')
                ->join('reposo','ex_re_id','=','re_id')
                ->join('tipo_reposo','re_tire_id','=','tire_id')
                ->join('departamentos','us_dp_id','=','dp_id')
                ->join('roles','us_ro_id','=','ro_id')            
                ->join('tipo_usuarios','us_tdu_id','=','tdu_id')
                ->where('re_id',$idR)
                ->where('us_ced',$idUs)
                ->get();
            return response()->json($usuario);
        }
    }
    public function buscaPer(Request $request,$desde,$id)
    {
        if ($request->ajax()) {
            $permisos = DB::table('ex_x_usuario')
            ->join('usuarios','exu_ced','=','us_ced')
            ->join('excepciones','exu_ex_id','=','ex_id')
            ->join('permiso','ex_per_id','=','per_id')
            ->where('per_status',1)
            ->where('us_ced',$id)
            ->where('per_fecha_ini','<=',$desde)
            ->where('per_fecha_fin','>=',$desde)->count();
            return response()->json($permisos);
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
    public function buscaRepo(Request $request,$desde,$id)
    {
        if ($request->ajax()) {
            $reposos = DB::table('ex_x_usuario')
            ->join('usuarios','exu_ced','=','us_ced')
            ->join('excepciones','exu_ex_id','=','ex_id')
            ->join('reposo','ex_re_id','=','re_id')
            ->where('re_status',1)
            ->where('us_ced',$id)
            ->where('re_fecha_ini','<=',$desde)
            ->where('re_fecha_fin','>=',$desde)
            ->count();
            return response()->json($reposos);
        }
    }
    public function buscaVac(Request $request,$desde,$id)
    {
        if ($request->ajax()) {
            $reposos = DB::table('ex_x_usuario')
            ->join('usuarios','exu_ced','=','us_ced')
            ->join('excepciones','exu_ex_id','=','ex_id')
            ->join('vacaciones','ex_vac_id','=','vac_id')
            ->where('vac_status',1)
            ->where('us_ced',$id)
            ->where('vac_fecha_ini','<=',$desde)
            ->where('vac_fecha_fin','>=',$desde)
            ->count();
            return response()->json($reposos);
        }
    }
    public function buscaAu(Request $request,$desde,$id)
    {
        if ($request->ajax()) {
            $reposos = DB::table('ex_x_usuario')
            ->join('usuarios','exu_ced','=','us_ced')
            ->join('excepciones','exu_ex_id','=','ex_id')
            ->join('autorizacion','ex_au_id','=','au_id')
            ->where('au_status',1)
            ->where('us_ced',$id)
            ->where('au_permiso','=',$desde)->count();
            return response()->json($reposos);
        }
    }
    public function tipoRe(Request $request)
    {
        if ($request->ajax()) {
            $treposo= DB::table('tipo_reposo')->get();
            return response()->json($treposo);
        }
    }
    public function getUsuarios(Request $request){
        if($request->ajax()){
            $usuario = DB::table('ex_x_usuario')
            ->join('usuarios','exu_ced','=','us_ced')
            ->join('excepciones','exu_ex_id','=','ex_id')
            ->join('reposo','ex_re_id','=','re_id')
            ->join('tipo_reposo','re_tire_id','=','tire_id')
            ->join('departamentos','us_dp_id','=','dp_id')
            ->join('roles','us_ro_id','=','ro_id')            
            ->join('tipo_usuarios','us_tdu_id','=','tdu_id')
            ->orderBy('us_nom','asc')
            ->where('re_status',1)
            ->get();
            return response()->json($usuario);
        }
    }
    public function Reposo(Request $request,$ced){
        if($request->ajax()){
            session_start();

            $dp_acco = dp_x_us::where('dxu_us_ced',$_SESSION['id'])->pluck('dxu_dp_id');
            if ($_SESSION['rol'] !='SUPER ADMINISTRADOR'){
                $dp = Usuarios::where('us_ced',$_SESSION['id'])->get()->pluck('us_dp_id')->last();
                $usuarios = Usuarios::where('us_ced',$ced)
                ->join('departamentos','us_dp_id','=','dp_id')
                ->join('roles','us_ro_id','=','ro_id')            
                ->join('tipo_usuarios','us_tdu_id','=','tdu_id')
                ->whereIn('dp_id',$dp_acco)->get();
            }
            else{
                $usuarios = Usuarios::where('us_ced',$ced)
                ->join('departamentos','us_dp_id','=','dp_id')
                ->join('roles','us_ro_id','=','ro_id')            
                ->join('tipo_usuarios','us_tdu_id','=','tdu_id')->get();
            }
            return response()->json($usuarios);
        }
    }
    public function ReposoG(Request $request,$ced){
        
        session_start();
        $dp_acco = dp_x_us::where('dxu_us_ced',$_SESSION['id'])->pluck('dxu_dp_id');

        $usuarios =DB::table('ex_x_usuario')
        ->join('usuarios','exu_ced','=','us_ced')
        ->join('departamentos','us_dp_id','=','dp_id')
        ->join('excepciones','exu_ex_id','=','ex_id')
        ->join('reposo','ex_re_id','=','re_id')
        ->join('tipo_reposo','re_tire_id','=','tire_id')
        ->where('us_ced',$ced)
        ->whereIn('dp_id',$dp_acco)
        ->where('re_status',1)
        ->get();

        return $usuarios;
    }

    public function getdpReposos(Request $request,$dp)
    {
        if($request->ajax()){
            $usuarios = DB::table('ex_x_usuario')
                ->join('usuarios','exu_ced','=','us_ced')
                ->join('excepciones','exu_ex_id','=','ex_id')
                ->join('reposo','ex_re_id','=','re_id')
                ->join('tipo_reposo','re_tire_id','=','tire_id')
                ->join('departamentos','us_dp_id','=','dp_id')
                ->join('roles','us_ro_id','=','ro_id')            
                ->join('tipo_usuarios','us_tdu_id','=','tdu_id')
                ->where('re_status',1)
                ->where('dp_id',$dp)->get();
            return response()->json($usuarios);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function buscarep()
    {
        if (isset($_POST['busca'])) {
            if (isset($_POST['cedula'])) {
                $excepcion = DB::table('ex_x_usuario')
                ->select('us_nom','us_ape','us_ced','dp_nombre','tdu_tipo','re_ce_med','re_diagnostico','re_validado','tire_tipo')
                ->join('usuarios','exu_ced','=','us_ced')
                ->join('excepciones','exu_ex_id','=','ex_id')
                ->join('reposo','ex_re_id','=','re_id')
                ->join('tipo_reposo','re_tire_id','=','tire_id')
                ->join('departamentos','us_dp_id','=','dp_id')
                ->join('roles','us_ro_id','=','ro_id')            
                ->join('tipo_usuarios','us_tdu_id','=','tdu_id')
                ->where('us_ced','=',Input::get('cedula'))
                ->get();
            }
        }
        $tiporeposo = tipo_reposo::all();
        $departamento = departamentos::all();
        return view('Asistencia/Excepciones/reposos',compact('tiporeposo','excepcion','departamento'));
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

           'centro'=>'required',
           'desde'=>'required',
           'hasta'=>'required',
           'diagnostico'=>'required',
           'validado'=>'required',
            ]);
            if($v->fails()){
                return redirect()->back()->withErrors($v->errors());
            }
        }

        session_start();
        

        if ( $request->file !='') {

            $file =$request->file('file');
            $nombre = $file->getClientOriginalName();
            $path = "storage/reposos";
            $request->file('file')->move($path,$nombre);

           // \Storage::disk('local')->put($nombre, \File::get($file));
        }
        else{
            $nombre ='';
        }

        //Registra el reposo en la tabla reposos
        $reposo = new reposo;
        //Genera el id del registro
        
        $Repprimary ='re_id';
        $reposo->re_fecha_ini = $request->desde;
        $reposo->re_fecha_fin = $request->hasta;
        $reposo->re_ce_med = $request->centro;
        $reposo->re_diagnostico = $request->diagnostico;
        $reposo->re_validado = $request->validado;
        $reposo->re_tire_id = $request->tireposo;
        $reposo->re_status = 1;
        $reposo->re_adjunto =  $nombre;
        $reposo->re_us_reg = $_SESSION['id'];
        $reposo->save();
        $insertR = $reposo->save();

        //Registra la Excepcion
        $excep = new excepciones;
        $excepprimary ='ex_id';
        $excep->ex_re_id = $reposo->re_id;
        $excep->ex_status = 1;
        $excep->save();
        $insertEx = $excep->save();

        //Asigna la excepcion al usuario
        $exu = new ex_x_usuario;
        $exuprimary ='exu_id';
        $exu->exu_ex_id = $excep->ex_id;
        $exu->exu_ced = $request->cedula;;
        $exu->save();
        $insertExu = $exu->save();
        
        //Tomamos los datos de el explorador
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        //y lo mandamos a la funcion para saber desde que explorador esta iniciando el usuario
        $navegador = $this->getBrowser($user_agent); 
        $fecha = Carbon::now();
        $fecha->toDateString();
        //Registramos el inicio de sesión en nuestra tabla de auditoría
        $aud = new auditoria;
        $audId ='aud_id';
        $aud->aud_tipo = strtoupper('agregar');
        $aud->aud_desc = strtoupper('registro de reposo');
        $aud->aud_machine_ip = $_SERVER['REMOTE_ADDR'];
        $aud->aud_machine_name = gethostname();
        $aud->aud_machine_os =PHP_OS.'-'.php_uname();
        $aud->aud_machine_explorer = $navegador;
        $aud->aud_ced = $_SESSION['id'];
        $aud->aud_fecha =$fecha;
        $aud->save();

        if ($insertR == 1 && $insertEx == 1 && $insertExu == 1) {
            Session::flash('flash_message','Registro realizado de manera exitosa');
        }
        else{
            Session::flash('session','Error al realizar registro');
        }

        return redirect()->back();
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
    public function update(Request $request)
    {
        if (Input::get('validado') =='') {
            $validado = 1;
        }
        else{
            $validado = DB::table('reposo')->where('re_id',Input::get('repId'))->get()->pluck('re_validado')->last();
        }

        if ($request->file('file') !='') {
            $file =$request->file('file');
            $nombre = $file->getClientOriginalName();
            $path = "storage/reposos";
            $request->file('file')->move($path,$nombre);
            //\Storage::disk('local')->put($nombre, \File::get($file));
        }
        else{
            $nombre = DB::table('reposo')->where('re_id',Input::get('repId'))->get()->pluck('re_adjunto')->last();
        }
        $reposo = DB::table('reposo')
        ->where('re_id',Input::get('repId'))
        ->update(['re_fecha_ini'=> $request->desde,'re_fecha_fin'=> $request->hasta,'re_diagnostico'=> $request->diagnostico,'re_validado'=> $validado,'re_tire_id'=> $request->tireposo,'re_ce_med'=> $request->centro,'re_adjunto'=> $nombre]);

        session_start();
        //Tomamos los datos de el explorador
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        //y lo mandamos a la funcion para saber desde que explorador esta iniciando el usuario
        $navegador = $this->getBrowser($user_agent);
        $fecha = Carbon::now();
        $fecha->toDateString();

        //Registramos el inicio de sesión en nuestra tabla de auditoría
        $aud = new auditoria;
        $audId ='aud_id';
        $aud->aud_tipo = strtoupper('editar');
        $aud->aud_desc = strtoupper('modificacion de reposo');
        $aud->aud_machine_ip = $_SERVER['REMOTE_ADDR'];
        $aud->aud_machine_name = gethostname();
        $aud->aud_machine_os =PHP_OS.'-'.php_uname();
        $aud->aud_machine_explorer = $navegador;
        $aud->aud_ced = $_SESSION['id'];
        $aud->aud_fecha =$fecha;
        $aud->save();

        if ($reposo == 1) {
            Session::flash('flash_message','Actualización realizada de manera exitosa');
        }
        else{
            Session::flash('session','Error al realizar actualización');
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
        //Modifica estatus de reposo
        if ($request->ajax()) {
            session_start();
            //Tomamos los datos de el explorador
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            
            //y lo mandamos a la funcion para saber desde que explorador esta iniciando el usuario
            $navegador = $this->getBrowser($user_agent);
            $fecha = Carbon::now();
            $fecha->toDateString();

            //Registramos el inicio de sesión en nuestra tabla de auditoría
            $aud = new auditoria;
            $audId ='aud_id';
            $aud->aud_tipo = strtoupper('eliminar');
            $aud->aud_desc = strtoupper('modificacion de estatus de reposo');
            $aud->aud_machine_ip = $_SERVER['REMOTE_ADDR'];
            $aud->aud_machine_name = gethostname();
            $aud->aud_machine_os =PHP_OS.'-'.php_uname();
            $aud->aud_machine_explorer = $navegador;
            $aud->aud_ced = $_SESSION['id'];
            $aud->aud_fecha =$fecha;
            $aud->save();

            $reposo = DB::table('reposo')
            ->where('re_id',$id)
            ->update(['re_status'=>0]);
            return redirect()->back();
        }
    }

    public function repActive(Request $request,$id)
    {
        //Modifica estatus de reposo
        if ($request->ajax()) {
            session_start();


            $reposo = DB::table('reposo')
            ->where('re_id',$id)
            ->update(['re_status'=> 1 ]);

            //Tomamos los datos de el explorador
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            
            //y lo mandamos a la funcion para saber desde que explorador esta iniciando el usuario
            $navegador = $this->getBrowser($user_agent);
            $fecha = Carbon::now();
            $fecha->toDateString();

            //Registramos el inicio de sesión en nuestra tabla de auditoría
            $aud = new auditoria;
            $audId ='aud_id';
            $aud->aud_tipo = strtoupper('eliminar');
            $aud->aud_desc = strtoupper('modificacion de estatus de reposo');
            $aud->aud_machine_ip = $_SERVER['REMOTE_ADDR'];
            $aud->aud_machine_name = gethostname();
            $aud->aud_machine_os =PHP_OS.'-'.php_uname();
            $aud->aud_machine_explorer = $navegador;
            $aud->aud_ced = $_SESSION['id'];
            $aud->aud_fecha =$fecha;
            $aud->save();

            return response()->json($reposo);
        }
    }
}
