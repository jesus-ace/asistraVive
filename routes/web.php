<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use Asistencias\departamentos;
use Asistencias\Usuarios;
use Asistencias\horario_x_usuario;
Route::get('horariosss',function()
{
    $user= horario_x_usuario::select('us_ced','tiho_id')->join('usuarios', 'hxu_cedula', '=', 'us_ced')
        ->join('roles','us_ro_id', '=', 'ro_id')            
        ->join('tipo_usuarios','us_tdu_id', '=', 'tdu_id')
        ->join('tipos_horarios', 'hxu_tiho_id', '=', 'tiho_id')
        ->groupBy('us_ced','tiho_id')
        ->lists('tiho_id'); 

	$usuarios = horario_x_usuario::join('usuarios', 'hxu_cedula', '=', 'us_ced')
        ->join('roles','us_ro_id', '=', 'ro_id')            
        ->join('tipo_usuarios','us_tdu_id', '=', 'tdu_id')
        ->join('tipos_horarios', 'hxu_tiho_id', '=', 'tiho_id')
        ->orderBy('hxu_fecha_created', 'desc')
        ->get(); 
    return $user;
});

use Asistencias\auditoria;
Route::get('clearAud',function(){

	
	DB::table('asistencia')->truncate();
});

//Ruta que me muestra el login

Route::get('/',function(){
	return view('login');
});
use Asistencias\Asistencia;

Route::get('fecha_r',function(){
	$desde=strtotime("2018-02-01");
	$hasta=strtotime("2018-06-05");
	$resultado = Asistencia::whereBetween('asi_entrada',[$desde,$hasta]);
	for($i=$desde; $i<=$hasta; $i+=86400){
		foreach ($resultado as $re) {
			if ($re->asi_entrada == $desde[$i]){
				echo date("d-m-Y", $i)."<br>";
			}
			else{
				echo "no";
			}
	    	
		}
	}
});

Route::post('bfechap',function(){
	$desde=strtotime($_POST['desde']);
	$hasta=strtotime($_POST['hasta']);
	$asistencia = DB::table('asistencia')
                ->join( 'carnet_us','asi_carus_id',' =', 'carus_id' )
                ->join ('horario_x_usuario','carus_hxu_id',' =', 'hxu_id')
                ->join('tipos_horarios', 'hxu_tiho_id', '=', 'tiho_id')
                ->join ('usuarios', 'hxu_cedula',' =', 'us_ced') 
                ->join ('departamentos','us_dp_id',' =' ,'dp_id')
                ->join ('tipo_usuarios','us_tdu_id',' =', 'tdu_id')
                ->whereBetween('asi_entrada', [$desde, $hasta])
                ->orderBy('asi_entrada', 'desc')
                ->groupBy('us_ced','asi_id','carus_id','hxu_id','tiho_id','dp_id','tdu_id')
                ->get();
	for($i=$desde; $i<=$hasta; $i+=86400){

	    echo date("d-m-Y", $i)."<br>";
	}
});
/*
  ///////////////////////////////////////////////////////////////
 //             *** RUTAS DE LA VISTA DE HOME ******          //
///////////////////////////////////////////////////////////////
*/

//Busca las cantidades de personas para vacaciones, reposos, asistencias, etc, del día actual.

/*
///////////////////////
///»» Aqui inicia »»//
/////////////////////
*/
	//Busca las asistencias del dia para la gráfica del home
	Route::get('getAsistencias', 'HomeController@getAsistencias');
	//Busca las inasistencias
	Route::get('getInasistencias', 'HomeController@getInasistencias');
	//Busca reposos cantidad de reposos
	Route::get('getRetrasos','HomeController@getRetrasos');
	//Busca los retiros anticipados
	Route::get('getRetirosant','HomeController@getRetirosant');
	//Busca Vacaciones
	Route::get('getVacaciones', 'HomeController@getVacaciones');
	//Busca cantidad de permisos remunerados
	Route::get('getPremunerados','HomeController@getPremunerados');
	//Busca cantidad de permisos NO remunerados
	Route::get('getPnremunerados', 'HomeController@getPnremunerados');
	//Busca cantidad de reposos
	Route::get('getReposos','HomeController@getReposos');
	//Busca cantidad de salidas sin marcar
	Route::get('getSalidasm', 'HomeController@getSalidasm');
	//Ruta de asistencia actual 
	Route::get('asi_actual','ControlController@asi_actual');

/*
////////////////////////
///«« Aqui termina ««//
//////////////////////
*/

/*
  ///////////////////////////////////////////////////////////////
 //          *** RUTAS DE LA VISTA DE EMPLEADOS ******        //
///////////////////////////////////////////////////////////////
*/


Route::resource('empleados','UsuarioController'); // index
Route::post('registrar', 'UsuarioController@store'); //registra el usuario

