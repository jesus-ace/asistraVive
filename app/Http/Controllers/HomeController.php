<?php

namespace Asistencias\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Asistencias\Http\Requests;
use Asistencias\Http\Controllers\Controller;
use Asistencias\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Asistencias\Usuarios;
use Asistencias\carnet_us;
use Asistencias\permiso;
use Asistencias\reposo;
use Asistencias\horario_x_usuario;
use Asistencias\departamentos;
use Asistencias\excepciones;
use Asistencias\ex_x_usuario;
use Asistencias\auditoria;
use Asistencias\Asistencia;
use Asistencias\roles;
use Adldap\Laravel\Facades\Adldap;
use Asistencias\tipo_usuario;
use AdldapUserModelTrait;
use Asistencias\Tipos_horarios;
use Asistencias\vacaciones;
use Asistencias\sexo;
use Asistencias\config;
use Carbon\Carbon;
use Asistencias\Alertas;
use Asistencias\Acceso_cliente;
use Asistencias\carnet_seriales_inutilizados;
use Asistencias\acceso;
use Asistencias\dp_x_us;
use Session;
use DB;
use Validator;

class HomeController extends Controller
{
    use AuthenticatesUsers;
    protected $redirectPath ='/login';
    protected $username ='login';
    private $cn;
    private $server = "172.16.80.12";
    private $port = "389";
    private $basedn = "dc=vive,dc=gob,dc=ve";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /*
    ////////////////////////////////
    ///Realiza conexión con ldap///
    //////////////////////////////
    */
    public function __construct()
    {
        $this->cn= ldap_connect($this->server,$this->port);
        ldap_set_option($this->cn,LDAP_OPT_PROTOCOL_VERSION,3);
        return $this->cn;
        session_start();
    }
    /*
    //////////////////////////////////////////
    ///Valida que exista una sesion activa///
    ////////////////////////////////////////
    */
    public function showLogin()
    {
        // Verificamos que el usuario no esté autenticado
        if (Auth::check())
        {
            // Si está autenticado lo mandamos a la raíz donde estara el mensaje de bienvenida.
            return Redirect::to('home');
        }
        // Mostramos la vista login.blade.php (Recordemos que .blade.php se omite.)
        return View('login');
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


    /*
    ////////////////////////////////
    ///Valida la existencia del usuario tanto en ldap como en la bd local///
    //////////////////////////////
    */
    public function iniciar(Request $request)
    {
        $ldap = config::where('cof_tipo','LDAP')->get()->pluck('cof_value')->last();
        $sigesp = config::where('cof_tipo','SIGESP')->get()->pluck('cof_value')->last();
        $local = config::where('cof_tipo','LOCAL')->get()->pluck('cof_value')->last();
        $password = $request->pass;
        $login = $request->login;
        $cnn = $this->cn;
        $base= $this->basedn;
        //Si la conexión con ldap esta activa, autentica a el usuario a traves de los datos del directorio
        if ($ldap == 1) {
            $filter="(|(uid=$login))";
            $justthese = array("userpassword");

            $sr = ldap_search($cnn, $base, $filter, $justthese);
            
            $cout = ldap_count_entries($cnn, $sr);

            if ($cout) {
                $entry = ldap_first_entry($cnn, $sr);
                $user_cn = ldap_get_dn($cnn, $entry);
                $attr = "userpassword";
                $user = ldap_get_entries($cnn, $sr);
                $hash = $user[0]["userpassword"][0];
                $salt = substr(base64_decode(substr($hash,6)),20);
                $encrypted_password ='{SSHA}'. base64_encode(sha1( $password.$salt, TRUE ). $salt);
                $r=ldap_compare($cnn, $user_cn, $attr, $encrypted_password);
                if ($r === true){
                    $user = DB::table('usuarios')->where('us_login', $request->login)->get()->pluck('us_login')->last();
                    if ($user !=''){
                            $user = DB::table('usuarios')->where('us_login', $request->login)->get()->pluck('us_login')->last();
                            $foto = DB::table('usuarios')->where('us_login', $request->login)->get()->pluck('us_foto')->last();
                            $rol = DB::table('usuarios')
                            ->join('departamentos','us_dp_id','=','dp_id')
                            ->join('roles','us_ro_id','=','ro_id')            
                            ->join('tipo_usuarios','us_tdu_id','=','tdu_id')
                            ->where('us_login', $request->login)->get()->pluck('ro_nom')->last();
                            $roll = DB::table('usuarios')
                                ->join('departamentos','us_dp_id','=','dp_id')
                                ->join('roles','us_ro_id','=','ro_id')            
                                ->join('tipo_usuarios','us_tdu_id','=','tdu_id')
                                ->where('us_login', $request->login)->get()->pluck('ro_id')->last();

                            $id = DB::table('usuarios')->where('us_login', $request->login)->get()->pluck('us_ced')->last();
                            $nom = explode(" ",DB::table('usuarios')->where('us_login', $request->login)->get()->pluck('us_nom')->last());
                            $ape = explode(" ",DB::table('usuarios')->where('us_login', $request->login)->get()->pluck('us_ape')->last());

                        if ($rol !='EMPLEADO') {

                            session_start();
                            $_SESSION['login'] = $user;
                            $_SESSION['acceso'] = $roll;
                            $_SESSION['foto'] = $foto;
                            $_SESSION['rol'] = $rol;
                            $_SESSION['id'] =$id;
                            $_SESSION['nombre'] = $nom[0].' '.$ape[0];
                            if ($roll !=43) {
                                if($roll != 44){
                                    
                                    $fecha = Carbon::now();
                                    $fecha->toDateString(); 
                                    //Tomamos los datos de el explorador
                                    $user_agent = $_SERVER['HTTP_USER_AGENT'];
                                    //y lo mandamos a la funcion para saber desde que explorador esta iniciando el usuario
                                    $navegador = $this->getBrowser($user_agent);

                                    //Registramos el inicio de sesión en nuestra tabla de auditoría
                                    $aud = new auditoria;
                                    $audId ='aud_id';
                                    $aud->aud_tipo = strtoupper('ingreso');
                                    $aud->aud_desc = strtoupper('inicio de sesión');
                                    $aud->aud_machine_ip = $_SERVER['REMOTE_ADDR'];
                                    $aud->aud_machine_name = gethostname();
                                    $aud->aud_machine_os =PHP_OS.'-'.php_uname();
                                    $aud->aud_machine_explorer = $navegador;
                                    $aud->aud_ced = $_SESSION['id'];
                                    $aud->aud_fecha =$fecha;
                                    $aud->save();
                                    
                                    Session::flash('flash_message','Bienvenido al sistema.');
                                    return $this->index();
                                }
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
                            $dia = date('Y-m-d');
                            $not = Alertas::where('alert_fecha',$dia)->get();
                            // 
                          // echo ("holaa");

                               //
                          /*  $fecha = $now = new \DateTime();
                            $fecha->format('Y-m-d');
                            $notentrada= Asistencia::where('asi_entrada', '=', $fecha)*/
                            $usersent = Asistencia::join('carnet_us','carus_id','=','asi_carus_id')->join('horario_x_usuario','hxu_id','=','carus_hxu_id')->join('usuarios','hxu_cedula','=','us_ced')->join('departamentos','dp_id','=','us_dp_id')->where('asi_entrada', $dia)->orderBy('asi_entrada_hora','desc')->take(6)->get();
                            $usersal = Asistencia::join('carnet_us','carus_id','=','asi_carus_id')->join('horario_x_usuario','hxu_id','=','carus_hxu_id')->join('usuarios','hxu_cedula','=','us_ced')->join('departamentos','dp_id','=','us_dp_id')->where('asi_salida', $dia)->orderBy('asi_salida_hora','desc')->take(6)->get();

                            $usuarios =DB::table('ex_x_usuario')
                                ->where('au_status',1)
                                ->join('usuarios','exu_ced','=','us_ced')
                                ->join('departamentos','us_dp_id','=','dp_id')
                                ->join('roles','us_ro_id','=','ro_id')
                                ->join('excepciones','exu_ex_id','=','ex_id')
                                ->join('autorizacion','ex_au_id','=','au_id')
                                ->where('au_permiso',$dia)
                                ->Paginate(5);

                            $departamento = DB::table('departamentos')
                                ->orderBy('dp_nombre','asc')
                                ->get();
                            $ip_maquina = $_SERVER['REMOTE_ADDR'];
                            $autip = Acceso_cliente::where('mcjacc_ip', $ip_maquina)->where('mcjacc_pantalla','Seguridad')->where('mcjacc_status', 'TRUE')->get();
                            return view('Marcaje.notificaciones',compact('usersent','usersal','not','autip','usuarios','dia','departamento'));
                        }                        
                        //Si no coincide la contraseña,
                        Session::flash('session','Disculpe, no tiene acceso a este sistema');
                        return view('login');
                    }
                    else{
                        Session::flash('session','Disculpe, debe registrarse en el sistema.');
                            return view('login');
                    }
                }
                else{
                    Session::flash('session','Usuario o clave inválidos.');
                            return view('login');
                }
            }
            else
            {
                //Si no consigue el usuario en el directorio del ldap buscamos que exista el usuario en nuestra base de datos
                $login = DB::table('usuarios')->where('us_login', $request->login)->count();
                //Si existe, buscamos la contraseña de dicho usuario
                $pass = usuarios::select('us_pass')->where('us_login','=',$request->login)->pluck('us_pass');
                //Tomamos la contraseña que envia el usuario y la encriptamos
                $verifica ='{SSHA}'. base64_encode(sha1($password));
                //Y seteamos SSHA a el campo que traemos de la base de datos
                $verifica2 ='{SSHA}'.$pass ;
                //si existe un usuario 
                if ($login == 1) {
                    //pasamos a validar la contraseña
                    if ($verifica == $verifica2) {
                        if ($rol !='EMPLEADO') {
                            //si todo coincide correctamento tenemos acceso al sistema
                            $user = DB::table('usuarios')->where('us_login', $request->login)->pluck('us_login');
                            $foto = DB::table('usuarios')->where('us_login', $request->login)->pluck('us_foto');
                            $rol = DB::table('usuarios')
                            ->join('departamentos','us_dp_id','=','dp_id')
                            ->join('roles','us_ro_id','=','ro_id')            
                            ->join('tipo_usuarios','us_tdu_id','=','tdu_id')
                            ->where('us_login', $request->login)->pluck('ro_nom');

                            $roll = DB::table('usuarios')
                            ->join('departamentos','us_dp_id','=','dp_id')
                            ->join('roles','us_ro_id','=','ro_id')            
                            ->join('tipo_usuarios','us_tdu_id','=','tdu_id')
                            ->where('us_login', $request->login)->pluck('ro_id');


                            $id = DB::table('usuarios')->where('us_login', $request->login)->pluck('us_ced');
                            $nom = explode(" ",DB::table('usuarios')->where('us_login', $request->login)->get()->pluck('us_nom')->last());
                            $ape = explode(" ",DB::table('usuarios')->where('us_login', $request->login)->get()->pluck('us_ape')->last());
                            session_start();


                            //Obtenems al usuario



                            $id = DB::table('usuarios')->where('us_login', $request->login)->pluck('us_ced');

                            $_SESSION['login'] = $user;
                            $_SESSION['foto'] = $foto;
                            $_SESSION['acceso'] = $roll;
                            $_SESSION['rol'] = $rol;
                            $_SESSION['id'] =$id;
                            $_SESSION['nombre'] = $nom[0].' '.$ape[0];


                            $fecha = Carbon::now();
                            $fecha->toDateString(); 
                            //Tomamos los datos de el explorador
                            $user_agent = $_SERVER['HTTP_USER_AGENT'];
                            //y lo mandamos a la funcion para saber desde que explorador esta iniciando el usuario
                            $navegador = $this->getBrowser($user_agent);

                            //Registramos el inicio de sesión en nuestra tabla de auditoría
                            $aud = new auditoria;
                            $audId ='aud_id';
                            $aud->aud_tipo = strtoupper('ingreso');
                            $aud->aud_desc = strtoupper('inicio de sesión');
                            $aud->aud_machine_ip = $_SERVER['REMOTE_ADDR'];
                            $aud->aud_machine_name = gethostname();
                            $aud->aud_machine_os =PHP_OS.'-'.php_uname();
                            $aud->aud_machine_explorer = $navegador;
                            $aud->aud_ced = $_SESSION['id'];
                            $aud->aud_fecha =$fecha;
                            $aud->save();
                            Session::flash('flash_message','Bienvenido al sistema');
                            return $this->index();
                        }                        
                        //Si no coincide la contraseña,
                        Session::flash('session','Disculpe, no tiene acceso a este sistema');
                        return view('login');
                    }
                    //Si no coincide la contraseña,
                    Session::flash('session','Usuario o clave inválidos');
                    return view('login');
                }
                Session::flash('session','Usuario o clave inválidos');
                return view('login');
            }
        } // Si no la busca en la base de datos local
        else{
            //Buscamos que exista el usuario que recibimos en nuestra base de datos
            $login = DB::table('usuarios')->where('us_login', $request->login)->count();

            //Obtenems al usuario
            $user = DB::table('usuarios')->where('us_login', $request->login)->get()->pluck('us_login')->last();
            $foto = DB::table('usuarios')->where('us_login', $request->login)->get()->pluck('us_foto')->last();

            $rol = DB::table('usuarios')
            ->join('departamentos','us_dp_id','=','dp_id')
            ->join('roles','us_ro_id','=','ro_id')            
            ->join('tipo_usuarios','us_tdu_id','=','tdu_id')
            ->where('us_login', $request->login)->get()->pluck('ro_nom')->last();

            $roll = DB::table('usuarios')
            ->join('departamentos','us_dp_id','=','dp_id')
            ->join('roles','us_ro_id','=','ro_id')            
            ->join('tipo_usuarios','us_tdu_id','=','tdu_id')
            ->where('us_login', $request->login)->get()->pluck('ro_id')->last();

            $id = DB::table('usuarios')->where('us_login', $request->login)->get()->pluck('us_ced')->last();
            $nom = explode(" ",DB::table('usuarios')->where('us_login', $request->login)->get()->pluck('us_nom')->last());
            $ape = explode(" ",DB::table('usuarios')->where('us_login', $request->login)->get()->pluck('us_ape')->last());
            //Si existe, buscamos la contraseña de dicho usuario
            $pass = usuarios::select('us_pass')->where('us_login','=',$request->login)->get()->pluck('us_pass')->last();
            //Tomamos la contraseña que envia el usuario y la encriptamos
            $verifica ='{SSHA}'. base64_encode(sha1($password));
            //Y seteamos SSHA a el campo que traemos de la base de datos
            $verifica2 ='{SSHA}'.$pass ;
            //si existe un usuario 
            if ($login == 1) {
                //pasamos a validar la contraseña
                if ($verifica == $verifica2) {
                    if ($rol !='EMPLEADO') {
                        session_start();
                        $_SESSION['login'] = $user;
                        $_SESSION['foto'] = $foto;
                        $_SESSION['acceso'] = $roll;
                        $_SESSION['rol'] = $rol;
                        $_SESSION['id'] =$id;
                        $_SESSION['nombre'] = $nom[0].' '.$ape[0];
                        if ($rol !='SEGURIDAD') {
                            
                            $fecha = Carbon::now();
                            $fecha->toDateString(); 
                            //Tomamos los datos de el explorador
                            $user_agent = $_SERVER['HTTP_USER_AGENT'];
                            //y lo mandamos a la funcion para saber desde que explorador esta iniciando el usuario
                            $navegador = $this->getBrowser($user_agent);

                            //Registramos el inicio de sesión en nuestra tabla de auditoría
                            $aud = new auditoria;
                            $audId ='aud_id';
                            $aud->aud_tipo = strtoupper('ingreso');
                            $aud->aud_desc = strtoupper('inicio de sesión');
                            $aud->aud_machine_ip = $_SERVER['REMOTE_ADDR'];
                            $aud->aud_machine_name = gethostname();
                            $aud->aud_machine_os =PHP_OS.'-'.php_uname();
                            $aud->aud_machine_explorer = $navegador;
                            $aud->aud_ced = $_SESSION['id'];
                            $aud->aud_fecha =$fecha;
                            $aud->save();
                            //si todo coincide correctamento tenemos acceso al sistema
                            Session::flash('flash_message','Bienvenido al sistema');
                            return $this->index();
                        }
                        $dia = date('Y-m-d');
                        $not = Alertas::where('alert_fecha',$dia)->get();
                        Session::flash('flash_message','Bienvenido al sistema');
                        $usersent = Asistencia::join('carnet_us','carus_id','=','asi_carus_id')->join('horario_x_usuario','hxu_id','=','carus_hxu_id')->join('usuarios','hxu_cedula','=','us_ced')->join('departamentos','dp_id','=','us_dp_id')->where('asi_entrada', $dia)->orderBy('asi_entrada_hora','desc')->take(5)->get();
                        $usersal = Asistencia::join('carnet_us','carus_id','=','asi_carus_id')->join('horario_x_usuario','hxu_id','=','carus_hxu_id')->join('usuarios','hxu_cedula','=','us_ced')->join('departamentos','dp_id','=','us_dp_id')->where('asi_salida', $dia)->orderBy('asi_salida_hora','desc')->take(5)->get();
                        $ip_maquina = $_SERVER['REMOTE_ADDR'];
                        $autip = Acceso_cliente::where('mcjacc_ip', $ip_maquina)->where('mcjacc_pantalla','Seguridad')->where('mcjacc_status', 'TRUE')->get();
                        return view('Marcaje.notificaciones',compact('usersent','usersal','not','autip'));
                    }
                    //Si no coincide la contraseña,
                    Session::flash('session','Disculpe, no tiene acceso a este sistema');
                    return view('login');
                }
                //Si no coincide la contraseña,
                Session::flash('session','Usuario o clave inválidos');
                return view('login');
            }
            Session::flash('session','Usuario o clave inválidos');
            return view('login');
        }
        
    }

    public function index(){

        //Toma fecha actual del servidor
        $fecha = new Carbon;
        $dp_acco = dp_x_us::where('dxu_us_ced',$_SESSION['id'])->pluck('dxu_dp_id');
        $reposo = DB::table('ex_x_usuario')
        ->join('usuarios','exu_ced','=','us_ced')
        ->join('departamentos','us_dp_id','=','dp_id')
        ->join('excepciones','exu_ex_id','=','ex_id')
        ->join('reposo','ex_re_id','=','re_id')
        ->join('tipo_reposo','re_tire_id','=','tire_id')                
        ->whereIn('dp_id',$dp_acco)
        ->where('re_fecha_ini','<=',$fecha->toDateString())
        ->where('re_fecha_fin','>=',$fecha->toDateString())
        ->where('re_status',1)
        ->pluck('us_ced');
       

        $permisosnr = DB::table('ex_x_usuario')
        ->join('usuarios','exu_ced','=','us_ced')
        ->join('departamentos','us_dp_id','=','dp_id')
        ->join('roles','us_ro_id','=','ro_id')
        ->join('excepciones','exu_ex_id','=','ex_id')
        ->join('permiso as per','ex_per_id','=','per_id')
        ->where('per_fecha_ini','<=',$fecha->toDateString())
        ->where('per_fecha_fin','>=', $fecha->toDateString())
        ->where('per_status', 1)
        ->where('per_remunerado',2)               
        ->whereIn('dp_id',$dp_acco)
        ->pluck('us_ced');

        
        $permisosr = DB::table('ex_x_usuario')
        ->join('usuarios','exu_ced','=','us_ced')
        ->join('departamentos','us_dp_id','=','dp_id')
        ->join('roles','us_ro_id','=','ro_id')
        ->join('excepciones','exu_ex_id','=','ex_id')
        ->join('permiso as per','ex_per_id','=','per_id')              
        ->whereIn('dp_id',$dp_acco)
        ->where('per_fecha_ini','<=',$fecha->toDateString())
        ->where('per_fecha_fin','>=',$fecha->toDateString())
        ->where('per_status',1)
        ->where('per_remunerado',1)
        ->pluck('us_ced');

        $vacaciones = DB::table('ex_x_usuario')
        ->join('usuarios','exu_ced','=','us_ced')
        ->join('departamentos','us_dp_id','=','dp_id')
        ->join('roles','us_ro_id','=','ro_id')
        ->join('excepciones','exu_ex_id','=','ex_id')
        ->join('vacaciones','ex_vac_id','=','vac_id')
        ->where('vac_fecha_ini','<=',$fecha->toDateString())
        ->where('vac_fecha_fin','>=',$fecha->toDateString())                 
        ->whereIn('dp_id',$dp_acco)
        ->where('vac_status',1)
        ->pluck('us_ced');

        $carnet = asistencia::select('asi_carus_id')->where('asi_entrada',$fecha->toDateString())->pluck('asi_carus_id');

        $inasis = carnet_us::join('horario_x_usuario','hxu_id','=','carus_hxu_id')
        ->join('usuarios','us_ced','=','hxu_cedula')
        ->join('departamentos','us_dp_id','=','dp_id')
        ->where('us_status',1)
        ->whereNotIn('carus_id',$carnet)
        ->whereNotIn('us_ced',$reposo)
        ->whereNotIn('us_ced',$permisosnr)
        ->whereNotIn('us_ced',$permisosr)                
        ->whereIn('dp_id',$dp_acco)
        ->whereNotIn('us_ced',$vacaciones)
        ->count(); 

        $asistencia = DB::table('asistencia')
        ->join('carnet_us','carus_id','=','asi_carus_id')
        ->join('horario_x_usuario','hxu_id','=','carus_hxu_id')
        ->join('usuarios','us_ced','=','hxu_cedula')
        ->join('departamentos','us_dp_id','=','dp_id')                
        ->whereIn('dp_id',$dp_acco)
        ->select(DB::raw('count(*) as asi_carus_id'))
        ->where('asi_entrada', $fecha->toDateString())
        ->groupBy('asi_carus_id')
        ->get();

        $retrasos = horario_x_usuario::join('carnet_us','carus_hxu_id','=','hxu_id')
        ->join('asistencia','asi_carus_id','=','carus_id')
        ->join('tipos_horarios','hxu_tiho_id','=','tiho_id')
        ->join('usuarios','hxu_cedula','=','us_ced')
        ->join('departamentos','us_dp_id','=','dp_id')
        ->where('asi_entrada',$fecha->toDateString())                 
        ->whereIn('dp_id',$dp_acco)
        ->whereRaw('asi_entrada_hora > tiho_hora_en')
        ->get();

        $retiros = horario_x_usuario::join('carnet_us','carus_hxu_id','=','hxu_id')
        ->join('asistencia','asi_carus_id','=','carus_id')
        ->join('tipos_horarios','hxu_tiho_id','=','tiho_id')
        ->join('usuarios','hxu_cedula','=','us_ced')
        ->join('departamentos','us_dp_id','=','dp_id')
        ->where('asi_entrada',$fecha->toDateString())                         
        ->whereIn('dp_id',$dp_acco)
        ->whereRaw('asi_salida_hora < tiho_hora_sa')
        ->get();

        $salidasm =DB::table('asistencia')
        ->join('carnet_us','asi_carus_id','=','carus_id')
        ->join('horario_x_usuario','carus_hxu_id','=','hxu_id')
        ->join('tipos_horarios','hxu_tiho_id','=','tiho_id')
        ->join('usuarios','hxu_cedula','=','us_ced') 
        ->join('departamentos','us_dp_id','=','dp_id')              
        ->whereIn('dp_id',$dp_acco)
        ->where('asi_entrada',$fecha->toDateString())
        ->wherenull('asi_salida')
        ->get();
        

        $sr = substr($fecha,8,2); 
        $cantidad = $sr -1;
        $cant = $sr +1;
        $date = $fecha->subDay($cantidad);
        $date1= $fecha->addDay($cant);

        $conFeriados = asistencia::join('carnet_us','asi_carus_id','=','carus_id')
        ->join ('horario_x_usuario','carus_hxu_id','=','hxu_id')
        ->join('tipos_horarios','hxu_tiho_id','=','tiho_id')
        ->join('usuarios','hxu_cedula','=','us_ced') 
        ->join('departamentos','us_dp_id','=','dp_id')
        ->whereBetween('asi_entrada',[$date->toDateString(),$date1->toDateString()])              
        ->whereIn('dp_id',$dp_acco)
        ->whereNotNull('asi_diaf_id')->count();

        $inasistenciaMes = carnet_us::join('horario_x_usuario','carus_hxu_id','=','hxu_id')
        ->join('usuarios','hxu_cedula','=','us_ced')
        ->join('departamentos','us_dp_id','=','dp_id')
        ->join('asistencia','asi_carus_id','=','carus_id')
        ->join('tipos_horarios','hxu_tiho_id','=','tiho_id')
        ->whereRaw('asi_entrada_hora > tiho_hora_en')
        ->whereBetween('asi_entrada', [$date->toDateString(),$date1->toDateString()])              
        ->whereIn('dp_id',$dp_acco)
        ->count();
        
        return view('home', compact('reposo','permisosnr','permisosr','vacaciones','asistencia','retrasos','retiros','inasis','salidasm','conFeriados','inasistenciaMes'));
    }

    //Busca las cantidades de personas para vacaciones, reposos, asistencias, etc, del día actual.

    /*
      //////////////////////////////////////////////////////////////////
     //                      »» Aqui inicia »»                       //
    //////////////////////////////////////////////////////////////////
    */
    //Permisos Remunerados
    public function getPremunerados()
    {
        
        session_start(); 
        $dp_acco = dp_x_us::where('dxu_us_ced',$_SESSION['id'])->pluck('dxu_dp_id');
        $fecha = Carbon::now();
        $fecha->toDateString();
        $permisos =DB::table('ex_x_usuario')
        ->join('usuarios','exu_ced','=','us_ced')
        ->join('departamentos','us_dp_id','=','dp_id')
        ->join('roles','us_ro_id','=','ro_id')
        ->join('excepciones','exu_ex_id','=','ex_id')
        ->join('permiso as per','ex_per_id','=','per_id')              
        ->whereIn('dp_id',$dp_acco)
        ->where('per_fecha_ini','<=',$fecha)
        ->where('per_fecha_fin','>=',$fecha)
        ->where('per_status',1)
        ->where('per_remunerado',1)
        ->count();
        
        return $permisos;
        
    }
   
    //Permisos NO remunerados
    public function getPnremunerados()
    {
        session_start();
        $fecha = Carbon::now();
        $fecha->toDateString();
        $dp_acco = dp_x_us::where('dxu_us_ced',$_SESSION['id'])->pluck('dxu_dp_id');
        $permisosnr = DB::table('ex_x_usuario')
        ->join('usuarios','exu_ced','=','us_ced')
        ->join('departamentos','us_dp_id','=','dp_id')
        ->join('roles','us_ro_id','=','ro_id')
        ->join('excepciones','exu_ex_id','=','ex_id')
        ->join('permiso as per','ex_per_id','=','per_id')              
        ->whereIn('dp_id',$dp_acco)
        ->where('per_fecha_ini','<=',$fecha)
        ->where('per_fecha_fin','>=',$fecha)
        ->where('per_status', 1)
        ->where('per_remunerado',2)
        ->count();
        
        return $permisosnr;
    }
    //Reposos
    public function getReposos()
    {
        session_start();
        $fecha = Carbon::now();
        $fecha->toDateString(); 
        $dp_acco = dp_x_us::where('dxu_us_ced',$_SESSION['id'])->pluck('dxu_dp_id');
        $reposo = DB::table('ex_x_usuario')
        ->join('usuarios','exu_ced','=','us_ced')
        ->join('excepciones','exu_ex_id','=','ex_id')
        ->join('reposo','ex_re_id','=','re_id')
        ->join('tipo_reposo','re_tire_id','=','tire_id')
        ->join('departamentos','us_dp_id','=','dp_id')         
        ->whereIn('dp_id',$dp_acco)
        ->where('re_fecha_ini','<=',$fecha)
        ->where('re_fecha_fin','>=',$fecha)
        ->where('re_status',1)
        ->count();
            
        return $reposo;
        
    }
    // Asistencias
    public function getAsistencias(Request $request)
    {
        session_start();
        $fecha = Carbon::now();
        $fecha->toDateString(); 
        $dp_acco = dp_x_us::where('dxu_us_ced',$_SESSION['id'])->pluck('dxu_dp_id');

        $asistencia =DB::table('asistencia')
        ->join('carnet_us','asi_carus_id','=','carus_id')
        ->join('horario_x_usuario','carus_hxu_id','=','hxu_id')
        ->join('tipos_horarios','hxu_tiho_id','=','tiho_id')
        ->join('usuarios','hxu_cedula','=','us_ced') 
        ->join('departamentos','us_dp_id','=','dp_id')        
        ->whereIn('dp_id',$dp_acco)
        ->where('asi_entrada',$fecha)
        ->get();
                
        return response()->json($asistencia);
    
    }
    //Entradas con salidas sin marcar
    public function getSalidasm(Request $request)
    {
        if($request->ajax()){

            session_start();

            $fecha = Carbon::now();
            $fecha->toDateString();

            $dp_acco = dp_x_us::where('dxu_us_ced',$_SESSION['id'])->pluck('dxu_dp_id');

            $salidasm =DB::table('asistencia')
            ->join('carnet_us','asi_carus_id','=','carus_id')
            ->join('horario_x_usuario','carus_hxu_id','=','hxu_id')
            ->join('tipos_horarios','hxu_tiho_id','=','tiho_id')
            ->join('usuarios','hxu_cedula','=','us_ced') 
            ->join('departamentos','us_dp_id','=','dp_id')        
            ->whereIn('dp_id',$dp_acco)
            ->where('asi_entrada',$fecha)
            ->wherenull('asi_salida')
            ->get();
            
            return response()->json($salidasm);
        }
    }
    //Inasistencias
    public function getInasistencias(Request $request)
    {
        session_start();

        $fecha = Carbon::now();
        $fecha->toDateString();
        $dp_acco = dp_x_us::where('dxu_us_ced',$_SESSION['id'])->pluck('dxu_dp_id');

        $user =  carnet_us::select('carus_id')
        ->join('horario_x_usuario','carus_hxu_id','=','hxu_id')
        ->join('usuarios','hxu_cedula','=','us_ced')
        ->join('departamentos','us_dp_id','=','dp_id')        
        ->whereIn('dp_id',$dp_acco)
        ->count(); 

        $reposo = DB::table('ex_x_usuario')
        ->join('usuarios','us_ced','=','exu_ced')
        ->join('excepciones','ex_id','=','exu_ex_id')
        ->join('reposo','ex_re_id','=','re_id')
        ->join('tipo_reposo','re_tire_id','=','tire_id')
        ->join('departamentos','us_dp_id','=','dp_id')
        ->where('re_fecha_ini','<=',$fecha->toDateString())
        ->where('re_fecha_fin','>=',$fecha->toDateString())        
        ->whereIn('dp_id',$dp_acco)
        ->where('re_status', 1)
        ->pluck('us_ced');
                

        $permisosnr = DB::table('ex_x_usuario')
        ->join('usuarios','exu_ced','=','us_ced')
        ->join('departamentos','us_dp_id','=','dp_id')
        ->join('roles','us_ro_id','=','ro_id')
        ->join('excepciones','exu_ex_id','=','ex_id')
        ->join('permiso as per','ex_per_id','=','per_id')
        ->where('per_fecha_ini','<=',$fecha->toDateString())
        ->where('per_fecha_fin','>=',$fecha->toDateString())        
        ->whereIn('dp_id',$dp_acco)
        ->where('per_status',1)
        ->where('per_remunerado',2)
        ->pluck('us_ced');
                
        $permisosr = DB::table('ex_x_usuario')
        ->join('usuarios','exu_ced','=','us_ced')
        ->join('departamentos','us_dp_id','=','dp_id')
        ->join('roles','us_ro_id','=','ro_id')
        ->join('excepciones','exu_ex_id','=','ex_id')
        ->join('permiso as per','ex_per_id','=','per_id')
        ->where('per_fecha_ini','<=',$fecha->toDateString())
        ->where('per_fecha_fin','>=',$fecha->toDateString())
        ->where('per_status',1)
        ->where('per_remunerado',1)        
        ->whereIn('dp_id',$dp_acco)
        ->pluck('us_ced');

        $vacaciones = DB::table('ex_x_usuario')
        ->join('usuarios','exu_ced','=','us_ced')
        ->join('departamentos','us_dp_id','=','dp_id')
        ->join('roles','us_ro_id','=','ro_id')
        ->join('excepciones','exu_ex_id','=','ex_id')
        ->join('vacaciones','ex_vac_id','=','vac_id')
        ->where('vac_fecha_ini','<=',$fecha->toDateString())
        ->where('vac_fecha_fin','>=',$fecha->toDateString())        
        ->whereIn('dp_id',$dp_acco)
        ->where('vac_status',1)
        ->pluck('us_ced');

        $carnet = asistencia::select('asi_carus_id')->where('asi_entrada',$fecha)->pluck('asi_carus_id');

        $inasis = carnet_us::join('horario_x_usuario','hxu_id','=','carus_hxu_id')
        ->join('usuarios','us_ced','=','hxu_cedula')
        ->join('departamentos','dp_id','=','us_dp_id')  
        ->where('us_status',1)      
        ->whereIn('dp_id',$dp_acco)
        ->whereNotIn('carus_id',$carnet)
        ->whereNotIn('us_ced',$reposo)
        ->whereNotIn('us_ced',$permisosnr)
        ->whereNotIn('us_ced',$permisosr)
        ->whereNotIn('us_ced',$vacaciones)
        ->count(); 

        return $inasis;
    }
    //Cuenta los retrasos del dia
    public function getRetrasos(Request $request)
    {
         session_start();

        $fecha = Carbon::now();
        $fecha->toDateString();

        $dp_acco = dp_x_us::where('dxu_us_ced',$_SESSION['id'])->pluck('dxu_dp_id');

        $retrasos = horario_x_usuario::join('carnet_us','carus_hxu_id','=','hxu_id')
        ->join('asistencia','asi_carus_id','=','carus_id')
        ->join('tipos_horarios','hxu_tiho_id','=','tiho_id')
        ->join('usuarios','hxu_cedula','=','us_ced')
        ->join('departamentos','us_dp_id','=','dp_id')
        ->where('asi_entrada',$fecha->toDateString())       
        ->whereIn('dp_id',$dp_acco)
        ->whereRaw('asi_entrada_hora > tiho_hora_en')
        ->get();

        
        return $retrasos;
        

    }
    //Cuenta los retiros anticipados
    public function getRetirosant(Request $request)
    {
        $fecha = Carbon::now(); 
        session_start();

        $dp_acco = dp_x_us::where('dxu_us_ced',$_SESSION['id'])->pluck('dxu_dp_id');
        $retiros =horario_x_usuario::join('carnet_us','carus_hxu_id','=','hxu_id')
        ->join('asistencia','asi_carus_id','=','carus_id')
        ->join('tipos_horarios','hxu_tiho_id','=','tiho_id')
        ->join('usuarios','hxu_cedula','=','us_ced')
        ->join('departamentos','us_dp_id','=','dp_id')
        ->where('asi_entrada',$fecha->toDateString())      
        ->whereIn('dp_id',$dp_acco)
        ->whereRaw('asi_salida_hora < tiho_hora_sa')
        ->get();
        
        return $retiros;
    }

    //Vacaciones activas hasta la fecha
    public function getVacaciones()
    {

        session_start();

        $fecha = Carbon::now();
        $fecha->toDateString();

        $dp_acco = dp_x_us::where('dxu_us_ced',$_SESSION['id'])->pluck('dxu_dp_id');

        $vacaciones = DB::table('ex_x_usuario')
        ->join('usuarios','exu_ced','=','us_ced')
        ->join('departamentos','us_dp_id','=','dp_id')
        ->join('roles','us_ro_id','=','ro_id')
        ->join('excepciones','exu_ex_id','=','ex_id')
        ->join('vacaciones','ex_vac_id','=','vac_id')
        ->where('vac_fecha_ini','<=',$fecha->toDateString())
        ->where('vac_fecha_fin','>=',$fecha->toDateString())     
        ->whereIn('dp_id',$dp_acco)
        ->where('vac_status',1)
        ->count();
        
        return $vacaciones;
        
    }
    public function home()
    {
        
        session_start();
        if (isset($_SESSION['foto'])) {
            if (isset($_SESSION['acceso'])) {
                return $this->index();
            }
            else{
                //Si no existe una sesión creada...
                Session::flash('session','Por favor, inicie sesión.');
                return view('login');
            }
        }
        else{
            //Si no existe una sesión creada...
            Session::flash('session','Por favor, inicie sesión.');
            return view('login');
        }
       
    }
    
}
