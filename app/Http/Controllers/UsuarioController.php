<?php

namespace Asistencias\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Asistencias\Http\Controllers\LdapController;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Asistencias\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Hash;
use Adldap\Laravel\Facades\Adldap;
use Asistencias\horario_x_usuario;
use Asistencias\horario_historico;
use Asistencias\Tipos_horarios;
use Asistencias\departamentos;
use Asistencias\Http\Requests;
use Asistencias\tipo_usuario;
use Asistencias\auditoria;
use Asistencias\pantallas;
use Asistencias\preguntas;
use Asistencias\Usuarios;
use Asistencias\dp_x_us;
use Asistencias\config;
use Asistencias\acceso;
use Asistencias\roles;
use Asistencias\fotos;
use Asistencias\sexo;
use Carbon\Carbon;
use Session;
use PDO;
use DB;

class UsuarioController extends Controller
{ /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $cn;
    private  $server = "172.16.80.12";
    private  $port = "389";
    private  $basedn = "dc=vive,dc=gob,dc=ve";

    public function __construct(){
        $this->cn= ldap_connect($this->server,$this->port);
        ldap_set_option($this->cn,LDAP_OPT_PROTOCOL_VERSION,3);
        return $this->cn;
    }

    public function index()
    {
        session_start();

        if (isset($_SESSION['foto'])) {
            $dp_acco = dp_x_us::where('dxu_us_ced',$_SESSION['id'])->pluck('dxu_dp_id');

            $departamentos = DB::table('departamentos')
            ->whereIn('dp_id',$dp_acco)
            ->orderBy('dp_nombre','asc')
            ->get();

            
            $pdo = DB::table('usuarios')->join('departamentos','us_dp_id','=','dp_id')
            ->orderBy('us_nom','asc')
            ->where('us_status',1)
            ->whereIn('dp_id',$dp_acco)
            ->paginate(5);


            $roles= roles::where('ro_status',1)->get();
            $tipousuario= tipo_usuario::all();
            $horarios = DB::table('tipos_horarios')->where('tiho_status',1)->get();

            $preguntas = preguntas::get();

            $rol = roles::where('ro_id',$_SESSION['acceso'])->get()->pluck('ro_id')->last();

            $aco_emp = acceso::join('pantallas','aco_pnt_id','=','pnt_id')
            ->where('aco_ro_id',$rol)->where('pnt_nombre','p_empleados')->get()->pluck('pnt_nombre')->last();
            return view('Usuario/empleados',compact('fotos','roles','tipousuario','preguntas','departamentos','sexos','horarios','usuarios','asistencia','pdo','aco_emp','rolis'));
           
        }
        else{ 
            //Si la sesion no existe...
            Session::flash('session','A expirado la sesión');
            return view('login');
        }
    }