Route::get('usuariosdp/{id}', 'UsuarioController@getUsuariosdp' ); // Rutas para buscar empleados por departamentos por ajax
//Route::get('empleadoss', 'UsuarioController@getUsuarios');

// Busca a el empleado por el numero de cédula
Route::get('getced/{cedula}', 'UsuarioController@getUsuariosced' );
Route::get('getcedu/{cedula}', 'UsuarioController@getcedu' );
//Busca los catos del empleado
Route::get('getEmp/{cedula}', 'UsuarioController@getEmp');

//Busca todos los departamentos para la modal edit
Route::get('getDpUs', 'UsuarioController@getDpUs');
//Busca todos los role para la modal edit
Route::get('getRolUs', 'UsuarioController@getRolUs');
//Busca todos los horarios para la modal edit
Route::get('getHoraUs', 'UsuarioController@getHoraUs');
//Busca todos los tipos de usuario para la modal edit
Route::get('getTuUs','UsuarioController@getTuUs');
//Busca las preguntas secretas
Route::get('getPrgus','UsuarioController@getPrgus');
//Busca a los empleados por su nombre 
Route::get('getName/{name}', 'UsuarioController@getName');

//Refresca la pagina de empleados
Route::get('getUsers', 'UsuarioController@getUsers');

Route::post('updateE', 'UsuarioController@update');

Route::get('getNameLdap/{name}','LdapController@getNameLdap');

Route::get('getCedLdap/{ced}','LdapController@getCedLdap');

Route::get('getCedSigesp/{ced}','SigespController@getCedSigesp');

Route::get('existUs/{ced}','UsuarioController@search_local_user');

Route::get('sigesp/{ced}','LdapController@sigesp');

Route::post('cambiar_pass','UsuarioController@cambiar_pass');

Route::get('get_login/{login}','UsuarioController@get_login');

//Para hacer el insert masivo
Route::get('insert_m','LdapController@insert_ldap_sigesp');

//Route::get('sigesp_consulta', 'UsuarioController@getUsuariosceddp' );

/*Route::match(['get', 'post'],'Empleados',[
	'uses' =>'UsuarioController@buscar',  //ruta para buscar empleados
	'as' => 'buscare'
]);*/


/*
  ///////////////////////////////////////////////////////////////
 //          *** RUTAS DE LA VISTA DE HORARIOS ******         //
///////////////////////////////////////////////////////////////
*/

//Vista
Route::resource('horarios','HorariosController'); //INDEX

Route::post('horario','HorariosController@store'); //REGISTRAR UN NUEVO HORARIO
//ruta para editar horario
Route::get('horarioedit/{id}', 'HorariosController@update');
//Ruta para eliminar horario
Route::get('horariodelete/{id}', 'HorariosController@destroy');
//Ruta para buscar datos del horario a editar
Route::post('update', 'HorariosController@edit');
//Ruta para buscar empleados del departamento
Route::get('dptoHorario/{dpto}','HorariosController@dptoHorario');
//Ruta para asignar horarios en masa
Route::post('asigmasa','HorariosController@asigmasa');
//Ruta para activar un horario que ha sido eliminado
Route::get('horaActive/{id}','HorariosController@horaActive');


/*
  ///////////////////////////////////////////////////////////////
 //          *** RUTAS DE LA VISTA DE CONTROL ******          //
///////////////////////////////////////////////////////////////
*/

Route::get('getTipoDF','DiasFController@getTipoDF');

Route::get('inasis_act','ControlController@inasis_act');
//vista principal de control
Route::resource('control', 'ControlController');

//Vista de resultados de la busqueda de asistencia por empleado
Route::resource('resumen','ControlController@buscar');

//Busqueda general de empleados 
Route::get('departamento/{id}', 'ControlController@getDepartamento' );

//busqueda de empleado por cedula en la vista de control
Route::get('controlcedula/{ced}', 'ControlController@getUsuarioc' );

//Busqueda de todos los empleados en la vista de control
Route::get('todoscontrol', 'ControlController@getUsuarios' );

//Envia las variables al controllador para enviar los datos a la vista, recibirlos por el js y hacer el calculo de las horas
Route::get('asistencia/{id}', 'ControlController@getAsistencia' );

//Busqueda de asistencia de empleado por rango
Route::get('rango/{id}/{desde}/{hasta}', 'ControlController@getRango');

Route::get('dia/{id}/{dia}', 'ControlController@getDia');

Route::post('controldp','ControlController@controldp');

Route::get('inasis_dia','ControlController@inasis_dia');

Route::get('reposoAsi/{id}', 'ControlController@getExcepciones');

Route::post('buscarfecha','ControlController@getfecha');

Route::get('buscarexepRango/{id}/{desde}/{hasta}', 'ControlController@buscarexepRango');

