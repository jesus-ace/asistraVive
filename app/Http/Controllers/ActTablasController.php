<?php

namespace Asistencias\Http\Controllers;

use Illuminate\Http\Request;

use Asistencias\Http\Requests;
use Asistencias\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Asistencias\tipo_autorizacion;
use Asistencias\Tipos_horarios;
use Asistencias\dias_feriados;
use Asistencias\ex_x_usuario;
use Asistencias\autorizacion;
use Asistencias\tipo_usuario;
use Asistencias\tipo_dia_fe;
use Asistencias\tipo_reposo;
use Asistencias\excepciones;
use Asistencias\auditoria;
use Asistencias\preguntas;
use Asistencias\pantallas;
use Asistencias\permiso;
use Asistencias\modulos;
use Asistencias\reposo;
use Asistencias\config;
use Asistencias\acceso;
use Asistencias\roles;
use Asistencias\Usuarios;
use Asistencias\dp_x_us;
use Carbon\Carbon;
use Session;
use DB;

class ActTablasController extends Controller
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
            if ($_SESSION['rol'] !='EMPLEADO') {
                $presidente = DB::table('configs')->where('cof_tipo', 'FIRMA')->get()->pluck('cof_nombre')->last();

                $empresa = DB::table('configs')->where('cof_tipo', 'Empresa')->get()->pluck('cof_nombre')->last();

                $pagina = DB::table('configs')->where('cof_tipo', 'Pagina')->get()->pluck('cof_nombre')->last();

                $agradecimiento = DB::table('configs')->where('cof_tipo', 'Agradecimiento')->get()->pluck('cof_nombre')->last();

                $encabezado = DB::table('configs')->where('cof_tipo', 'Encabezado')->get()->pluck('cof_nombre')->last();

                $telefono = DB::table('configs')->where('cof_tipo', 'telefono')->get()->pluck('cof_nombre')->last();

                $descripcion = DB::table('configs')->where('cof_tipo', 'Descripcion')->get()->pluck('cof_nombre')->last();

                $sigespSt = DB::table('configs')->where('cof_tipo','SIGESP')->get()->pluck('cof_value')->last();

                $ldapSt = DB::table('configs')->where('cof_tipo','LDAP')->get()->pluck('cof_value')->last();

                $rol = roles::where('ro_id',$_SESSION['acceso'])->get()->pluck('ro_id')->last();

                $aco_conex = acceso::join('pantallas','aco_pnt_id','=','pnt_id')
                ->where('pnt_nombre','p_conexion')
                ->where('aco_ro_id',$rol)->get()->pluck('pnt_nombre')->last();

                return view('Config/conexion', compact('presidente','sigespSt','ldapSt','aco_conex','empresa','pagina','agradecimiento','encabezado','telefono','descripcion'));
            }                        
            //Si no coincide la contraseña,
            Session::flash('session','Disculpe, no tiene acceso a este sistema');
            return view('login');
        }
        else{ 
            //Si la sesion no existe...
            Session::flash('session', 'A expirado la sesión');
            return view('login');
        } 
    }

    public function acttablas()
    {
        session_start();
        if (isset($_SESSION['foto'])) {
            
            if ($_SESSION['rol'] !='EMPLEADO') {
                $horarios = tipos_horarios::where('tiho_status',0)->get();

                $auto =  ex_x_usuario::where('au_status',0)->join('excepciones','exu_ex_id','=','ex_id')->join('usuarios','exu_ced','=','us_ced')->join('autorizacion','ex_au_id','=','au_id')->join('tipo_autorizacion','au_tiau_id','=','tiau_id')->get();

                $dferiado = dias_feriados::where('diaf_status',0)->join('tipo_dia_fe','tife_id','=','diaf_tife_id')->get(); 

                $modulos = modulos::all();

                $permisos = ex_x_usuario::where('per_status',0)->join('excepciones','exu_ex_id','=','ex_id')->join('usuarios','exu_ced','=','us_ced')->join('permiso','ex_per_id','=','per_id')->get();

                $preg = preguntas::all();

                $reposos = ex_x_usuario::where('re_status',0)->join('excepciones','exu_ex_id','=','ex_id')->join('usuarios','exu_ced','=','us_ced')->join('reposo','ex_re_id','=','re_id')->join('tipo_reposo','tire_id','=','re_tire_id')->get();

                $tipo_aut = tipo_autorizacion::all();

                $tipo_df = tipo_dia_fe::all();

                $tipo_re = tipo_reposo::all();

                $tipo_us = tipo_usuario::all();

                $rol = roles::where('ro_id',$_SESSION['acceso'])->get()->pluck('ro_id')->last();

                $aco_act = acceso::join('pantallas','aco_pnt_id','=','pnt_id')
                ->where('pnt_nombre','p_actualizacion')
                ->where('aco_ro_id',$rol)->get()->pluck('pnt_nombre')->last();
                
                return view('Config/acttablas',compact('horarios','preg','auto','dferiado','modulos','permisos','reposos','tipo_aut','tipo_df','tipo_re','tipo_us','aco_act'));
            }                        
            //Si no coincide la contraseña,
            Session::flash('session','Disculpe, no tiene acceso a este sistema');
            return view('login');
        }
        else{ 
            //Si la sesion no existe...
            Session::flash('session', 'A expirado la sesión');
            return view('login');
        } 
    }
    public function update_cof(Request $request)
    {
        session_start();
        
        if (isset($request->estado_ldap)) {
            $ldap = DB::table('configs')
                ->where('cof_tipo', 'LDAP')
                ->update(['cof_value' => 1]);
        }
        else{
            $ldap = DB::table('configs')
                ->where('cof_tipo', 'LDAP')
                ->update(['cof_value' => 0]);
        }
        if (isset($request->estado_sigesp)) {
            $sig = DB::table('configs')
                ->where('cof_tipo', 'SIGESP')
                ->update(['cof_value' => 1]);
        }
        else{
            $sig = DB::table('configs')
                ->where('cof_tipo', 'SIGESP')
                ->update(['cof_value' => 0]);
        }

        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $navegador = $this->getBrowser($user_agent);

        

        $fecha = Carbon::now();
        $fecha->toDateString(); 

        $aud = new auditoria;
        $audId = 'aud_id';
        $aud->aud_tipo = strtoupper('EDITAR');
        $aud->aud_desc = strtoupper('MODIFICACION DE CONEXIÓN');
        $aud->aud_machine_ip = $_SERVER['REMOTE_ADDR'];
        $aud->aud_machine_name = gethostname();
        $aud->aud_machine_os =PHP_OS.' - '.php_uname();
        $aud->aud_machine_explorer = $navegador;
        $aud->aud_ced = $_SESSION['id'];
        $aud->aud_fecha =$fecha;
        $aud->save();
        $usuario =$aud->save();

        if ($ldap == 1 || $sig == 1) {
            Session::flash('flash_message','La conexión el sistema a sido actualizada');
        }
        else{
            Session::flash('session','Error al realizar la actualización');
        }
        
        return redirect()->back();
    }


    public function update_datos_emp(Request $request)
    {
        $presidente = DB::table('configs')
            ->where('cof_tipo', 'FIRMA')
            ->update(['cof_nombre' => $request->presidente]);

        $empresa = DB::table('configs')
            ->where('cof_tipo', 'Empresa')
            ->update(['cof_nombre' => $request->empresa]);

        $pagina = DB::table('configs')
            ->where('cof_tipo', 'Pagina')
            ->update(['cof_nombre' => $request->pagina]);

        $agradecimiento = DB::table('configs')
            ->where('cof_tipo', 'Agradecimiento')
            ->update(['cof_nombre' => $request->agradecimiento]);

        $encabezado = DB::table('configs')
            ->where('cof_tipo', 'Encabezado')
            ->update(['cof_nombre' => $request->encabezado]);

        $telefono = DB::table('configs')
            ->where('cof_tipo', 'telefono')
            ->update(['cof_nombre' => $request->telefono]);

        $descripcion = DB::table('configs')
            ->where('cof_tipo', 'Descripcion')
            ->update(['cof_nombre' => $request->descripcion]);

        if ($descripcion == 1) {
            Session::flash('flash_message','Los datos de la empresa han sido actualizados');
        }
        else{
            Session::flash('session','Error al realizar la actualización');
        }
        return redirect()->back();
    }

    public function getBrowser($user_agent){

        if(strpos($user_agent, 'MSIE') !== FALSE)
            return 'Internet explorer';
        elseif(strpos($user_agent, 'Edge') !== FALSE) //Microsoft Edge
            return 'Microsoft Edge';
        elseif(strpos($user_agent, 'Trident') !== FALSE) //IE 11
            return 'Internet explorer';
        elseif(strpos($user_agent, 'Opera Mini') !== FALSE)
            return "Opera Mini";
        elseif(strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR') !== FALSE)
            return "Opera";
        elseif(strpos($user_agent, 'Firefox') !== FALSE)
            return 'Mozilla Firefox';
        elseif(strpos($user_agent, 'Chrome') !== FALSE)
            return 'Google Chrome';
        elseif(strpos($user_agent, 'Safari') !== FALSE)
            return "Safari";
        else
            return 'No hemos podido detectar su navegador';
    }
   
   /*
     /////////////////////////////////////////////////////////
    ///     Inicio de gestión de preguntas de seguridad   ///
   /////////////////////////////////////////////////////////
   */

   //Insert

    public function new_preg(Request $request)
    {
        $preg = new preguntas;
        $preg->prg_pregunta = $request->new_preg;
        $preg->save();
        $insert = $preg->save();

        if ($insert == 1) {
            Session::flash('flash_message', 'Pregunta registrada de manera éxitosa.');
        }
        else{
            Session::flash('session', 'Error al realizar el registro');
        }

        return redirect()->back();

    }
    //Obtiene los datos para el update
    public function get_preg(Request $request, $id)
    {
        if ($request->ajax()) {
            $preg = preguntas::where('prg_id', $id)->get();
            return response()->json($preg);
        }
    }
    
    public function update_preg(Request $request)
    {
        $preg_edit =  preguntas::where('prg_id', $request->p_id)
                ->update(['prg_pregunta' => $request->p_editado]);
        

        if ($preg_edit == 1) {
            Session::flash('flash_message', 'Pregunta actualizada de manera éxitosa.');
        }
        else{
            Session::flash('session', 'Error al realizar el actualizacion');
        }

        return redirect()->back();
    }

    /*
     /////////////////////////////////////////////////////////
    ///      Fin de gestión de preguntas de seguridad     ///
   /////////////////////////////////////////////////////////
   */



    //Registra el nombre de una nueva pantalla
    public function new_pantalla(Request $request)
    {
        $pnt = new pantallas;
        $pnt->pnt_nombre = $request->pnt_nombre;
        $pnt->pnt_descripcion = $request->pnt_descripcion;
        $pnt->pnt_mod_id = $request->pnt_mod_id;
        $pnt->save();
        $insert = $pnt->save();

        if ($insert == 1) {
            Session::flash('flash_message', 'Pantalla registrada de manera éxitosa.');
        }
        else{
            Session::flash('session', 'Error al realizar el registro');
        }

        return redirect()->back();
    }


    public function new_mod(Request $request)
    {
        $mod = new modulos;
        $mod->mod_nom = $request->mod_nom;
        $mod->mod_alias = $request->mod_alias;
        $mod->mod_desc = $request->mod_desc;
        $insert = $mod->save();

        if ($insert == 1) {
            Session::flash('flash_message', 'Módulo registrado de manera éxitosa.');
        }
        else{
            Session::flash('session', 'Error al realizar el registro');
        }

        return redirect()->back();
    }

    /*
     /////////////////////////////////////////////////////////
    ///     Inicio de gestión de tipos de autorizacion    ///
   /////////////////////////////////////////////////////////
   */

    public function new_tiau(Request $request)
    {
        $tiau = new tipo_autorizacion;
        $tiau->tiau_tipo = $request->tau;
        $tiau->tiau_status = 1;
        $tiau->save();
        $insert = $tiau->save();

        if ($insert == 1) {
            Session::flash('flash_message', 'Tipo de autorización registrada de manera éxitosa.');
        }
        else{
            Session::flash('session', 'Error al realizar el registro');
        }

        return redirect()->back();

    }
    
    public function get_tiau(Request $request,$id)
    {
        if ($request->ajax()) {
            $preg = tipo_autorizacion::where('tiau_id', $id)->get();
            return response()->json($preg);
        }
    }

    public function update_tiau(Request $request)
    {
        $tiau = tipo_autorizacion::where('tiau_id', $request->tau_id)->update(['tiau_tipo' => $request->tau]);

        if ($tiau == 1) {
            Session::flash('flash_message', 'Tipo de autorización actualizada de manera éxitosa.');
        }
        else{
            Session::flash('session', 'Error al realizar el actualizacion');
        }

        return redirect()->back();
    }

    /*
      /////////////////////////////////////////////////////////
     ///     fin de gestión de tipos de autorizacion       ///
    /////////////////////////////////////////////////////////
    */
    

    /*
     /////////////////////////////////////////////////////////
    ///     Inicio de gestión de tipos de dias feriados   ///
   /////////////////////////////////////////////////////////
   */

    public function new_tdf(Request $request)
    {
        $tdf = new tipo_dia_fe;
        $tdf->tife_tipo = $request->tdiasf;
        $tdf->save();
        $insert = $tdf->save();

        if ($insert == 1) {
            Session::flash('flash_message', 'Tipo de día feriado registrado de manera éxitosa.');
        }
        else{
            Session::flash('session', 'Error al realizar el registro');
        }

        return redirect()->back();

    }
    
    public function get_tdf(Request $request,$id)
    {
        if ($request->ajax()) {
            $update = tipo_dia_fe::where('tife_id', $id)->get();
            return response()->json($update);
        }
    }

    public function update_tife(Request $request)
    {
        $tiau = tipo_dia_fe::where('tife_id', $request->tdiasf_id)->update(['tife_tipo' => $request->tdiasf]);

        if ($tiau == 1) {
            Session::flash('flash_message', 'Tipo de día feriado actualizado de manera éxitosa.');
        }
        else{
            Session::flash('session', 'Error al realizar el actualizacion');
        }

        return redirect()->back();
    }

    /*
     /////////////////////////////////////////////////////////
    ///      Fin de gestión de tipos de dias feriados     ///
   /////////////////////////////////////////////////////////
   */

    /*
     /////////////////////////////////////////////////////////
    ///       Inicio de gestión de tipos de reposos       ///
   /////////////////////////////////////////////////////////
   */

    public function new_tire(Request $request)
    {
        $tire = new tipo_reposo;
        $tire->tire_tipo = $request->trep;
        $tire->tire_status = 1;
        $tire->save();
        $insert = $tire->save();

        if ($insert == 1) {
            Session::flash('flash_message', 'Tipo de reposo registrado de manera éxitosa.');
        }
        else{
            Session::flash('session', 'Error al realizar el registro');
        }

        return redirect()->back();

    }
    
    public function get_tire(Request $request,$id)
    {
        if ($request->ajax()) {
            $update = tipo_reposo::where('tire_id', $id)->get();
            return response()->json($update);
        }
    }

    public function update_tire(Request $request)
    {
        $update = tipo_reposo::where('tire_id', $request->tire_id)->update(['tire_tipo' => $request->tire_tipo]);

        if ($update == 1) {
            Session::flash('flash_message', 'Tipo de reposo actualizado de manera éxitosa.');
        }
        else{
            Session::flash('session', 'Error al realizar el actualizacion');
        }

        return redirect()->back();
    }

    /*
     /////////////////////////////////////////////////////////
    ///      Fin de gestión de tipos de reposo            ///
   /////////////////////////////////////////////////////////
   */
     /*
     /////////////////////////////////////////////////////////
    ///       Inicio de gestión de tipos deusuario     ///
   /////////////////////////////////////////////////////////
   */

    public function new_tdu(Request $request)
    {
        $tire = new tipo_usuario;
        $tire->tdu_tipo = $request->tdu_tipo;
        $tire->tdu_status = 1;
        $tire->save();
        $insert = $tire->save();

        if ($insert == 1) {
            Session::flash('flash_message', 'Tipo de usuario registrado de manera éxitosa.');
        }
        else{
            Session::flash('session', 'Error al realizar el registro');
        }

        return redirect()->back();

    }
    
    public function get_tdu(Request $request,$id)
    {
        if ($request->ajax()) {
            $update = tipo_usuario::where('tdu_id', $id)->get();
            return response()->json($update);
        }
    }

    public function update_tdu(Request $request)
    {
        $update = tipo_usuario::where('tdu_id', $request->tdu_id)->update(['tdu_tipo' => $request->tdu_tipo]);

        if ($update == 1) {
            Session::flash('flash_message', 'Tipo de usuario actualizado de manera éxitosa.');
        }
        else{
            Session::flash('session', 'Error al realizar el actualizacion');
        }

        return redirect()->back();
    }

    /*
     /////////////////////////////////////////////////////////
    ///      Fin de gestión de tipos de usuario    ///
   /////////////////////////////////////////////////////////
   */


     /*
     /////////////////////////////////////////////////////////
    ///       Inicio de gestión de departamentos          ///
   /////////////////////////////////////////////////////////
   */
    public function departamentos()
    {
        session_start();
        if (isset($_SESSION['acceso'])) {
            
            $departamento = DB::table('departamentos')->orderBy('dp_nombre', 'asc')->paginate(10);

            return view('Config/departamentos', compact('departamento'));
        }
        else{
            //Si la sesion no existe...
            Session::flash('session','A expirado la sesión');
            return view('login');
        }
    }
    public function get_ced_dp(Request $request, $ced)
    {
        $user = usuarios::where('us_ced',$ced)
            ->join('departamentos','us_dp_id','=','dp_id')
            ->join('roles','us_ro_id','=','ro_id')            
            ->join('tipo_usuarios','us_tdu_id','=','tdu_id')
            ->get();
        return $user;
    }

    public function get_info_dp($id){
        $departamento = DB::table('departamentos')->where('dp_id',$id)->get();
        return $departamento;
    }

    public function editar_dp (Request $request){
        if ($request->estatus == 1) {
            $st = 1;
        }
        else{
            $st = 2;
        }

        $departamento = DB::table('departamentos')
        ->where('dp_id',$request->id)
        ->update(['dp_nombre'=> $request->nombre, 'dp_tlf_ppl'=> $request->principal, 'dp_tlf_sec'=> $request->secundario, 'dp_status'=> $st, 'dp_codigo'=> $request->codigo]);
        if ($departamento == 1) {
            Session::flash('flash','Departamento actualizaco exitosamente.');
        }
        else{
            Session::flash('session','Error al actualizar departamento');
        }
        return redirect()->back();
    }
    public function dptodelete($id){
        $departamento = DB::table('departamentos')
        ->where('dp_id',$id)
        ->update(['dp_status'=> 0]);
        return $departamento;
    }

    public function guarda_dp(Request $request){
        $us = dp_x_us::where('dxu_us_ced',$request->cedula)->count();
        if ($us != 0) {
            $destroy = dp_x_us::where('dxu_us_ced',$request->cedula);
            $destroy->delete();
        }
        if (isset($request->presidencia)) {

            $insert = dp_x_us::registra_dp($request->cedula,$request->presidencia);
        }

        if (isset($request->desp_presidencia)) {

            $insert = dp_x_us::registra_dp($request->cedula,$request->desp_presidencia);
        }

        if (isset($request->aten_ciudadano1)) {
            
            $insert = dp_x_us::registra_dp($request->cedula,$request->aten_ciudadano1);
        }

        if (isset($request->aud_interna)) {
            
            $insert = dp_x_us::registra_dp($request->cedula,$request->aud_interna);
        }

        if (isset($request->cons_jurd)) {
            
            $insert = dp_x_us::registra_dp($request->cedula,$request->cons_jurd);
        }

        if (isset($request->seg_int)) {
            
            $insert = dp_x_us::registra_dp($request->cedula,$request->seg_int);
        }

        if (isset($request->cont_edit)) {
            
            $insert = dp_x_us::registra_dp($request->cedula,$request->cont_edit);
        }
        //VP Gestion Interna
        if (isset($request->vp_gest_inter)) {
            
            if (isset($request->vp_gest_inter_co)) {
                
                $insert = dp_x_us::registra_dp($request->cedula,$request->vp_gest_inter_co);
            }
            if (isset($request->gerencia_plan_pres_org)) {
                
                $insert = dp_x_us::registra_dp($request->cedula,$request->gerencia_plan_pres_org);
            }
            if (isset($request->adm_cont_co)) {
               
                $insert = dp_x_us::registra_dp($request->cedula,$request->adm_cont_co);
            }
            if (isset($request->finanzas_co)) {
                
                $insert = dp_x_us::registra_dp($request->cedula,$request->finanzas_co);
            }
            if (isset($request->serv_gen_co)) {
                
                $insert = dp_x_us::registra_dp($request->cedula,$request->serv_gen_co);
            }
            if (isset($request->merc_asu_pub_co)) {
                
                $insert = dp_x_us::registra_dp($request->cedula,$request->merc_asu_pub_co);
            }
            if (isset($request->tal_hum_co)) {
                
                $insert = dp_x_us::registra_dp($request->cedula,$request->tal_hum_co);
            }
        }
        //VP Gestion Productiva
        if (isset($request->vp_gest_prod)) {
            
            if (isset($request->vp_gest_prod_co)) {
                
                $insert = dp_x_us::registra_dp($request->cedula,$request->vp_gest_prod_co);
            }
            if (isset($request->unodos_tv)) {
                
                $insert = dp_x_us::registra_dp($request->cedula,$request->unodos_tv);
            }
            if (isset($request->programacion)) {
                
                $insert = dp_x_us::registra_dp($request->cedula,$request->programacion);
            }
            if (isset($request->prod_int)) {
               
                $insert = dp_x_us::registra_dp($request->cedula,$request->prod_int);
            }
            if (isset($request->img_promo)) {
               
                $insert = dp_x_us::registra_dp($request->cedula,$request->img_promo);
            }
            if (isset($request->com_pop)) {
                
                $insert = dp_x_us::registra_dp($request->cedula,$request->com_pop);
            }
            if (isset($request->postPd)) {
                
                $insert = dp_x_us::registra_dp($request->cedula,$request->postPd);
            }
            if (isset($request->sedes)) {
                
                if (isset($request->sede_oriente)) {
                    
                    $insert = dp_x_us::registra_dp($request->cedula,$request->sede_oriente);
                }
                if (isset($request->sede_llanos)) {
                    
                    $insert = dp_x_us::registra_dp($request->cedula,$request->sede_llanos);
                }
                if (isset($request->sede_orinoco)) {
                    
                    $insert = dp_x_us::registra_dp($request->cedula,$request->sede_orinoco);
                }
                if (isset($request->sede_c_occ)) {
                    
                    $insert = dp_x_us::registra_dp($request->cedula,$request->sede_c_occ);
                }
                if (isset($request->sede_andes)) {
                   
                    $insert = dp_x_us::registra_dp($request->cedula,$request->sede_andes);
                }
                if (isset($request->sede_occ)) {
                    
                    $insert = dp_x_us::registra_dp($request->cedula,$request->sede_occ);
                }
            }
        }

        //VP Gestion para el Desarrollo Tecnológico
        if (isset($request->vp_gest_des_tec)) {
            
            if (isset($request->vp_gest_des_tec_co)) {
                
                $insert = dp_x_us::registra_dp($request->cedula,$request->vp_gest_des_tec_co);
            }
            if (isset($request->ingenieria)) {
                
                $insert = dp_x_us::registra_dp($request->cedula,$request->ingenieria);
            }
            if (isset($request->tec_info)) {
                
                $insert = dp_x_us::registra_dp($request->cedula,$request->tec_info);
            }
            if (isset($request->serv_prod)) {
                
                $insert = dp_x_us::registra_dp($request->cedula,$request->serv_prod);
            }
        }

        //VP Gestion de operaciones
        if (isset($request->vp_gest_ope)) {
            
            if (isset($request->vp_gest_ope_co)) {
                
                $insert = dp_x_us::registra_dp($request->cedula,$request->vp_gest_ope_co);
            }
            if (isset($request->comunicaciones)) {
                
                $insert = dp_x_us::registra_dp($request->cedula,$request->comunicaciones);
            }
            if (isset($request->op_tecn)) {
                
                $insert = dp_x_us::registra_dp($request->cedula,$request->op_tecn);
            }
            if (isset($request->transporte)) {
                
                $insert = dp_x_us::registra_dp($request->cedula,$request->transporte);
            }
        }
        Session::flash('flash_message','Asignación de departamentos exitosa');
        return redirect()->back();
    }


    /*
     /////////////////////////////////////////////////////////
    ///      Fin de gestión de departamentos              ///
   /////////////////////////////////////////////////////////
   */
}