    public function search_local_user($ced)
    {
        $user = usuarios::where('us_ced',$ced)->join('departamentos','us_dp_id','=','dp_id')
            ->join('roles','us_ro_id','=','ro_id')            
            ->join('tipo_usuarios','us_tdu_id','=','tdu_id')
            ->count();
        return $user;
    }
    public function getUsuariosced(Request $request,$ced)
    {
        $ldap = config::where('cof_tipo','LDAP')->get()->pluck('cof_value')->last();
        $sigesp = config::where('cof_tipo','SIGESP')->get()->pluck('cof_value')->last();
        $local = config::where('cof_tipo','LOCAL')->get()->pluck('cof_value')->last();
        $user= $this->search_local_user($ced);
        if ($ldap == 1) {

            if($user != 1){

                $pdo =$this->_findUser($ced);
                
                if($pdo == ''){
                    $db = DB::connection('vive_2016');
                    $datos = $db->getPdo()->prepare("SELECT * FROM fn_usuarios_carnets(?,?)");
                    $datos->execute(['cedula',$ced]);
                    $pdo = $datos->fetchAll(PDO::FETCH_CLASS,'stdClass');
                    return $pdo;
                }
            }
            else{
                $pdo ='Este usuario ya existe';
            }
            
        }
        elseif($sigesp == 1){
            if ($request->ajax()) {
                if($user != 1){
                    $db = DB::connection('vive_2016');
                    $datos = $db->getPdo()->prepare("SELECT * FROM fn_usuarios_carnets(?,?)");
                    $datos->execute(['cedula',$ced]);
                    $pdo = $datos->fetchAll(PDO::FETCH_CLASS,'stdClass');
                    return $pdo;
                }
                else{
                    $pdo ='Este usuario ya existe';
                }
            }
        }
        else{
            if($user != 1){
                $pdo ='';
            }
            else{
                $pdo ='Este usuario ya existe';
            }
        }
        return response()->json($pdo);
    }
    public function _findUser($filter){
        $cnn = $this->cn;
        $base= $this->basedn;
        $filtro="(|(uid=*$filter*)(cn=*$filter*)(employeenumber=*$filter*))";
        $justthese = array("employeenumber","uid","userpassword","givenname","sn","sambauserworkstations","mail",);
        $sr = ldap_search($cnn,$base,$filtro,$justthese);
        $count = ldap_count_entries($cnn,$sr);
    
        if ($count){
            $perfil = ldap_get_entries($cnn,$sr);
            $acounts = array();
            for($i=0;$i<$count;$i++){
                $p=$perfil[$i]["uid"][0];
                $fr="(|(uid=$p))";
                $je = array("uid");
                $sd = ldap_search($cnn,$base,$fr,$je);
                $entry = ldap_first_entry($cnn,$sd);
                $acounts[$i]["login"] = $perfil[$i]["uid"][0];
                $acounts[$i]["nombres"] = $perfil[$i]["givenname"][0];
                $acounts[$i]["apellidos"] = $perfil[$i]["sn"][0];
                $acounts[$i]["correo"] = $perfil[$i]["mail"][0];
                if (array_key_exists('sambauserworkstations',$perfil[$i])) {
                    $acounts[$i]["workstations"] = $perfil[$i]["sambauserworkstations"][0];
                }else{
                    $acounts[$i]["workstations"] = "Sin Workstations";
                }
            }
            return $acounts;
        }else{
            return FALSE;
        }
    }
    
    public function getUsuariosdp(Request $request,$id){
        if($request->ajax()){
            
            $usuarios = usuarios::where('dp_id',$id)->join('departamentos','us_dp_id','=','dp_id')
            ->join('roles','us_ro_id','=','ro_id')            
            ->join('tipo_usuarios','us_tdu_id','=','tdu_id')
            ->where('us_status',1)
            ->orderby('us_nom','asc')
            ->get();
            
            return response()->json($usuarios);
        }
    }
    public function getcedu(Request $request,$ced)
    {
        session_start();
        $dp_acco = dp_x_us::where('dxu_us_ced',$_SESSION['id'])->pluck('dxu_dp_id');
        $user = DB::table('usuarios')->join('departamentos','us_dp_id','=','dp_id')
        ->orderBy('us_nom','asc')
        ->where('us_ced',$ced) 
        ->where('us_status',1)
        ->whereIn('dp_id',$dp_acco)
        ->get();

        
        return $user;
            
    }
    public function getDpUs(Request $request)
    {
        if($request->ajax()){

            $departamento = DB::table('departamentos')->get();
            return response()->json($departamento);
        }
    }
    public function getTuUs(Request $request)
    {
        if($request->ajax()){

            $tipo = DB::table('tipo_usuarios')->get(); 
            return response()->json($tipo);
        }
    }
    public function getPrgus(Request $request)
    {
        if ($request->ajax()) {
            $preguntas = DB::table('preguntas')->get(); 
            return response()->json($preguntas);
        }
    }
    public function getRolUs(Request $request)
    {
        if($request->ajax()){

            $roles = DB::table('roles')->where('ro_status',1)->get();
            return response()->json($roles);
        }
    }

    public function getHoraUs(Request $request)
    {
        if($request->ajax()){

            $horario = DB::table('tipos_horarios')->where('tiho_status',1)->get();;
            return response()->json($horario);
        }
    }
    public function getUsuarios(Request $request,$id){
        if($request->ajax()){
             session_start();

            $dp_acco = dp_x_us::where('dxu_us_ced',$_SESSION['id'])->pluck('dxu_dp_id');
            if ($_SESSION['rol'] !='SUPER ADMINISTRADOR') {

                $usuarios = DB::table('usuarios')
                ->join('departamentos','us_dp_id','=','dp_id')
                ->join('roles','us_ro_id','=','ro_id')            
                ->join('tipo_usuarios','us_tdu_id','=','tdu_id')
                ->whereIn('dp_id',$dp_acco)
                ->orderBy('us_ced','asc')
                ->get();
            }
            else{
                $usuarios = DB::table('usuarios')
                ->join('departamentos','us_dp_id','=','dp_id')
                ->join('roles','us_ro_id','=','ro_id')            
                ->join('tipo_usuarios','us_tdu_id','=','tdu_id')
                ->orderBy('us_ced','asc')
                ->get();
            }
            return response()->json($usuarios);
        }
    }