Route::get('buscarexepDia/{id}/{dia}', 'ControlController@buscarexepDia');

Route::post('resumeng','ControlController@resumeng');

Route::get('guardar_status','ControlController@guardar_status');

Route::post('regresar_dpto','ControlController@regresar_dpto');

/*
  /////////////////////////////////////////////////////////////////////////////////
 //              *** RUTAS PARA GENERAR ARCHIVOS PDF Y EXCEL******              //
/////////////////////////////////////////////////////////////////////////////////
*/


//Asistencia normal

//General
Route::get('generarpdfg/{dia}/{desde}/{hasta}/{departamento}/{sabdom}/{tipo}', 'ControlController@generar_pdf_g');
Route::get('generarexcelg/{dia}/{desde}/{hasta}/{departamento}/{sabdom}/{tipo}', 'ControlController@generar_excel_g');
//Por empleado
Route::get('generarpdf/{ced}/{dia}/{desde}/{hasta}/{sabdom}/{tipo}', 'ControlController@generar_pdf');
use Maatwebsite\Excel\Facades\Excel;

Route::get('generarexcel/{ced}/{dia}/{desde}/{hasta}/{sabdom}/{tipo}' ,'ControlController@generar_excel');

//Asistencia excel fin de semana
//Por empleado
Route::get('generarexcelsabdom/{ced}/{dia}/{desde}/{hasta}/{sabdom}/{tipo}' ,'ControlController@generarexcelsabdom');

//General
Route::get('generarexcelgsabdom/{dia}/{desde}/{hasta}/{departamento}/{sabdom}/{tipo}','ControlController@generarexcelgsabdom');


Route::get('excell/{dia}/{desde}/{hasta}/{departamento}/{tipo}','ControlController@excell');


/*
  //////////////////////////////////////////////////////////////////////////////////
 //          *** RUTAS DE LA VISTA DE ASISTENCIA GENERAL (MARCAJE) ******        //
//////////////////////////////////////////////////////////////////////////////////
*/


Route::resource('asistenciag', 'AsistenciaController'); // INDEX
Route::post('asistencia', 'AsistenciaController@store'); // REGISTRA LA ASISTENCIA

Route::get('marcar_entrada/{cod}', 'AsistenciaController@marcar_entrada');

Route::get('nuevo_cliente', 'Acceso_clienteController@create' );
Route::resource('admin_cliente', 'Acceso_clienteController' );
Route::post('guardacliente', 'Acceso_clienteController@store'); 
Route::get('editacceso/{id}', 'Acceso_clienteController@edit');
Route::post('updatecliente','Acceso_clienteController@update');

Route::resource('notificaciones','NotificacionesController');
Route::get('get_entradas_dia','NotificacionesController@get_entradas_dia');
Route::get('get_salidas_dia','NotificacionesController@get_salidas_dia');
Route::get('get_not_dia','NotificacionesController@get_not_dia');
Route::get('delete_alerta/{id}','NotificacionesController@delete_alerta');
Route::get('BcedulaNot/{cedula}', 'NotificacionesController@Bcedula'); 
Route::get('getDepNot/{id}', 'NotificacionesController@getDepAut');
/*
  ///////////////////////////////////////////////////////////////////////////////
 //          *** RUTAS DE LA VISTA DE ASISTENCIA EXTRAORDINARIA ******        //
///////////////////////////////////////////////////////////////////////////////
*/


//Route::resource('asistenciaextra','AsistenciaextraController');
Route::get('buscaasi/{id}', 'AsistenciaextraController@getUsuariosdp' ); // Rutas para buscar empleados por departamentos por ajax
Route::get('buscarcedulaextra/{id}', 'AsistenciaextraController@getUsuarioc' );
Route::match(['get', 'post'],'AsistenciaExtra',[
	'uses' =>'AsistenciaextraController@buscar',  //ruta para buscar asistencia 
	'as' => 'buscarasi'
]);
Route::post('registrarentrada','AsistenciaextraController@registrarentrada');//REGISTRA ASISTENCIA EXTRA
Route::post('registrarsalida','AsistenciaextraController@registrarsalida');//REGISTRA ASISTENCIA EXTRA

//Elimina Todos los registros de asistencia (Uso solo para pruebas)
Route::get('limpiarAsi', 'AsistenciaextraController@LimpiarAsistencia' );
Route::get('buscadatos/{id}', 'AsistenciaextraController@getDatos' );
Route::get('buscadatos2/{id}', 'AsistenciaextraController@getDatos2' );
Route::get('buscadatos3/{id}', 'AsistenciaextraController@getDatos3' );
Route::get('obtener_hora_fechaE/{fecha_entrada}/{id}','AsistenciaextraController@getAsiDate');


Route::get('refrescarex','AsistenciaextraController@refrescarex');


