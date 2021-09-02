<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="I14KMFPhosSaHwjSeIYEAvpolp6cTkVqYBpYiTov">
    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/marcaje/cliente.css')}}">


	<title>Admin Cliente</title>
</head>
	<div class="container-fluid">
		<div class="row">
			<div class=" franjas ">
					<a href="inicio" class="butn btn-cabecera" >
						<img src="assets/img/iconos/home1.png" class="imgmenuh" />
					</a>
					<!-- Botones para USUARIO -->

					<a href="#" class="buton btn-barra" style=" z-index: 3; position: relative;">
						<img src="assets/img/iconos/usuario1.png" class="imagencabecera" />
					</a>
					<a href="empleados" class="buton btn-barra">
						<img src="assets/img/iconos/empleado.png" class="imagencabecera" />
					</a>

					<a id="horario" href="inicio" class="buton btn-barra">
						<img src="assets/img/iconos/horario.png" class="imagencabecera" />
					</a>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-2">
				<div id="sidebar" class="sidebar">
					<!--Boton para abrir el menú-->
					<a id="abrir" class="abrir-cerrar" onclick="mostrar()">
						<img src="assets/img/iconos/menu.svg" class="abrir">
					</a>
					<!--Boton para cerrar el menú-->
					<a href="#" id="cerrar" class="boton-cerrar" onclick="ocultar()" >
						<img src="assets/img/iconos/menu.svg" class="cerrar">
					</a>
					<ul id="mimenu">
						<div class="list-group ">
						    <li><a href="#"><label><img src="assets/img/iconos/welcome.png" class="asistra">ASISTRA</label></a></li>
						    <li>
						    	<a class="linea" href="#">
							    	<img src="assets/img/iconos/nombre.svg" class="imgmenu6">Rol/Nombre
						    	</a>
						    </li>
						    <li>
						    	<a class="linea" href="home">
						    	<img src="assets/img/iconos/home.png" class="imgmenu7"> Home
						    	</a>
						    </li>
						    <li>
						    	<!-- aqui-->	
					    		<a href="#us" class="linea" data-toggle="collapse" data-parent="#mimenu">
							    	<img src="assets/img/iconos/usuario1.png" class="imgmenu6">Usuarios
							    	</a>
						    	<!-- aqui-->
						    	<div class="collapse" id="us">
						        	<a onclick="mostrar_btn_empleado()" href="empleados">
						        		<img src="assets/img/iconos/empleado.png" class="imgmenu5">
						        		Empleados						        		
						        	</a>
						      		<a href="horarios" >
						      			<img src="assets/img/iconos/horario.png" class="imgmenu1">Horarios	
						      		</a>

						        	<a href="carnet">
						        		<img src="assets/img/iconos/asistenciag.png" class="imgmenu5">
						        		Carnet
						        	</a>
						    	</div>
						    </li>
						    <li>
						    	<a href="#asi" class="linea" data-toggle="collapse" data-parent="#mimenu">
						    		<img src="assets/img/iconos/asistencia.png" class="imgmenu7">
						    		Asistencia
						    	</a>
						    	<div class="collapse" id="asi">
						        	<a href="control">
						        		<img src="assets/img/iconos/control.png" class="imgmenu5">
						        		Control
						        	</a>
						        	<a href="asistenciag">
						        		<img src="assets/img/iconos/asistenciag.png" class="imgmenu5">
						        		Asistencia G.
						        	</a>
						        	<a href="asistenciaextra">
						        		<img src="assets/img/iconos/asistenciaextra.png" class="imgmenu5">
						        		Asistencia Ex.</a>

						      		<a href="#submenu" data-toggle="collapse" data-parent="#asi">
						      			<img src="assets/img/iconos/excepciones.png" class="imgmenu6">
						      			Excepciones
						      		</a>
						      		<div class="collapse" id="submenu">
						        		<a href="reposos">
						        			<img src="assets/img/iconos/reposos.png" class="imgmenu4">
						        			Reposos
						        		</a>
						      			<a href="permisos">
						      				<img src="assets/img/iconos/permisos.png" class="imgmenu2">
						      				Permisos
						      			</a>
						      			<a href="vacaciones">
							      			<img src="assets/img/iconos/vacaciones.png" class="imgmenu2">
							      			Vacaciones
						      			</a>
						      			<a href="autorizacion">
						      			<img src="assets/img/iconos/autorizacion.svg" class="imgmenu3">
						      			Autorización
						      			</a>
						    		</div>
						    	</div>
						    </li>
						    <li>
						    	<a href="#conf" class="linea" data-toggle="collapse" data-parent="#mimenu">
						    		<img src="assets/img/iconos/configuracion.png" class="imgmenu3">Configuracion
						    	</a>
						    	<div class="collapse" id="conf">
						        	<a href="roles">
						        		<img src="assets/img/iconos/roles.svg" class="imgmenu4">
						        		Roles
						        	</a>
						        	<a href="diasf">
						        		<img src="assets/img/iconos/diasf.png" class="imgmenu1">
						        		Días Feriados
						        	</a>
						        	<a href="estadisticas">
						        		<img src="assets/img/iconos/estadisticas.png" class="imgmenu1"">
						        		Estadísticas
						        	</a>
						      		<a href="auditoria">
						      			<img src="assets/img/iconos/auditoria.svg" class="imgmenu1">
						      			Auditoría
						      		</a>
						      		<a href="acttablas">
						      			<img src="assets/img/iconos/acttablas.png" class="imgmenu1">
						      			Act. de Tablas
						      		</a>
						      		<a href="respaldo">
							      		<img src="assets/img/iconos/respaldo.png" class="imgmenu1">
							      		Respaldo
						      		</a>
						    	</div>
						    </li>
						</div>
					</ul>  
				</div>
			</div>
			<div id="contenido">
				
				@yield('contenido')
			</div>
		</div>
	</div>
			</div>

	<script src="{{asset('assets/js/jquery.js')}}"></script>
	<script src="{{asset('assets/js/funcion_menu.js')}}"></script>
	<script src="{{asset('assets/js/Usuarios/buscar_usuario.js')}}"></script>	
	<script src="{{asset('assets/js/Usuarios/dropdown.js')}}"></script>	
	<script src="{{asset('assets/js/Usuarios/horarios.js')}}"></script>
	<script src="{{asset('assets/js/Asistencia/asistenciaextra.js')}}"></script>
	<script src="{{asset('assets/js/Asistencia/excepciones/reposos.js')}}"></script>
	<script src="{{asset('assets/js/Asistencia/excepciones/permisos.js')}}"></script>
	<script src="{{asset('assets/js/Asistencia/excepciones/vacaciones.js')}}"></script>
	<script src="{{asset('assets/js/Asistencia/excepciones/autorizacion.js')}}"></script>
	<script src="{{asset('assets/js/Asistencia/hora_marcaje.js')}}"></script>
	<script src="{{asset('assets/js/funciones.js')}}"></script>
 	<script src="{{asset('assets/js/bootstrap.min.js')}}"></script>
 	<script src="{{asset('assets/js/angular.min.js')}}"></script>
	<script src="{{asset('assets/js/config.js')}}"></script>
 	<script src="{{asset('assets/js/ui-bootstrap-tpls-2.5.0.min.js')}}"> </script>
 	<script type="text/javascript"> $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });</script>

</body></html>