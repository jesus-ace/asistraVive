@extends('menu')
@section('contenido')
<div class="row">
<?php if (! $errors->isEmpty()): ?>
	<div class="row">
		<div class="col-lg-4" style="margin-left: 750px;" id="Alertaerror">
			<div class="alert alert-danger">
				<p><strong>Lo sentimos </strong> Por favor corrige los siguientes errores</p>
				<?php foreach ($errors->all() as $error): ?>
					<li>{{ $error}}</li>
				<?php endforeach ?>	
			</div>
		</div>
	</div>
<?php endif ?> 

@if(Session::has('flash_message'))
	<div class="row">
		<div class="col-lg-4" style="margin-left: 750px;" id="Alerta">
			<div class="alert alert-success">
			{{Session::get('flash_message')}}
			</div>	
		</div>
	</div>
@endif
@if(Session::has('session'))
	<div class="row">
		<div class="col-lg-4" style="margin-left: 750px;" id="Alerta">
			<div class="alert alert-danger">
			{{Session::get('session')}}
			</div>	
		</div>
	</div>
@endif
</div>
@if($aco_emp == 'p_empleados')

<input type="hidden" name="ventana" id="ventana" value="empleados">

<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

<div class="panel panel-default" style="margin-left: 20px; margin-right: 20px;">
  	<div class="panel-heading"  style="background-color: #e5e8e8;">
    	<h3 class="panel-title">PANEL DE BÚSQUEDA 
    		<a href="empleados" title="Refrescar lista de usuarios">
				<img src="assets/img/iconos/refrescar.svg"  style="height: 25px; margin-top: -5px;" align="right">
			</a>
		</h3>
  	</div>
  	<div class="panel-body">
		<div class="row" style="z-index: -1000;">
			<div class="col-lg-4 boton_oculto"  id="deusu">
				<span class="input-group">
					<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i>
				</span>
				<select class="form-control input-xs" name="departamento" id="departamentoUs" title="Busqueda por departamento">
					<option selected disabled value="0">Seleccione un departamento</option>
					<?php foreach ($departamentos as $dp ): ?>
						<option value="{{$dp->dp_id}}">
							{{$dp->dp_nombre}}
						</option>
					<?php endforeach ?>
				</select>
			</div>
			<div class="form-group col-lg-4">
				<span class="input-group">
					<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i>
				</span>
				<input title="Busqueda por cédula" class="form-control" type="number" id="ceduBuscar" name="cedula" min="2000000" placeholder="Ingrese la cédula del empleado" pattern="d/{7}">
			</div>
			<div class="form-group col-lg-4">
				<span class="input-group">
					<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i>
				</span>
				<input title="Busqueda por nombre" class="form-control" type="text" id="nomBuscar" name="nombre" placeholder="Ingrese el nombre del empleado" maxlength="20">
			</div>	
		</div>
  	</div>
</div>

<div class="panel panel-default" style="margin-left: 20px; margin-right: 20px; display: none;" id="search_ldap_sigesp">
  	<div class="panel-heading"  style="background-color: #e5e8e8;">
    	<h3 class="panel-title">PANEL DE BÚSQUEDA POR LDAP Y SIGESP</h3>
  	</div>
  	<div class="panel-body">
    	<div class="col-lg-12">		
			<div class="row" >
				<div class="btn btn-group col-lg-4 boton_oculto">
					<button 
						class="btn" 
						id="search_ldap" 
						onmouseover="this.style.background='#839FE8'; this.style.color='#FFFFFF '"
						onmouseout="this.style.background='#DFDFDF'; this.style.color='#2F2F2F'">

						<b>Buscar en LDAP</b>

					</button>
					<button 
						class="btn" 
						id="search_sigesp" 
						onmouseover="this.style.background='#839FE8'; this.style.color='#FFFFFF '"
						onmouseout="this.style.background='#DFDFDF'; this.style.color='#2F2F2F'">

						<b>Buscar en Sigesp</b>

					</button>
				</div>

				<div class="col-lg-1 text-left">
					<a href="#" id="icon_delete" title="Ocultar busqueda">
						<img src="assets/img/iconos/icon_delete.png"  style="height: 25px; margin-top: 12px;" class="input_user">
					</a>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-12 text-left">
					<input type="number" name="us_search_ced_ldap" class="form-control input_user" id="us_search_ced_ldap" placeholder="Bucador de LDAP : Indique la cédula  del empleado" min="1000000">
				</div>

				<div class="col-lg-12 text-left">
					<input type="number" name="us_search_ced_sigesp" class="form-control input_user" id="us_search_ced_sigesp" placeholder="Bucador de SIGESP : Indique la cédula  del empleado" min="1000000">
				</div>
			</div>
		</div>
  	</div>
