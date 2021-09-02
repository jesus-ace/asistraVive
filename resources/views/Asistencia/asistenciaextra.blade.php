@extends('menu')
@section('contenido')

<?php if (! $errors->isEmpty()): ?>
	<div class="alert alert-danger col-lg-4" style="margin-left: 750px;" ng-disable id="erroresFAE">
		<p><strong>Lo sentimos</strong>Por favor corrige los siguientes errores</p>
		<?php foreach ($errors->all() as $error): ?>
			<li>{{ $error}}</li>
		<?php endforeach ?>	
	</div>
<?php endif ?> 

@if(Session::has('flash_message'))
	<div class="col-lg-4" style="margin-left: 750px;" id="successAE">
		<div class="alert alert-success">
		{{Session::get('flash_message')}}
		</div>	
	</div>
@endif
@if(Session::has('session'))
	<div class="col-lg-4" style="margin-left: 750px;" id="dangerAE">
		<div class="alert alert-danger">
		{{Session::get('session')}}
		</div>	
	</div>
@endif


@if($aco_emp == 'p_asistenciae')
	<input type="hidden" name="ventana" id="ventana" value="asistenciaextra">
	<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
	<div class="col-lg-12">

		<div class="panel panel-default">
	  		<div class="panel-heading" style="background: #e5e8e8;">
	    		<h3 class="panel-title">
	    			ASISTENCIA EXTRAORDINARIA
		    		<a href="asistenciaextra" id="refrescarex" title="Refrescar página">
						<img src="assets/img/iconos/refrescar.svg"  style="height: 25px;" align="right">
					</a>
			</h3>
	  		</div>
	  		<div class="panel-body">
	    		<div class="row">
	    			<div class="form-group col-lg-6">
						<span class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i>
						</span>
						<input class="form-control" type="number" min="2000000" name="cedula" id="cedulaextra" placeholder="Por favor ingrese la cedula del trabajador">
					</div>
					<div class="form-group col-lg-6">
						<div class="input-field col-lg-10">
							<select class="form-control input-xs" name="departamento" id="departamentoAsi" title="Busqueda por departamento">
								<option selected disabled value="0">Seleccione un departamento</option>
								<?php foreach ($departamento as $dp ): ?>
									<option value="{{$dp->dp_id}}">
										{{$dp->dp_nombre}}
									</option>
								<?php endforeach ?>
							</select>			
						</div>
					</div>
					
				</div>
	  		</div>
		</div>	
		<br>
		


	<div class="panel panel-default" >
	  <!-- Default panel contents -->
	<div class="panel-heading" style="background: #e5e8e8;">REGISTRO DE ENTRADA Y SALIDA EXTRAORDINARIA</div>

	 	<div class="responsive">
		  	<table class="table table-hover">
				<thead style='background: #f2f3f4  repeat-x center top; font-size:14px;'>
					<tr>
						<th>
							Foto
						</th>
						<th>
							Datos
						</th>
						<th>
							Entrada
						</th>
						<th>
							Salida
						</th>
					</tr>
				</thead>
				<tbody id="empleadosAsi">
		@if(count($usuarios)>0)
					<?php foreach ($usuarios as $usuario): ?>
						
						<tr>				
							
							<td>
								<img src="imagenes2/{{$usuario->us_foto}}"  style="height: 60px;border-radius: 6px 6px 6px 6px; border:1px solid #B2B2B2;">
							</td>
							<td>
								<label>Nombre y Apellido:</label> 

									{{$usuario->us_nom}}    						
									{{$usuario->us_ape}}

								<label>Cédula:</label>  

									{{$usuario->us_ced}}

								<br>
								<label>Departamento: </label> 

									{{$usuario->dp_nombre}}
								<br>

								<label>Tipo de usuario:	</label>

									{{$usuario->tdu_tipo}}
							</td>
							<td>
								<a href="#" data-toggle="modal" data-target="#modalEntrada" class="registraEntrada" onclick="registraEntrada({{$usuario->us_ced}})">
									<img src="assets/img/iconos/entrada.svg" class="imgmenuus">
								</a>
								<div class="modal fade" id="modalEntrada" role="dialog">
								    <div class="modal-dialog">
								        <div class="modal-content text-center">
							            	<div class="panel panel-default">
												<div class="panel-heading" style="background-color: #e5e8e8;">
												    <h3 class="panel-title">
												    	<b>REGISTRAR ENTRADA</b>
												    </h3>
												</div>
												<div class="panel-body">
													<form action="registrarentrada" method="post">
														<div class="row">
												        	<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
												        	<div class="col-lg-12" id="entrada">
												        		
												        	</div>
												        	<div class="col-lg-12">
												            	<div class="form-group">
												        			<div class="col-lg-6 text-left">
												        				<label>SELECCIONE LA FECHA SIN HORA DE ENTRADA</label>
												        			</div>
												        			<div class="col-lg-6 form-group">
												        				<select name="salidasinentrada" class="form-control" id="Fentrada"  onchange="agregar_hora()">
																		</select>
																	</div>	
												        		</div>
												    		</div>	
												    		<div id="hora_e">
													        	<div class="col-lg-12">
													        		<div class="form-group">
													        			<div class="col-lg-6 text-left">
													        				<label>INDIQUE LA FECHA DE ENTRADA</label>
													        			</div>
													        			<div class="col-lg-6">
													        				<input type="date" name="fechaentrada" class="form-control" required id="fechaEntrada" placeholder="yyyy-mm-dd">
													        			</div>	
													        		</div>							                		
													        	</div>
													        	<div class="col-lg-12">
													        		<div class="form-group">
													        			<div class="col-lg-6 text-left" style="margin-top: 15px;">
													        				<label>HORA DE ENTRADA</label>
													        				<label>FORMATO DE 24 HORAS</label>
													        			</div>
													        			<div class="col-lg-6" style="margin-top: 20px;">
													        				<input type="time" name="horaentrada" class="form-control" id="horaEnt" placeholder="hh:mm:ss" required/><br>
													        			</div>
													        		</div>
													        	</div>	
												        	</div>				                	
													    </div>
													    <div class="col-lg-12"  style="border-top: 1px solid #ccc; font-size: 14px;">
														    <div class="col-lg-6 text-left" style="margin-top: 10px;">
														        <button type="button" class="btn btn-default" data-dismiss="modal">
														        	CERRAR
														        </button>
														    </div>
															<div class="col-lg-6" style="margin-top: 10px;">
																<div class="form-group text-right">
																	<label>
																		<button type="submit" name="Registrar" class="btn" value="Registrar entrada" style="background-color: #48c9b0; color:white;">
																			<b>REGISTRAR</b>
																		</button>
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
							</td>
							<td>
								<a href="#" data-toggle="modal" data-target="#modalSalida" onclick="registraSalida({{$usuario->us_ced}})">
									<img src="assets/img/iconos/salida.svg" class="imgmenuus">
								</a>
								<div class="modal fade" id="modalSalida" role="dialog">
								    <div class="modal-dialog">
								        <div class="modal-content text-center">
								            <div class="panel panel-default">
	  											<div class="panel-heading" style="background-color: #e5e8e8;">
	    											<h3 class="panel-title">
	    												<b>REGISTRAR SALIDA</b>
	    											</h3>
	 											</div>
	  											<div class="panel-body">
	    											<div class="row">
												        <form action="registrarsalida" method="post">
												        	<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

												        	<input type="hidden" name="hentrada" id="hentrada">
												        	<input type="hidden" name="asi_idd" id="asi_idd">
												        	<div class="col-lg-12" id="salida">
												        		
												        	</div>
												        	<div class="col-lg-12">
												            	<div class="form-group">
												        			<div class="col-lg-6 text-left">
												        				<label>SELECCIONE LA FECHA DE ENTRADA</label>
												        			</div>
												        			<div class="col-lg-6 form-group">
												        				<select name="entradasinsalida" class="form-control" id="Fsalida" required>
																		</select>
																	</div>	
												        		</div>
												    		</div>	
												        	<div class="col-lg-12">
												        		<div class="form-group">
												        			<div class="col-lg-6 text-left">
												        				<br><label>INDIQUE LA FECHA DE SALIDA</label>
												        			</div>
												        			<div class="col-lg-6">
												        				<br><input type="date" name="fechasalida"  id="fechaSa" class="form-control" placeholder="yyyy-mm-dd" required >
												        			</div>	
												        		</div>							                		
												        	</div>
												        	<div class="col-lg-12">
												        		<div class="form-group">
												        			<div class="col-lg-6 text-left">
												        				<br>
												        				<label>HORA DE SALIDA</label>
												        				<label>FORMATO DE 24 HORAS</label>
												        			</div>
												        			<div class="col-lg-6">
												        				<input type="time" name="horasalida" style="margin-top: 20px;" id="hsalida" class="form-control" placeholder="hh:mm:ss" required/><br>
												        			</div>
												        		</div>
												        	</div>
												        	<div class="col-lg-12"  style="border-top: 1px solid #ccc; font-size: 14px;">
															    <div class="col-lg-6 text-left" style="margin-top: 20px;">
															        <button type="button" class="btn btn-default" data-dismiss="modal">
															        	CERRAR
															        </button>
															    </div>
																<div class="col-lg-6" style="margin-top: 10px;">
																	<div class="form-group text-right">
																		<label>
																			<button  type="submit" name="Registrar" class="btn" value="Registrar salida" style="background-color: #48c9b0; color:white;">
																				<b>REGISTRAR SALIDA</b>
																			</button>
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

		<center id="centerasi" class="paginacion_f">
			<?php echo $usuarios->render(); ?>
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
	<script src="assets/js/Asistencia/asistenciaextra.js"></script>

	<link rel="stylesheet" type="text/css" href="assets/datePicker/css/bootstrap-datepicker3.css">
    <link rel="stylesheet" type="text/css" href="assets/datePicker/css/bootstrap-standalone.css">
    <!-- Languaje -->
    <script src="assets/datePicker/js/bootstrap-datepicker.js"></script>
    <script src="assets/datePicker/locales/bootstrap-datepicker.es.min.js"></script>

<script>
	//entrada
    $('#fechaEntrada').datepicker({
        format: "yyyy-mm-dd",
        language: "es",
        autoclose: true
    });
	//salida
    $('#fechaSa').datepicker({
        format: "yyyy-mm-dd",
        language: "es",
        autoclose: true
    });
</script>
@endsection