@extends('menu')
@section('contenido')
<div class="row">
	<?php if (! $errors->isEmpty()): ?>
		<div class=" col-lg-4 alert alert-danger" style="margin-left: 750px;" id="alerta">
			<p><strong>Lo sentimos </strong>Por favor corrige los siguientes errores</p>
			<?php foreach ($errors->all() as $error): ?>
				<li>{{ $error}}</li>
			<?php endforeach ?>	
		</div>
	<?php endif ?> 
	@if(Session::has('flash_message'))
		<div class="col-lg-4" style="margin-left: 750px;" id="alerta">
			<div class="alert alert-success">
			{{Session::get('flash_message')}}
			</div>	
		</div>
	@endif
	@if(Session::has('session'))
		<div class="col-lg-4" style="margin-left: 750px;" id="alerta">
			<div class="alert alert-danger">
			{{Session::get('session')}}
			</div>	
		</div>
	@endif
</div>

@if($aco_vac == 'p_vacaciones')
<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
<input type="hidden" name="ventana" id="ventana" value="vacaciones">

<div class="panel panel-default" style="margin-left: 20px;margin-right:20px; ">
  	<div class="panel-heading" style="background-color: #e5e8e8;">
    	<h3 class="panel-title">
    		VACACIONES
    		<a href="vacaciones" id="refrescarp">
				<img src="assets/img/iconos/refrescar.svg"  style="height: 25px;" align="RIGHT">
			</a>
    	</h3>
  	</div>
  	<div class="panel-body">
    	<div class="form-group col-lg-6">
			<span class="input-group">
				<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i>
			</span>
			<input class="form-control" type="number" min="2000000" name="cedula" id="cedulavac" placeholder="Por favor ingrese la cedula del trabajador">
		</div>	
		<div class="form-group col-lg-6" style="display: none;" id="dpvac">
			<span class="input-group">
				<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i>
			</span>
			<select class="form-control" name="departamento" id="departamentoV">
				<option selected disabled> Seleccione un departamento</option>
				@foreach($departamento as $dp)
					<option value="{{$dp->dp_id}}"> {{$dp->dp_nombre}} </option>
				@endforeach
			</select>
		</div>
  	</div>
</div>

