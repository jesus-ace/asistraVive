<?php

namespace Asistencias\Http\Controllers;

use Illuminate\Http\Request;

use Asistencias\Http\Requests;
use Asistencias\Http\Controllers\Controller;
use Asistencias\horario_x_usuario;
use Asistencias\notificaciones;
use Asistencias\departamentos;
use Asistencias\Asistencia;
use Asistencias\carnet_us;
use Asistencias\usuarios;
use Asistencias\Alertas;
use Asistencias\Acceso_cliente;
use Session;
use DB;

class NotificacionesController extends Controller
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
        else{ 
            //Si la sesion no existe...
            Session::flash('session', 'A expirado la sesión');
            return view('login');
        }
        
    
    }
    public function get_entradas_dia()
    {
        $dia = date('Y-m-d');

        $usersent = Asistencia::join('carnet_us','carus_id','=','asi_carus_id')->join('horario_x_usuario','hxu_id','=','carus_hxu_id')->join('usuarios','hxu_cedula','=','us_ced')->join('departamentos','dp_id','=','us_dp_id')->where('asi_entrada', $dia)->orderBy('asi_entrada_hora','desc')->take(6)->get();

        return $usersent;
    }

    public function get_salidas_dia()
    {
        $dia = date('Y-m-d');

        $usersal = Asistencia::join('carnet_us','carus_id','=','asi_carus_id')->join('horario_x_usuario','hxu_id','=','carus_hxu_id')->join('usuarios','hxu_cedula','=','us_ced')->join('departamentos','dp_id','=','us_dp_id')->where('asi_salida', $dia)->orderBy('asi_salida_hora','desc')->take(6)->get();

        return $usersal;
    }

    public function get_not_dia(Request $request)
    {
        $dia = date('Y-m-d');

        $not = Alertas::where('alert_fecha', $dia)->get();

        return response()->json($not);
    }
    public function Bcedula(Request $request,$ced)
    {
        $dia = date('Y-m-d');
        if ($request->ajax()) {
            session_start();

            $usuarios =DB::table('ex_x_usuario')
                ->join('usuarios','exu_ced','=','us_ced')
                ->join('departamentos','us_dp_id','=','dp_id')
                ->join('roles','us_ro_id','=','ro_id')
                ->join('excepciones','exu_ex_id','=','ex_id')
                ->join('autorizacion','ex_au_id','=','au_id')
                ->join('tipo_autorizacion','au_tiau_id','=','tiau_id')
                ->where('au_status',1)
                ->where('us_ced',$ced)
                ->where('au_permiso',$dia)
                ->get();
            
            return response()->json($usuarios);
        }
    }

    public function getDepAut(Request $request,$id)
    {

        $dia = date('Y-m-d');
        if ($request->ajax()) {

            $usuarios =DB::table('ex_x_usuario')
            ->join('usuarios','exu_ced','=','us_ced')
            ->join('departamentos','us_dp_id','=','dp_id')
            ->join('roles','us_ro_id','=','ro_id')
            ->join('excepciones','exu_ex_id','=','ex_id')
            ->join('autorizacion','ex_au_id','=','au_id')
            ->join('tipo_autorizacion','au_tiau_id','=','tiau_id')
            ->where('dp_id',$id)
            ->where('au_permiso',$dia)
            ->where('au_status',1)
            ->get();
            return response()->json($usuarios);
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete_alerta(Request $request,$id)
    {
        $dAlert= Alertas::where('alert_id', $id)->delete();
        return response()->json($dAlert);
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
