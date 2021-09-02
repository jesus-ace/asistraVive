@extends('menu')
@section('contenido')
<div class="row">
<?php if (! $errors->isEmpty()): ?>
	<div class="col-lg-4" style="margin-left: 750px;" id="Alertaerror">
		<div class="alert alert-danger">
			<p><strong>Lo sentimos </strong> Por favor corrige los siguientes errores</p>
			<?php foreach ($errors->all() as $error): ?>
				<li>{{ $error}}</li>
			<?php endforeach ?>	
		</div>
	</div>
<?php endif ?> 
@if(Session::has('flash_message'))
	<div class="col-lg-4" style="margin-left: 750px;" id="alerta">
		<div class="alert alert-success">
		{{Session::get('flash_message')}}
		</div>	
	</div>
@endif
</div>
@if($aco_hora == 'p_horarios')
<input type="hidden" name="ventana" id="ventana" value="horarios">

		<div class="panel panel-default" style="margin-left: 20px; margin-right: 20px;">
		  	<!-- Default panel contents -->
		  	<div class="panel-heading"  style="background-color: #e5e8e8;">
		  		HORARIOS
		  		<img src="assets/img/iconos/horario.svg" style="height: 35px; margin-left: 15px;">
		  		<a href="#" data-toggle="modal" data-target="#agregarho" title="Registrar un nuevo horario" style="display: none;" id="bph_agregar"> 
					<img src="assets/img/iconos/registroh.svg" style="height: 30px;"  align="right">
				</a>
				<a title="Asignación en masa" href="#" data-toggle="modal" data-target="#asigmasa"  align="right" style="display: none;" id="bph_asig_masa"> 
					<img src="assets/img/iconos/addexc.svg" style="height: 30px;"  align="right">
				</a>
		  	</div>
 			<div class="table-responsive">
			  	<!-- Table -->
			  	<table class="table table-hover">
					<thead style='background: #f2f3f4  repeat-x center top; font-size:14px;'>
						<tr>
							<th>
								Días Laborables
							</th>
							<th>
								Holgura entrada
							</th>
							<th>
								Hora de entrada
							</th>
							<th>
								Hora de Salida
							</th>
							<th>
								Holgura salida
							</th>
							<th>				
								Turno
							</th>
							<th class="text-right">
								<div class="modal fade" id="agregarho" tabindex="-1" role="dialog" aria-labelledby="AgregarHorario" aria-hidden="true">
									<div class="modal-dialog">	
										<div class="modal-content text-center">
											<div class="panel panel-default">
											  	<div class="panel-heading"  style="background-color: #e5e8e8;">
											    	<h3 class="panel-title">
											    		<b>
											    			REGISTRAR UN NUEVO HORARIO
											    		</b>
											    		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
											    	</h3>
											  	</div>
											  	<div class="panel-body">
											  		<form method="POST" action="horario" >
											  			{{ csrf_field() }}
												    	<ul class="list-group text-left">

														  	<li class="list-group-item text-center">
														  		DÍAS DE LA SEMANA
														  	</li>
														  	<li class="list-group-item">
														  		<label class="v">
														  			LUNES 
														  			<input type="checkbox" name="dias[]" id="lunes" value="Lunes" style="margin-right: 10px;"> 
														  		</label>
														  		<label class="v">
															  		MARTES
															  		<input type="checkbox" name="dias[]" id="martes" value="Martes" style="margin-right: 10px;">
														  		</label>
														  		<label class="v">
															  		MIÉRCOLES
															  		<input type="checkbox" name="dias[]" id="miercoles" value="Miercoles" style="margin-right: 10px;">
														  		</label>
														  		<label class="v">
														  			JUEVES
														  			<input type="checkbox" name="dias[]" id="jueves" value="Jueves" style="margin-right: 10px;">
														  		</label>
														  		<label>
														  			VIERNES
														  			<input type="checkbox" name="dias[]" id="viernes" value="Viernes">
														  		</label>
														  	</li>
														  	<li class="list-group-item text-center">
														  		FIN DE SEMANA
														  	</li>
														  	<li class="list-group-item text-center">
														  		<label class="v">
														  			SÁBADO
														  			<input type="checkbox" name="dias[]" id="sabado" value="Sabado" style="margin-right: 10px;">
														  		</label>
														  		<label>
														  			DOMINGO
																	<input type="checkbox" name="dias[]" id="domingo" value="Domingo">
														  		</label>
														  	</li>
														  	<li class="list-group-item">
														  		<label class="form-inline">
														  			HORA DE ENTRADA
														  			<input type="time" name="time1" class=" v form-control hora_entrada_add" value="08:00" required>
														  		</label>
														  		<label class="form-inline" style="border-left: 1px solid #ccc;   font-size: 14px;">
														  			<p style=" margin-left: 10px;">
														  			HORA DE SALIDA
														  			<input type="time" name="time2" class="form-control hora_salida_add" value="17:00" required>
														  			</p>
														  		</label>
														  	</li>

														  	<li class="list-group-item">
														  		<label class="form-inline" style="font-size: 12px;">
														  			HOLGURA DE ENTRADA
														  			<input type="time" name="holgura_en" id="holgura_en" class=" v holgura_en form-control" title="Tiempo de holgura antes de la hora de entrada" required value="07:00">
														  		</label>
														  		<label class="form-inline" style="border-left: 1px solid #ccc; font-size: 12px;">
														  			<p style=" margin-left: 10px;">
														  			HOLGURA DE SALIDA
														  			<input type="time" name="holgura_sa" class="holgura_sa form-control" title="Tiempo de holgura despues de la hora de salida" required value="18:00">
														  			</p>
														  		</label>
														  	</li>
														</ul>
														<div class="col-lg-6 text-left">
															<button type="button" class="btn btn-default" data-dismiss="modal">CERRAR</button>
														</div>
														<div class="col-lg-6 text-right">
															<button style="background-color: #48c9b0; color:white;" class="btn" type="submit" name="registrar">
																<b>REGISTRAR</b>
															</button>
														</div>
													</form>
											  	</div>
											</div>
										</div>
									</div>
								</div>
							</th>
							<th class="text-right">
								<div class="modal fade" id="asigmasa" tabindex="-1" role="dialog" aria-labelledby="Asigmasa" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content text-center">
											<div class="panel panel-default">
											  	<div class="panel-heading"  style="background-color: #e5e8e8;">
											    	<h3 class="panel-title">
											    		<b>
												    		ASIGNACIÓN EN MASA
												    	</b>
												    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
											   		</h3>
											  	</div>
											  	<div class="panel-body">
											  		<form method="POST" action="asigmasa" >
											  			{{ csrf_field() }}
												  		<ul class="list-group text-left">
												  			<li class="list-group-item">
												  				<label for="inputHorario">Horario</label>
																<select name="horario" class="form-control" id="horarioAsig">
																	<option selected disabled>Seleccione un horario</option>
																	@foreach ($horarios as $ho)
																		<option value="{{$ho->tiho_id}}">
																			{{$ho->tiho_turno}}
																			{{$ho->tiho_dias}}
																			{{$ho->tiho_hora_en}}
																			{{$ho->tiho_hora_sa}}
																		</option>
																	@endforeach
																</select>
												  			</li>
												  			<li class="list-group-item" style="display: none;" id="dpMasa">
												  				<label>Departamento</label>
																<select class="form-control" id="dptoHorario" name="departamento" required>
																	<option disabled selected value="0"> Seleccione un departamento</option>
																	<?php foreach ($departamentos as $dp ): ?>
																		<option value="{{$dp->dp_id}}">{{$dp->dp_nombre}}</option>
																	<?php endforeach ?>
																</select>
												  			</li>
												  			<li class="list-group-item" style="display: none;" id="listaAM">
												  				<table class="table">
													  				<thead>
													  					<tr>
													  						<th>
													  							FOTO
													  						</th>
													  						<th>
													  							NOMBRES Y APELLIDOS
													  						</th>
													  						<th>
													  							CÉDULA
													  						</th>
													  						<th  style="font-size: 12px;">
													  							<input type="checkbox" name="todos" class="form-group" title="Seleccionar todo" id="asig_masa_lista">
													  						</th>
													  					</tr>
													  				</thead>
													  				<tbody id="asigUs">

													  				</tbody>
													  			</table>
												  			</li>
												  		</ul>	
												  		<div class="col-lg-6 text-left">
															<button type="button" class="btn btn-default" data-dismiss="modal">CERRAR</button>
														</div>
														<div class="col-lg-6 text-right">
															<button style="background-color: #48c9b0; color:white;" class="btn" type="submit" name="guardar">
																<b>GUARDAR</b>
															</button>
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
					<tbody id="horarioCuerpo">
						<?php foreach ($horarios as $horario):?>
							<tr>
								<td>						
									{{$horario->tiho_dias}}.
								</td>
								<td>						
									{{$horario->tiho_holgura_entrada}}
								</td>
								<td>						
									{{$horario->tiho_hora_en}}
								</td>
								<td>
									{{$horario->tiho_hora_sa}}
								</td>
								<td>
									{{$horario->tiho_holgura_salida}}
								</td>
								<td>
									{{$horario->tiho_turno}}
								</td>
								<td class="text-right">
									<a class="edithorario bph_modificar" tiho="{{$horario->tiho_id}}" href="#" data-toggle="modal" data-target="#editarho" title="Editar horario" style="display: none;"> 
										<img src="assets/img/iconos/editar.svg" class="imgmenuho" >
									</a>
									<div class="modal fade" id="editarho" tabindex="-1" role="dialog" aria-labelledby="editarhorario" aria-hidden="true">
										<div class="modal-dialog">
											<div class="modal-content text-center">
												<div class="panel panel-default">
												  	<div class="panel-heading" style="background-color: #e5e8e8;">
												    	<h3 class="panel-title">
												    		<b>
												    			EDITAR HORARIO
												    			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												    		</b>
												    	</h3>
												  	</div>
												  	<div class="panel-body">
												    	<form method="POST" action="update" >
												  			{{ csrf_field() }}
													  		<ul class="list-group text-left" id="horas">
													  			
													  		</ul>
															<div class="col-lg-6 text-left">
																<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
															</div>
															<div class="col-lg-6 text-right">
																<button style="background-color: #48c9b0; color:white;" class="btn" type="submit" name="modificar">
																	<b>MODIFICAR</b>
																</button>
															</div>
													  	</form>
												  	</div>
												</div>
											</div>
										</div>
									</div>
									


									<a href="#" class="bph_eliminar eliminarhorario" ho_ho="{{$horario->tiho_id}}" title="Eliminar horario" style="display: none;" > 
										<img src="assets/img/iconos/eliminar.svg" class="imgmenuho">
									</a>
								</td>
								
							</tr>
						<?php endforeach ?>
					</tbody>
				</table>
			</div>
		</div>

<center  class="paginacion_f">
	<?php echo $horarios->render(); ?>
</center>
@else
<center>
	<img src="imagenes/denegado.png" style="height: 500px">
	<br>
	<b style="font-size: 50px;text-shadow: 1px 2px 5px red">ACCESO DENEGADO</b><br>
	<b style="margin-bottom: 15px;">Usted no tiene permiso para ingresar a esta pantalla</b>
</center>
@endif
	<script src="assets/js/jquery.js"></script>
	<script src="assets/js/Usuarios/horarios.js"></script>
@endsection