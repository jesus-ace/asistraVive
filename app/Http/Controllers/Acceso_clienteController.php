<?php

namespace Asistencias\Http\Controllers;

use Illuminate\Http\Request;

use Asistencias\Http\Requests;
use Asistencias\Http\Controllers\Controller;
use Asistencias\carnet_us;
use Asistencias\Asistencia;
use Asistencias\Notificaciones;
use Asistencias\Acceso_cliente;
use Asistencias\acceso;
use Asistencias\roles;
use Session;
use DB;
class Acceso_clienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   session_start();
        // Valida que exista una sesion activa
        if (isset($_SESSION['foto'])) {

            if ($_SESSION['rol'] !='EMPLEADO') {
                //Obtener todos los registros de clientes
                $cliente = Acceso_cliente::all();
                //Obtiene el id del rol del usuario que inicio la sesion
                $rol = roles::where('ro_id',$_SESSION['acceso'])->get()->pluck('ro_id')->last();
                //Obtiene el nombre de la pantalla del usuario en el caso de que tenga el permiso
                $aco_acc = acceso::join('pantallas','aco_pnt_id','=','pnt_id')
                ->where('pnt_nombre','p_newcliente')
                ->where('aco_ro_id',$rol)->get()->pluck('pnt_nombre')->last();

                return view('Marcaje.Admin_cliente', compact('cliente','aco_acc'));                
            }                        
            //Si no coincide la contraseña,
            Session::flash('session','Disculpe, no tiene acceso a este sistema');
            return view('login');
        }// si no existe una sesión activa devuelve a la vista del login
        else{ 
            //Si la sesion no existe...
            Session::flash('session', 'A expirado la sesión');
            return view('login');
        }
   //  return view('Marcaje.Admin_cliente');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        session_start();
        // Valida que exista una sesion activa
        if (isset($_SESSION['foto'])) {
            //Obtiene todos los datos de los clientes y los usuarios que hicieron el registro
            $cliente = Acceso_cliente::join('usuarios','us_ced','=','mcjacc_us_reg')->orderBy('us_nom','asc')->get(); 

            //Obtiene el id del rol del usuario que inicio la sesion
            $rol = roles::where('ro_id',$_SESSION['acceso'])->get()->pluck('ro_id')->last();

            //Obtiene el nombre de la pantalla del usuario en el caso de que tenga el permiso
            $aco_acc = acceso::join('pantallas','aco_pnt_id','=','pnt_id')
            ->where('pnt_nombre','p_newcliente')
            ->where('aco_ro_id',$rol)->get()->pluck('pnt_nombre')->last();

            return view('Marcaje.nuevo_cliente', compact('cliente','aco_acc'));
        }
        else{ 
            //Si la sesion no existe...
            Session::flash('session', 'A expirado la sesión');
            return view('login');
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
        session_start();
            
        //Obtener todos los registros de clientes
        $cliente = Acceso_cliente::all();
        $uso=Acceso_cliente::where('mcjacc_ip', $request->mcjacc_ip)->where('mcjacc_pantalla', $request->mcjacc_pantalla)->get();
        
        if (count($uso)>0) {

            $pant=Acceso_cliente::where('mcjacc_ip', $request->mcjacc_ip)->pluck('mcjacc_pantalla');
            
            $info="La ip ".$request->mcjacc_ip." ya se encuentra asignada a la pantalla de ".$pant."";

            if (count($uso) >= 1) {
                Session::flash('session', "La ip ".$request->mcjacc_ip." ya se encuentra asignada a la pantalla de ".$pant.".");
            }
        
        }
        else {
            $cliente = new Acceso_cliente();
            $cliente->mcjacc_ip = $request->mcjacc_ip;
            $cliente->mcjacc_descripcion = $request->mcjacc_descripcion;
            $cliente->mcjacc_status = $request->mcjacc_status;
            $cliente->mcjacc_pantalla = $request->mcjacc_pantalla;
            $cliente->mcjacc_us_reg = $_SESSION['id'];
       
            if ($cliente->save()) {
                Session::flash('flash_message', 'Cliente registrado con éxito.');
            }
            else{
                Session::flash('session', 'Error al realizar el registro');
            }
                
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
        $cliente =DB::table('acceso_cliente')->where('mcjacc_id',$id)->get();

        return $cliente;
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
        $cliente = Acceso_cliente::all();
        $uso=Acceso_cliente::where('mcjacc_ip', $request->mcjacc_ip)->where('mcjacc_pantalla', $request->mcjacc_pantalla)->get();
        
        if (isset($request->mcjacc_pantalla)) {
            $pantalla = $request->mcjacc_pantalla;
        }
        else{
            $pantalla = DB::table('acceso_cliente')->where('mcjacc_id', $request->id)->get()->pluck('mcjacc_pantalla')->last();
        }

        if (count($uso)>0) {

            $pant=Acceso_cliente::where('mcjacc_ip', $request->mcjacc_ip)->get()->pluck('mcjacc_pantalla')->last();
            
            $info="La ip ".$request->mcjacc_ip." ya se encuentra asignada a la pantalla de ".$pant."";


            if (count($uso) >= 1) {
                Session::flash('session', "La ip ".$request->mcjacc_ip." ya se encuentra asignada a la pantalla de ".$pant.".");
            }
        
        }
        else {

            if (isset($request->mcjacc_status)) {
                $estado = $request->mcjacc_status;
            }
            else{
                $estado = DB::table('acceso_cliente')->where('mcjacc_id', $request->id)->get()->pluck('mcjacc_status')->last();
            }


            $update = DB::table('acceso_cliente')
                    ->where('mcjacc_id', $request->id)
                    ->update(['mcjacc_ip' => $request->mcjacc_ip, 'mcjacc_descripcion' => $request->mcjacc_descripcion, 'mcjacc_status' => $estado, 'mcjacc_pantalla' => $pantalla]);
            
            if ($update == 1) {
                Session::flash('flash_message', 'Actualización realizada con exito');
            }
            else{
                Session::flash('session', 'Error al realizar el registro');
            }
        }

        return redirect()->back();
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
