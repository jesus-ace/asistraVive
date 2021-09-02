<?php

namespace Asistencias\Http\Controllers;

use Illuminate\Http\Request;

use Asistencias\Http\Requests;
use Asistencias\Http\Controllers\Controller;
use Asistencias\Http\Controllers\UsuarioController;
use Adldap\Laravel\Facades\Adldap;
use Asistencias\departamentos;
use Asistencias\Usuarios;
use Asistencias\horario_x_usuario;
use Asistencias\auditoria;
use Asistencias\carnet_us;
use Carbon\Carbon;
use PDO;
use DB;

class LdapController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $cn;
    private  $server = "172.16.80.12";
    private  $port = "389";
    private  $basedn = "dc=vive,dc=gob,dc=ve";


    // ------------ CONEXIÓN A LDAP ----------- //
    public function __construct(){
        $this->cn= ldap_connect($this->server,$this->port);
        ldap_set_option($this->cn,LDAP_OPT_PROTOCOL_VERSION,3);
        return $this->cn;
    }
    public function index()
    {
        //
    }
    public function getNameLdap(Request $request,$name)
    {
        if ($request->ajax()) {
            $user=Adldap::search()->where('givenname','=',$name)->get();
            return $user;
        }
    }
    public function getCedLdap(Request $request,$filter)
    {
        //$acounts=Adldap::search()->where('employeeNumber','=',$ced)->get();
        $cnn = $this->cn;
        $base= $this->basedn;
        $filtro="(|(uid=*$filter*)(cn=*$filter*)(employeenumber=*$filter*))";
        $justthese = array("employeenumber","uid","userpassword","givenname","sn","sambauserworkstations","mail","sambadomainname",);
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
                $acounts[$i]["cedula"] = $perfil[$i]["employeenumber"][0];
                $acounts[$i]["departamento"] = $perfil[$i]["sambadomainname"][0];                
                if (array_key_exists('sambauserworkstations',$perfil[$i])) {
                    $acounts[$i]["workstations"] = $perfil[$i]["sambauserworkstations"][0];
                }else{
                    $acounts[$i]["workstations"] = "Sin Workstations";
                }
            }
            return $acounts;
        }else{
            return $acounts ="";
        }

        return $acounts;
    }
    public function sigesp()
    {
        /*$user=Adldap::search()->where('employeeNumber','=','18443795')->get();
            return $user;*/
            $db = DB::connection('vive_2016');

            $datos = $db->getPdo()->prepare("SELECT * FROM fn_usuarios_carnets(?,?)");

            $datos->execute(['todos', '']);

            $result_emp = $datos->fetchAll(PDO::FETCH_CLASS, 'stdClass');
            return dd($result_emp);
            //return response()->json($result_emp);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function insert_ldap_sigesp()
    {
        $user = DB::connection('db_app')->table('usuarios_carnets')->orderBy('cedula_usuario','asc')->take(5)->pluck('cedula_usuario');


        $usuarios = usuarios::all();

        $usu = DB::connection('db_app')->table('usuarios_carnets')->orderBy('cedula_usuario','asc')->whereIn('cedula_usuario',['$usuarios'])->pluck('carnet_usuario');

        for ($i=0; $i < count($usuarios); $i++) { 

            $usu = DB::connection('db_app')->table('usuarios_carnets')->orderBy('cedula_usuario','asc')->where('cedula_usuario',$usuarios[$i]["us_ced"])->get()->pluck('carnet_usuario')->last();

            $horario = DB::table('horario_x_usuario')->orderBy('hxu_cedula','asc')->where('hxu_cedula',$usuarios[$i]["us_ced"])->get()->pluck('hxu_id')->last();
            /*echo $usuarios[$i]["us_nom"].' cedula '.$usuarios[$i]["us_ced"].' codigo del carnet '.$usu.' id del horario '.$horario.'<br>';*/
            if ($usu != '') { 
                $carnet = new carnet_us;
                $carnet->carus_ced = $usuarios[$i]["us_ced"];
                $carnet->carus_codigo = $usu;
                $carnet->carus_hxu_id = $horario;
                $carnet->carus_status = 1;
                $carnet->carus_tipo_carnet = 1;
                $carnet->carus_fecha_vencimiento = '2018-12-31';
                $carnet->carus_motivo = 1;
                $carnet->carus_selloprensa = 'No';
                $carnet->save();
            }
            else{
              
            }
            /*$carnet = new carnet_us;
            $carnet->carus_ced = $usuarios[$i]["us_ced"];
            $carnet->carus_codigo = $usu;
            $carnet->carus_hxu_id = $horario;
            $carnet->carus_status = 1;
            $carnet->carus_tipo_carnet = 1;
            $carnet->carus_fecha_vencimiento = '2018-12-31';
            $carnet->carus_motivo = 1;
            $carnet->carus_selloprensa = 'No';
            $carnet->save();*/
        }

        /*$db = DB::connection('vive_2016');

        $datos = $db->getPdo()->prepare("SELECT * FROM fn_usuarios_carnets(?,?)");

        $datos->execute(['todos','']);

        $result_emp = $datos->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        
        foreach ($result_emp as $ced) {

            $departamento = departamentos::get_id_dp($ced->cod_uni);
            $filter = $ced->cedula;
            $cnn = $this->cn;
            $base= $this->basedn;
            $filtro="(|(uid=*$filter*)(cn=*$filter*)(employeenumber=*$filter*))";
            $justthese = array("employeenumber","uid","userpassword","givenname","sn","sambauserworkstations","mail","sambadomainname",);
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
                    $acounts[$i]["cedula"] = $perfil[$i]["employeenumber"][0];
                    $acounts[$i]["departamento"] = $perfil[$i]["sambadomainname"][0];                
                    if (array_key_exists('sambauserworkstations',$perfil[$i])) {
                        $acounts[$i]["workstations"] = $perfil[$i]["sambauserworkstations"][0];
                    }else{
                        $acounts[$i]["workstations"] = "Sin Workstations";
                    }
                } 
            }
            else{
                echo $acounts ="NO HAY".'<br>';
            }
            for($i=0;$i<$count;$i++){
                $usuarios = usuarios::where('us_ced',$acounts[$i]["cedula"])->get();
                if ($usuarios != '[]') {
                    
                    echo 'Ya existe este usuario '.$acounts[$i]["nombres"];
                }
                else{
                    $date = Carbon::now();
                    $date->toDateString();

                    $usuario = new Usuarios;
                    $usuario->us_local = 1;
                    $usuario->us_ldap= 1; 
                    $usuario->us_status = 1;          
                    $usuario->us_tdu_id = 1;
                    $usuario->us_nom = strtoupper($acounts[$i]["nombres"]);
                    $usuario->us_ape = strtoupper($acounts[$i]["apellidos"]);
                    $usuario->us_ced = $acounts[$i]["cedula"];
                    $usuario->us_mail = $acounts[$i]["correo"];
                    $usuario->us_dp_id = $departamento;
                    $usuario->us_ro_id = 41;
                    $usuario->us_preg = 2;
                    $usuario->us_resp = base64_encode(sha1('comida'));
                    $usuario->us_login = $acounts[$i]["login"];
                    $usuario->us_pass = base64_encode(sha1($acounts[$i]["cedula"]));
                    $usuario->us_time_reg = $date;
                    $usuario->us_last_aco = $date;
                    $usuario->us_user_reg = 27318116;
                    $usuario->us_foto = $acounts[$i]["cedula"].'.jpg';
                    $usuario->us_car_cod_id= 44;
                    $usuario->save();
                    $insert = $usuario->save();

                    $hxu = new horario_x_usuario;
                    $hxuprimary ='hxu_id';
                    $hxu->hxu_cedula = $usuario->us_ced;
                    $hxu->hxu_tiho_id = 1;
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
                    $aud->aud_desc = strtoupper('registro de usuario'.$usuario->us_ced);
                    $aud->aud_machine_ip = $_SERVER['REMOTE_ADDR'];
                    $aud->aud_machine_name = gethostname();
                    $aud->aud_machine_os =PHP_OS.'-'.php_uname();
                    $aud->aud_machine_explorer = $navegador;
                    $aud->aud_ced =27318116;
                    $aud->aud_fecha =$fecha;
                    $aud->save();
                    echo "Se agrego al usuario".$acounts[$i]["nombres"];
                }
                
            } 
            //return $ced->des_uni;/*$acounts[0]["nombres"];
            
        }*/ 
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
