<?php

namespace Asistencias\Http\Controllers;

use Illuminate\Http\Request;

use Asistencias\Http\Requests;
use Illuminate\Support\Facades\Input;
use Asistencias\Http\Controllers\Controller;
use Asistencias\departamentos;
use Asistencias\roles;
use Asistencias\sexo;
use Asistencias\Usuarios;
use Asistencias\permiso;
use Asistencias\excepciones;
use Asistencias\ex_x_usuario;
use Asistencias\auditoria;
use Carbon\Carbon;
use Asistencias\acceso;
use Asistencias\dp_x_us;
use Session;
use DB;

class PermisosController extends Controller
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

            $us_dp = Usuarios::where('us_ced', $_SESSION['id'])->get()->pluck('us_dp_id')->last();
            $us_dep = departamentos::where('dp_id',$us_dp)->get()->pluck('dp_nombre')->last(); 
            $excepcion = permiso::permiso_acco($us_dep);
            $departamento = departamentos::departamento_acco($us_dep);
               
            $rol = roles::where('ro_id',$_SESSION['acceso'])->get()->pluck('ro_id')->last();

            $aco_per = acceso::join('pantallas','aco_pnt_id','=','pnt_id')
            ->where('pnt_nombre','p_permisos')
            ->where('aco_ro_id',$rol)->get()->pluck('pnt_nombre')->last();

            return view('Asistencia/Excepciones/permisos', compact('departamento','excepcion','aco_per'));

        }
        else{ 
            //Si la sesion no existe...
            Session::flash('session','A expirado la sesión');
            return view('login');
        }
    }
    public function buscaCedPer(Request $request, $ced)
    {
            session_start();

            $dp_acco = dp_x_us::where('dxu_us_ced',$_SESSION['id'])->pluck('dxu_dp_id');

            $usuarios =DB::table('ex_x_usuario')
            ->join('usuarios','exu_ced','=','us_ced')
            ->join('departamentos','us_dp_id','=','dp_id')
            ->join('excepciones','exu_ex_id','=','ex_id')
            ->join('permiso','ex_per_id','=','per_id')
            ->where('us_ced',$ced)           
            ->whereIn('dp_id',$dp_acco)
            ->where('per_status',1)
            ->get();
            
            return $usuarios;
        }

    public function permi_act()
    {
        session_start();
        if (isset($_SESSION['foto'])) {
            //Toma fecha actual del servidor
            $fecha = Carbon::now();
            //y la ajustamos al formato que vamos a utilizar
            $fecha->toDateString();

            $dp_acco = dp_x_us::where('dxu_us_ced',$_SESSION['id'])->pluck('dxu_dp_id');

            $departamento = DB::table('departamentos')     
            ->whereIn('dp_id',$dp_acco)
            ->orderBy('dp_nombre','asc')
            ->get();

            $excepcion = DB::table('ex_x_usuario')
            ->join('usuarios','exu_ced','=','us_ced')
            ->join('departamentos','us_dp_id','=','dp_id')
            ->join('roles','us_ro_id','=','ro_id')
            ->join('excepciones','exu_ex_id','=','ex_id')
            ->join('permiso as per','ex_per_id','=','per_id')
            ->where('per_fecha_ini','<=',$fecha)
            ->where('per_fecha_fin','>=', $fecha)     
            ->whereIn('dp_id',$dp_acco)
            ->orderBy('us_nom','asc')
            ->where('per_status',1)
            ->Paginate(5);

            
            $rol = roles::where('ro_id',$_SESSION['acceso'])->get()->pluck('ro_id')->last();
            $aco_per = acceso::join('pantallas','aco_pnt_id','=','pnt_id')
            ->where('pnt_nombre','p_permisos')
            ->where('aco_ro_id',$rol)->get()->pluck('pnt_nombre')->last();

            return view('Asistencia/Excepciones/permisos', compact('departamento','excepcion','aco_per'));
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
    public function refrescarP(Request $request)
    {
        if($request->ajax()){
            session_start();
            $dp_acco = dp_x_us::where('dxu_us_ced',$_SESSION['id'])->pluck('dxu_dp_id');
            if ($_SESSION['rol'] !='SUPER ADMINISTRADOR') {
                $dp = Usuarios::where('us_ced', $_SESSION['id'])->get()->pluck('us_dp_id')->last();
                $excepcion = DB::table('ex_x_usuario')
                ->join('usuarios','exu_ced','=','us_ced')
                ->join('departamentos','us_dp_id','=','dp_id')
                ->join('roles','us_ro_id','=','ro_id')
                ->join('excepciones','exu_ex_id','=','ex_id')
                ->join('permiso','ex_per_id','=','per_id')
                ->where('per_status',1)     
                ->whereIn('dp_id',$dp_acco)
                ->get();
            }
            else{
                $excepcion = DB::table('ex_x_usuario')
                ->join('usuarios','exu_ced','=','us_ced')
                ->join('departamentos','us_dp_id','=','dp_id')
                ->join('roles','us_ro_id','=','ro_id')
                ->join('excepciones','exu_ex_id','=','ex_id')
                ->join('permiso','ex_per_id','=','per_id')
                ->where('per_status',1)
                ->get();
            }
            
            return response()->json($excepcion);
        }
    }
    public function getdepp(Request $request, $id)
    {
        if($request->ajax()){
            
            $excepcion = DB::table('ex_x_usuario')
            ->join('usuarios','exu_ced','=','us_ced')
            ->join('departamentos','us_dp_id','=','dp_id')
            ->join('roles','us_ro_id','=','ro_id')
            ->join('excepciones','exu_ex_id','=','ex_id')
            ->join('permiso','ex_per_id','=','per_id')
            ->where('dp_id',$id)
            ->where('per_status',1)
            ->get();
            return response()->json($excepcion);
        }
    }
    public function buscaPer(Request $request,$desde,$id)
    {
        if ($request->ajax()) {
            $permisos = DB::table('ex_x_usuario')
            ->join('usuarios','exu_ced','=','us_ced')
            ->join('excepciones','exu_ex_id','=','ex_id')
            ->join('permiso','ex_per_id','=','per_id')
            ->where('us_ced',$id)
            ->where('per_fecha_ini','<=',$desde)
            ->where('per_fecha_fin','>=',$desde)->count();
            return response()->json($permisos);
        }
    }
    public function buscaRepo(Request $request,$desde,$id)
    {
        if ($request->ajax()) {
            $reposos = DB::table('ex_x_usuario')
            ->join('usuarios','exu_ced','=','us_ced')
            ->join('excepciones','exu_ex_id','=','ex_id')
            ->join('reposo','ex_re_id','=','re_id')
            ->where('us_ced', $id)
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
            ->where('us_ced',$id)
            ->where('au_permiso','=',$desde)->count();
            return response()->json($reposos);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function permiso(Request $request, $ced){
        if($request->ajax()){
            session_start();
            if ($_SESSION['rol'] !='SUPER ADMINISTRADOR') {

                $us_dp = Usuarios::where('us_ced',$_SESSION['id'])->get()->pluck('us_dp_id')->last();

                $us_dep = departamentos::where('dp_id',$us_dp)->get()->pluck('dp_nombre')->last(); 
                

                $usuarios = Usuarios::where('us_ced',$ced)
                ->join('departamentos','us_dp_id','=','dp_id')
                ->where('dp_nombre',$us_dep)
                ->get();
               
            }
            else{
                $departamento = DB::table('departamentos')->orderBy('dp_nombre','asc')->get();

                $usuarios = Usuarios::where('us_ced',$ced)
                ->join('departamentos','us_dp_id','=','dp_id')
                ->get();
                
            }            
            return response()->json($usuarios);
        }
    }
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
    public function store(Request $request)
    {
        if(isset($_POST['Registrar']))
        {
            //Verifica que los campos necesarios para registrar el permiso este llenos
            $v = \Validator::make($request->all(),[

           'descripcion'=>'required',
           'desde'=>'required',
           'hasta'=>'required',
           'remunerado'=>'required',
            ]);
            if($v->fails()){
                return redirect()->back()->withErrors($v->errors());
            }
        }
        session_start();
        //Registra el permiso
        if ( $request->file !='') {

            $file =$request->file('file');
            $nombre = $file->getClientOriginalName();

            //\Storage::disk('local')->put($nombre,  \File::get($file));
            $path = "storage/permisos";
            $request->file('file')->move($path, $nombre);
        }
        else{
            $nombre ='';
        }


        $permiso = new permiso;
        $id ='per_id';
        $permiso->per_fecha_ini = Input::get('desde');
        $permiso->per_fecha_fin = Input::get('hasta');
        $permiso->per_desc = Input::get('descripcion');
        $permiso->per_remunerado = Input::get('remunerado');
        $permiso->per_status = 1;
        $permiso->per_us_reg = $_SESSION['id'];
        $permiso->per_adjunto = $nombre;
        $permiso->save();
        $insertP = $permiso->save();
        //Registra el permiso como una excepcion

        $exc = new excepciones;
        $excid ='ex_id';
        $exc->ex_per_id = $permiso->per_id;
        $exc->ex_status = 1;
        $exc->save();
        $insertEx = $exc->save();
        //Asigna la excepcion al usuario

        $exu = new ex_x_usuario;
        $exu->exu_ex_id = $exc->ex_id;
        $exu->exu_ced = $request->id;
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
        $aud->aud_desc = strtoupper('registro de permiso');
        $aud->aud_machine_ip = $_SERVER['REMOTE_ADDR'];
        $aud->aud_machine_name = gethostname();
        $aud->aud_machine_os =PHP_OS.'-'.php_uname();
        $aud->aud_machine_explorer = $navegador;
        $aud->aud_ced = $_SESSION['id'];
        $aud->aud_fecha =$fecha;
        $aud->save();

        if ($insertP == 1 && $insertEx ==1 && $insertExu == 1) {
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
    public function show(Request $request, $idP,$idUs)
    {
        if($request->ajax()){
            $excepcion = ex_x_usuario::where('us_ced',$idUs)
            ->join('usuarios','exu_ced','=','us_ced')
            ->join('departamentos','us_dp_id','=','dp_id')
            ->join('roles','us_ro_id','=','ro_id')
            ->join('excepciones','exu_ex_id','=','ex_id')
            ->join('permiso','ex_per_id','=','per_id')
            ->where('per_id',$idP)->get();
            return response()->json($excepcion);
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
        if (Input::get('remunerado') =='') {
            $remunerado = 1;
        }
        else{
            $remunerado = Input::get('remunerado');
        }


        if ( $request->file !='') {

            $file =$request->file('file');
            $nombre = $file->getClientOriginalName();
            
            $path = "storage/permisos";
            $request->file('file')->move($path, $nombre);

           // \Storage::disk('local')->put($nombre,  \File::get($file));
        }
        else{
            $nombre = DB::table('permiso')->where('per_id',Input::get('perId'))->get()->pluck('per_adjunto')->last();
        }

        $permiso = DB::table('permiso')
        ->where('per_id', Input::get('perId'))
        ->update(['per_fecha_ini'=> Input::get('desde'),'per_fecha_fin'=> Input::get('hasta'),'per_desc'=> Input::get('descripcion'),'per_remunerado'=> $remunerado,'per_adjunto'=> $nombre]);

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
        $aud->aud_desc = strtoupper('modificacion de permiso');
        $aud->aud_machine_ip = $_SERVER['REMOTE_ADDR'];
        $aud->aud_machine_name = gethostname();
        $aud->aud_machine_os =PHP_OS.'-'.php_uname();
        $aud->aud_machine_explorer = $navegador;
        $aud->aud_ced = $_SESSION['id'];
        $aud->aud_fecha =$fecha;
        $aud->save();
        if ($permiso == 1) {
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
    public function delete(Request $request, $id)
    {
        //Modifica estatus horario
        if ($request->ajax()) {
            
            session_start();


            $permiso = DB::table('permiso')
            ->where('per_id', $id)
            ->update(['per_status'=>0]);

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
            $aud->aud_desc = strtoupper('modificacion de estatus de permiso - Desactivado');
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

    public function perActive(Request $request, $id)
    {
        //Modifica estatus horario
        if ($request->ajax()) {
            
            session_start();


            $permiso = DB::table('permiso')
            ->where('per_id', $id)
            ->update(['per_status'=> 1]);

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
            $aud->aud_desc = strtoupper('modificacion de estatus de permiso - Activado');
            $aud->aud_machine_ip = $_SERVER['REMOTE_ADDR'];
            $aud->aud_machine_name = gethostname();
            $aud->aud_machine_os =PHP_OS.'-'.php_uname();
            $aud->aud_machine_explorer = $navegador;
            $aud->aud_ced = $_SESSION['id'];
            $aud->aud_fecha =$fecha;
            $aud->save();

            return response()->json($permiso);
        }
    }

}