<div class="col-lg-12">
	<div class="panel panel-default">
  	<div class="panel-heading" style="background-color: #e5e8e8;">EMPLEADOS
  		<a href="#" data-toggle="modal" data-target="#modalForm"  id="bpv_agregar" style="display: none;"> 
			<img src="assets/img/iconos/addexc.svg" align="right" style="height: 35px; margin-top: -7px">
		</a>
		<!-- Modal -->
						<div class="modal fade" id="modalForm" role="dialog">
						    <div class="modal-dialog">
						        <div class="modal-content text-center">
						            <div class="panel panel-default">
  										<div class="panel-heading" style="background-color: #e5e8e8;">
    										<h3 class="panel-title">
    											<b>REGISTRAR VACACIONES</b>
    											<button type="button" class="close" data-dismiss="modal">
								                    <span aria-hidden="true">×</span>
								                </button>
    										</h3>
  										</div>
  										<div class="panel-body">
    										<div class="col-lg-12">
    											<form method="post" action="registrarVacaciones">
	    											<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
										        	<div class="form-group col-lg-12">
														<span class="input-group">
															<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i>
														</span>
														<input class="form-control" 
															type="number" 
															id="cedulav" 
															name="cedula" 
															placeholder="Por favor ingrese la cédula del trabajador" 
															min="2000000" 
															pattern="d/{8}" 
															required/>
													</div>
													<div class="col-lg--12" id="formVaca">
														<input type="hidden" name="us_id" value="${element.us_ced}">		
														<div class="col-lg-6 text-left">
											            	<div class="form-group">
																<label for="inputNombre">Nombre</label>
																<input type="text" 
																name="nombre" class="
																form-control" 
																disabled
																placeholder="Por favor ingrese su nombre">
												                
												            </div>
											            </div>
											            <div class="col-lg-6 text-left">
											            	<div class="form-group">
																<label for="inputApellido">Apellido</label>
																<input type="text" 
																name="apellido" 
																disabled
																class="form-control"
																placeholder="Por favor ingrese su apellido">
												                
												            </div>
											            </div>
											            <div class="col-lg-6 text-left">
											            	<div class="form-group">
																<label for="inputDepartamento">Departamento</label>
												                <select class="form-control" name="reposo" required>
												                	<option selected disabled></option>
												                </select>
												            </div>
											            </div>
											            <div class="col-lg-6 text-left">
											            	<div class="form-group">
																<label for="inputFechaini">Fecha de inicio</label>
												            	<input type="date" name="desde" class="form-control" id="InicioV" required placeholder="yyyy-mm-dd">   
												            </div>
											            </div>
											            <div class="col-lg-6 text-left">
											            	<div class="form-group">
																<label for="inputFechafin">Fecha de culmunación</label>
												            	<input type="date" name="hasta" class="form-control" id="FinV" required placeholder="yyyy-mm-dd">   
												            </div>
											            </div>
											            	<div class="form-group">
											            		<div class="col-lg-3 text-left">
											            			<label id="inputCantidad">Cantidad de vacaciones</label>
											            		</div>
											            		<div class="col-lg-3">
											            			<select name="cantidad" id="cantidad" class="form-control" required>
											            				<option disabled selected>Nro.</option>
											            				<option value="1">1</option>
											            				<option value="2">2</option>
											            			</select>
											            		</div>
											            	</div>
														<div class="col-lg-12 text-left">
															<div class="form-group">
																<label>¿Vacaciones pagadas?</label>
																<label class="radio-inline">
																	<h4><input type="radio" name="pagadas" id="si" value="1" checked> Si</h4>
																</label>
																<label class="radio-inline">
																	<h4><input type="radio" name="pagadas" id="no" value="2" >No</h4>
																</label>
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
	</div>

 	 <!-- Table -->
  	<div class="table-responsive">
  		<table class="table table-hover">
				@if(count($excepcion)>0)
			<thead style='background: #f2f3f4  repeat-x center top; font-size:14px;'>
				<tr>
					<th>
						Foto
					</th>
					<th>
						Trabajador
					</th>
					<th style="font-size: 12px;">
						Fecha inicio
					</th>

					<th style="font-size: 10px;">
						Fecha de culminación
					</th>
					<th class="text-right">						
					</th>
				</tr>
			</thead>
			<tbody id="vacacionesBody">
				<?php foreach ($excepcion as $us): $fechaIni = new DateTime($us->vac_fecha_ini); $fechaFin= new DateTime($us->vac_fecha_fin); ?>
					<tr>
						<td>
							<img src="imagenes2/{{$us->us_foto}}"  style="height: 60px;border-radius: 6px 6px 6px 6px; border:1px solid #B2B2B2;">
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
						</td>
						<td>
							{{$fechaIni->format('d-m-Y')}} 
						</td>
						<td>
							{{$fechaFin->format('d-m-Y')}}
						</td>
						<td class="text-right">
							<a class="showPermiso"  onclick="showVacaciones({{$us->vac_id}},{{$us->us_ced}})" href="#" data-toggle="modal" data-target="#showPermiso"> 
								<img src="assets/img/iconos/ver.svg" class="imgmenuho" >
							</a>
							<div class="modal fade" id="showPermiso" tabindex="-1" role="dialog" aria-labelledby="EditarPermiso" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content text-center">
										<div class="panel panel-default">
  											<div class="panel-heading" style="background-color: #e5e8e8;">
    											<h3 class="panel-title">
    												<b>DETALLES DE LAS VACACIONES</b>
    												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    											</h3>
  											</div>
  											<div class="panel-body">
    											<ul class="list-group text-left" id="vacacionesShow">
													
												</ul>
  											</div>
										</div>
									</div>
								</div>
							</div>
							<a class="editVacaciones" style="display: none;" onclick="editVacaciones({{$us->vac_id}},{{$us->us_ced}})" href="#" data-toggle="modal" data-target="#editVacaciones"> 
								<img src="assets/img/iconos/editar.svg" class="imgmenuho" >
							</a>
							<div class="modal fade" id="editVacaciones" tabindex="-1" role="dialog" aria-labelledby="EditarVac" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content text-center">
										<div class="panel panel-default">
  											<div class="panel-heading" style="background-color: #e5e8e8;">
    											<h3 class="panel-title">
    												<b>EDITAR VACACIONES</b>
    												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    											</h3>
  											</div>
  											<div class="panel-body">
    											<form method="POST" action="updateVacaciones" >
    												{{csrf_field()}}
    												<div id="VacacionesEdit">
				
													</div>
													<div class="col-lg-12" style="border-top: 1px solid #ccc; font-size: 14px;">
														<div class="col-lg-6 text-left" style="margin-top: 10px;">
															<button class="btn btn-default" type="button" data-dismiss="modal">Cerrar</button>
														</div>
														<div class="col-lg-6 text-right" style="margin-top: 10px;">
															<button type="submit" name="registrar" id="submitP" value="Modificar" class="btn" style="background-color: #48c9b0; color:white;">
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
							<a href="#" onclick="eliminaV({{$us->vac_id}})" title="Eliminar vacaciones" class="deleteVac" style="display: none;">
								<img src="assets/img/iconos/eliminar.svg" style="height: 25px; margin-top: 5px" align="right">
							</a>
						</td>
					</tr>
					
				<?php endforeach ?>	
			</tbody>
				@else
		   			<tr>
						<td>
							<strong>Disculpe,</strong> no se encontraron resultados...
						</td>
					</tr>
		       	@endif
		</table>
		<center id="centerasi" class="paginacion_f">
			<?php echo $excepcion->render(); ?>
		</center>
  	</div>
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
<script src="assets/js/Asistencia/excepciones/vacaciones.js"></script>	

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