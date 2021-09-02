@extends('menu')
@section('contenido')

<div class="row">
	<?php if (! $errors->isEmpty()): ?>
		<div class="alert alert-danger" ng-disable>
			<p><strong>Lo sentimos </strong>Por favor corrige los siguientes errores</p>
			<?php foreach ($errors->all() as $error): ?>
				<li>{{ $error}}</li>
			<?php endforeach ?>	
		</div>
	<?php endif ?> 
	@if(Session::has('flash_message'))
		<div class="col-lg-4" style="margin-left: 750px;" id="alertaP" class="alertaP">
			<div class="alert alert-success">
			{{Session::get('flash_message')}}
			</div>	
		</div>
	@endif
	@if(Session::has('session'))
		<div class="col-lg-4" style="margin-left: 750px;" id="alertaPd" class="alertaPd">
			<div class="alert alert-danger">
			{{Session::get('session')}}
			</div>	
		</div>
	@endif
</div>

@if($aco_per == 'p_permisos')
<input type="hidden" name="ventana" id="ventana" value="permiso">
<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

<div class="panel panel-default">
  	<div class="panel-heading" style="background-color: #e5e8e8;">
    	<h3 class="panel-title">
    	PERMISOS
    	<a href="permisos" >
			<img src="assets/img/iconos/refrescar.svg"  style="height: 25px;" align="RIGHT">
		</a>
    </h3>
  	</div>
  	<div class="panel-body">
    	<div class="row">			
			<div class="form-group col-lg-6">
				<span class="input-group">
					<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i>
				</span>
				<input class="form-control CedulaPermisoBody" type="number" maxlength="8" min="1000000" name="cedula" placeholder="Por favor ingrese la cedula del trabajador" id="CedulaPermisoBody" onchange="CedulaPermisoBody();">
			</div>
			<div class="form-group col-lg-6" id="depPer" style="display: none;">
				<span class="input-group">
					<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i>
				</span>
				<select class="form-control" name="departamento" id="departamentoP" onchange="CambiaDepBody();">
					<option disabled selected>Seleccione un departamento</option>
					@foreach($departamento as $dp)
					<option value="{{$dp->dp_id}}">{{$dp->dp_nombre}}</option>
					@endforeach
				</select>
			</div>				
		</div>
  	</div>
</div>