/*
  ///////////////////////////////////////////////////////////////
 //          *** RUTAS DE LA VISTA DE REPOSOS ******          //
///////////////////////////////////////////////////////////////
*/

Route::resource('reposos', 'RepososController'); //INDEX

Route::match(['get', 'post'],'Reposos',[
	'uses' =>'RepososController@buscarep',  //ruta para buscar reposos
	'as' => 'buscarre'
]);
Route::get('reposo/{cedula}', 'RepososController@Reposo');
Route::get('reposoG/{cedula}', 'RepososController@ReposoG');
Route::post('registrarepo', 'RepososController@store');
Route::get('todosreposo', 'RepososController@getUsuarios' );
Route::get('buscaReposo/{idR}/{idUs}','RepososController@buscaReposo');
Route::get('tipoRe', 'RepososController@tipoRe');
Route::post('updateReposo', 'RepososController@update');

Route::get('buscaPer/{desde}/{us}', 'RepososController@buscaPer');

Route::get('buscaRep/{desde}/{us}', 'RepososController@buscaRepo');

Route::get('buscaVac/{desde}/{us}', 'RepososController@buscaVac');

Route::get('buscaAu/{desde}/{us}', 'RepososController@buscaAu');

Route::get('getdpReposos/{id}','RepososController@getdpReposos');
Route::get('reposo_delete/{id}','RepososController@delete');
//Activar reposo eliminado
Route::get('repActive/{id}','RepososController@repActive');

//Ruta home vista reposos
Route::get('repo_act','RepososController@repo_act');

Route::get('ver_reposo/{archivo}', function ($archivo) {
     
	$public_path = public_path();
    $url = $public_path.'/storage/reposos/'.$archivo;
    
    if ($archivo != 't.pdf')
    {
       return response()->download($url);
    }
    else{
     	return view('Asistencia/Excepciones/archivonoencontrado');
    }

});

/*
  ///////////////////////////////////////////////////////////////
 //          *** RUTAS DE LA VISTA DE PERMISOS ******         //
///////////////////////////////////////////////////////////////
*/


Route::resource('permisos', 'PermisosController'); //INDEX

Route::get('regper/{cedula}', 'PermisosController@permiso');

Route::match(['get', 'post'],'Permisos',[
	'uses' =>'PermisosController@buscar',  //ruta para buscar PERMISOS
	'as' => 'buscarpermiso'
]);
Route::get('buscaCedPer/{ced}','PermisosController@buscaCedPer');
Route::post('registraper', 'PermisosController@store'); // RUTA PARA REGISTRAR REPOSOS
Route::get('refrescarP', 'PermisosController@refrescarP');
Route::get('buscaPermiso/{idP}/{idUs}','PermisosController@show');
Route::post('updatePermiso', 'PermisosController@update');
Route::get('getdepp/{id}', 'PermisosController@getdepp');

Route::get('buscaPerP/{desde}/{us}', 'PermisosController@buscaPer');

Route::get('buscaRepP/{desde}/{us}', 'PermisosController@buscaRepo');

Route::get('buscaVacP/{desde}/{us}', 'PermisosController@buscaVac');

Route::get('buscaAuP/{desde}/{us}', 'PermisosController@buscaAu');

Route::get('permiso_delete/{id}','PermisosController@delete');

Route::get('permi_act','PermisosController@permi_act');

//Ruta para activar permisos eliminados
Route::get('perActive/{id}','PermisosController@perActive');


Route::get('ver_permiso/{archivo}', function ($archivo) {
    $public_path = public_path();
    $url = $public_path.'/storage/reposos/'.$archivo;
     //verificamos si el archivo existe y lo retornamos
    if ($archivo != 't.pdf')
    {
       return response()->download($url);
    }
    else{
     	return view('Asistencia/Excepciones/archivonoencontrado');
    }

});


/*
  ///////////////////////////////////////////////////////////////
 //         *** RUTAS DE LA VISTA DE VACACIONES ******        //
///////////////////////////////////////////////////////////////
*/


Route::resource('vacaciones', 'VacacionesController');
Route::match(['get', 'post'], 'Vacaciones', [
	'uses' => 'VacacionesController@buscar', //Ruta para buscar vacaciones
	'as' => 'buscarvaca'
]);
Route::post('registrarVacaciones', 'VacacionesController@store');

Route::get('bvacaciones/{cedula}', 'VacacionesController@vacaciones');
Route::get('busuario/{cedula}' , 'VacacionesController@Busuario');
Route::get('buscarVac/{idV}/{idU}','VacacionesController@show');
Route::post('updateVacaciones', 'VacacionesController@update');
Route::get('getdpVac/{id}', 'VacacionesController@getdpVac');

Route::get('buscaPerV/{desde}/{us}', 'VacacionesControlle@buscaPer');

