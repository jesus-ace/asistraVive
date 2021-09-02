
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="I14KMFPhosSaHwjSeIYEAvpolp6cTkVqYBpYiTov">
    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/mycss.css">
    <!--<link rel="stylesheet" href="assets/css/sweetalert.css">-->

	<title>ASISTRA</title>
</head>
<body>
	<input type="hidden" name="id_rol" id="id_rol" value="{{$_SESSION['acceso']}}">
	<div class="container-fluid" >
		<div class="row" style="z-index: 1000;">
			<div class="franjas">
				<a href="home" class="butn btn-cabecera" >
					<img src="assets/img/iconos/nuevos/home.svg" class="imgmenuh" />
					<label style="color: white; font-size: 14px; margin-left: 10px;">ASISTRA</label>
				</a>


				<!-- Botones para modulo USUARIO -->

				<a href="#" class="buton btn-barra" style=" z-index: 3; position: relative; display:none;" id="usuarios">
					<img src="assets/img/iconos/usuarios/usuarios.svg" class="imagencabecera" />
				</a>		
				<a href="empleados" class="buton btn-barra" id="empleados" title="Empleados" style=" display:none;">
					<img src="assets/img/iconos/usuarios/empleados.svg" class="imagencabecera" />
				</a>
				<a href="horarios" class="buton btn-barra" id="horarios" title="Horarios" style=" display:none;">
					<img src="assets/img/iconos/horario.svg" class="imagencabecera" />
				</a>


				<!--Botones para modulo Asistncia -->

				<a href="#" class="buton btn-barra" style="z-index: 3; position: relative; display:none;" id="asistencia" title="Asistencia">
					<img src="assets/img/iconos/asistencia.svg" class="imagencabecera" />
				</a>

				<a href="control" class="buton btn-barra" id="control" title="Control" style=" display:none;">
					<img src="assets/img/iconos/control.svg" class="imagencabecera" />
				</a>

				<a href="resumen" class="buton btn-barra" id="resumen" title="Resumen de Asistencia" style=" display:none;">
					<img src="assets/img/iconos/resumen.svg" class="imagencabecera" />
				</a>

				<a href="asistenciaextra" class="buton btn-barra" id="asistenciaextra" title="Asistencia Extraordinaria" style=" display:none;">
					<img src="assets/img/iconos/asistenciaextra.svg" class="imagencabecera" />
				</a>

				<a href="asistenciag" class="buton btn-barra" id="asistenciag" title="Marcaje" style=" display:none;">
					<img src="assets/img/iconos/marcaje.png" class="imagencabecera" />
				</a>

				<a href="notificaciones" class="buton btn-barra" id="notificaciones" title="Notificaciones" style=" display:none;">
					<img src="assets/img/iconos/notificaciones.svg" class="imagencabecera" />
				</a>

					<!--Botones para submodulo Excepciones-->

					<a href="#" class="buton btn-barra" id="excepciones" title="Excepciones" style=" display:none;">
						<img src="assets/img/iconos/excepciones.svg" class="imagencabecera" />
					</a>

					<!-- Tipos de excepciones --> 

						<a href="reposos" class="buton btn-barra" id="reposos" title="Reposos" style=" display:none;">
							<img src="assets/img/iconos/reposos.svg" class="imagencabecera" />
						</a>
						<a href="permisos" class="boton btn-barras" id="permisos" title="Permisos" style=" display:none;">
							<img src="assets/img/iconos/permisos.svg" class="imagencabecera" />
						</a>
						<a href="vacaciones" class="boton btn-barras" id="vacaciones" title="Vacaciones" style=" display:none;">
							<img src="assets/img/iconos/vacaciones.svg" class="imagencabecera" />
						</a>
						<a href="autorizacion" class="boton btn-barras" id="autorizacion" title="Autorización" style=" display:none;">
							<img src="assets/img/iconos/autorizacion.svg" class="imagencabecera" />
						</a>


				<!--Botones para modulo Configuración-->

				<a href="#" class="buton btn-barra" style="z-index: 3; position: relative;  display:none;" id="config" title="Configuración">
					<img src="assets/img/iconos/configuracion.svg" class="imagencabecera" />
				</a>
				<a href="roles" class="buton btn-barra" id="roles" title="Roles" style=" display:none;">
					<img src="assets/img/iconos/roles.svg" class="imagencabecera" />
				</a>
				<a href="diasf" class="buton btn-barra" id="diasf" title="Días Feriados" style=" display:none;">
					<img src="assets/img/iconos/diasf.svg" class="imagencabecera" />
				</a>
				<a href="estadisticas" class="buton btn-barra" id="estadisticas" title=" Estadísticas" style=" display:none;">
					<img src="assets/img/iconos/estadisticas.svg" class="imagencabecera" />
				</a>
				<a href="acttablas" class="buton btn-barra" id="act" title="Actualización de tablas" style=" display:none;">
					<img src="assets/img/iconos/acttablas.svg" class="imagencabecera" />
				</a>
				<a href="auditoria" class="buton btn-barra" id="auditoria" title="Auditoría" style="display: none;">
					<img src="assets/img/iconos/auditoria.svg" class="imagencabecera" />
				</a>
				<a href="departamentos" class="buton btn-barra" id="departamentos" title="Gestión de Departamentos" style=" display:none;">
					<img src="assets/img/iconos/departamentos.svg" class="imagencabecera" />
				</a>
				<ul class="nav navbar-nav navbar-right list-group" ">
					<li class="col-lg-10" style="color:#4D94B7;margin-top:3%;margin-right:-20%;text-shadow: 6px 4px 14px #3d677b99; font-size: 20px;">
						
						<b>{{$_SESSION['nombre']}}</b>
					</li>
			   	
			        <li class="col-lg-4">
	                	<!--Boton para abrir el menú-->
						<p class="a-c" data-toggle="collapse" onclick="mostrarr()">
							<img src="imagenes2/{{$_SESSION['foto']}}" align="right" style=" height: 50px; width: 60px; border-radius: 25px 0px 25px 0px; border: 2px #4D94B7 solid; margin-top: -1px; margin-right: 17px;" class="ab"  id="ab" >
						</p><!--Boton para cerrar el menú-->
						<p class="a-c"  data-toggle="collapse" onclick="ocultarr()">
							<img src="imagenes2/{{$_SESSION['foto']}}" align="right" style=" height: 50px; width: 60px; border-radius: 25px 0px 25px 0px; border: 2px #4D94B7 solid; margin-top: -11px; margin-right: 17px;" class="crr"  id="crr" >
						</p>
						<div id="sidebara" class="sidebara">
							<div>
								
								<ul style="font-size: 12px;">
									<!--<li style="margin-left: 10px;">
					                    <a href="#" style="margin-top: -30px;" onclick="cambiar_pass()">
					                    	<img src="assets/img/iconos/Login/candadito.svg" style="height: 28px;">
					                        <b style="color: white;">Cambiar Contraseña</b>
					                    </a>
						            </li>-->
						            <li id="logoutt" style="margin-top: 5px;margin-left: 10px;border: 0;border-top: 2px solid #c5cacc;margin-bottom: 10px;">
					                    <a href="logout" style="margin-top: 10px;"">
					                    	<img src="assets/img/iconos/Login/exitt.svg" style="height: 28px;">
					                        <b style="color: white;">Cerrar sesión</b>
					                    </a>
						            </li>
						            <li id="pasa1-c" style="display: none;">
						            	<form action="cambiar_pass" method="post">
						            		{{csrf_field()}}
						            		<input
				                                class="form-control"
				                                name="pass1"
				                                id="pass1h" 
				                                type="password"
				                                placeholder="Nueva contraseña" 
				                                required
				                                style=" margin-top: 10px;"
				                            >
							            	<input class="form-control"
				                                name="pass2"
				                                id="pass2h" 
				                                type="password"
				                                placeholder="Repetir contraseña" 
				                                required style=" margin-top: 10px;"
				                            >

							            	<button type="button" class="btn" style="margin-top: 15px;" onclick="cancelar_cc()">Cancelar</button>

							            	<button type="submit" class="btn" style="margin-top: 15px; margin-left: 25px;" onclick="chequea_pass()">Guardar</button>
						            	</form>
						            </li>
						        </ul>
					    	</div>
						</div>
	                </li>
			 	</ul>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-2">
				<div id="sidebar" class="sidebar" style="z-index: 1000;">
					<!--Boton para abrir el menú-->
					<a id="abrir" class="abrir-cerrar" onclick="mostrar()" title="Desplegar menú completo">
						<img src="assets/img/iconos/menu.svg" class="abrir">
					</a>
					<!--Boton para cerrar el menú-->
					<a href="#" id="cerrar" class="boton-cerrar" onclick="ocultar()" title="Ocultar menú">
						<img src="assets/img/iconos/menu.svg" class="cerrar">
					</a>
					<ul id="mimenu" style="margin-top: 15px;">
						<div class="list-group ">
						    <li>
						    	<a class="linea" href="home" style="color: white;" title="Inicio" id="inicioLateral" style="display:none;">
						    	<img src="assets/img/iconos/nuevos/home.svg" class="imgmenu7" title="Inicio"> <b>Inicio</b>
						    	</a>
						    </li>
						    <li>
						    	<!-- aqui-->	
					    		<a href="#us" class="linea" data-toggle="collapse" data-parent="#mimenu" id="usuariosLateral" title="Usuarios" style="color: white; display: none;">
							    	<img src="assets/img/iconos/usuarios/usuarios.svg" class="imgmenu6"><b>Usuarios</b>
							    	</a>
						    	<!-- aqui-->
						    	<div class="collapse" id="us"  style="background-color: #5D9DB9;">
						        	<a onclick="mostrar_btn_empleado()" href="empleados" id="empleadosLateral" title="Empleados" style="color: white; display: none;">
						        		<img src="assets/img/iconos/usuarios/empleados.svg" class="imgmenu5">
						        		<b>Empleados</b>						        		
						        	</a>
						      		<a href="horarios"  id="horariosLateral" title="Horarios" style="color: white; display: none;">
						      			<img src="assets/img/iconos/horario.svg" class="imgmenu1">
						      			<b>Horarios</b>						      			
						      		</a>
						      		<a href="estadisticasCarnets"  id="CarnetLateral" title="Carnets" style="color: white;display: none;">
						      			<img src="assets/img/iconos/marcaje.svg" class="imgmenu1">
						      			<b>Carnet</b>						      			
						      		</a>
						    	</div>
						    </li>
						    <li>
						    	<a href="#asi" class="linea" id="asistenciaLateral" data-toggle="collapse" data-parent="#mimenu" title="Asistencia" style="color: white; display: none;">
						    		<img src="assets/img/iconos/asistencia.svg" class="imgmenu7">
						    		<b>Asistencia</b>
						    	</a>
						    	<div class="collapse" id="asi" style="background-color: #5D9DB9;">
						        	<a href="control" title="Control de asistencia" style="color: white; display: none;" id="controlLateral">
						        		<img src="assets/img/iconos/control.svg" class="imgmenu5">
						        		<b>Control</b>
						        	</a>
						        	<a href="asistenciag" title="Marcaje" style="color: white; display: none;" id="marcajeLateral">
						        		<img src="assets/img/iconos/bar-code.svg" style="height: 35px; margin-right: 30px; margin-left: 5px;">
						        		<b>Marcaje</b>
						        	</a>
						        	<a href="notificaciones" title="Notificaciones" style="color: white;display: none;" id="notificacionesLateral">
						        		<img src="assets/img/iconos/notificaciones.svg" style="height: 35px; margin-right: 30px; margin-left: 5px;">
						        		<b>Notificaciones</b>
						        	</a>
						        	<a href="asistenciaextra" title="Asistencia Extraordinaria" style="color: white; display: none;" id="asistenciaeLateral">
						        		<img src="assets/img/iconos/asistenciaextra.svg" class="imgmenu5">
						        		<b>Extraordinaria</b>
						        	</a>
						      		<a href="#submenu" data-toggle="collapse" data-parent="#asi" title="Excepciones" style="color: white; display: none;" id="excepcionesLateral"  class="linea">
						      			<img src="assets/img/iconos/excepciones.svg" class="imgmenu6">
						      			<b>Excepciones</b>
						      		</a>
						      		<div class="collapse" id="submenu" title="Reposos" style="background-color: #4D849C">
						        		<a href="reposos" style="color: white; display: none;" id="repososLateral">
						        			<img src="assets/img/iconos/reposos.svg" class="imgmenu4">
						        			<b>Reposos</b>
						        		</a>
						      			<a href="permisos" title="Permisos" style="color: white; display: none;" id="permisosLateral">
						      				<img src="assets/img/iconos/permisos.svg" class="imgmenu2">
						      				<b>Permisos</b>
						      			</a>
						      			<a href="vacaciones" title="Vacaciones" style="color: white; display: none;" id="vacacionesLateral">
							      			<img src="assets/img/iconos/vacaciones.svg" class="imgmenu2">
							      			<b>Vacaciones</b>
						      			</a>
						      			<a href="autorizacion" class="tooltips" title="Autorización de ingreso" style="color: white; display: none;" id="autoLateral">
						      				<img src="assets/img/iconos/autorizacion.svg" class="imgmenu3">
						      			<b>Autorización</b>
						      			</a>
						    		</div>
						    	</div>
						    </li>
						    <li>
						    	<a href="#conf" class="linea" data-toggle="collapse" data-parent="#mimenu" title="Configuración" style="color: white; display: none;" id="configLateral">
						    		<img src="assets/img/iconos/configuracion.svg" class="imgmenu3"><b>Configuración</b>
						    	</a>
						    	<div class="collapse" id="conf"  style="background-color: #5D9DB9;">
						        	<a href="roles" title="Roles" style="color: white; display: block;" id="rolesLateral">
						        		<img src="assets/img/iconos/roles.svg" class="imgmenu4">
						        		<b>Roles</b>
						        	</a>
						        	<a href="diasf" title="Dias feriados" style="color: white; display: none;" id="diasfLateral">
						        		<img src="assets/img/iconos/diasf.svg" class="imgmenu9">
						        		<b>Días Feriados</b>
						        	</a>
						        	<a href="departamentos" title="Gestión de Departamentos" style="color: white;">
						        		<img src="assets/img/iconos/departamentos.svg" class="imgmenu1"">
						        		<b>Gestión</b>
						        	</a>
						      		<a href="auditoria" title="Auditoria" style="color: white; display: none;" id="auditoriaLateral">
						      			<img src="assets/img/iconos/auditoria.svg" class="imgmenu1">
						      			<b>Auditoría</b>
						      		</a>
						      		<a href="acttablas" title="Actualización de tablas" style="color: white; display: none;" id="acttablasLateral">
						      			<img src="assets/img/iconos/acttablas.svg" class="imgmenu9">
						      			<b>Act. de Tablas</b>
						      		</a>
						      		<a href="nuevo_cliente" title="Nuevo cliente" style="color: white; display: none;" id="nClienteLateral">
						      			<img src="assets/img/iconos/ncliente.svg" class="imgmenu9" style="height: 40px;">
						      			<b>Nuevo Cliente</b>
						      		</a>
						      		<a href="conexion" title="Configuracion de conexión" style="color: white; display: none;" id="conexionLateral">
						      			<img src="assets/img/iconos/conexion1.svg" class="imgmenu8" style="height: 40px;">
						      			<b>Conexión</b>
						      		</a>
						    	</div>	
						    </li>
						</div>
					</ul>  
				</div>
			</div>
			<div id="contenido">
				<div class="container-fluid">
					<div class="row" style="z-index: -1000;">
						@yield('contenido')

					<footer class="text-center" style="position:fixed; left:0px; bottom:0px; height:30px; width:100%;"> COVETEL S.A</footer>
					
					</div>
				</div>
			</div>
		</div>
	</div>

	
	<script src="assets/js/jquery.js"></script>

	<script src="assets/js/funcionmenu.js"></script>


	<script src="assets/js/home.js"></script>

 	<script src="assets/js/bootstrap.min.js"></script>

 	<script src="assets/js/angular.min.js"></script>

	<script src="assets/js/config.js"></script>

	<script src="assets/js/moment.js"></script>

	<script src="assets/js/Chart.js"></script>

	<script src="assets/js/Chart.min.js"></script>
	
	<!--Sweet Alert-->
	<script src="assets/js/sweetalert.min.js"></script>
	<!-- DatePicker-->
	<link rel="stylesheet" type="text/css" href="assets/datePicker/css/bootstrap-datepicker3.css">
	<link rel="stylesheet" type="text/css" href="assets/datePicker/css/bootstrap-standalone.css">
	<!-- Languaje -->
	<script src="assets/datePicker/js/bootstrap-datepicker.js"></script>
	<script src="assets/datePicker/locales/bootstrap-datepicker.es.min.js"></script>

 	<script src="assets/js/ui-bootstrap-tpls-2.5.0.min.js"> </script>
 	<script type="text/javascript"> $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });</script>

</body>
</html>