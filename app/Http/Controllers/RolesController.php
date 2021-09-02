<?php

namespace Asistencias\Http\Controllers;

use Illuminate\Http\Request;

use Asistencias\Http\Requests;
use Asistencias\Http\Controllers\Controller;
use Asistencias\pantallas;
use Asistencias\modulos;
use Asistencias\acceso;
use Asistencias\auditoria;
use Asistencias\roles;
use Carbon\Carbon;
use Asistencias\Acciones;
use  Session;
use DB;

class RolesController extends Controller
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
            $roles = DB::table('roles')->where('ro_status',1)->paginate(8);

            $rol = roles::where('ro_id',$_SESSION['acceso'])->get()->pluck('ro_id')->last();

            $aco_rol = acceso::join('pantallas','aco_pnt_id','=','pnt_id')
            ->where('pnt_nombre','p_roles')
            ->where('aco_ro_id',$rol)->get()->pluck('pnt_nombre')->last();

            return view('Config/roles', compact('roles','aco_rol'));
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
    public function accesos(Request $request,$id)
    {
        if ($request->ajax()) {
            $accesos = DB::table('accesos')
                ->join('roles','aco_ro_id','=','ro_id')
                ->join('modulos','aco_mod_id','=','mod_id')
                ->join('pantallas','aco_pnt_id','=','pnt_id')
                ->where('aco_ro_id',$id)
                ->get();
            return response()->json($accesos);
        }
    }
    public function rolesedit(Request $request, $id)
    {
        if ($request->ajax()) {
            $accesos = DB::table('accesos')->where('aco_ro_id',$id)->join('roles','ro_id','=','aco_ro_id')->get();
            return response()->json($accesos);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        if(isset($_POST['registrar']))
        {
            $v = \Validator::make($request->all(),[

            'rol_name'=>'required',
            'rol_desc'=>'required'
            ]);
            if($v->fails()){
                return redirect()->back()->withErrors($v->errors());
            }
        }
        //Creamos el nuevo rol para poder asignarle los accesos que va a tener
        //Tomamos la fecha y la hora 
        $date = Carbon::now();
        $date->toDateString();

        session_start();

        

        if (!isset($request->m_usuarios) && !isset($request->m_asistencia) && !isset($request->m_config)) {
            Session::flash('session','Debe seleccionar algun módulo, por favor, intente nuevamente para poder asignarle los accesos.');
            return redirect()->back();
        }
        else{
            $rol = new roles;
            $rol->ro_nom = strtoupper($request->rol_name);
            $rol->ro_desc = strtoupper($request->rol_desc);
            $rol->ro_time_reg = $date;
            $rol->ro_status = 1;
            $rol->ro_user_reg = $_SESSION['id'];
            $rol->save();
            $insert = $rol->save();
            //Modulo usuarios
            if (isset($request->m_usuarios)) {
                $m_usuarios = DB::table('modulos')->where('mod_nom','m_usuarios')->get()->pluck('mod_id')->last();

                if (!isset($request->p_empleados) && !isset($request->horarios) && !isset($request->sm_carnet) ) {
                    Session::flash('session','Debe seleccionar alguna pantalla para el módulo de usuarios, por favor, edite el rol para poder asignarle los accesos.');
                }
                else{
                    //Empleados
                    if (isset($request->p_empleados)) {
                        $p_empleados = DB::table('pantallas')->where('pnt_nombre','p_empleados')->get()->pluck('pnt_id')->last();

                        //Si el boton de agregar o el de modificar de la tabla empleados tiene algun contenido
                        if (isset($request->bpe_agregar) || isset($request->bpe_modificar)) {

                            //Evalua cual botón tiene contenido y crea el acceso a esa pantalla con ese boton
                            if (isset($request->be_agregar)) {
                                $be_agregar = DB::table('acciones')->where('ac_nom','agregar')->get()->pluck('ac_id')->last();
                                
                                $this->crear_aco_botone($rol->ro_id,$m_usuarios,$p_empleados,$be_agregar);
                            }
                            if (isset($request->bpe_modificar)) {
                                $bpe_modificar = DB::table('acciones')->where('ac_nom','modificar')->get()->pluck('ac_id')->last();
                                
                                $this->crear_aco_botone($rol->ro_id,$m_usuarios,$p_empleados,$bpe_modificar);
                            }
                        }
                        //si no crea el acceso a la pantalla sin darles acceso a los botones
                        else{
                            $this->crear_aco_sin_botone($rol->ro_id,$m_usuarios, $p_empleados);
                        }
                    }
                    if (isset($request->p_horarios)) {
                        $p_horarios = DB::table('pantallas')->where('pnt_nombre','p_horarios')->get()->pluck('pnt_id')->last();

                        //Evalua si alguno de los botones tiene contenido
                        if (isset($request->bph_agregar) || isset($request->bph_modificar) || isset($request->bph_eliminar)) {
                           
                           if (isset($request->bph_agregar)) {

                                $bph_agregar = DB::table('acciones')->where('ac_nom','agregar')->get()->pluck('ac_id')->last();
                                
                                $this->crear_aco_botone($rol->ro_id,$m_usuarios,$p_horarios,$bph_agregar);
                            }
                            if (isset($request->bph_modificar)) {
                                $bph_modificar = DB::table('acciones')->where('ac_nom','modificar')->get()->pluck('ac_id')->last();
                               
                                $this->crear_aco_botone($rol->ro_id,$m_usuarios,$p_horarios,$bph_modificar);
                            }
                            if (isset($request->bph_eliminar)) {
                                $bph_eliminar = DB::table('acciones')->where('ac_nom','eliminar')->get()->pluck('ac_id')->last();

                                $this->crear_aco_botone($rol->ro_id,$m_usuarios,$p_horarios,$bph_eliminar);
                            }
                        }
                        //si no tiene contenido crea el acceso a la pantalla sin darles acceso a los botones
                        else{
                            $this->crear_aco_sin_botone($rol->ro_id,$m_usuarios, $p_horarios);
                        }
                        
                    }
                    if (isset($request->sm_carnet)) {

                        $sm_carnet = DB::table('modulos')->where('mod_nom','sm_carnet')->get()->pluck('mod_id')->last();

                        if (!isset($request->b_carnet_nuevo) && !isset($request->b_carnet_prov) && !isset($request->p_conf_dis) && !isset($request->b_hist_c) && !isset($request->p_cespe)) {

                            Session::flash('session','Debe seleccionar alguna pantalla para el sub-módulo de carnet, por favor, edite el rol para poder asignarle los accesos.');
                        }
                        else{

                            $p_carnet = DB::table('pantallas')->where('pnt_nombre','p_carnet')->get()->pluck('pnt_id')->last();

                            $this->crear_aco_sin_botone($rol->ro_id,$sm_carnet, $p_carnet);

                            if (isset($request->b_carnet_nuevo)) {
                                
                                $sm_cempleado = DB::table('modulos')->where('mod_nom','sm_cempleado')->get()->pluck('mod_id')->last();
                                if (!isset($request->p_carnet_new) && !isset($request->p_reportes)) {

                                    Session::flash('session','Debes seleccionar al menos una pantalla para asignar el sub-modulo de Carnet para empleados');
                                }
                                else{
                                    if (isset($request->p_carnet_new)) {

                                        $p_carnet_new = DB::table('pantallas')->where('pnt_nombre','p_cnempleado')->get()->pluck('pnt_id')->last();

                                        if (isset($request->bpcn_modificar)) {

                                            $bpcn_modificar = DB::table('acciones')->where('ac_nom','modificar')->get()->pluck('ac_id')->last();
                                            $this->crear_aco_botone($rol->ro_id,$sm_cempleado,$p_carnet_new,$bpcn_modificar);
                                        }
                                        else{
                                            $this->crear_aco_sin_botone($rol->ro_id,$sm_cempleado, $p_carnet_new);
                                        }
                                    }
                                    if (isset($request->p_reportes)) {

                                        $p_reportes = DB::table('pantallas')->where('pnt_nombre','p_cnreporte')->get()->pluck('pnt_id')->last();

                                        if (isset($request->bpcr_modificar)) {

                                            $bpcr_modificar = DB::table('acciones')->where('ac_nom','modificar')->get()->pluck('ac_id')->last();

                                            $this->crear_aco_botone($rol->ro_id,$sm_cempleado,$p_reportes,$bpcr_modificar);
                                        }
                                        else{
                                            $this->crear_aco_sin_botone($rol->ro_id,$sm_cempleado, $p_reportes);
                                        }
                                    }
                                }
                            }
                            if (isset($request->b_carnet_prov)) {

                                $sm_cprovisional = DB::table('modulos')->where('mod_nom','sm_cprovisional')->get()->pluck('mod_id')->last();

                                if (!isset($request->p_carnet_new) && !isset($request->p_reportes) && !isset($request->p_seriales)) {
                                    Session::flash('session','Debes seleccionar al menos una pantalla para asignar el sub-modulo de Carnet para empleados');
                                }
                                else{

                                    if (isset($request->p_prox_new)) {
                                        
                                        $p_cnprovisional = DB::table('pantallas')->where('pnt_nombre','p_cnprovisional')->get()->pluck('pnt_id')->last();

                                        if (isset($request->bpcpn_modificar)) {

                                            $bpcpn_modificar = DB::table('acciones')->where('ac_nom','modificar')->get()->pluck('ac_id')->last();

                                            $this->crear_aco_botone($rol->ro_id,$sm_cprovisional,$p_cnprovisional,$bpcpn_modificar);

                                        }
                                        else{

                                            $this->crear_aco_sin_botone($rol->ro_id,$sm_cprovisional, $p_cnprovisional);

                                        }
                                    }
                                    if (isset($request->p_prov_rep)) {

                                        $p_prov_rep = DB::table('pantallas')->where('pnt_nombre','p_cnpreportes')->get()->pluck('pnt_id')->last();

                                        if (isset($request->bpcpr_modificar)) {

                                            $bpcpr_modificar = DB::table('acciones')->where('ac_nom','modificar')->get()->pluck('ac_id')->last();

                                            $this->crear_aco_botone($rol->ro_id,$sm_cprovisional,$p_prov_rep,$bpcpr_modificar);

                                        }
                                        else{

                                            $this->crear_aco_sin_botone($rol->ro_id,$sm_cprovisional, $p_prov_rep);

                                        }
                                    }
                                    if (isset($request->p_seriales)) {

                                        $p_seriales = DB::table('pantallas')->where('pnt_nombre','p_cnpseriales')->get()->pluck('pnt_id')->last();

                                        if (isset($request->bpcps_agregar) || isset($request->bpcps_modificar)) {

                                            if (isset($request->bpcps_agregar)) {

                                                $bpcps_agregar = DB::table('acciones')->where('ac_nom','agregar')->get()->pluck('ac_id')->last();

                                                $this->crear_aco_botone($rol->ro_id,$sm_cprovisional,$p_seriales,$bpcps_agregar);

                                            }

                                            if (isset($request->bpcps_modificar)) {

                                                $bpcps_modificar = DB::table('acciones')->where('ac_nom','modificar')->get()->pluck('ac_id')->last();

                                                $this->crear_aco_botone($rol->ro_id,$sm_cprovisional,$p_seriales,$bpcps_modificar);
                                            }
                                        }
                                        else{

                                            $this->crear_aco_sin_botone($rol->ro_id,$sm_cprovisional, $p_seriales);
                                        }
                                    }
                                }
                            } 
                            if (isset($request->b_hist_c)) {
                                
                                $b_hist_c = DB::table('modulos')->where('mod_nom','sm_historicos')->get()->pluck('mod_id')->last();

                                if (isset($request->p_histoe)) {

                                    $p_histoe = DB::table('pantallas')->where('pnt_nombre','p_chistoricoe')->get()->pluck('pnt_id')->last();

                                    if (isset($request->bphce_imprimir)) {

                                        $bphce_imprimir = DB::table('acciones')->where('ac_nom','imprimir')->get()->pluck('ac_id')->last();
                                        $this->crear_aco_botone($rol->ro_id,$b_hist_c,$p_histoe,$bphce_imprimir);
                                    }
                                }
                                else{
                                    $this->crear_aco_sin_botone($rol->ro_id,$b_hist_c, $b_hist_c);
                                }

                                if (isset($request->p_histop)) {

                                    $p_histop = DB::table('pantallas')->where('pnt_nombre','p_chistoricop')->get()->pluck('pnt_id')->last();

                                    if (isset($request->bphcp_imprimir)) {

                                        $bphcp_imprimir = DB::table('acciones')->where('ac_nom','imprimir')->get()->pluck('ac_id')->last();
                                        
                                        $this->crear_aco_botone($rol->ro_id,$b_hist_c,$p_histop,$bphcp_imprimir);
                                    }
                                }
                                else{
                                    $this->crear_aco_sin_botone($rol->ro_id,$b_hist_c, $b_hist_c);
                                }
                            }

                            if (isset($request->p_conf_dis)) {
                                
                                $p_conf_dis = DB::table('pantallas')->where('pnt_nombre','p_ccof_diseño')->get()->pluck('pnt_id')->last();
                                
                                $this->crear_aco_sin_botone($rol->ro_id,$sm_carnet, $p_conf_dis);
                            }
                            
                            if (isset($request->p_cpvencer)) {
                                
                                $p_cpvencer = DB::table('pantallas')->where('pnt_nombre','p_cvencer')->get()->pluck('pnt_id')->last();

                                if (isset($request->bpcpv_imprimir) || isset($request->bpcpv_modificar) ) {

                                    if (isset($request->bpcpv_imprimir)){

                                        $bpcpv_imprimir =  DB::table('acciones')->where('ac_nom','imprimir')->get()->pluck('ac_id')->last();

                                        $this->crear_aco_botone($rol->ro_id,$sm_carnet,$p_cpvencer,$bpcpv_imprimir);
                                    }
                                    if (isset($request->bpcpv_modificar)){

                                        $bpcpv_modificar =  DB::table('acciones')->where('ac_nom','modificar')->get()->pluck('ac_id')->last();

                                        $this->crear_aco_botone($rol->ro_id,$sm_carnet,$p_cpvencer,$bpcpv_modificar);
                                    }
                                }
                                else{

                                    $this->crear_aco_sin_botone($rol->ro_id,$sm_carnet, $p_cpvencer);
                                }
                            }
                            
                            if (isset($request->p_crobados)) {
                                
                                $p_crobados = DB::table('pantallas')->where('pnt_nombre','p_crobados')->get()->pluck('pnt_id')->last();

                                if (isset($request->bpcr_imprimir)){

                                    $bpcr_imprimir =  DB::table('acciones')->where('ac_nom','imprimir')->get()->pluck('ac_id')->last();

                                    $this->crear_aco_botone($rol->ro_id,$sm_carnet,$p_crobados,$bpcr_imprimir);
                                }
                                else{

                                    $this->crear_aco_sin_botone($rol->ro_id,$sm_carnet, $p_crobados);
                                }
                            }

                             if (isset($request->p_churtados)) {
                                
                                $p_churtados = DB::table('pantallas')->where('pnt_nombre','p_churtados')->get()->pluck('pnt_id')->last();

                                if (isset($request->bpch_imprimir)){

                                    $bpch_imprimir =  DB::table('acciones')->where('ac_nom','imprimir')->get()->pluck('ac_id')->last();

                                    $this->crear_aco_botone($rol->ro_id,$sm_carnet,$p_churtados,$bpch_imprimir);
                                }
                                else{

                                    $this->crear_aco_sin_botone($rol->ro_id,$sm_carnet, $p_churtados);
                                }
                            }
                            
                            if (isset($request->p_cextra)) {
                                
                                $p_cextra = DB::table('pantallas')->where('pnt_nombre','p_cextraviados')->get()->pluck('pnt_id')->last();

                                if (isset($request->bpce_imprimir)){

                                    $bpce_imprimir =  DB::table('acciones')->where('ac_nom','imprimir')->get()->pluck('ac_id')->last();

                                    $this->crear_aco_botone($rol->ro_id,$sm_carnet,$p_cextra,$bpce_imprimir);
                                }
                                else{

                                    $this->crear_aco_sin_botone($rol->ro_id,$sm_carnet, $p_cextra);
                                }
                            }
                            
                            if (isset($request->p_cvencidos)) {
                                
                                $p_cvencidos = DB::table('pantallas')->where('pnt_nombre','p_cvencidos')->get()->pluck('pnt_id')->last();

                                if (isset($request->bpcv_imprimir) || isset($request->bpcv_modificar) ) {

                                    if (isset($request->bpcv_imprimir)){

                                        $bpcv_imprimir =  DB::table('acciones')->where('ac_nom','imprimir')->get()->pluck('ac_id')->last();

                                        $this->crear_aco_botone($rol->ro_id,$sm_carnet,$p_cvencidos,$bpcv_imprimir);
                                    }
                                    if (isset($request->bpcv_modificar)){

                                        $bpcv_modificar =  DB::table('acciones')->where('ac_nom','modificar')->get()->pluck('ac_id')->last();

                                        $this->crear_aco_botone($rol->ro_id,$sm_carnet,$p_cvencidos,$bpcv_modificar);
                                    }
                                }
                                else{

                                    $this->crear_aco_sin_botone($rol->ro_id,$sm_carnet, $p_cvencidos);
                                }
                            }

                        }
                    }
                }
            }


            //Modulo de asistencia 
            if(isset($request->m_asistencia)){
                $m_asistencia = DB::table('modulos')->where('mod_nom','m_asistencia')->get()->pluck('mod_id')->last();

                if (!isset($request->p_control) && !isset($request->p_marcaje ) && !isset($request->p_asistenciae) && !isset($request->sm_excepciones) && !isset($request->p_notificaciones)) {
                    Session::flash('session','Debe seleccionar alguna pantalla para el módulo de asistencia, por favor, edite el rol para poder asignarle los accesos.');
                }
                else{
                    //Pantalla de control
                    if (isset($request->p_control)) {
                        $p_control = DB::table('pantallas')->where('pnt_nombre','p_control')->get()->pluck('pnt_id')->last();
                        if (isset($request->bc_imprimir)) {
                            $bc_imprimir = DB::table('acciones')->where('ac_nom','imprimir')->get()->pluck('ac_id')->last();
                            $this->crear_aco_botone($rol->ro_id,$m_asistencia, $p_control,$bc_imprimir);
                        }
                        elseif (isset($request->bpc_modificar)) {
                            $bc_modificar = DB::table('acciones')->where('ac_nom','modificar')->get()->pluck('ac_id')->last();
                            $this->crear_aco_botone($rol->ro_id,$m_asistencia, $p_control,$bc_modificar);
                        }
                        else{
                            $this->crear_aco_sin_botone($rol->ro_id,$m_asistencia, $p_control);
                        }
                    }
                    if (isset($request->p_marcaje)) {
                        $p_marcaje = DB::table('pantallas')->where('pnt_nombre','p_marcaje')->get()->pluck('pnt_id')->last();
                        
                        $this->crear_aco_sin_botone($rol->ro_id,$m_asistencia, $p_marcaje);
                    }
                    if (isset($request->p_asistenciae)) {
                        $p_asistenciae = DB::table('pantallas')->where('pnt_nombre','p_asistenciae')->get()->pluck('pnt_id')->last();
                        
                        $this->crear_aco_sin_botone($rol->ro_id,$m_asistencia, $p_asistenciae);
                    }
                    if (isset($request->p_notificaciones)) {

                        $p_notificaciones = DB::table('pantallas')->where('pnt_nombre','p_notificaciones')->get()->pluck('pnt_id')->last();
                        
                        $this->crear_aco_sin_botone($rol->ro_id,$m_asistencia, $p_notificaciones);
                    }
                    //Sumodulo de excepciones
                    if (isset($request->sm_excepciones)) {
                        $sm_excepciones = DB::table('modulos')->where('mod_nom','m_excepciones')->get()->pluck('mod_id')->last();

                        if (!isset($request->p_reposos) && !isset($request->p_permisos ) && !isset($request->p_vacaciones) && !isset($request->p_autorizacion)) {
                            Session::flash('session','Debe seleccionar alguna pantalla para el modulo de excepciones');
                            return redirect()->back();
                        }
                        else{
                             //Pantalla de reposos
                            if (isset($request->p_reposos)) {
                                $p_reposos = DB::table('pantallas')->where('pnt_nombre','p_reposos')->get()->pluck('pnt_id')->last();

                                if (isset($request->bpr_agregar) || isset($request->bpr_modificar) || $request->bpr_eliminar) {
                                    if (isset($request->bpr_agregar)) {
                                        $bpr_agregar = DB::table('acciones')->where('ac_nom','agregar')->get()->pluck('ac_id')->last();
                                        
                                        $this->crear_aco_botone($rol->ro_id,$sm_excepciones,$p_reposos,$bpr_agregar);

                                    }
                                    if (isset($request->bpr_modificar)) {
                                        $bpr_modificar = DB::table('acciones')->where('ac_nom','modificar')->get()->pluck('ac_id')->last();
                                        
                                        $this->crear_aco_botone($rol->ro_id,$sm_excepciones,$p_reposos,$bpr_modificar);
                                    }
                                    if (isset($request->bpr_eliminar)) {
                                        $bpr_eliminar = DB::table('acciones')->where('ac_nom','eliminar')->get()->pluck('ac_id')->last();
                                        
                                        $this->crear_aco_botone($rol->ro_id,$sm_excepciones,$p_reposos,$bpr_eliminar);
                                    }
                                }
                                else{
                                    $this->crear_aco_sin_botone($rol->ro_id,$m_asistencia, $p_reposos);
                                }
                                
                            }

                            //Pantalla permisos
                            if (isset($request->p_permisos)) {
                                $p_permisos = DB::table('pantallas')->where('pnt_nombre','p_permisos')->get()->pluck('pnt_id')->last();

                                if (isset($request->bpp_agregar) || isset($request->bpp_modificar) || $request->bpp_eliminar) {
                                    if (isset($request->bpp_agregar)) {
                                        $bpp_agregar = DB::table('acciones')->where('ac_nom','agregar')->get()->pluck('ac_id')->last();
                                        
                                        $this->crear_aco_botone($rol->ro_id,$sm_excepciones,$p_permisos,$bpp_agregar);
                                    }
                                    if (isset($request->bpp_modificar)) {
                                        $bpp_modificar = DB::table('acciones')->where('ac_nom','modificar')->get()->pluck('ac_id')->last();
                                        
                                        $this->crear_aco_botone($rol->ro_id,$sm_excepciones,$p_permisos,$bpp_modificar);
                                    }
                                    if (isset($request->bpp_eliminar)) {
                                        $bpp_eliminar = DB::table('acciones')->where('ac_nom','eliminar')->get()->pluck('ac_id')->last();
                                        
                                        $this->crear_aco_botone($rol->ro_id,$sm_excepciones,$p_permisos,$bpp_eliminar);
                                    }
                                }
                                else{
                                    $this->crear_aco_sin_botone($rol->ro_id,$m_asistencia, $p_permisos);
                                }
                            }

                            //Pantalla vacaciones
                            if (isset($request->p_vacaciones)) {
                                $p_vacaciones = DB::table('pantallas')->where('pnt_nombre','p_vacaciones')->get()->pluck('pnt_id')->last();

                                if (isset($request->bpv_agregar) || isset($request->bpv_modificar) || isset($request->bpv_eliminar)) {
                                    if (isset($request->bpv_agregar)) {
                                        $bpv_agregar = DB::table('acciones')->where('ac_nom','agregar')->get()->pluck('ac_id')->last();
                                        
                                        $this->crear_aco_botone($rol->ro_id,$sm_excepciones,$p_vacaciones,$bpv_agregar);
                                    }
                                    if (isset($request->bpv_modificar)) {
                                        $bpv_modificar = DB::table('acciones')->where('ac_nom','modificar')->get()->pluck('ac_id')->last();
                                        
                                        $this->crear_aco_botone($rol->ro_id,$sm_excepciones,$p_vacaciones,$bpv_modificar);
                                    }
                                    if (isset($request->bpv_eliminar)) {
                                        $bpv_eliminar = DB::table('acciones')->where('ac_nom','eliminar')->get()->pluck('ac_id')->last();
                                        
                                        $this->crear_aco_botone($rol->ro_id,$sm_excepciones,$p_vacaciones,$bpv_eliminar);
                                    }
                                }
                                else{
                                    $this->crear_aco_sin_botone($rol->ro_id,$m_asistencia, $p_vacaciones);
                                }
                            }

                            //Pantalla autorizacion
                            if (isset($request->p_autorizacion)) {
                                $p_autorizacion = DB::table('pantallas')->where('pnt_nombre','p_autorizacion')->get()->pluck('pnt_id')->last();

                                if (isset($request->bpa_agregar) || isset($request->bpa_modificar) || isset($request->bpa_eliminar)) {
                                    if (isset($request->bpa_agregar)) {
                                        $bpa_agregar = DB::table('acciones')->where('ac_nom','agregar')->get()->pluck('ac_id')->last();
                                   
                                        $this->crear_aco_botone($rol->ro_id,$sm_excepciones,$p_autorizacion,$bpa_agregar);
                                    }
                                    if (isset($request->bpa_modificar)) {
                                        $bpa_modificar = DB::table('acciones')->where('ac_nom','modificar')->get()->pluck('ac_id')->last();
                                        
                                        $this->crear_aco_botone($rol->ro_id,$sm_excepciones,$p_autorizacion,$bpa_modificar);
                                    }
                                    if (isset($request->bpa_eliminar)) {
                                        $bpa_eliminar = DB::table('acciones')->where('ac_nom','eliminar')->get()->pluck('ac_id')->last();
                                        
                                        $this->crear_aco_botone($rol->ro_id,$sm_excepciones,$p_autorizacion,$bpa_eliminar);
                                    }
                                }
                                else{
                                    $this->crear_aco_sin_botone($rol->ro_id,$m_asistencia, $p_autorizacion);
                                }
                            }
                        }
                    }
                }
            }


            //Modulo de configuración
            if (isset($request->m_config)) {
                $m_config = DB::table('modulos')->where('mod_nom','m_config')->get()->pluck('mod_id')->last();

                if (!isset($request->p_roles) && !isset($request->p_diasf ) && !isset($request->p_audit) && !isset($request->p_actualizacion) && !isset($request->p_conexion)) {
                    Session::flash('session','Debe seleccionar alguna pantalla para el modulo de configuracion, edite el rol para darle acceso a este modulo');
                }
                else{
                    //Pantalla roles
                    if (isset($request->p_roles)) {
                        $p_roles = DB::table('pantallas')->where('pnt_nombre','p_roles')->get()->pluck('pnt_id')->last();

                        if (isset($request->bproles_agregar) || isset($request->bproles_modificar) || isset($request->bproles_eliminar)) {
                            if (isset($request->bproles_agregar)) {
                                $bproles_agregar = DB::table('acciones')->where('ac_nom','agregar')->get()->pluck('ac_id')->last();

                                $this->crear_aco_botone($rol->ro_id,$m_config,$p_roles,$bproles_agregar);
                            }
                            if (isset($request->bproles_modificar)) {
                                $bproles_modificar = DB::table('acciones')->where('ac_nom','modificar')->get()->pluck('ac_id')->last();

                                $this->crear_aco_botone($rol->ro_id,$m_config,$p_roles,$bproles_modificar);
                            }
                            if (isset($request->bproles_eliminar)) {
                                $bproles_eliminar = DB::table('acciones')->where('ac_nom','eliminar')->get()->pluck('ac_id')->last();
                                
                                $this->crear_aco_botone($rol->ro_id,$m_config,$p_roles,$bproles_eliminar);
                            }
                        }
                        else{
                            $this->crear_aco_sin_botone($rol->ro_id,$m_config, $p_roles);
                        }
                        
                    }
                    //Pantalla dias feriados
                    if (isset($request->p_diasf)) {
                        $p_diasf = DB::table('pantallas')->where('pnt_nombre','p_diasf')->get()->pluck('pnt_id')->last();

                        if (isset($request->bpdf_agregar) || isset($request->bpdf_modificar) || isset($request->bpdf_eliminar)) {
                            if (isset($request->bpdf_agregar)) {
                                $bpdf_agregar = DB::table('acciones')->where('ac_nom','agregar')->get()->pluck('ac_id')->last();
                                
                                 $this->crear_aco_botone($rol->ro_id,$m_config,$p_diasf,$bpdf_agregar);
                            }
                            if (isset($request->bpdf_modificar)) {
                                $bpdf_modificar = DB::table('acciones')->where('ac_nom','modificar')->get()->pluck('ac_id')->last();
                                
                                 $this->crear_aco_botone($rol->ro_id,$m_config,$p_diasf,$bpdf_modificar);
                            }
                            if (isset($request->bpdf_eliminar)) {
                                $bpdf_eliminar = DB::table('acciones')->where('ac_nom','eliminar')->get()->pluck('ac_id')->last();
                                
                                 $this->crear_aco_botone($rol->ro_id,$m_config,$p_diasf,$bpdf_eliminar);
                            }
                        }
                        else{
                            $this->crear_aco_sin_botone($rol->ro_id,$m_config, $p_diasf);
                        }
                    }
                    //Pantalla auditoria
                    if (isset($request->p_audit)) {
                        $p_audit = DB::table('pantallas')->where('pnt_nombre','p_audit')->get()->pluck('pnt_id')->last();

                        if (isset($request->da_imprimir)) {

                            $da_imprimir = DB::table('acciones')->where('ac_nom','imprimir')->get()->pluck('ac_id')->last();

                            $this->crear_aco_botone($rol->ro_id,$m_config, $p_audit,$da_imprimir);

                        }
                        else{           

                            $this->crear_aco_sin_botone($rol->ro_id,$m_config, $p_audit);
                        }

                    }
                    //Pantalla actualizacion
                    if (isset($request->p_actualizacion)) {

                        $p_actualizacion = DB::table('pantallas')->where('pnt_nombre','p_actualizacion')->get()->pluck('pnt_id')->last();

                        if (isset($request->bact_agregar) || isset($request->bact_modificar) ) {

                            if (isset($request->bact_agregar)) {

                                $bact_agregar = DB::table('acciones')->where('ac_nom','agregar')->get()->pluck('ac_id')->last();
                                
                                $this->crear_aco_botone($rol->ro_id,$m_config,$p_actualizacion,$bact_agregar);
                            }
                            if (isset($request->bact_modificar)) {

                                $bact_modificar = DB::table('acciones')->where('ac_nom','modificar')->get()->pluck('ac_id')->last();
                                
                                 $this->crear_aco_botone($rol->ro_id,$m_config,$p_actualizacion,$bact_modificar);
                            }
                        }
                        else{
                            $this->crear_aco_sin_botone($rol->ro_id,$m_config, $p_actualizacion);
                        }
                    }
                    //Pantalla conexión
                    if (isset($request->p_newcliente)) {

                        $p_newcliente = DB::table('pantallas')->where('pnt_nombre','p_newcliente')->get()->pluck('pnt_id')->last();

                        if (isset($request->bnc_agregar) || isset($request->bnc_modificar) ) {

                            if (isset($request->bnc_agregar)) {

                                $bnc_agregar = DB::table('acciones')->where('ac_nom','agregar')->get()->pluck('ac_id')->last();
                                
                                $this->crear_aco_botone($rol->ro_id,$m_config,$p_newcliente,$bnc_agregar);
                            }
                            if (isset($request->bnc_modificar)) {

                                $bnc_modificar = DB::table('acciones')->where('ac_nom','modificar')->get()->pluck('ac_id')->last();
                                
                                 $this->crear_aco_botone($rol->ro_id,$m_config,$p_newcliente,$bnc_modificar);
                            }
                        }
                        else{

                            $this->crear_aco_sin_botone($rol->ro_id,$m_config, $p_newcliente);
                        }
                    }

                    //Pantalla conexión
                    if (isset($request->p_conexion)) {
                        $p_conexion = DB::table('pantallas')->where('pnt_nombre','p_conexion')->get()->pluck('pnt_id')->last();
                        
                        $this->crear_aco_sin_botone($rol->ro_id,$m_config, $p_conexion);
                    }
                }
            }
        }
        if ($insert == 1) {
            Session::flash('flash_message','Rol creado exitósamente');
        }
        else{
            Session::flash('session','Error al realizar el registro');
        }
        return redirect()->back();
    }
    // Creando accesos con botones
    public function crear_aco_botone($rol,$modulo,$pantalla,$boton)
    {
        $acceso = new acceso;
        $acceso->aco_ro_id = $rol;
        $acceso->aco_mod_id = $modulo;
        $acceso->aco_pnt_id = $pantalla;
        $acceso->aco_ac_id = $boton;
        $acceso->aco_user_reg = $_SESSION['id'];
        $acceso->save();

        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $navegador = $this->getBrowser($user_agent);

        $fecha = Carbon::now();
        $fecha->toDateString(); 

        $aud = new auditoria;
        $audId ='aud_id';
        $aud->aud_tipo = strtoupper('EDITAR');
        $aud->aud_desc = strtoupper('REGISTRO DE UN NUEVO ROL');
        $aud->aud_machine_ip = $_SERVER['REMOTE_ADDR'];
        $aud->aud_machine_name = gethostname();
        $aud->aud_machine_os =PHP_OS.'-'.php_uname();
        $aud->aud_machine_explorer = $navegador;
        $aud->aud_ced = $_SESSION['id'];
        $aud->aud_fecha =$fecha;
        $aud->save();
        $usuario =$aud->save();
    }
    public function crear_aco_sin_botone($rol,$modulo,$pantalla)
    {
        $acceso = new acceso;
        $acceso->aco_ro_id = $rol;
        $acceso->aco_mod_id = $modulo;
        $acceso->aco_pnt_id = $pantalla;
        $acceso->aco_user_reg = $_SESSION['id'];
        $acceso->save();

        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $navegador = $this->getBrowser($user_agent);
        
        $fecha = Carbon::now();
        $fecha->toDateString(); 

        $aud = new auditoria;
        $audId ='aud_id';
        $aud->aud_tipo = strtoupper('EDITAR');
        $aud->aud_desc = strtoupper('REGISTRO DE UN NUEVO ROL');
        $aud->aud_machine_ip = $_SERVER['REMOTE_ADDR'];
        $aud->aud_machine_name = gethostname();
        $aud->aud_machine_os =PHP_OS.'-'.php_uname();
        $aud->aud_machine_explorer = $navegador;
        $aud->aud_ced = $_SESSION['id'];
        $aud->aud_fecha =$fecha;
        $aud->save();
        $usuario =$aud->save();
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
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
    public function destroy(Request $request, $id)
    {
       //Modifica horario
        if ($request->ajax()) {
            $roles = DB::table('roles')
            ->where('ro_id', $id)
            ->update(['ro_status'=>0]);

            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            $navegador = $this->getBrowser($user_agent);
            
            $fecha = Carbon::now();
            $fecha->toDateString(); 

            $aud = new auditoria;
            $audId ='aud_id';
            $aud->aud_tipo = strtoupper('EDITAR');
            $aud->aud_desc = strtoupper('MODIFICACIÓN DE ESTATUS DE UN ROL');
            $aud->aud_machine_ip = $_SERVER['REMOTE_ADDR'];
            $aud->aud_machine_name = gethostname();
            $aud->aud_machine_os =PHP_OS.'-'.php_uname();
            $aud->aud_machine_explorer = $navegador;
            $aud->aud_ced = $_SESSION['id'];
            $aud->aud_fecha =$fecha;
            $aud->save();
            $usuario =$aud->save();

            return redirect()->back();
        }
    }
}