</div>
<div class="panel panel-default" style="margin-left: 20px; margin-right: 20px;">
  	<!-- Default panel contents -->
  	<div class="panel-heading"  style="background-color: #e5e8e8;">
  		<label>EMPLEADOS</label><!-- Boton de la modal -->
		<a href="#" data-toggle="modal" data-target="#modalForm" style="display: none;" id="bpe_agregar"> 
			<img src="assets/img/iconos/usuarios/agregaru.png" align="right" style="height: 35px; margin-top: -8px" title="Agregar un nuevo usuario">
		</a>
	</div>
	<div class="table-responsive">
	  	<!-- Table -->
	  	<table class="table table-hover">
			<thead style='background: #f2f3f4  repeat-x center top; font-size:14px;'>
				<tr>
					<th class="text-center">
						FOTO
					</th>
					<th class="text-center">
						NOMBRES
					</th>
					<th class="text-center">
						APELLIDOS
					</th>
					<th class="text-center">
						CÉDULA
					</th>
					<th class="text-center">
						DEPARTAMENTO
					</th>
					<th class="text-right">
						<div class="modal fade" id="modalForm" role="dialog">
						    <div class="modal-dialog">
						        <div class="modal-content text-center">
						        	<div class="panel panel-default">
  										<div class="panel-heading" style="background-color: #e5e8e8;">
    										<h3 class="panel-title">
    											<b>
    												REGISTRAR USUARIO
    											</b>
    											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    										</h3>
  										</div>
	  									<div class="panel-body">
	  									<form method="POST" action="registrar" role="form" accept-charset="UTF-8" enctype="multipart/form-data">
	  										{{ csrf_field() }}
	    									<ul class="list-group text-left">
											  	<li class="list-group-item text-center">
											  		<label>
											  			CÉDULA
											  		</label>
											  		<input 
							                        type="number" 
							                        class="form-control"
							                        name="cedula" 
							                        id="ceduUS" 
							                        maxlength="8" 
							                        minlength="7" 
							                        placeholder="Escribe tu cedula"
							                        required>
											  	</li>
											</ul>
											<div class="col-lg-6 text-left" id="tipodeusuario">
							                    <div class="form-group">
							                        <label for="inputTipous">Tipo de usuario</label>
							                        <select class="form-control" name="tipousuario" required>
							                        	@foreach($tipousuario as $tipo)
							                            	<option value="{{$tipo->tdu_id}}">{{$tipo->tdu_tipo}}</option>
							                            @endforeach
							                        </select>
							                    </div>
							                </div>
						                    <div id="addUser">
						                    	<div class="col-lg-6 text-left">
								                    <div class="form-group">                                            
								                        <label for="inputFoto">Foto</label> 
								                        <input
								                            class="form-control"
								                            name="foto"
								                            id="fotovUs" 
								                            type="text"
								                            >
								                    </div>
								                </div>
								                    <div class="col-lg-6 text-left">
								                        <div class="form-group">
								                            <label for="inputNombre">Nombre</label>
								                            <input 
								                                type="text" 
								                                class="form-control" 
								                                name="nombre" 
								                                id="nombreUs" 
								                                maxlength="20" 
								                                placeholder="Escribe tu nombre" 
								                                min="3"
								                                
								                                required
								                                title="Por favor llene este campo correctamente para poder continuar"/>
								                        </div>
								                    </div>
								                    <div class="col-lg-6 text-left">
								                        <div class="form-group">
								                            <label for="inputApellido">Apellido</label>
								                            <input 
								                                type="text" 
								                                class="form-control"
								                                name="apellido" 
								                                id="apellidoUs" 
								                                placeholder="Escribe tu apellido"
								                                maxlength="20"
								                                
								                                title="Por favor llene este campo correctamente para poder continuar" required/>
								                        </div>
								                    </div>
								                    <div class="col-lg-6 text-left">
								                        <div class="form-group">
								                            <label for="inputApellido">Correo Electronico</label>
								                            <input 
								                                type="text" 
								                                class="form-control"
								                                name="correo" 
								                                id="correoUs"
								                                 pattern="[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*@[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{1,5}" 
								                                placeholder="Escribe tu correo electronico"
								                                title="Por favor llene este campo correctamente para poder continuar. Ejemplo: tucorreo@vive.com" required/>
								                        </div>
								                    </div>
								                    <div class="col-lg-6 text-left">
								                        <div class="form-group">                                            
								                            <label for="inputLogin">Login</label> 
								                                <input 
								                                    class="form-control" 
								                                    id="loginUs" 
								                                    type="text" 
								                                    name="login" 
								                                    onchange="get_login();" 
								                                    placeholder="Ingrese el login" 
								                                    required
								                                    />
								                        </div>
								                    </div>
								                    <div class="col-lg-6 text-left">
								                        <div class="form-group">                                            
								                            <label for="inputContraseña">Contraseña</label> 
								                            <input
								                                class="form-control passUs"
								                                name="pass"
								                                id="passUs" 
								                                type="password"
								                                placeholder="Ingrese la contraseña" 
								                                required
								                                >
								                        </div>
								                    </div>
								                    <div class="col-lg-6 text-left">
								                        <div class="form-group">                                            
								                            <label for="inputContraseña">Repetir contraseña</label> 
								                            <input
								                                class="form-control repitepass"
								                                name="repitepass"
								                                id="repitepass" 
								                                type="password"
								                                placeholder="Ingrese la contraseña" 
								                                required
								                                >
								                        </div>
								                    </div>
								                    
						                    </div>
						                    <div class="col-lg-6 text-left" id="horariosAdd">
						                        <div class="form-group">
						                            <label for="inputHorario">Pregunta de seguridad</label>
						                            <select class="form-control"  name="pregunta" required>
						                                <option disabled selected value="0">Elige una pregunta de seguridad</option>
						                                @foreach($preguntas as $prg)
						                                	<option value="{{$prg->prg_id}}"> {{$prg->prg_pregunta}}  </option>
						                                @endforeach
						                            </select>
						                        </div>
						                    </div>
						                    <div class="col-lg-6 text-left">
								                        <div class="form-group">                                            
								                            <label for="inputRespuesta">Respuesta</label> 
								                            <input 
								                                class="form-control" 
								                                id="respuestaUs" 
								                                type="password" 
								                                name="respuesta" 
								                                placeholder="Ingrese la respuesta a la pregunta" 
								                                pattern="[a-Z]"
								                                required 
								                                />
								                        </div>
								                    </div>
						                     <div class="col-lg-6 text-left" id="DeptoAddd">
						                        <div class="form-group">
						                            <label for="inputDepartamento">Departamento</label>
						                            <select class="form-control" id="departamentosAddd" name="departamento" required>
						                                <option selected value="0" disabled> Departamento</option>
						                                @foreach($departamentos as $dpto)
						                                	<option value="{{$dpto->dp_id}}">{{$dpto->dp_nombre}}</option>
						                                @endforeach
						                            </select>
						                        </div>
						                    </div>
						                    <div class="col-lg-6 text-left" id="rolesAdd">
						                        <div class="form-group">
						                            <label for="inputRol">Rol</label>
						                            <select class="form-control"  name="roles" required>
						                                <option disabled selected value="0">Elige un rol</option>
						                                @foreach($roles as $rol)
						                                	<option value="{{$rol->ro_id}}">{{$rol->ro_nom}}</option>
						                                @endforeach
						                            </select>
						                        </div>
						                    </div>

						                    <div class="col-lg-12 text-left" id="horariosAdd">
						                        <div class="form-group">
						                            <label for="inputHorario">Horario</label>
						                            <select class="form-control"  name="horario" required>
						                                <option disabled selected value="0">Elige un horario</option>
						                                @foreach($horarios as $hora)
						                                	<option value="{{$hora->tiho_id}}"> {{$hora->tiho_turno}} {{$hora->tiho_dias}} {{$hora->tiho_hora_en}} - {{$hora->tiho_hora_sa}} </option>
						                                @endforeach
						                            </select>
						                        </div>
						                    </div>
						                    <div class="col-lg-12" style="border-top: 1px solid #ccc; font-size: 14px;">
							                    <div class="col-lg-6 text-left" style="margin-top: 10px;">
											        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
											    </div>
											    <div class="col-lg-6 text-right" style="margin-top: 10px;">
											    	<button type="submit" name="registrar"  class="btn"  style="background-color: #48c9b0; color:white;" >
											    		<b>Registrar</b>
											    	</button>
											    </div>
											</div>
										</form>
	  									</div>
									</div>
						        </div>
						    </div>
						</div>
					</th>
				</tr>
			</thead>
			<tbody id="listaEmpleados">
				<?php foreach ($pdo as $resultado ): 
					if ($resultado->us_foto == '') {
						$foto = 'mafalda.jpg';
					}
					else{
						$foto = $resultado->us_foto;
					}
				?>
					<tr value="{{$resultado->us_ced}}">
						<td class="text-center">
							<img src="imagenes2/{{$foto}}" style="height: 60px;border-radius: 6px 6px 6px 6px; border:1px solid #B2B2B2;">
						</td>
						<td class="text-center">
							{{$resultado->us_nom}}
						</td>
						<td class="text-center">
							{{$resultado->us_ape}}<br>
						</td>
						<td class="text-center">
							{{$resultado->us_ced}}<br>
						</td>
						<td class="text-center"> 
							{{$resultado->dp_nombre}}
						</td>
						<td class="text-right">
							<div class="boton_oculto">
								<a class="editEmpleado"  onclick="editUsuario({{$resultado->us_ced}})" href="#" data-toggle="modal"  data-target="#editarUS" title="Editar usuario" > 
									<img src="assets/img/iconos/editar.svg"  class="imgmenuho">
								</a>
							</div>
							
							<div class="modal fade" id="editarUS" tabindex="-1" role="dialog" aria-labelledby="EditarUsuario" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content text-center">
										<div class="panel panel-default">
										  	<div class="panel-heading" style="background-color: #e5e8e8;">
										    	<h3 class="panel-title">
										    		<b>
										    			EDITAR USUARIO
										    		</b>
										    		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										    	</h3>
										  	</div>
										  	<div class="panel-body">
										  		<form method="POST" action="updateE">
										  			{{ csrf_field() }}
											    	<div class="col-lg-6 text-left">
											            <div class="form-group">
											                <label for="inputTipous">Tipo de usuario</label>
											                <select class="form-control" id="tipodeusuarioedit" name="tipousuario" >
											                	
											                </select>
											            </div>
											        </div>
												 	<div id="empleadosEdit">

													</div>
													<div class="col-lg-12 text-left">
											        	<div class="form-group">
															<label for="inputHorario">Horario</label>
															<select class="form-control" id="horarioEdit" name="horarioEdit" required>
																
															</select>
														</div>
											        </div>
											        <div class="col-lg-12" style="border-top: 1px solid #ccc; font-size: 14px;">
												        <div class="col-lg-6 text-left" style="margin-top: 10px;">
													        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
													    </div>
													    <div class="col-lg-6 text-right" style="margin-top: 10px;">
													    	<button type="submit" name="registrar" value="Modificar" class="btn"  style="background-color: #48c9b0; color:white;" >
													    		<b>MODIFICAR</b>
													    	</button>
													    </div>
													</div>
												</form>
										  	</div>
										</div>
									</div>
								</div>
							</div>
						</td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
<center class="paginacion_f" id="center">
	<?php echo $pdo->render(); ?>
</center>
	</div>
</div>
@else
<center>
	<img src="imagenes/denegado.png" style="height: 500px">
	<br>
	<b style="font-size: 50px;text-shadow: 1px 2px 5px red">ACCESO DENEGADO</b><br>
	<b style="margin-bottom: 15px;">Usted no tiene permiso para ingresar a esta pantalla</b>
</center>
@endif

	
	<script src="assets/js/jquery.js"></script>
	<script src="assets/js/Usuarios/buscar_usuario.js"></script>
@endsection	
