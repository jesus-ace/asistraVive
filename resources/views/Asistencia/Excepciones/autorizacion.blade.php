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
@if($aco_auto == 'p_autorizacion')
<input type="hidden" name="ventana" id="ventana" value="autorizacion">
<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

<div class="panel panel-default" style="margin-right: 20px; margin-left: 20px; z-index: 1">
  	<div class="panel-heading" style="background-color: #e5e8e8;">
    	<h3 class="panel-title">
    		AUTORIZACIÓN
			<a href="autorizacion" title="Refrescar pagina">
				<img src="assets/img/iconos/refrescar.svg" style="height: 25px; margin-top: -7px;" align="right">
			</a>
    	</h3>
  	</div>
  	<div class="panel-body">
    	<ul class="list-group text-left" >
    		<li class="list-group-item text-left">
    			<label class="v"><p style="margin-right: 15px;">BUSQUEDA POR DE FECHA</p></label>
    			<label class="v">
    				<p style="margin-right: 10px;">
    				DÍA
    				<input type="checkbox" selected name="dia" id="dia_au" style="margin-right: 10px; margin-left: 20px;" checked>
    				</p>
    			</label>
    			<label class="v">
    				<p style="margin-right: 10px;">
    					<input class="form-control form-inline" type="date" name="dias" id="diasAu" placeholder="yyyy-mm-dd">
    				</p>
    			</label>
    			<label class="v">
    				<p style="margin-right: 10px;">
    					RANGO
    					<input type="checkbox" selected name="dia" id="rango_au" style="margin-right: 10px; margin-left: 15px;">
    				</p>
    			</label>
    			<label class="v">
    				<p style="margin-right: 10px;">
    					<input class="form-control form-inline" type="date" name="desde" id="desdeAu" disabled="disabled" placeholder="yyyy-mm-dd">
    				</p>
    			</label>
    			<label>
    				<p style="margin-right: 10px;">
    					<input class="form-control form-inline" type="date" name="hasta" id="hastaAu" disabled="disabled" placeholder="yyyy-mm-dd">
    				</p>
    			</label>
    		</li>
    	</ul>
    		<div class="col-lg-6">
    			<label>
    				<span class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i>
					</span>
					<input class="form-control" type="number" min="1000000" name="cedula" id="cedulaAut" placeholder="Por favor ingrese la cedula del trabajador">
					</span>
				</label>
			</div>
			<div class="col-lg-6">
				<label class="form-group">
					<span class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i>
					</span>
					<select class="form-control" name="departamento" id="departamentoAu">
						<option selected disabled> Seleccione un departamento</option>
						@foreach($departamento as $dp)
							<option value="{{$dp->dp_id}}"> {{$dp->dp_nombre}} </option>
						@endforeach
					</select>
					</span>
				</label>
    		</div>
  	</div>
</div>

