@extends('menu')
@section('contenido')


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

@if($aco_act == 'p_actualizacion')
<input type="hidden" name="ventana" id="ventana" value="acttablas">

<div class="panel panel-default">
  	<div class="panel-heading" style="background-color: #e5e8e8;">
    	<h3 class="panel-title">ACTUALIZACIÓN DE TABLAS</h3>
  	</div>
  	<div class="panel-body">
  		<div class="col-lg-12">
	  		<!--ACTUALIZAR ESTATUS DE AUTORIZACIONES-->
	    	<div class="panel panel-default">
	  			<div class="panel-heading" style="background-color: #e5e8e8;">
	  				<b>AUTORIZACIONES</b>
	  				<a href="#">
	  					<img src="assets/img/iconos/add.svg" align="right"  style="height: 3%" onclick="$('#panell1').toggle(500);">
	  				</a>
	  			</div>
				<div class="panel-body" style="display: none;" id="panell1">
					<div class="responsive">
						@if(count($auto)>0)
							<table class="table">
								<thead>
									<tr>
										<th>
											Foto
										</th>
										<th>
											Empleado
										</th>
										
										<th>
											Tipo de autorización
										</th>
										<th>
											Permiso
										</th>
										<th>
											Descripción
										</th>
										<th>
											
										</th>
									</tr>
								</thead>
								<tbody>									
									<?php foreach ($auto as $auto): ?>
										<tr>
											<td>
												<img src="imagenes2/{{$auto->us_foto}}" style="height: 50px;">
											</td>
											<td>
												{{$auto->us_nom}} {{$auto->us_ape}}
											</td>
											<td>
												{{$auto->tiau_tipo}}
											</td>
											<td>
												{{$auto->au_permiso}}
											</td>
											<td>
												{{$auto->au_desc}}
											</td>
											<td>
												<a href="#" class="activar_auto activa_datos" auto="{{$auto->au_id}}" style="display: none;"> 
													<img src="assets/img/iconos/check3.svg" style="height: 13%;" title="Activar autorización">
												</a>
											</td> 
										</tr>
									<?php endforeach ?>
								</tbody>
							</table>
						@else
                   			<div>
								<strong>No</strong> hay información para mostrar.
							</div>
                   		@endif
					</div>
				</div>
			</div>
		</div>


		<div class="col-lg-12">
			<!--ACTUALIZAR ESTATUS DE HORARIOS-->
	    	<div class="panel panel-default">
	  			<div class="panel-heading" style="background-color: #e5e8e8;">
	  				<b>HORARIOS</b>
	  				
	  					<img src="assets/img/iconos/add.svg" align="right"  style="height: 3%" onclick="$('#panell2panell6').toggle(500);">
	  				
	  			</div>
				<div class="panel-body" style="display: none;" id="panell2panell6">
					<div class="responsive">
						@if(count($horarios)>0)
							<table class="table">
								<thead>
									<tr>
										<th>
											Turno
										</th>
										<th>
											Días laborales
										</th>
										<th>
											Holgura de entrada
										</th>
										<th>
											Hora de entrada
										</th>
										<th>
											Hora de salida
										</th>
										<th>
											Holgura de salida
										</th>
										<th>
											
										</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($horarios as $hora): ?>
										<tr>
											<td>
												{{$hora->tiho_turno}}
											</td>
											<td>
												{{$hora->tiho_dias}}
											</td>
											<td>
												{{$hora->tiho_holgura_entrada}}
											</td>
											<td>
												{{$hora->tiho_hora_en}}
											</td>
											<td>
												{{$hora->tiho_hora_sa}}
											</td>
											<td>
												{{$hora->tiho_holgura_salida}}
											</td>
											<td>
												<a href="#" class="activar_hora activa_datos" hora="{{$hora->tiho_id}}" style="display: none;">
													<img src="assets/img/iconos/check3.svg" style="height: 13%;" title="Activar horario.">
												</a>
											</td>
										</tr>
									<?php endforeach ?>
								</tbody>
							</table>
						@else
                   			<div >
										<strong>No</strong> hay información para mostrar.
							</div>
                       	@endif
					</div>
				</div>
			</div>
		</div>

  		
		



		<div class="col-lg-12">
		<!--ACTUALIZAR ESTATUS DE PERMISOS-->
    	<div class="panel panel-default">
  			<div class="panel-heading" style="background-color: #e5e8e8;">
  				<b>PERMISOS</b>
  				
  					<img src="assets/img/iconos/add.svg" align="right"  style="height: 3%" onclick="$('#panell6').toggle(500);">
  				
  			</div>
			<div class="panel-body" style="display: none;" id="panell6">
				@if(count($permisos)>0)
					<div class="responsive">
						<table class="table">
							<thead>
								<tr>
									<th>
										Foto
									</th>
									<th>
										Empleado
									</th>
									<th>
										Fecha de inicio
									</th>
									<th>
										Fecha fin
									</th>
									<th>
										Descripción del permiso
									</th>
									<th>
										¿Remunerado?
									</th>
									<th>
										
									</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($permisos as $per): 
								if ($per->per_remunerado == 1) {
									$remunerado = 'Si';
								}
								else{
									$remunerado = 'No';
								}
								?>
									<tr>
										<td>
											<img src="imagenes2/{{$per->us_foto}}" style="height: 50px;">
										</td>
										<td>
											{{$per->us_nom}} {{$per->us_ape}}
										</td>
										<td>
											{{$per->per_fecha_ini}}
										</td>
										<td>
											{{$per->per_fecha_fin}}
										</td>
										<td>
											{{$per->per_desc}}
										</td>
										<td>
											{{$remunerado}}
										</td>
										<td>
											<a href="#" class="activar_per activa_datos" per="{{$per->per_id}}" style="display: none;">
												<img src="assets/img/iconos/check3.svg" style="height: 13%;" title="Activar permiso">
											</a>
										</td>
									</tr>
								<?php endforeach ?>
							</tbody>
						</table>
					</div>
				@else
           			<div>
								<strong>No</strong> hay información para mostrar.
					</div>
               	@endif
			</div>
		</div>
	</div>

		

		<div class="col-lg-12">
			<!--ACTUALIZAR ESTATUS DE REPOSOS-->
	    	<div class="panel panel-default">
	  			<div class="panel-heading" style="background-color: #e5e8e8;">
	  				<b>REPOSOS</b>
	  				
	  					<img src="assets/img/iconos/add.svg" align="right"  style="height: 3%" onclick="$('#panell8').toggle(500);">
	  				
	  			</div>
				<div class="panel-body" style="display: none;" id="panell8">

				@if(count($reposos)>0)
					<div class="responsive">
						<table class="table">
							<thead>
								<tr>
									<th>
										Foto
									</th>
									<th>
										Empleado
									</th>
									<th>
										Tipo de reposo
									</th>
									<th>
										Fecha de inicio
									</th>
									<th>
										Fecha fin
									</th>
									<th>
										Centro médico
									</th>
									<th>
										Diagnóstico
									</th>
									<th>
										¿Validado?
									</th>
									<th>
										
									</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($reposos as $rep): if ($rep->re_validado == 1) {
									$validado = 'Si';
								}
								else{
									$validado = 'No';
								} ?>
									<tr>
										<td>
											<img src="imagenes2/{{$rep->us_foto}}" style="height: 50px;">
										</td>
										<td>
											{{$rep->us_nom}} {{$rep->us_ape}}
										</td>
										<td>
											{{$rep->tire_tipo}}
										</td>
										<td>
											{{$rep->re_fecha_ini}}
										</td>
										<td>
											{{$rep->re_fecha_fin}}
										</td>
										<td>
											{{$rep->re_ce_med}}
										</td>
										<td>
											{{$rep->re_diagnostico}}
										</td>
										<td>
											{{$validado}}
										</td>
										<td>
											<a href="#" class="activar_rep activa_datos" rep="{{$rep->re_id}}" style="display: none;">
												<img src="assets/img/iconos/check3.svg" style="height: 13%;" title="Activar reposo">
											</a>
										</td>
									</tr>
								<?php endforeach ?>
							</tbody>
						</table>
					</div>
				@else
           			<div>
						<strong>No</strong> hay información para mostrar.
					</div>
               	@endif
				</div>
			</div>
		</div>
		


		<!--ACTUALIZAR ESTATUS DE DÍAS FERIADOS-->
		<div class="col-lg-12">
	    	<div class="panel panel-default">
	  			<div class="panel-heading" style="background-color: #e5e8e8;">
	  				<b>DÍAS FERIADOS</b>
	  				
	  					<img src="assets/img/iconos/add.svg" align="right"  style="height: 3%" onclick="$('#panell3').toggle(500);">
	  				
	  			</div>
				<div class="panel-body" style="display: none;" id="panell3">
				@if(count($dferiado)>0)
					<div class="responsive">
						<table class="table">
							<thead>
								<tr>
									<th>
										Fecha
									</th>
									<th>
										Descripción
									</th>
									<th>
										Tipo de día feriado
									</th>
									<th>
										
									</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($dferiado as $diaf): ?>
									<tr>
										<td>
											{{$diaf->diaf_feriado}}
										</td>
										<td>
											{{$diaf->diaf_desc}}
										</td>
										<td>
											{{$diaf->tife_tipo}}
										</td>
										<td>
											<a href="#" class="activar_diaf activa_datos" diaf="{{$diaf->diaf_id}}" style="display: none;">
												<img src="assets/img/iconos/check3.svg" style="height: 13%;" title="Activar dia feriado">
											</a>
										</td>
									</tr>
								<?php endforeach ?>
							</tbody>
						</table>
					</div>
				@else
           			<div>
						<strong>No</strong> hay información para mostrar.
					</div>
               	@endif
				</div>
			</div>
		</div>


  	</div>