Route::get('buscaRepV/{desde}/{us}', 'VacacionesControlle@buscaRepo');

Route::get('buscaVacV/{desde}/{us}', 'VacacionesControlle@buscaVac');

Route::get('buscaAuV/{desde}/{us}', 'VacacionesControlle@buscaAu');

Route::get('vacaciones_delete/{id}','VacacionesController@delete');

/*
  ///////////////////////////////////////////////////////////////
 //        *** RUTAS DE LA VISTA DE AUTORIZACIÓN ******       //
///////////////////////////////////////////////////////////////
*/
Route::resource('autorizacion', 'AutorizacionController');

Route::post('registrarAuto', 'AutorizacionController@store');

Route::get('autorizacion1/{id}', 'AutorizacionController@Regauto');

// Busca autorizaciones de un día por ajax
Route::get('Bdia/{fecha}', 'AutorizacionController@Bdia'); 

//Busca autorizaciones por rango ajax
Route::get('Brango/{fecha}/{fecha2}', 'AutorizacionController@Brango'); 

// Busca datos de empleado por ajax
Route::get('Bcedula/{cedula}', 'AutorizacionController@Bcedula'); 

// Busca datos de empleado  en rango de fehapor ajax
Route::get('BCedulaRango/{fecha}/{fecha2}/{cedula}', 'AutorizacionController@BCedulaRango'); 
 //Busca autorizaciones por rango ajax
Route::get('BCedulaDia/{fecha}/{cedula}', 'AutorizacionController@BCedulaDia');
//Busca los datos del usuario y de la autorizacion
Route::get('buscaAut/{idAu}/{idUs}', 'AutorizacionController@show');
//Editar autorización
Route::post('updateAutorizacion', 'AutorizacionController@update');
//Busca todos los tipos de autorizacion para la modal de editar en la ventana de autorización 
Route::get('buscaTipoaut', 'AutorizacionController@buscaTipoaut');
//Refresca la pagina de autorizacion
Route::get('refrescara','AutorizacionController@refrescara');
Route::get('getDepAut/{id}', 'AutorizacionController@getDepAut');

Route::get('autorizacion_delete/{id}','AutorizacionController@delete');

Route::get('autoActive/{id}','AutorizacionController@autoActive');


/*
  ///////////////////////////////////////////////////////////////
 //            *** RUTAS DE LA VISTA DE ROLES ******          //
///////////////////////////////////////////////////////////////
*/
Route::get('limpiaRol', function(){
	\Asistencias\roles::truncate();
    return redirect()->back();
});
Route::resource('roles', 'RolesController');
Route::post('registroRol','RolesController@store');
Route::get('accesos/{rol}','RolesController@accesos');
Route::get('rolesedit/{id}', 'RolesController@rolesedit');
Route::get('roldelete/{id}', 'RolesController@destroy');


/*
  ///////////////////////////////////////////////////////////////////
 //          *** RUTAS DE LA VISTA DE DÍAS FERIADOS ******        //
///////////////////////////////////////////////////////////////////
*/

//Vista principal
Route::resource('diasf', 'DiasFController');
//Mostrar datos de día feriado en modal editar
Route::get('editFeriados/{id}','DiasFController@show');
//Ruta para registrar dias feriados
Route::post('feriado', 'DiasFController@store');
//Ruta para editar dia feriado
Route::post('updateFeriado', 'DiasFController@update');
//Ruta para aliminar Dia feriado
Route::get('feriadosdelete/{id}','DiasFController@destroy');
Route::get('diafActive/{id}','DiasFController@diafActive');


/*
  ///////////////////////////////////////////////////////////////
 //           *** RUTAS DE  DE AUDITORIA ******          //
///////////////////////////////////////////////////////////////
*/
Route::resource('auditoria', 'AudController');
Route::get('auditoria_pdf','AudController@auditoria_pdf');
Route::post('aud_search','AudController@aud_search');

Route::get('auditoria_searchpdf/{desde}/{hasta}/{dia}/{usuario}/{ipb}/{name_m}/{explorer_m}','AudController@auditoria_pdf_b');

/*
  ///////////////////////////////////////////////////////////////
 //      *** RUTAS DE  DE ACTUALIZACIÓN DE TABLAS ******      //
///////////////////////////////////////////////////////////////
*/
Route::resource('conexion', 'ActTablasController');
Route::post('update_cof','ActTablasController@update_cof');
//Registra una nueva pregunta de seguridad;
Route::post('new_preg','ActTablasController@new_preg');
//Obtiene los datos de la pregunta de seguridad a editar
Route::get('get_preg/{id}','ActTablasController@get_preg');
//Edita los datos de la pregunta de seguridad
Route::post('update_preg','ActTablasController@update_preg');

//Registra el nombre de una nueva pantalla 
Route::post('new_pantalla','ActTablasController@new_pantalla');
Route::post('new_mod','ActTablasController@new_mod');

