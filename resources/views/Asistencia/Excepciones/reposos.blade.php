@extends('menu')
@section('contenido')
<div class="row">
	<?php if (! $errors->isEmpty()): ?>
		<div class="alert alert-danger" ng-disable>
			<p><strong>Lo sentimos</strong>Por favor corrige los siguientes errores</p>
			<?php foreach ($errors->all() as $error): ?>
				<li>{{ $error}}</li>
			<?php endforeach ?>	
		</div>
	<?php endif ?> 
	@if(Session::has('flash_message'))
		<div class="col-lg-4" style="margin-left: 750px;" id="alertaR">
			<div class="alert alert-success">
			{{Session::get('flash_message')}}
			</div>	
		</div>
	@endif
	@if(Session::has('session'))
		<div class="col-lg-4" style="margin-left: 750px;" id="alertaRd">
			<div class="alert alert-danger">
			{{Session::get('session')}}
			</div>	
		</div>
	@endif
</div>

@if($aco_rep == 'p_reposos')
<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
	<input type="hidden" name="ventana" id="ventana" value="reposos">

	<div class="panel panel-default" style="margin-left: 16px; margin-right: 16px;">
  		<div class="panel-heading" style="background-color: #e5e8e8;">
    		<h3 class="panel-title">
    			<b>REPOSOS</b>
				<a href="reposos" title=" Refrescar página">
						<img src="assets/img/iconos/refrescar.svg"  style="height: 25px;" align="right">
				</a>
			</h3>
  		</div>
  		<div class="panel-body">
    		<div class="row">			
				
				<div class="form-group col-lg-5">
					<span class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i>
					</span>
					<input class="form-control" type="number" min="1000000" max="35000000" name="cedula" id="cedulaR" placeholder="Por favor ingrese la cedula del trabajador">
				</div>		
				<div class="form-group col-lg-6" style="display:none;" id="dpResp">
					<span class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i>
					</span>
					<select class="form-control" name="departamento" id="departamentoR">
						<option selected disabled>Seleccione un departamento</option>
						@foreach($departamento as $dp)
							<option value="{{$dp->dp_id}}">{{$dp->dp_nombre}}</option>
						@endforeach
					</select>
				</div>
			</div>
  		</div>
	</div>