<div class="panel panel-default">
  	<!-- Default panel contents -->
  	<div class="panel-heading" style="background-color: #e5e8e8;">
  		EMPLEADOS
  		<a href="#" data-toggle="modal" data-target="#modalForm" id="bpp_agregar" style="display: none;"> 
			<img src="assets/img/iconos/addexc.svg" style="height: 35px; margin-top: -7px;" align="right">
		</a>
		<!-- Modal -->
						<div class="modal fade" id="modalForm" role="dialog">
						    <div class="modal-dialog">
						        <div class="modal-content text-center">
						            <div class="panel panel-default">
  										<div class="panel-heading" style="background-color: #e5e8e8;">
    										<h3 class="panel-title">
    											<b>
    												REGISTRAR PERMISO
    											</b>
    											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    										</h3>
  										</div>
  										<div class="panel-body">
    										<form method="post" action="registraper" accept-charset="UTF-8" enctype="multipart/form-data">
											   
											        <div class="row">
											        	<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
											        	<div class="form-group col-lg-12">
															<span class="input-group">
																<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i>
															</span>
															<input class="form-control" 
																type="number" 
																id="cedulaper" 
																name="cedula" 
																placeholder="Por favor ingrese la cedula del trabajador" 
																min="1000000" 
																pattern="d/{8}" 
																required/>
														</div>
														<div id="formper">
															<input type="hidden" name="id" value="${usuario.us_ced}">
															<div class="col-lg-6 text-left">
												            	<div class="form-group">
																	<label for="inputNombre">Nombre</label>
																	<input type="text" name="nombre" class="form-control" placeholder="Por favor ingrese su nombre" disabled >
													                
													            </div>
												            </div>
												            <div class="col-lg-6 text-left">
												            	<div class="form-group">
																	<label for="inputApellido">Apellido</label>
																	<input type="text" name="apellido" class="form-control" placeholder="Por favor ingrese su apellido" disabled >
													                
													            </div>
												            </div>
												            <div class="col-lg-6 text-left">
												            	<div class="form-group">
																	<label for="inputCedula">Cédula</label>
																	<input type="nomber" name="cedula" class="form-control" placeholder="Por favor ingrese su cédula" disabled >
													                
													            </div>
												            </div>
												            <div class="col-lg-6 text-left">
												            	<div class="form-group">
																	<label for="inputDepartamento">Departamento</label>
													                <select class="form-control" name="reposo">
													                	<option  disabled selected></option>
													                	
													                </select>
													            </div>
												            </div>
											                <div class="col-lg-6 text-left">
											                	<div class="form-group">
																	<label for="inputFechaini">Fecha de inicio</label>
													            	<input type="date" name="desde" class="form-control" id="fechaIniP" required placeholder="yyyy-mm-dd">   
													            </div>
											                </div>
											                <div class="col-lg-6 text-left">
											                	<div class="form-group">
																	<label for="inputFechafin">Fecha de culmunación</label>
													            	<input type="date" name="hasta" class="form-control"  id="fechaFinP" required placeholder="yyyy-mm-dd">   
													            </div>
											                </div>
											                <div class="col-lg-6 text-left">
											                	<div class="form-group">
																	<label for="inputAdjunto">Añadir archivo adjunto</label>
													            	<input type="file" name="file" class="form-control"  id="adjunto"   accept="application/pdf">   
													            </div>
											                </div>
											                <div class="col-lg-6 text-left">
											                	<div class="form-group">
																	<label for="inputDiag">Descripción</label>
																		<input type="text" name="descripcion" class="form-control" placeholder="Indique la descripción del permiso" maxlength="250" title="Por favor ingrese una descripción no mayor a 250 palabras">								            	  
													            </div>
											                </div>
															<div class="col-lg-12">
																<div class="form-group">
																	<label>¿El permiso es remunerado?</label>
																	<label class="radio-inline">
																		<h4><input type="radio" name="remunerado" id="siP" value="1" checked> Si</h4>
																	</label>
																	<label class="radio-inline">
																		<h4><input type="radio" name="remunerado" id="noP" value="2" >No</h4>
																	</label>
																</div>
															</div>
														</div>
											        </div>
											    
											</form>
										</div>
									</div>
						        </div>
						    </div>
						</div>
  	</div>
  	<div class="table-responsive">
  		<table class="table table-hover">
			<thead style='background: #f2f3f4  repeat-x center top; font-size:14px;'>
				<tr>
					<th>
						Foto
					</th>
					<th>
						Trabajador
					</th>
					<th>
						Fecha de permiso
					</th>
					<th></th>
					<th>
						
					</th>
				</tr>
			</thead>
			<tbody id="permisobody">
				@if(count($excepcion)>0)
				<?php foreach ($excepcion as $us): $fechaIni = new DateTime($us->per_fecha_ini); $fechaFin = new DateTime($us->per_fecha_fin);?>
					<tr>
						<td>
							<img src="imagenes2/{{$us->us_foto}}" style="height: 60px;border-radius: 6px 6px 6px 6px; border:1px solid #B2B2B2;">
						</td>
						<td>						
							<label>Nombre y Apellido:</label> 

								{{$us->us_nom}}    						
								{{$us->us_ape}}

							<label>Cédula:</label>  

								{{$us->us_ced}}

							<br>
							<label>Departamento: </label> 

								{{$us->dp_nombre}}
							<br>	
						</td>
						<td>
							{{$fechaIni->format('d-m-Y')}} / {{$fechaFin->format('d-m-Y')}}
						</td>
						<td class="text-right">
							<a class="showPermiso"  onclick="showPermiso({{$us->per_id}},{{$us->us_ced}})" href="#" data-toggle="modal" data-target="#showPermiso"> 
								<img src="assets/img/iconos/ver.svg" class="imgmenuho" >
							</a>
							<div class="modal fade" id="showPermiso" tabindex="-1" role="dialog" aria-labelledby="EditarPermiso" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="panel panel-default">
											<div class="panel-heading text-center" style="background-color: #e5e8e8;">
    											<h3 class="panel-title">
    												<b>
    													DETALLES DEL PERMISO
    												</b>
    												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    											</h3>
  											</div>
  											<div class="panel-body">
    											<ul class="list-group text-left" id="permisoShow">
			
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
							<a class="editPermiso" style="display: none;" onclick="editPermiso({{$us->per_id}},{{$us->us_ced}})" href="#" data-toggle="modal" data-target="#editPermiso"> 
								<img src="assets/img/iconos/editar.svg" class="imgmenuho" >
							</a>
							<div class="modal fade" id="editPermiso" tabindex="-1" role="dialog" aria-labelledby="EditarPermiso" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content text-center">
										<div class="panel panel-default">
  											<div class="panel-heading" style="background-color: #e5e8e8;">
  												<h3 class="panel-title">
  													<b>EDITAR PERMISO</b>
  													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
  												</h3>
  											</div>
	  										<div class="panel-body">
	    										<form method="POST" action="updatePermiso" accept-charset="UTF-8" enctype="multipart/form-data">
													<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
													<div class="row">
														<div id="PermisoEdit">
															
														</div>										 	
											        </div>
													<div class="col-lg-12" style="border-top: 1px solid #ccc; font-size: 14px;">
														<div class="col-lg-6 text-left" style="margin-top: 10px;">
															<button class="btn btn-default" data-dismiss="modal">Cerrar</button>
														</div>
														<div class=" col-lg-6 text-right" style="margin-top: 10px;">
															<button type="submit" name="registrar" id="submitP" value="Modificar" class="btn"  style="background-color: #48c9b0; color:white;" >
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
						<td>
							<a href="#" class="deletePermiso" style="display: none;" onclick="eliminaP({{$us->per_id}})" title="Eliminar permiso">
								<img src="assets/img/iconos/eliminar.svg" style="height: 25px; margin-top: 5px" align="right">
							</a>
						</td>
					</tr>
					
				<?php endforeach ?>	
				@else
		   			<tr>
						<td>
							<strong>Disculpe,</strong> no se encontraron resultados...
						</td>
					</tr>
		       	@endif
			</tbody>
		</table>
		<center id="centerasi" class="paginacion_f">
			<?php echo $excepcion->render(); ?>
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
<script src="assets/js/Asistencia/excepciones/permisos.js"></script>
	

<link rel="stylesheet" type="text/css" href="assets/datePicker/css/bootstrap-datepicker3.css">
<link rel="stylesheet" type="text/css" href="assets/datePicker/css/bootstrap-standalone.css">
<!-- Languaje -->
<script src="assets/datePicker/js/bootstrap-datepicker.js"></script>
<script src="assets/datePicker/locales/bootstrap-datepicker.es.min.js"></script>

<script>
	//entrada
    $('#fechaIniP').datepicker({
        format: "yyyy-mm-dd",
        language: "es",
        autoclose: true
    });
	//salida
    $('#fechaFinP').datepicker({
        format: "yyyy-mm-dd",
        language: "es",
        autoclose: true
    });
</script>
@endsection