//Registra un nuevo tipo de autorización
Route::post('new_tiau','ActTablasController@new_tiau');
//Obtener tipo de autorización
Route::get('get_tiau/{id}','ActTablasController@get_tiau');
//Editar tipo autorización
Route::post('update_tiau','ActTablasController@update_tiau');


//Registra un nuevo tipo de dia feriado
Route::post('new_tdf','ActTablasController@new_tdf');
//Obtener tipo de dia feriado
Route::get('get_tdf/{id}','ActTablasController@get_tdf');
Route::post('update_tife','ActTablasController@update_tife');


//Actualiza datos de la empresa
Route::post('update_datos_emp','ActTablasController@update_datos_emp');
//Registra un nuevo tipo de reposo
Route::post('new_tire','ActTablasController@new_tire');
//Obtener tipo de reposo
Route::get('get_tire/{id}','ActTablasController@get_tire');
Route::post('update_tire','ActTablasController@update_tire');


//Registra un nuevo tipo de usuario
Route::post('new_tdu','ActTablasController@new_tdu');
//Obtener tipo de usuario
Route::get('get_tdu/{id}','ActTablasController@get_tdu');
//Editar tipo de usuario
Route::post('update_tdu','ActTablasController@update_tdu');


//Gestion de departamentos

Route::get('get_ced_dp/{ced}','ActTablasController@get_ced_dp');

Route::post('guarda_dp','ActTablasController@guarda_dp');
Route::get('get_info_dp/{id}','ActTablasController@get_info_dp');

Route::post('editar_dp','ActTablasController@editar_dp');

Route::get('dptodelete/{id}','ActTablasController@dptodelete');
/*
  ///////////////////////////////////////////////////////////////
 //           *** RUTAS DE  DE HOMECONTROLLER ******          //
///////////////////////////////////////////////////////////////
*/

Route::group(['before' => 'auth'], function()
{
	Route::get('home', 'HomeController@showLogin'); //vista de inicio en caso de que el usuario ya sea identificado
});

Route::post('Inicio',[
	'uses' =>'HomeController@iniciar',  //validar datos del login
	'as' => 'ingresar'
]);




Route::get('home', 'HomeController@home');
Route::get('acttablas', 'ActTablasController@acttablas');
Route::get('departamentos','ActTablasController@departamentos');


/*
  ///////////////////////////////////////////////////////////////////////////////
 //              *** RUTAS PARA EL ARCHIVO DE CONFIGURACIÓN ******            //
///////////////////////////////////////////////////////////////////////////////
*/

Route::get('statusLdap','ConfigController@statusLdap');
Route::get('statusSigesp','ConfigController@statusSigesp');

Route::get('logout', function(){
	session_start();
	session_destroy();
	return view('login');
});
//Route::get('logout', 'FrontControllers@logOut'); //Finalizar sesión



Route::get('formulario', 'ImagenesController@index');
Route::post('formulario', 'ImagenesController@create');
Route::get('storage/{archivo}', function ($archivo) {
     $public_path = public_path();
     $url = $public_path.'/imagenes/'.$archivo;
     //verificamos si el archivo existe y lo retornamos
     if (Storage::exists($archivo))
     {
       return response()->download($url);
     }
     else{
     	return 'Imagen no encontrada';
     }

});

//Ldap
Route::get('consulta','LdapController@ldap');

Route::get('/','HomeController@showLogin');
Route::get('exit','FrontControllers@login'); //Mostrar login salida











/*
  ///////////////////////////////////////////////////////////////////////////////
 //              *** RUTAS PARA VISTA DE CARNETS ******            //
///////////////////////////////////////////////////////////////////////////////
*/

//RUTAS PARA LA VISTA DE CARNET (Usuarios con carnet)
Route::resource('estadisticasCarnets', 'CarnetController');
 Route::get('carnet', 'CarnetController@conCarnets'); //index vista empleados con carnets
 Route::get('editarEmpleado/{id}', 'CarnetController@datosEmpleado'); //captura datos de empleado en modal para modificarlos
  Route::get('editEmpleadoCC/{id}', 'CarnetController@getDatoEmpleado'); //captura datos de empleado en modal para modificarlos
Route::get('vistaDatos/{id}', 'CarnetController@datosVista'); //captura datos de vista carnet en modal (Revisar con datos de sigesp)
Route::get('vistasDato/{id}', 'CarnetController@datosVista2'); //captura datos de vista carnet en modal (Revisar con datos de sigesp)