<div class="col-lg-12">
<div class="panel panel-default">
  	<!-- Default panel contents -->
	<div class="panel-heading" style="background-color: #e5e8e8;">
		EMPLEADOS
		<a href="#" data-toggle="modal" data-target="#modalReposos" style="display: none;" id="bpr_agregar"> 
			<img src="assets/img/iconos/addexc.svg" style="height: 35px; margin-top: -7px;" align="right">
		</a>
	<!-- Modal -->
	<div class="modal fade" id="modalReposos" role="dialog">
	    <div class="modal-dialog">
	        <div class="modal-content text-center">
	            <div class="panel panel-default">
						<div class="panel-heading" style="background-color: #e5e8e8;">
						<h3 class="panel-title">
							<b>REGISTRAR REPOSO</b>
							<button type="button" class="close" data-dismiss="modal">
			                    <span aria-hidden="true">×</span>
			                </button>
						</h3>
						</div>
						<div class="panel-body">
							<form method="post" action="registrarepo" accept-charset="UTF-8" enctype="multipart/form-data">
							<div class="row">
			                	<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
			                	<input type="hidden" name="tipo_aud" id="tipo_aud" value="Registro">
			                	<input type="hidden" name="desc_aud" id="desc_aud" value="Registro de reposo">
			                	
			                	<div class="col-lg-12">
									<div class="form-group col-lg-12 text-left">
										<label>Cédula</label>
										<input class="form-control" 
											type="number" 
											id="cedula" 
											name="cedula" 
											placeholder="Por favor ingrese la cedula del trabajador" 
											min="1000000" 
											pattern="d/{8}" 
											required/>
									</div>																	            
								</div>
								<div class="col-lg-12" id="formulariorep">
									<div class="col-lg-6 text-left">
									<input type="hidden" value="${usuario.us_ced}" name="us_id">
					            	<div class="form-group">
											<label for="inputNombre">Nombre</label>
											<input type="text" name="nombre" class="form-control" id="re_name" disabled >
							                
							            </div>
					            	</div>
					                <div class="col-lg-6 text-left">
					                	<div class="form-group">
											<label for="inputApellido">Apellido</label>
											<input type="text" name="apellido" class="form-control" id="re_ape" disabled >
							                
							            </div>
					                </div>
					                <div class="col-lg-6 text-left">
					                	<div class="form-group">
											<label for="inputDepartamento">Departamento</label>
							                <select class="form-control" name="reposo" id="re_dp">
							                	
							                </select>
							            </div>
					                </div>					                
									<div class="col-lg-6 text-left rtdu">
				                    	<div class="form-group">
											<label for="inputTipoRep">Tipo de reposo</label>
							                <select class="form-control" name="tireposo" id="re_tdr" required>
							                	<option  disabled selected>Seleccione un tipo de reposo</option>
							                	<?php foreach ($tiporeposo as $tr): ?>
							                		<option value="{{$tr->tire_id}}">{{$tr->tire_tipo}}</option>
							                	<?php endforeach ?>
							                </select>
							            </div>
					                </div>
					                <div class="col-lg-12 text-left">
					                	<div class="form-group">
											<label for="inputCentromedico">Centro médico</label>
							                <input type="text" name="centro" class="form-control" id="re_centro" placeholder="Ingrese el centro médico" required>
							            </div>
					                </div>
					                <div class="col-lg-6 text-left">
					                	<div class="form-group">
											<label for="inputFechaini">Fecha de inicio</label>
							            	<input type="date" name="desde" class="form-control" id="inicioR" required placeholder="yyyy-mm-dd">   
							            </div>
					                </div>
					                <div class="col-lg-6 text-left">
					                	<div class="form-group">
											<label for="inputFechafin">Fecha de culmunación</label>
							            	<input type="date" name="hasta" class="form-control" id="culmunacionR" required placeholder="yyyy-mm-dd">   
							            </div>
					                </div>
					                <div class="col-lg-6 text-left">
					                	<div class="form-group">
											<label for="inputAdjunto">Añadir archivo adjunto</label>
							            	<input type="file" class="form-control" name="file" accept="application/pdf"> 
							            </div>
					                </div>
					                <div class="col-lg-6 text-left">
					                	<div class="form-group">
											<label for="inputDiag">Diagnóstico</label>
											<input type="text" name="diagnostico" id="re_diag" class="form-control" placeholder="Indique el diagnóstico" maxlength="250" title="Por favor ingrese una descripción no mayor a 250 palabras" required>							            	  
							            </div>
					                </div>
									<div class="col-lg-12">
										<div class="form-group">
											<label>¿Es validado por el IVSS?</label>
											<label class="radio-inline">
												<h4><input type="radio" name="validado" id="si" value="1" checked> Si</h4>
											</label>
											<label class="radio-inline">
												<h4><input type="radio" name="validado" id="no" value="2" >No</h4>
											</label>
										</div>
									</div>
								</div>
								<div class="col-lg-12"  style="border-top: 1px solid #ccc; font-size: 14px;">
									<div class="col-lg-6 text-left"  style="margin-top:10px;">
						        		<button type="button" class="btn btn-default" data-dismiss="modal">CERRAR
						        		</button>
									</div>
									<div class="col-lg-6 text-right"  style="margin-top:10px;">
										<button type="submit" name="Registrar" value=" Registrar reposo" class="btn" style="background-color: #48c9b0; color:white;" onclick="chequea_contenido()">
										<b>REGISTRAR</b>
										</button>
									</div>
								</div>	
								</form>		     
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
					Tipo de reposo
				</th>
				<th></th>
				<th class="text-right">
					<!-- Boton de la modal -->

					</div>
				</th>
			</tr>
		</thead>
		<tbody id="reposoM">
		@if(count($excepcion)>0)
			<?php foreach ($excepcion as $us): ?>
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
						{{$us->tire_tipo}}
					</td>
					<td class="text-right">
						<a class="showReposo"  onclick="showReposo({{$us->re_id}},{{$us->us_ced}})" href="#" data-toggle="modal" data-target="#showReposo"> 
							<img src="assets/img/iconos/ver.svg" class="imgmenuho" >
						</a>
						<div class="modal fade" id="showReposo" tabindex="-1" role="dialog" aria-labelledby="EditarPermiso" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content text-center">
									<div class="panel panel-default">
  										<div class="panel-heading" style="background-color: #e5e8e8;">
    										<h3 class="panel-title">
    											<b>DETALLES DEL REPOSO</b>
    											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    										</h3>
  										</div>
  										<div class="panel-body">
    										<ul class="list-group text-left" id="reposoShow">
    											
    										</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
						<a class="editReposo"  onclick="editReposo({{$us->re_id}},{{$us->us_ced}})" href="#" data-toggle="modal" data-target="#editarRE" style="display: none;"> 
							<img src="assets/img/iconos/editar.svg" class="imgmenuho" >
						</a>
						<div class="modal fade" id="editarRE" tabindex="-1" role="dialog" aria-labelledby="EditarReposo" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content text-center">
									<div class="panel panel-default">
										<div class="panel-heading" style="background-color: #e5e8e8;">
    										<h3 class="panel-title">
    											<b>EDITAR REPOSO</b>
    										</h3>
  										</div>
	  									<div class="panel-body">
	  										<form method="POST" action="updateReposo" accept-charset="UTF-8" enctype="multipart/form-data">
	  											{{ csrf_field() }}
		    									<div class="row">
		    										<div class="col-lg-12">
														<div class="col-lg-6 text-left">
										                	<div class="form-group">
																<label for="inputTipoRep">Tipo de reposo</label>
												                <select class="form-control" name="tireposo" id="treposo" required>
												                	<?php foreach ($tiporeposo as $tr): ?>
												                		<option value="{{$tr->tire_id}}">{{$tr->tire_tipo}}</option>
												                	<?php endforeach ?>
												                </select>
												            </div>
										                </div>
														<div id="editReposo">
															
														</div>
														<div class="col-lg-12" style="border-top: 1px solid #ccc; font-size: 14px;">
															<div class="col-lg-6 text-left" style="margin-top: 10px;">
													    		<button type="button" class="btn btn-default" data-dismiss="modal">CERRAR
													    		</button>
															</div>
															<div class="col-lg-6 text-right" style="margin-top: 10px;">
																<button type="submit" class="btn" style="background-color: #48c9b0; color:white;">
																	<b>MODIFICAR</b>
																</button>
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
					</td>
					<td>
						<a href="#" onclick="eliminarR({{$us->re_id}})" title="Eliminar reposo" class="deleteReposo" style="display: none;">
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
	</div>
</div>
</div>

<div class="col-lg-12">
	
	<center id="centerasi" class="paginacion_f">
			<?php echo $excepcion->render(); ?>
		</center>
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
<script src="assets/js/Asistencia/excepciones/reposos.js"></script>
	

<link rel="stylesheet" type="text/css" href="assets/datePicker/css/bootstrap-datepicker3.css">
<link rel="stylesheet" type="text/css" href="assets/datePicker/css/bootstrap-standalone.css">
<!-- Languaje -->
<script src="assets/datePicker/js/bootstrap-datepicker.js"></script>
<script src="assets/datePicker/locales/bootstrap-datepicker.es.min.js"></script>

<script>
	//entrada
    $('#inicioR').datepicker({
        format: "yyyy-mm-dd",
        language: "es",
        autoclose: true
    });
	//salida
    $('#culmunacionR').datepicker({
        format: "yyyy-mm-dd",
        language: "es",
        autoclose: true
    });
</script>
@endsection