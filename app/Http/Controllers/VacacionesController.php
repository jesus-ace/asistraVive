<?php

namespace Asistencias\Http\Controllers;

use Illuminate\Http\Request;
use Asistencias\Http\Requests;
use Illuminate\Support\Facades\Input;
use Asistencias\Http\Controllers\Controller;
use Asistencias\departamentos;
use Asistencias\excepciones;
use Asistencias\ex_x_usuario;
use Asistencias\auditoria;
use Asistencias\Usuarios;
use Asistencias\vacaciones;
USE Asistencias\dp_x_us;
use Carbon\Carbon;
use Session;
use Asistencias\roles;
use Asistencias\acceso; 
use DB;

class VacacionesController extends Controller
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

            
            $excepcion = DB::table('ex_x_usuario')
            ->join('usuarios','exu_ced','=','us_ced')
            ->join('departamentos','us_dp_id','=','dp_id')
            ->join('roles','us_ro_id','=','ro_id')
            ->join('excepciones','exu_ex_id','=','ex_id')
            ->join('vacaciones','ex_vac_id','=','vac_id')
            ->where('vac_status',1)
            ->whereIn('dp_id',$dp_acco)
            ->paginate(5);
            
            $rol = roles::where('ro_id',$_SESSION['acceso'])->get()->pluck('ro_id')->last();

            $aco_vac = acceso::join('pantallas','aco_pnt_id','=','pnt_id')
            ->where('pnt_nombre','p_vacaciones')
            ->where('aco_ro_id',$rol)->get()->pluck('pnt_nombre')->last();

            return view('Asistencia/Excepciones/vacaciones', compact('departamento','excepcion','aco_vac'));
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
    public function Busuario(Request $request, $ced)
    {
        if ($request->ajax()) {
            session_start();

            $dp_acco = dp_x_us::where('dxu_us_ced',$_SESSION['id'])->pluck('dxu_dp_id');

            $usuarios = DB::table('ex_x_usuario')
            ->join('usuarios','exu_ced','=','us_ced')
            ->join('departamentos','us_dp_id','=','dp_id')
            ->join('roles','us_ro_id','=','ro_id')
            ->join('excepciones','exu_ex_id','=','ex_id')
            ->join('vacaciones','ex_vac_id','=','vac_id')
            ->where('vac_status',1)   
            ->where('us_ced', $ced)
            ->whereIn('dp_id',$dp_acco)
            ->get();

            return response()->json($usuarios);
        }
    }
    public function getdpVac(Request $request,$id)
    {
        if ($request->ajax()) {
            $departamento = DB::table('ex_x_usuario')
            ->join('usuarios','exu_ced','=','us_ced')
            ->join('departamentos','us_dp_id','=','dp_id')
            ->join('roles','us_ro_id','=','ro_id')
            ->join('excepciones','exu_ex_id','=','ex_id')
            ->join('vacaciones','ex_vac_id','=','vac_id')
            ->where('vac_status',1)
            ->where('dp_id', $id)
            ->get();
            return response()->json($departamento);
        }
    }
    public function buscaPer(Request $request,$desde,$id)
    {
        if ($request->ajax()) {
            $permisos = DB::table('ex_x_usuario')
            ->join('usuarios','exu_ced','=','us_ced')
            ->join('excepciones','exu_ex_id','=','ex_id')
            ->join('permiso','ex_per_id','=','per_id')
            ->where('us_ced', $id)
            ->where('per_fecha_ini','<=', $desde)
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
            ->where('us_ced', $id)
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
            ->where('us_ced', $id)
            ->where('au_permiso','=', $desde)->count();
            return response()->json($reposos);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function vacaciones(Request $request, $ced)
    {
        if ($request->ajax()) {
             session_start();
            if ($_SESSION['rol'] !='SUPER ADMINISTRADOR') {
                $dp = Usuarios::where('us_ced', $_SESSION['id'])->get()->pluck('us_dp_id')->last();
                $usuarios = Usuarios::where('us_ced', $ced)
                ->join('departamentos','us_dp_id','=','dp_id')
                ->where('dp_id',$dp)
                ->get();
            }
            else{
                $usuarios = Usuarios::where('us_ced', $ced)
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
    public function buscar()
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
            //Verifica que los campos necesarios para registrar el reposo este llenos
            $v = \Validator::make($request->all(),[

           'desde'=>'required',
           'hasta'=>'required',
           'cantidad'=>'required',
            ]);
            if($v->fails()){
                return redirect()->back()->withErrors($v->errors());
            }
        }
        //Registra las vacaciones
        if ($request->cantidad =='') {
            $cantidad = 1;
        }
        else{
            $cantidad = $request->cantidad;
        }

        if (Input::get('pagadas')=='') {
            $pagadas = 1;
        }
        else{
            $pagadas = Input::get('pagadas');
        }
        session_start();

        $vacaciones = new vacaciones;
        $id ='per_id';
        $vacaciones->vac_fecha_ini = Input::get('desde'); //fecha de inicio de las vacaciones
        $vacaciones->vac_fecha_fin = Input::get('hasta'); //fecha de culminacion de las vacaciones
        $vacaciones->vac_cant = $cantidad; //Cantidad de vacaciones
        $vacaciones->vac_pago = $pagadas; //Indica si las vacaciones fueron pagadas
        $vacaciones->vac_status = 1; //Status de las vacaciones ( 1 = Registradas 0 = Eliminadas )
        $vacaciones->vac_us_reg = $_SESSION['id'];
        $vacaciones->save();
        $insertV = $vacaciones->save();
        //Registra el permiso como una excepcion

        $exc = new excepciones;
        $excid ='ex_id';
        $exc->ex_vac_id = $vacaciones->vac_id;
        $exc->ex_status = 1;
        $exc->save();
        $insertEx = $exc->save();

        //Asigna la excepcion al usuario

        $exu = new ex_x_usuario;
        $exu->exu_ex_id = $exc->ex_id;
        $exu->exu_ced = Input::get('us_id');
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
        $aud->aud_desc = strtoupper('registro de vacaciones');
        $aud->aud_machine_ip = $_SERVER['REMOTE_ADDR'];
        $aud->aud_machine_name = gethostname();
        $aud->aud_machine_os =PHP_OS.'-'.php_uname();
        $aud->aud_machine_explorer = $navegador;
        $aud->aud_ced = $_SESSION['id'];
        $aud->aud_fecha =$fecha;
        $aud->save();

        if ($insertV == 1 && $insertEx ==1 && $insertExu == 1) {
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
    public function show(Request $request,$idV,$idU)
    {
        if ($request->ajax()) {
            $usuarios = DB::table('ex_x_usuario')
            ->join('usuarios','exu_ced','=','us_ced')
            ->join('departamentos','us_dp_id','=','dp_id')
            ->join('roles','us_ro_id','=','ro_id')
            ->join('excepciones','exu_ex_id','=','ex_id')
            ->join('vacaciones','ex_vac_id','=','vac_id')
            ->where('vac_id', $idV)
            ->where('us_ced', $idU)
            ->get();
            return response()->json($usuarios);
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
        if ($request->pagadas =='') {
            $pagadas = 1;
        }
        else{
            $pagadas = $request->pagadas;
        }
        $vacaciones = DB::table('vacaciones')
        ->where('vac_id', Input::get('Vacid'))
        ->update(['vac_fecha_ini'=> Input::get('desde'),'vac_fecha_fin'=> Input::get('hasta'),'vac_cant'=> Input::get('cantidad'),'vac_pago'=> $pagadas]);

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
        $aud->aud_desc = strtoupper('modificacion de vacaciones');
        $aud->aud_machine_ip = $_SERVER['REMOTE_ADDR'];
        $aud->aud_machine_name = gethostname();
        $aud->aud_machine_os =PHP_OS.'-'.php_uname();
        $aud->aud_machine_explorer = $navegador;
        $aud->aud_ced = $_SESSION['id'];
        $aud->aud_fecha =$fecha;
        $aud->save();

        if ($vacaciones == 1) {
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
        //Modifica estatus vacaciones
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
            $aud->aud_desc = strtoupper('modificacion de estatus de vacaciones');
            $aud->aud_machine_ip = $_SERVER['REMOTE_ADDR'];
            $aud->aud_machine_name = gethostname();
            $aud->aud_machine_os =PHP_OS.'-'.php_uname();
            $aud->aud_machine_explorer = $navegador;
            $aud->aud_ced = $_SESSION['id'];
            $aud->aud_fecha =$fecha;
            $aud->save();

            $vacaciones = DB::table('vacaciones')
            ->where('vac_id', $id)
            ->update(['vac_status'=>0]);
            return redirect()->back();
        }

    }
}