<div class="panel panel-default" style="margin-left: 20px; margin-right: 20px;">
  	<!-- Default panel contents -->
  	<div class="panel-heading" style="background-color: #e5e8e8;">
  		<h3 class="panel-title">
    		EMPLEADOS
    		<!-- Boton de la modal -->
			<a href="#" title="Agregar nueva autorización" data-toggle="modal" data-target="#modalAuto" style="display: none;" id="bpa_agregar"> 
				<img src="assets/img/iconos/addexc.svg" style="height: 35px; margin-top: -7px" align="right">
			</a>
			<!-- Modal -->
						<div class="modal fade" id="modalAuto" role="dialog">
						    <div class="modal-dialog">
						        <div class="modal-content text-center">
						            <div class="panel panel-default">
  										<div class="panel-heading" style="background-color: #e5e8e8;">
    										<h3 class="panel-title">
    											<b>REGISTRO DE AUTORIZACIÓN</b>
    											<button type="button" class="close" data-dismiss="modal">
					    							<span aria-hidden="true">×</span>
										        </button>
    										</h3>
  										</div>
  										<div class="panel-body">
											<form method="post" action="registrarAuto">
												{{ csrf_field() }}
												<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
									        	<div class="form-group col-lg-6 text-left">
									        		<label>Cédula</label>
													<span class="input-group">
														<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i>
													</span>
													<input class="form-control" 
														type="number" 
														id="cedulaau" 
														name="cedula" 
														placeholder="Por favor ingrese la cedula del trabajador" 
														min="1000000" 
														pattern="d/{8}" 
														required/>
												</div>
									        	<div class="col-lg-6 text-left">
													<div class="form-group">
														<label>Tipo de autorización</label>
										                <select class="form-control" name="autorizacion" required>
										                	<option selected disabled>
										                		Elija un tipo de autorización
										                	</option>
										                	<?php foreach ($tipo_autorizacion as $tipo): ?>
										                		<option value="<?php echo $tipo->tiau_id; ?>">
										                			{{$tipo->tiau_tipo}}
										                		</option>
										                	<?php endforeach ?>
										                </select>
									            	</div>
												</div>
									            <div id="formAut">
									            	<input type="hidden" name="empleado" value="${element.us_ced}">
													<div class="col-lg-6 text-left">
									                	<div class="form-group">
															<label for="inputNombre">Nombre</label>
															<input type="text" name="nombre" class="form-control" disabled>
											                
											            </div>
									                </div>
									                <div class="col-lg-6 text-left">
									                	<div class="form-group">
															<label for="inputApellido">Apellido</label>
															<input type="text" name="apellido" class="form-control" disabled>
											                
											            </div>
									                </div>
									                <div class="col-lg-6 text-left">
									                	<div class="form-group">
															<label for="inputDepartamento">Departamento</label>
											                <select class="form-control" name="departamento" required>
											                	<option disabled selected>
											                	</option>
											                </select>
											            </div>
									                </div>
									                <div class="col-lg-6 text-left">
									                	<div class="form-group">
															<label for="inputFechaini">Fecha de Ingreso</label>
											            	<input type="date" name="fecha" class="form-control" id="permiso" required placeholder="yyyy-mm-dd">   
											            </div>
									                </div>

													<div class="col-lg-12 text-left">
									                	<div class="form-group">
															<label for="inputDiag">Motivo de la Autorización</label>
																<input type="text" name="motivo" class="form-control" placeholder="ingrese el motivo" maxlength="250" title="Por favor ingrese una descripción no mayor a 250 palabras">
											            </div>
									                </div>
									            </div>
											</form>
 										</div>
									</div>
						        </div>
						    </div>
						</div>
    	</h3>
    </div>
    <div class="responsive">
		@if(count($usuarios)>0)
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
						Fecha
					</th>
					<th class="text-right">
						
					</th>
				</tr>
			</thead>
			<tbody id="autorizacionBody">
				<?php foreach ($usuarios as $us): $fecha = new DateTime($us->au_permiso); ?>
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
						</td>
						<td>
							{{$fecha->format('d-m-Y')}}
						</td>
						<td class="text-right">
							<a class="showAuto"  onclick="showAutorizacion({{$us->au_id}},{{$us->us_ced}})" href="#" data-toggle="modal" data-target="#showAuto"> 
								<img src="assets/img/iconos/ver.svg" class="imgmenuho" >
							</a>
							<div class="modal fade" id="showAuto" tabindex="-1" role="dialog" aria-labelledby="EditarPermiso" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content text-center">
										<div class="panel panel-default">
  											<div class="panel-heading">
    											<h3 class="panel-title">
    												<b>DETALLES DE LA AUTORIZACIÓN</b>
    												<button type="button" class="close" data-dismiss="modal">
						    							<span aria-hidden="true">×</span>
											        </button>
    											</h3>
  											</div>
  											<div class="panel-body">
    											<ul class="list-group text-left" id="autorizacionShow">
			
												</ul>
  											</div>
										</div>
									</div>
								</div>
							</div>
							<a class="editAutorizacion" style="display: none;" onclick="editAutorizacion({{$us->au_id}},{{$us->us_ced}})" href="#" data-toggle="modal" data-target="#editAutorizacion"> 
								<img src="assets/img/iconos/editar.svg" class="imgmenuho" >
							</a>
							<div class="modal fade" id="editAutorizacion" tabindex="-1" role="dialog" aria-labelledby="EditarPermiso" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content text-center">
										<div class="panel panel-default">
  											<div class="panel-heading">
    											<h3 class="panel-title">
    												<b>EDITAR AUTORIZACIÓN</b>
    												<button type="button" class="close" data-dismiss="modal">
						    							<span aria-hidden="true">×</span>
											        </button>
    											</h3>
  											</div>
  											<div class="panel-body">
  												<form method="POST" action="updateAutorizacion" >
  													{{csrf_field()}}
	    											<div class="col-lg-6 text-left">
										            	<div class="form-group">
										            		<label>Tipo de autorización</label>
															<select class="form-control" name="autorizacion" id="tipoaut" required>
											                	
											                </select>		                
											            </div>
										            </div>
													<div id="AutorizacionEdit">
														
													</div>
													<div class="col-lg-12" style="border-top: 1px solid #ccc; font-size: 14px;">
														<div class="col-lg-6 text-left" style="margin-top:10px;">
															<button type="button" data-dismiss="modal" class="btn btn-default">
																CERRAR
															</button>
														</div>
														<div class="col-lg-6 text-right" style="margin-top:10px;">
															<button type="submit" name="registrar" id="submitP" class="btn" style="background-color: #48c9b0; color:white;">
																MODIFICAR
															</button>
														</div>
													</div>	
												</form>
  											</div>
										</div>
									</div>
								</div>
							</div>
							<a href="#" style="display: none;" class="deleteAuto" onclick="eliminarA({{$us->au_id}})" title="Eliminar reposo">
								<img src="assets/img/iconos/eliminar.svg" style="height: 25px; margin-top: 5px" align="right">
							</a>
						</td>
					</tr>
					
				<?php endforeach ?>	
			</tbody>
		</table>
		@else 
			<tr>
				<td>
					<strong>Disculpe,</strong> no se encontraron resultados...
				</td>
			</tr>
			@endif 
		<center id="centerasi" class="paginacion_f">
			<?php echo $usuarios->render(); ?>
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

<script src="assets/js/Asistencia/excepciones/autorizacion.js"></script>

<link rel="stylesheet" type="text/css" href="assets/datePicker/css/bootstrap-datepicker3.css">
<link rel="stylesheet" type="text/css" href="assets/datePicker/css/bootstrap-standalone.css">
<!-- Languaje -->
<script src="assets/datePicker/js/bootstrap-datepicker.js"></script>
<script src="assets/datePicker/locales/bootstrap-datepicker.es.min.js"></script>

<script>
	//PANEL DE BUSQUEDA
    $('#diasAu').datepicker({
        format: "yyyy-mm-dd",
        language: "es",
        autoclose: true
    });
    $('#desdeAu').datepicker({
        format: "yyyy-mm-dd",
        language: "es",
        autoclose: true
    });
    $('#hastaAu').datepicker({
        format: "yyyy-mm-dd",
        language: "es",
        autoclose: true
    });


    //REGISTRO DE AUTORIZACION
    $('#permiso').datepicker({
        format: "yyyy-mm-dd",
        language: "es",
        autoclose: true
    });
</script>
@endsection