Route::post('updateEmpleado', 'CarnetController@modEmpleado'); // modifica datos del empleado con carnet 
Route::post('vistaPreviaEmpleado', 'CarnetController@modEmpleadoReportar'); // modifica datos del empleado con carnet para solo reportarlo
 Route::get('disenoCarnet', 'CarnetController@getPDF'); // muuestra pdf de carnets reportados
 Route::get('CodBarras', 'CarnetController@PDFCodBarras'); // muuestra pdf de carnets impreso nuevamente
Route::get('busEmpConCar/{id}', 'CarnetController@empleadoConCarnetBusca'); //captura datos de empleado en modal cuando hace busqueda
Route::get('vistasDatobusEmpConCar/{id}', 'CarnetController@empleadoConCarnetBuscaVista2'); //captura datos de vista carnet en modal (Revisar con datos de sigesp)

 Route::get('CodQR', 'CarnetController@getPDFCodQR'); // muuestra pdf de carnets impreso nuevamente


//RUTAS PARA LA VISTA DE CARNET (Usuarios sin carnet)
 Route::get('new_carnet', 'CarnetController@carnetNuevo'); // index 
 Route::get('crearCarnetEmp/{id}', 'CarnetController@datosEmpleadoNewCarnet'); //captura datos de empleado en modal para insertar registros de nuevo carnet
 Route::post('crearCarnet', 'CarnetController@registrarNewCarnet'); // Registra nuevo carnet
 Route::post('updateCarnet','CarnetController@cambio_prov_carem');
Route::get('disenoCarnetNew', 'CarnetController@pdfNewCarnet'); // muuestra pdf de carnets nuevos
Route::get('busEmpSinCar/{cedula}', 'CarnetController@empleadoSinCarnetBusca'); //captura datos de empleado en modal cuando hace busqueda

Route::get('busEmpCarProvi/{cedula}', 'CarnetController@empleadoCarnetProvBusca'); //captura datos de empleado en modal cuando hace busqueda carnet provisional empleado



//RUTAS PARA LA VISTA CARNETS PROVISIONALES
 Route::get('carnet_provisionales', 'CarnetController@provisionales'); // index con carnet
 Route::get('carnet_provisionales_new', 'CarnetController@provisionales_new'); // index sin carnet
 Route::get('carnet_seriales', 'CarnetController@seriales_carnet'); // index seriales
 Route::get('editarPasante/{id}', 'CarnetController@datosPasante'); //captura datos de pasante en modal pasante con carnet
 Route::get('editarProv/{id}', 'CarnetController@datosProv'); //captura datos de pasante en modal pasante con carnet
 Route::post('updatePasante', 'CarnetController@modPasante'); // modifica datos 
Route::get('busPasConCar/{cedula}', 'CarnetController@pasanteConCarnetBusca'); //captura datos de pasante en modal cuando hace busqueda
Route::post('createSerial', 'CarnetController@seriales'); // crea nuevos seriales de carnet provisional
Route::get('crearPasante/{id}', 'CarnetController@datosPasanteNew'); //captura datos de pasante en modal sin carnet
Route::post('createPasante', 'CarnetController@modPasantesNew'); // crea nuevo carnet
Route::get('carnetProvisional', 'CarnetController@carnetProvisionalNEW'); // muuestra pdf de carnets impreso (nueva asignacion)
Route::get('busPasSinCar/{cedula}', 'CarnetController@pasanteSinCarnetBusca'); //captura datos de pasante en modal cuando hace busqueda
 Route::get('listPasante/{codigo}', 'CarnetController@provisional_historico'); //captura datos de pasantes en modal para ver quienes tuvieron ese carnet provisional
Route::get('historico_carnet_provisional', 'CarnetController@Provisional_historico_carnet'); // INDEX historico pasantes
 Route::get('PDF_Carnet_Provisional', 'CarnetController@PdfHistoricoProvisional'); // muestra pdf de historico de carnets provisionales




// estadisticas de carnets
Route::get('Por_vencerse', 'CarnetController@vistaPorVencerse'); //index vista empleados con carnets por vencerse
Route::get('porVencerCarnet', 'CarnetController@porVencerse'); // modal para ver empleados con carnets por vencerse
Route::get('Carnets_Robados', 'CarnetController@vistaRobados'); //index vista empleados con carnets robados
Route::get('robadoCarnet', 'CarnetController@robados_car'); // modal para ver empleados con carnets robados
Route::get('Carnets_Hurtados', 'CarnetController@vistaHurtados'); //index vista empleados con carnets hurtados
Route::get('hurtadoCarnet', 'CarnetController@hurtados_car'); // modal para ver empleados con carnets hurtados
Route::get('Carnets_Extraviados', 'CarnetController@vistaExtraviados'); //index vista empleados con carnets extraviados
Route::get('extraviadoCarnet', 'CarnetController@extraviado_car'); // modal para ver empleados con carnets extraviados
Route::get('Carnets_Vencidos', 'CarnetController@vistaVencidos'); //index vista empleados con carnets vencidos
Route::get('vencidoCarnet', 'CarnetController@vencido_car'); // modal para ver empleados con carnets vencidos