</div>















<div class="panel panel-default">
  	<div class="panel-heading" style="background-color: #e5e8e8;">
    	<h3 class="panel-title"><b>GESTIÓN DE TABLAS</b></h3>
  	</div>
  	<div class="panel-body">


		<div class="col-lg-6">
			<!--GESTION DE PREGUNTAS SECRETAS-->
	    	<div class="panel panel-default">
	  			<div class="panel-heading" style="background-color: #e5e8e8;">
	  				<b>PREGUNTAS DE SEGURIDAD</b>
	  				
	  					<img src="assets/img/iconos/add.svg" align="right"  style="height: 3%" onclick="$('#panell7').toggle(500);">
	  				
	  			</div>
				<div class="panel-body" style="display: none;" id="panell7">
					<div class="responsive">
						<table class="table">
							<thead>
								<tr>
									<th>
										Pregunta
									</th>
									<th>
										<a href="#" style="display: none;" class="add_dataa">
											<img src="assets/img/iconos/addd.svg" align="right"  style="height: 20%" data-toggle="modal" data-target="#addPrg">
										</a>
										<div class="modal fade" id="addPrg" tabindex="-1" role="dialog" aria-labelledby="addPregunta" aria-hidden="true">
											<div class="modal-dialog">
												<div class="modal-content text-center">
													<div class="panel panel-default">
			  											<div class="panel-heading" style="background-color: #e5e8e8;">
			    											<h3 class="panel-title">
			    												<b>AGREGAR PREGUNTA</b>
			    												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			    											</h3>
			  											</div>
			  											<div class="panel-body">
			    											<form method="post" action="new_preg">
			    												{{csrf_field()}}
			    												<label>Pregunta de seguridad</label>
			    												<input autofocus type="text" name="new_preg" class="form-control" placeholder="Escribe la nueva pregunta de seguridad" required>
			    												<div class="col-lg-12" style="border-top: 1px solid #ccc; font-size: 14px; margin-top: 3%;">
			    													<div class="col-lg-6 text-left" style="margin-top: 2%;">
			    														<button 
			    															type="button" 
			    															class=" btn btn-default"
			    															data-dismiss="modal" 
			    															aria-hidden="true">
			    															Cancelar
			    														</button>
			    													</div>
			    													<div class="col-lg-6 text-right" style="margin-top: 2%;">
			    														<button type="submit" class="btn" style="background-color: #48c9b0; color:white;"><b>Guardar</b></button>
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
							<tbody>
								@if(count($preg)>0)
									<?php foreach ($preg as $prg): ?>
										<tr>
											<td>
												{{$prg->prg_pregunta}}
											</td>
											<td>
												<a href="#"  title="Editar pregunta" class="editPrg activa_datos" style="display: none;" onclick="editPrg({{$prg->prg_id}})">
													<img src="assets/img/iconos/editar.svg" class="imgmenuho" align="right" href="#" data-toggle="modal" data-target="#editPrg">
												</a>
											</td>
											<div class="modal fade" id="editPrg" tabindex="-1" role="dialog" aria-labelledby="editarPregunta" aria-hidden="true">
												<div class="modal-dialog">
													<div class="modal-content text-center">
														<div class="panel panel-default">
				  											<div class="panel-heading" style="background-color: #e5e8e8;">
				    											<h3 class="panel-title">
				    												<b>EDITAR PREGUNTA</b>
				    												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				    											</h3>
				  											</div>
				  											<div class="panel-body">
				  												<form method="post" action="update_preg">

				    												{{csrf_field()}}

				    												<div class="col-lg-12" id="upd_preg">
				    													<label>Pregunta de Seguridad</label>
				    													<input type="hidden" name="p_id" class="form-control" id="prg_id" required placeholder="Indique la pregunta de seguridad">
	    																<input type="text" name="p_editado" class="form-control"  required id="prg_prg">
				    												</div>

				    												<div class="col-lg-12" style="border-top: 1px solid #ccc; font-size: 14px; margin-top: 3%;">
				    													<div class="col-lg-6 text-left" style="margin-top: 2%;">
				    														<button 
				    															type="button" 
				    															class=" btn btn-default"
				    															data-dismiss="modal" 
				    															aria-hidden="true">
				    															Cancelar
				    														</button>
				    													</div>
				    													<div class="col-lg-6 text-right" style="margin-top: 2%;">
				    														<button type="submit" class="btn" style="background-color: #48c9b0; color:white;"><b>Guardar</b></button>
				    													</div>
				    												</div>

				    											</form>
				  											</div>
														</div>
													</div>
												</div>
											</div>
											
										</tr>
									<?php endforeach ?>
								@else
				           			<div>
										<strong>No</strong> hay información para mostrar.
									</div>
				               	@endif
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>



    	<div class="col-lg-6">
			<!--GESTION DE TIPOS DE USUARIOS-->
	    	<div class="panel panel-default">
	  			<div class="panel-heading" style="background-color: #e5e8e8;">
	  				<b>TIPOS DE USUARIOS</b>
	  				
	  					<img src="assets/img/iconos/add.svg" align="right"  style="height: 3%" onclick="$('#panell12').toggle(500);">
	  				
	  			</div>
				<div class="panel-body" style="display: none;" id="panell12">
					<div class="responsive">
						<table class="table">
							<thead>
								<tr>
									<th>
										Tipo de usuarios
									</th>
									<th>
										Estatus
									</th>
									<th>
										<a href="#"  style="display: none;" class="add_dataa">
											<img src="assets/img/iconos/addd.svg" align="right"  style="height: 20%" data-toggle="modal" data-target="#addrep">
										</a>
										<div class="modal fade" id="addrep" tabindex="-1" role="dialog" aria-labelledby="addrep" aria-hidden="true">
											<div class="modal-dialog">
												<div class="modal-content text-center">
													<div class="panel panel-default">
			  											<div class="panel-heading" style="background-color: #e5e8e8;">
			    											<h3 class="panel-title">
			    												<b>AGREGAR TIPO DE USUARIOS</b>
			    												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			    											</h3>
			  											</div>
			  											<div class="panel-body">
			    											<form action="new_tdu" method="post">
			  													{{csrf_field()}}
			  													<div class="row">
				    												<div class="col-lg-12">
				    													<label>Tipo de usuario</label>

				    													<input type="text" name="tdu_tipo" class="form-control" id="tdu_tipo" required placeholder="Indique el tipo de usuario">
				    												</div>
				    												<div class="col-lg-12" style="border-top: 1px solid #ccc; font-size: 14px; margin-top: 3%;">
				    													<div class="col-lg-6 text-left" style="margin-top: 2%;">
				    														<button class="btn" type="button" data-dismiss="modal" aria-hidden="true"> <b>Cancelar</b></button>
				    													</div>
				    													<div class="col-lg-6 text-right" style="margin-top: 2%;">
				    														<button class="btn" type="submit"  style="background-color: #48c9b0; color:white;"><b>Guardar</b></button>
				    													</div>
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
							<tbody>
								@if(count($tipo_us)>0)
									<?php foreach ($tipo_us as $tus): if ($tus->tdu_status == 1) {
										$status = 'Activo';
									}
									else{
										$status ='Inactivo';
									} 
									?>
										<tr>
											<td>
												{{$tus->tdu_tipo}}
											</td>
											<td>
												{{$status}}
											</td>
											<td>
											<a href="#" onclick="edit_tdu({{$tus->tdu_id}})" class="activa_datos" style="display: none;">
													<img src="assets/img/iconos/editar.svg" class="imgmenuho" align="right" href="#" data-toggle="modal" data-target="#tus">
												</a>
											</td>
											<div class="modal fade" id="tus" tabindex="-1" role="dialog" aria-labelledby="editTus" aria-hidden="true">
												<div class="modal-dialog">
													<div class="modal-content text-center">
														<div class="panel panel-default">
				  											<div class="panel-heading" style="background-color: #e5e8e8;">
				    											<h3 class="panel-title">
				    												<b>EDITAR TIPO DE USUARIOS</b>
				    												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				    											</h3>
				  											</div>
				  											<div class="panel-body">
				    											<form action="update_tdu" method="post">
				  													{{csrf_field()}}
				  													<div class="row">
					    												<div class="col-lg-12">
					    													<label>Tipo de usuario</label>
					    													<input type="hidden" name="tdu_id" class="form-control" id="tdu_id" required>

					    													<input type="text" name="tdu_tipo" class="form-control" id="tdu_tipo" required>
					    												</div>
					    												<div class="col-lg-12" style="border-top: 1px solid #ccc; font-size: 14px; margin-top: 3%;">
					    													<div class="col-lg-6 text-left" style="margin-top: 2%;">
					    														<button class="btn" type="button" data-dismiss="modal" aria-hidden="true"> <b>Cancelar</b></button>
					    													</div>
					    													<div class="col-lg-6 text-right" style="margin-top: 2%;">
					    														<button class="btn" type="submit"  style="background-color: #48c9b0; color:white;"><b>Guardar</b></button>
					    													</div>
					    												</div>
					    											</div>
				  												</form>
				  											</div>
														</div>
													</div>
												</div>
											</div>
											
										</tr>
									<?php endforeach ?>
								@else
				           			<div>
										<strong>No</strong> hay información para mostrar.
									</div>
				               	@endif
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>


		<div class="col-lg-6">
			<!--GESTION DE TIPOS DE REPOSOS-->
	    	<div class="panel panel-default">
	  			<div class="panel-heading" style="background-color: #e5e8e8;">
	  				<b>TIPOS DE REPOSOS</b>
	  				
	  					<img src="assets/img/iconos/add.svg" align="right"  style="height: 3%" onclick="$('#panell11').toggle(500);">
	  				
	  			</div>
				<div class="panel-body" style="display: none;" id="panell11">
					<div class="responsive">
						<table class="table">
							<thead>
								<tr>
									<th>
										Tipos de reposos
									</th>
									<th>
										<a href="#" style="display: none;" class="add_dataa">
											<img src="assets/img/iconos/addd.svg" align="right"  style="height: 20%" data-toggle="modal" data-target="#addTrep">
										</a>
										<div class="modal fade" id="addTrep" tabindex="-1" role="dialog" aria-labelledby="addTrep" aria-hidden="true">
											<div class="modal-dialog">
												<div class="modal-content text-center">
													<div class="panel panel-default">
			  											<div class="panel-heading" style="background-color: #e5e8e8;">
			    											<h3 class="panel-title">
			    												<b>AGREGAR TIPO DE REPOSO</b>
			    												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			    											</h3>
			  											</div>
			  											<div class="panel-body">
			    											<form action="new_tire" method="post">
			  													{{csrf_field()}}
			  													<div class="row">
				    												<div class="col-lg-12">
				    													<label>Tipo de reposo</label>

				    													<input type="text" name="trep" class="form-control" required placeholder="Indique el tipo de reposo">
				    												</div>
				    												<div class="col-lg-12" style="border-top: 1px solid #ccc; font-size: 14px; margin-top: 3%;">
				    													<div class="col-lg-6 text-left" style="margin-top: 2%;">
				    														<button class="btn" type="button" data-dismiss="modal" aria-hidden="true"> <b>Cancelar</b></button>
				    													</div>
				    													<div class="col-lg-6 text-right" style="margin-top: 2%;">
				    														<button class="btn" type="submit"  style="background-color: #48c9b0; color:white;"><b>Guardar</b></button>
				    													</div>
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
							<tbody>
								@if(count($tipo_re)>0)
									<?php foreach ($tipo_re as $trep): ?>
										<tr>
											<td>
												{{$trep->tire_tipo}}
											</td>
											<td>
												<a href="#" onclick="edit_rep({{$trep->tire_id}})" class="activa_datos" style="display: none;">
													<img src="assets/img/iconos/editar.svg" class="imgmenuho" align="right" href="#" data-toggle="modal" data-target="#trep">
												</a>
											</td>
											<div class="modal fade" id="trep" tabindex="-1" role="dialog" aria-labelledby="editRe1" aria-hidden="true">
												<div class="modal-dialog">
													<div class="modal-content text-center">
														<div class="panel panel-default">
				  											<div class="panel-heading" style="background-color: #e5e8e8;">
				    											<h3 class="panel-title">
				    												<b>EDITAR TIPO DE REPOSO</b>
				    												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				    											</h3>
				  											</div>
				  											<div class="panel-body">
				    											<form action="update_tire" method="post">
				  													{{csrf_field()}}
				  													<div class="row">
					    												<div class="col-lg-12">
					    													<label>Tipo de reposo</label>
					    													<input type="hidden" name="tire_id" class="form-control" id="tire_id" required>

					    													<input type="text" name="tire_tipo" class="form-control" id="tire_tipo" required>
					    												</div>
					    												<div class="col-lg-12" style="border-top: 1px solid #ccc; font-size: 14px; margin-top: 3%;">
					    													<div class="col-lg-6 text-left" style="margin-top: 2%;">
					    														<button class="btn" type="button" data-dismiss="modal" aria-hidden="true"> <b>Cancelar</b></button>
					    													</div>
					    													<div class="col-lg-6 text-right" style="margin-top: 2%;">
					    														<button class="btn" type="submit"  style="background-color: #48c9b0; color:white;"><b>Guardar</b></button>
					    													</div>
					    												</div>
					    											</div>
				  												</form>
				  											</div>
														</div>
													</div>
												</div>
											</div>
										</tr>
									<?php endforeach ?>
								@else
				           			<div>
										<strong>No</strong> hay información para mostrar.
									</div>
				               	@endif
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>



		<div class="col-lg-6">
			<!--GESTION DE TIPOS DE DÍAS FERIADOS-->
	    	<div class="panel panel-default">
	  			<div class="panel-heading" style="background-color: #e5e8e8;">
	  				<b>TIPOS DE DÍAS FERIADOS</b>
	  				
	  					<img src="assets/img/iconos/add.svg" align="right"  style="height: 3%" onclick="$('#panell10').toggle(500);">
	  				
	  			</div>
				<div class="panel-body" style="display: none;" id="panell10">
					<div class="responsive">
						<table class="table">
							<thead>
								<tr>
									<th>
										Tipo de día feriado
									</th>
									<th>
										<a href="#" style="display: none;" class="add_dataa">
											<img src="assets/img/iconos/addd.svg" align="right"  style="height: 20%" data-toggle="modal" data-target="#addTdf">
										</a>
										<div class="modal fade" id="addTdf" tabindex="-1" role="dialog" aria-labelledby="addTdiaf" aria-hidden="true">
											<div class="modal-dialog">
												<div class="modal-content text-center">
													<div class="panel panel-default">
			  											<div class="panel-heading" style="background-color: #e5e8e8;">
			    											<h3 class="panel-title">
			    												<b>AGREGAR TIPO DE DÍA FERIADO</b>
			    												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			    											</h3>
			  											</div>
			  											<div class="panel-body">
			    											<form action="new_tdf" method="post">
			  													{{csrf_field()}}
			  													<div class="row">
				    												<div class="col-lg-12">
				    													<label>Tipo de día feriado</label>
				    													<input type="text" name="tdiasf" class="form-control" id="tdiasf" required placeholder="Indique el tipo de día feriado.">
				    												</div>
				    												<div class="col-lg-12" style="border-top: 1px solid #ccc; font-size: 14px; margin-top: 3%;">
				    													<div class="col-lg-6 text-left" style="margin-top: 2%;">
				    														<button class="btn" type="button" data-dismiss="modal" aria-hidden="true"> <b>Cancelar</b></button>
				    													</div>
				    													<div class="col-lg-6 text-right" style="margin-top: 2%;">
				    														<button class="btn" type="submit"  style="background-color: #48c9b0; color:white;"><b>Guardar</b></button>
				    													</div>
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
							<tbody>
								@if(count($tipo_df)>0)
									<?php foreach ($tipo_df as $tdf):  ?>
										<tr>
											<td>
												{{$tdf->tife_tipo}}
											</td>
											<td>
												<a href="#" onclick="editTdf({{$tdf->tife_id}})" class="activa_datos" style="display: none;">
													<img src="assets/img/iconos/editar.svg" class="imgmenuho" align="right" href="#" data-toggle="modal" data-target="#tdfe">
												</a>
											</td>
											<div class="modal fade" id="tdfe" tabindex="-1" role="dialog" aria-labelledby="editDiaf" aria-hidden="true">
												<div class="modal-dialog">
													<div class="modal-content text-center">
														<div class="panel panel-default">
				  											<div class="panel-heading" style="background-color: #e5e8e8;">
				    											<h3 class="panel-title">
				    												<b>EDITAR TIPO DE DÍA FERIADO</b>
				    												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				    											</h3>
				  											</div>
				  											<div class="panel-body">
				    											<form action="update_tife" method="post">
				  													{{csrf_field()}}
				  													<div class="row">
					    												<div class="col-lg-12">
					    													<label>Tipo de día feriado</label>
					    													<input type="hidden" name="tdiasf_id" class="form-control" id="tdiasf_id" required>

					    													<input type="text" name="tdiasf" class="form-control" id="tdiasf" required>
					    												</div>
					    												<div class="col-lg-12" style="border-top: 1px solid #ccc; font-size: 14px; margin-top: 3%;">
					    													<div class="col-lg-6 text-left" style="margin-top: 2%;">
					    														<button class="btn" type="button" data-dismiss="modal" aria-hidden="true"> <b>Cancelar</b></button>
					    													</div>
					    													<div class="col-lg-6 text-right" style="margin-top: 2%;">
					    														<button class="btn" type="submit"  style="background-color: #48c9b0; color:white;"><b>Guardar</b></button>
					    													</div>
					    												</div>
					    											</div>
				  												</form>
				  											</div>
														</div>
													</div>
												</div>
											</div>
										</tr>
									<?php endforeach ?>
								@else
				           			<div>
										<strong>No</strong> hay información para mostrar.
									</div>
				               	@endif
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>




		<div class="col-lg-6">
			<!--GESTION DE PREGUNTAS SECRETAS-->
	    	<div class="panel panel-default">
	  			<div class="panel-heading" style="background-color: #e5e8e8;">
	  				<b>TIPOS DE AUTORIZACIÓN</b>
	  				<img src="assets/img/iconos/add.svg" align="right"  style="height: 3%" onclick="$('#panell9').toggle(500);">
	  			</div>
				<div class="panel-body" style="display: none;" id="panell9">
					<div class="responsive">
						<table class="table">
							<thead>
								<tr>
									<th>
										Tipo de autorización
									</th>
									<th>
										<a href="#" style="display: none;" class="add_dataa">
											<img src="assets/img/iconos/addd.svg" align="right"  style="height: 20%" data-toggle="modal" data-target="#addTau">
										</a>
										<div class="modal fade" id="addTau" tabindex="-1" role="dialog" aria-labelledby="addTautorizacion" aria-hidden="true">
											<div class="modal-dialog">
												<div class="modal-content text-center">
													<div class="panel panel-default">
			  											<div class="panel-heading" style="background-color: #e5e8e8;">
			    											<h3 class="panel-title">
			    												<b>AGREGAR TIPO DE AUTORIZACIÓN</b>
			    												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			    											</h3>
			  											</div>
			  											<div class="panel-body">
			  												<form action="new_tiau" method="post">
			  													{{csrf_field()}}
			  													<div class="row">
				    												<div class="col-lg-12">
				    													<label>Tipo de autorización</label>
				    													<input type="text" name="tau" class="form-control" id="tau" required placeholder="Indique el tipo de autorización">
				    												</div>
				    												<div class="col-lg-12" style="border-top: 1px solid #ccc; font-size: 14px; margin-top: 3%;">
				    													<div class="col-lg-6"" style="margin-top: 2%;">
				    														<button class="btn" type="button" data-dismiss="modal" aria-hidden="true"> <b>Cancelar</b></button>
				    													</div>
				    													<div class="col-lg-6" style="margin-top: 2%;">
				    														<button class="btn" type="submit"  style="background-color: #48c9b0; color:white;"><b>Guardar</b></button>
				    													</div>
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
							<tbody>
								@if(count($tipo_df)>0)
									<?php foreach ($tipo_aut as $tau): ?>
										<tr>
											<td>
												{{$tau->tiau_tipo}}
											</td>
											<td>
												<a href="#" onclick="edit_tiau({{$tau->tiau_id}})" class="activa_datos" style="display: none;">
													<img src="assets/img/iconos/editar.svg" class="imgmenuho" align="right" href="#" data-toggle="modal" data-target="#taut">
												</a>
											</td>
											<div class="modal fade" id="taut" tabindex="-1" role="dialog" aria-labelledby="editarPregunta" aria-hidden="true">
												<div class="modal-dialog">
													<div class="modal-content text-center">
														<div class="panel panel-default">
				  											<div class="panel-heading" style="background-color: #e5e8e8;">
				    											<h3 class="panel-title">
				    												<b>EDITAR TIPO DE AUTORIZACIÓN</b>
				    												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				    											</h3>
				  											</div>
				  											<div class="panel-body">
				    											<form action="update_tiau" method="post">
				  													{{csrf_field()}}
				  													<div class="row">
				  														<div class="col-lg-12">
					    													<input type="hidden" name="tau_id" class="form-control" id="tauEdit_id" required >
					    												</div>
					    												<div class="col-lg-12">
					    													<label>Tipo de autorización</label>
					    													<input type="text" name="tau" class="form-control" id="tauEdit" required placeholder="Indique el tipo de autorización">
					    												</div>
					    												<div class="col-lg-12" style="border-top: 1px solid #ccc; font-size: 14px; margin-top: 3%;">
					    													<div class="col-lg-6 text-left" style="margin-top: 2%;">
					    														<button class="btn" type="button" data-dismiss="modal" aria-hidden="true"> <b>Cancelar</b></button>
					    													</div>
					    													<div class="col-lg-6 text-right" style="margin-top: 2%;">
					    														<button class="btn" type="submit"  style="background-color: #48c9b0; color:white;"><b>Guardar</b></button>
					    													</div>
					    												</div>
					    											</div>
				  												</form>
				  											</div>
														</div>
													</div>
												</div>
											</div>
											
										</tr>
									<?php endforeach ?>
								@else
				           			<div>
										<strong>No</strong> hay información para mostrar.
									</div>
				               	@endif
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<!--<div class="col-lg-6">
		 AGREGAR UNA NUEVA PANTALLA
            <div class="panel panel-default">
                <div class="panel-heading">
                    <b>PANTALLA</b>
                    
                        <img src="assets/img/iconos/add.svg" align="right"  style="height: 3%" onclick="$('#panell5').toggle(500);">
                    
                </div>
                <div class="panel-body" style="display: none;" id="panell5">
                    <form action="new_pantalla" method="post">
                        {{csrf_field()}}
                        <div class="form-group col-lg-6">
                            <label>Descripción de la pantalla</label>
                            <input type="text" name="pnt_descripcion" class="form-control" value="Pantalla de " required>
                        </div>
                        <div class="form-group col-lg-6">
                            <label>Nombre de  la pantalla</label>
                            <input type="text" name="pnt_nombre" class="form-control" value="p_" required>
                        </div>
                        <div class="form-group col-lg-6">
                            <select class="form-control" name="pnt_mod_id" required>
                                <option selected disabled > Selecciona el modulo al cual pertenece</option>
                                @foreach($modulos as $mod)
                                    <option value="{{$mod->mod_id}}">{{$mod->mod_alias}}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group col-lg-12"  style="border-top: 1px solid #ccc; font-size: 14px;">
                            <div class="col-lg-6 text-left" style="margin-top: 2%;">
                                <button type="button" class="btn btn-default" onclick="$('#panel5').hide(500)">Cancelar</button>
                            </div>
                            <div class="col-lg-6 text-right" style="margin-top: 2%;">
                                <button type="submit" class="btn" style="background-color: #48c9b0; color:white;" ><b>Guardar</b></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>-->