    public function getEmp(Request $request,$ced)
    {
        
        $usuarios = horario_x_usuario::join('usuarios','hxu_cedula','=','us_ced')
        ->join('roles','us_ro_id','=','ro_id')            
        ->join('tipo_usuarios','us_tdu_id','=','tdu_id')
        ->join('tipos_horarios','hxu_tiho_id','=','tiho_id')
        ->where('us_ced',$ced)
        ->orderBy('hxu_fecha_created','desc')
        ->take(1)
        ->get();
        
        return $usuarios;
    
    }

    //Busca a los empleados por su nombre
    public function getName(Request $request,$name)
    {
        //if($request->ajax()){
            session_start();
            $dp_acco = dp_x_us::where('dxu_us_ced',$_SESSION['id'])->pluck('dxu_dp_id');

            $usuarios = usuarios::join('departamentos','us_dp_id','=','dp_id')
            ->join('roles','us_ro_id','=','ro_id')            
            ->join('tipo_usuarios','us_tdu_id','=','tdu_id')
            ->orderby('us_nom','asc')
            ->where('us_nom','like','%'.strtoupper($name).'%')
            ->whereIn('dp_id',$dp_acco)
            ->get();
             
            return $usuarios;        
            //return response()->json($usuarios);
        //}
    }
    //Trae a todos los usuarios luego de refrescar pagina con el icono de refrescar
    public function getUsers(Request $request)
    {
        if($request->ajax()){
            session_start();
            if ($_SESSION['rol'] !='SUPER ADMINISTRADOR') {
                
                $dp_acco = dp_x_us::where('dxu_us_ced',$_SESSION['id'])->pluck('dxu_dp_id');
                $ldap = config::where('cof_tipo','LDAP')->get()->pluck('cof_value')->last();
                $sigesp = config::where('cof_tipo','SIGESP')->get()->pluck('cof_value')->last();
                $local = config::where('cof_tipo','LOCAL')->get()->pluck('cof_value')->last();
                
                $usuarios = usuarios::join('departamentos','us_dp_id','=','dp_id')
                ->join('roles','us_ro_id','=','ro_id')            
                ->join('tipo_usuarios','us_tdu_id','=','tdu_id')
                ->whereIn('dp_id',$dp_acco)
                ->orderby('us_nom','asc')
                ->get();
            }
            else{
                $ldap = config::where('cof_tipo','LDAP')->get()->pluck('cof_value')->last();
                $sigesp = config::where('cof_tipo','SIGESP')->get()->pluck('cof_value')->last();
                $local = config::where('cof_tipo','LOCAL')->get()->pluck('cof_value')->last();
                
                $usuarios = usuarios::join('departamentos','us_dp_id','=','dp_id')
                ->join('roles','us_ro_id','=','ro_id')            
                ->join('tipo_usuarios','us_tdu_id','=','tdu_id')
                ->orderby('us_nom','asc')
                ->get();
            }
            return response()->json($usuarios);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('empleados');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
    public function store(Request $request)
    {
        if(isset($_POST['registrar']))
        {
            $v = \Validator::make($request->all(),[

           'nombre'=>'required',
           'apellido'=>'required',
           'cedula'=>'required',
           'roles'=>'required',
           'pregunta'=>'required',
           'respuesta'=>'required',
           'tipousuario'=>'required',
           'horario'=>'required'
            ]);
            if($v->fails()){
                return redirect()->back()->withErrors($v->errors());
            }
        }
        /*if ($request->cod_uni =='') {
            $departamento = $request->departamento;
        }
        else{
            $departamento = departamentos::where('dp_codigo',$request->departamento)->get()->pluck('dp_id')->last();
        }*/
        if (!isset($request->foto)) {
            $foto ='prueba.jpg';
        }
        else{
            $foto = $request->foto;
        }
        session_start();
        $date = Carbon::now();
        $date->toDateString();
        $horario = $request->horario;
        $usuario = new Usuarios;
        $usuario->us_local = 1;
        $usuario->us_ldap= 0; 
        $usuario->us_status =1;          
        $usuario->us_tdu_id = $request->tipousuario;
        $usuario->us_nom = strtoupper($request->nombre);
        $usuario->us_ape = strtoupper($request->apellido);
        $usuario->us_ced = $request->cedula;
        $usuario->us_mail = $request->correo;
        $usuario->us_dp_id = $request->departamento;
        $usuario->us_ro_id = $request->roles;
        $usuario->us_preg = $request->pregunta;
        $usuario->us_resp = base64_encode(sha1($request->respuesta));
        $usuario->us_login = $request->login;
        $usuario->us_pass = base64_encode(sha1($request->pass));
        $usuario->us_time_reg = $date;
        $usuario->us_last_aco = $date;
        $usuario->us_user_reg = $_SESSION['id'];
        $usuario->us_foto = $foto;
        $usuario->us_car_cod_id= 44;
        $usuario->save();
        $insert = $usuario->save();

        $hxu = new horario_x_usuario;
        $hxuprimary ='hxu_id';
        $hxu->hxu_cedula = $usuario->us_ced;
        $hxu->hxu_tiho_id = $horario;
        $hxu->hxu_fecha_created = $date;
        $hxu->save();
        $inserth = $hxu->save();

        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $navegador = $this->getBrowser($user_agent);

        $fecha = Carbon::now();
        $fecha->toDateString(); 

        //Registramos en la tabla de auditoría
        $aud = new auditoria;
        $audId ='aud_id';
        $aud->aud_tipo = strtoupper('Agregar');
        $aud->aud_desc = strtoupper('registro de usuario');
        $aud->aud_machine_ip = $_SERVER['REMOTE_ADDR'];
        $aud->aud_machine_name = gethostname();
        $aud->aud_machine_os =PHP_OS.'-'.php_uname();
        $aud->aud_machine_explorer = $navegador;
        $aud->aud_ced = $_SESSION['id'];
        $aud->aud_fecha =$fecha;
        $aud->save();
        if ($insert == 1) {
            Session::flash('flash_message','Usuario '.$usuario->us_nom.' '.$usuario->us_ape.' registrado con exito');
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
    public function edit($id)
    {
        Usuarios::findOrFail($id)->Update();

    return redirect('Usuario/empleados');
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
        session_start();

        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $navegador = $this->getBrowser($user_agent);

        $fecha = Carbon::now();
        $fecha = $fecha->toDateTimeString();
        $usuario = DB::table('usuarios')
        ->where('us_ced',$request->id)
        ->update(['us_tdu_id'=> $request->tipousuario]);
        

        $hora = horario_historico::where('hh_cedula',$request->id)
        ->orderBy('hh_time_reg','desc')
        ->take(1)->get()
        ->pluck('hh_tiho_id')->last();

        $insert = DB::table('horario_x_usuario')
            ->where('hxu_cedula',$request->id)
            ->update(['hxu_tiho_id'=> $request->horarioEdit]);
            
        if ($request->horarioEdit != $hora) {
            $h_x_h = new horario_historico;
            $h_x_h->hh_cedula = $request->id;
            $h_x_h->hh_tiho_id = $request->horarioEdit;
            $h_x_h->hh_us_reg = $_SESSION['id'];
            $h_x_h->hh_time_reg = $fecha;
            $h_x_h->save();
            $h_x_h_s = $h_x_h->save();
        }

       //Registramos en la tabla de auditoría
        $aud = new auditoria;
        $audId ='aud_id';
        $aud->aud_tipo = strtoupper('EDITAR');
        $aud->aud_desc = strtoupper('MODIFICACION de usuario');
        $aud->aud_machine_ip = $_SERVER['REMOTE_ADDR'];
        $aud->aud_machine_name = gethostname();
        $aud->aud_machine_os =PHP_OS.'-'.php_uname();
        $aud->aud_machine_explorer = $navegador;
        $aud->aud_ced = $_SESSION['id'];
        $aud->aud_fecha =$fecha;
        $aud->save();

        if ($usuario == 1 ) {
            Session::flash('flash_message','Actualización exitosa');
        }
        else{
            Session::flash('session','Error al realizar la actualización');
        }
        return redirect()->back();
    }
    //Cambiar contraseña
    public function cambiar_pass(Request $request)
    {
        session_start();
        $insert = DB::table('usuarios')
            ->where('us_ced',$_SESSION['id'])
            ->update(['us_pass'=> base64_encode(sha1($request->pass1))]);

        if ($insert == 1 ) {
            Session::flash('flash_message','Cambio de clave de manera local exitoso.');
        }
        else{
            Session::flash('session','Error al realizar la actualización');
        }
        return redirect()->back();
    }
}