Route::get('sinCarnet', 'CarnetController@noCarnet'); // modal para ver empleados que no tienen carnets 

//RUTAS PARA LA VISTA DE CONFIGURACION DE DISEÑO DE CARNET (IMAGEN DE FONDO)
  Route::get('conf_diseno_carnet', 'CarnetController@fondo_carnets');
  Route::post('gestionarCarnet', 'CarnetController@saveFondo');//Guarda imagen de fondo del carnet 
  Route::post('imagenPosterior', 'CarnetController@carnetPosterior'); // Modifica datos de imagen posterior del carnet

//RUTAS PARA LA VISTA DE HISTORICO DE IMPRESIONES DE CARNET 
Route::get('carnet_historico', 'CarnetController@historico_carnet'); // INDEX
Route::get('getHistorico/{cedula}', 'CarnetController@busHistory' );  // buscador carnet_historico-----
Route::get('getHistoricoProv/{cedula}', 'CarnetController@busHistoryProv' );  // buscador carnet_historico-----
 Route::get('PDF_Carnet_Historico', 'CarnetController@PdfHistoricoEmpleado'); // muestra pdf de historico de carnets 

 
// Captura datos de grafico de barras --------
Route::get('totalVencerse', 'CarnetController@porVencertotal'); // total de carnets por vencerse 
Route::get('totalRobados', 'CarnetController@robadosTotal'); // total de carnets robados 
Route::get('totalHurtados', 'CarnetController@hurtadosTotal'); // total de carnets hurtados 
Route::get('totalExtraviados', 'CarnetController@extraviadosTotal'); // total de carnets extraviados 
Route::get('totalVencidos', 'CarnetController@vencidosTotal'); // total de carnets vencidos 
Route::get('totalSinCarnets', 'CarnetController@sinCarnetTotal'); // total de emp sin carnets  

// PDF REPORTES---------

 Route::get('PDF_Sin-Carnet', 'CarnetController@PdfSinCarnet'); // muestra pdf de empleados sin carnets 
 Route::get('PDF_Con-Carnet', 'CarnetController@PdfConCarnet'); // muestra pdf de empleados con carnets 


 Route::get('PDF_Por_Vencerse', 'CarnetController@PdfPorVencerse'); // muestra pdf de carnets por vencerse
 Route::post('buscaFechasPorVencerse', 'CarnetController@PdfPorVencerseFechas'); // muestra pdf de carnets por vencerse por rango de fechas
 Route::get('PDF_Carnet_Robados', 'CarnetController@PdfRobados'); // muestra pdf de carnets robados
 Route::post('buscaFechasRobados', 'CarnetController@PdfRobadosFechas'); // muestra pdf de carnets por vencerse por rango de fechas

 Route::get('PDF_Carnet_Hurtado', 'CarnetController@PdfHurtados'); // muestra pdf de carnets hurtados
 Route::post('buscaFechasHurtados', 'CarnetController@PdfHurtadosFechas'); // muestra pdf de carnets hurtados por rango de fechas

 Route::get('PDF_Carnet_Extraviado', 'CarnetController@PdfExtraviados'); // muestra pdf de carnets extraviados
  Route::post('buscaFechasExtraviados', 'CarnetController@PdfExtraviadosFechas'); // muestra pdf de carnets hurtados por rango de fechas

 Route::get('PDF_Carnet_Vencido', 'CarnetController@PdfVencidos'); // muestra pdf de carnets vencidos
  Route::post('buscaFechasVencidos', 'CarnetController@PdfVencidosFechas'); // muestra pdf de carnets hurtados por rango de fechas


 Route::get('editarEmpleadoPV/{id}', 'CarnetController@modalPorVencerse'); //captura datos de empleado en modal de carnet por vencerse para modificarlos
Route::post('EmpleadoPorVencerse', 'CarnetController@modCarnetPorVencerse'); // modifica datos del empleado con carnet por vencerse/vencido
Route::get('busCarPorVencerse/{cedula}', 'CarnetController@busPorVencerse'); //captura datos de empleado en modal cuando hace busqueda
 Route::get('getRobados/{cedula}', 'CarnetController@buskRobados'); //busca carnets robados 

 Route::get('getHurtado/{cedula}', 'CarnetController@buskHurtados'); //busca carnets hurtado 

 Route::get('getExtraviado/{cedula}', 'CarnetController@buskExtraviado'); //busca carnets extraviado 

 Route::get('getVencido/{cedula}', 'CarnetController@buskVencidos'); //busca carnets vencidos 


//Route::resource('conf', 'CarnetController');

Route::post('prueba', 'CarnetController@store');

Route::post('encabezadoSave', 'CarnetController@carnetsPosteriore');