<!--<div class="col-lg-6">
            AGREGAR UN NUEVO MÓDULO
            <div class="panel panel-default">
                <div class="panel-heading">
                    <b>MÓDULO</b>
                    
                        <img src="assets/img/iconos/add.svg" align="right"  style="height: 3%" onclick="$('#panell4').toggle(500);">
                    
                </div>
                <div class="panel-body" style="display: none;" id="panell4">
                    <form action="new_mod" method="post">
                    	{{csrf_field()}}
                        <div class="form-group col-lg-6">
                            <label>Alias del módulo</label>
                            <input type="text" name="mod_alias" class="form-control" value="Módulo de ">
                        </div>
                        <div class="form-group col-lg-6">
                            <label>Nombre del módulo</label>
                            <input type="text" name="mod_nom" class="form-control" value="m_">
                        </div>
                        <div class="form-group col-lg-6">
                            <label>Descripción del módulo</label>
                            <input type="text" name="mod_desc" class="form-control" value="Acceso al modulo de ">
                        </div>
                        
                        <div class="form-group col-lg-12"  style="border-top: 1px solid #ccc; font-size: 14px;">
                            <div class="col-lg-6 text-left" style="margin-top: 2%;">
                                <button class="btn btn-default">Cancelar</button>
                            </div>
                            <div class="col-lg-6 text-right" style="margin-top: 2%;">
                                <button class="btn" style="background-color: #48c9b0; color:white;" ><b>Guardar</b></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

  	</div>-->
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
	<script src="assets/js/Config/actTablas.js"></script>
@endsection
	