<?php

namespace Asistencias\Http\Controllers;

use Illuminate\Http\Request;

use Asistencias\Http\Requests;
use Asistencias\Http\Controllers\Controller;
use Asistencias\dias_feriados;
use Asistencias\tipo_dia_fe;
use Asistencias\acceso;
use Asistencias\roles;
use Asistencias\auditoria;
use Carbon\Carbon;
use Session;
use DB;
class DiasFController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {  
        if (session_start()) {
            $diasf = DB::table('dias_feriados')
            ->join('tipo_dia_fe', 'diaf_tife_id', '=', 'tife_id')
            ->orderBy('diaf_feriado', 'asc')
            ->where('diaf_status', 1)
            ->paginate(10);
            $tipofe = tipo_dia_fe::all();
             $rol = roles::where('ro_id',$_SESSION['acceso'])->get()->pluck('ro_id')->last();

            $aco_df = acceso::join('pantallas','aco_pnt_id','=','pnt_id')
            ->where('pnt_nombre','p_diasf')
            ->where('aco_ro_id',$rol)->get()->pluck('pnt_nombre')->last();
            return view('Config/diasf', compact('diasf','tipofe','aco_df'));
        }
        else
            return view('login');
        
        
    }

    
    public function getTipoDF()
    {
        $tipo = tipo_dia_fe::get();

        return $tipo;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Verifica que los campos necesarios para registrar el permiso este llenos
        $v = \Validator::make($request->all(),[

            'tipoferiado' => 'required',
            'fecha' => 'required',
            'desc' => 'required',
        ]);
        if($v->fails()){
            return redirect()->back()->withErrors($v->errors());
        }
        $diaf = new dias_feriados;
        $diaf->diaf_feriado = $request->fecha;
        $diaf->diaf_desc = $request->desc;
        $diaf->diaf_tife_id = $request->tipoferiado;
        $diaf->diaf_status = 1;
        $diaf->save();
        $insert = $diaf->save();

        $fecha = Carbon::now();
        $fecha->toDateString();
        session_start();

        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $navegador = $this->getBrowser($user_agent);

        $aud = new auditoria;
        $audId = 'aud_id';
        $aud->aud_tipo = strtoupper('EDITAR');
        $aud->aud_desc = strtoupper('REGISTRO DE DÍA FERIADO');
        $aud->aud_machine_ip = $_SERVER['REMOTE_ADDR'];
        $aud->aud_machine_name = gethostname();
        $aud->aud_machine_os =PHP_OS.' - '.php_uname();
        $aud->aud_machine_explorer = $navegador;
        $aud->aud_ced = $_SESSION['id'];
        $aud->aud_fecha =$fecha;
        $aud->save();
        $usuario =$aud->save();

        if ($insert == 1 ) {
            Session::flash('flash_message', 'El día feriado a sido registrado con exito');
        }
        else{
            Session::flash('session', 'Error al realizar el registro');
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
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if ($request->ajax()) {
            $diasf = DB::table('dias_feriados')
            ->join('tipo_dia_fe', 'diaf_tife_id', '=', 'tife_id')
            ->where('diaf_id', $id)
            ->orderBy('diaf_feriado', 'asc')->get();
            return response()->json($diasf);
        }
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
        $fecha = Carbon::now();
        $fecha->toDateString(); 
        session_start();

        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $navegador = $this->getBrowser($user_agent);

        $aud = new auditoria;
        $audId = 'aud_id';
        $aud->aud_tipo = strtoupper('EDITAR');
        $aud->aud_desc = strtoupper('REGISTRO DE DÍA FERIADO');
        $aud->aud_machine_ip = $_SERVER['REMOTE_ADDR'];
        $aud->aud_machine_name = gethostname();
        $aud->aud_machine_os =PHP_OS.' - '.php_uname();
        $aud->aud_machine_explorer = $navegador;
        $aud->aud_ced = $_SESSION['id'];
        $aud->aud_fecha =$fecha;
        $aud->save();
        $usuario =$aud->save();
        
        $diaf = DB::table('dias_feriados')
        ->where('diaf_id', $request->id)
        ->update(['diaf_feriado' => $request->fecha, 'diaf_desc' => $request->desc, 'diaf_tife_id' =>$request->tipoferiado]);
        if ($diaf == 1 ) {
            Session::flash('flash_message', 'El día feriado a sido actualizado con exito');
        }
        else{
            Session::flash('session', 'Error al realizar el actualización');
        }return redirect()->back();
       
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        //Modifica status del dia feriado
        if ($request->ajax()) {
            $diaf = DB::table('dias_feriados')
            ->where('diaf_id', $id)
            ->update(['diaf_status' => 0]);
            $fecha = Carbon::now();
            $fecha->toDateString(); 
            session_start();

            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            $navegador = $this->getBrowser($user_agent);
            
            $aud = new auditoria;
            $audId = 'aud_id';
            $aud->aud_tipo = strtoupper('eliminar');
            $aud->aud_desc = strtoupper('CAMBIO DE ESTATUS DE DÍA FERIADO - DESACTIVO');
            $aud->aud_machine_ip = $_SERVER['REMOTE_ADDR'];
            $aud->aud_machine_name = gethostname();
            $aud->aud_machine_os =PHP_OS.' - '.php_uname();
            $aud->aud_machine_explorer = $navegador;
            $aud->aud_ced = $_SESSION['id'];
            $aud->aud_fecha =$fecha;
            $aud->save();
            $usuario =$aud->save();
            
            return redirect()->back();
        }
    }

    public function diafActive(Request $request,$id)
    {
        //Modifica status del dia feriado
        if ($request->ajax()) {
            
            $diaf = DB::table('dias_feriados')
            ->where('diaf_id', $id)
            ->update(['diaf_status' => 1]);

            $fecha = Carbon::now();
            $fecha->toDateString(); 
            session_start();

            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            $navegador = $this->getBrowser($user_agent);
            
            $aud = new auditoria;
            $audId = 'aud_id';
            $aud->aud_tipo = strtoupper('ELIMINAR');
            $aud->aud_desc = strtoupper('CAMBIO DE ESTATUS DE DIA FERIADO - ACTIVO');
            $aud->aud_machine_ip = $_SERVER['REMOTE_ADDR'];
            $aud->aud_machine_name = gethostname();
            $aud->aud_machine_os =PHP_OS.' - '.php_uname();
            $aud->aud_machine_explorer = $navegador;
            $aud->aud_ced = $_SESSION['id'];
            $aud->aud_fecha =$fecha;
            $aud->save();
            $usuario =$aud->save();
            
            return response()->json($diaf);
        }
    }
}
