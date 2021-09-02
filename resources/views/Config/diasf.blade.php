@extends('menu')
@section('contenido')
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
<div class="row">
@if(Session::has('flash_message'))
	<div class="col-lg-4" style="margin-left: 750px;" id="Alerta">
		<div class="alert alert-success">
		{{Session::get('flash_message')}}
		</div>	
	</div>
@endif
@if(Session::has('session'))
	<div class="col-lg-4" style="margin-left: 750px;" id="Alerta">
		<div class="alert alert-danger">
		{{Session::get('session')}}
		</div>	
	</div>
@endif
</div>

@if($aco_df == 'p_diasf')
<input type="hidden" name="ventana" id="ventana" value="diasferiados">

<div class="panel panel-default">
  	<!-- Default panel contents -->
  	<div class="panel-heading" style="background-color: #e5e8e8;">
  		<h3 class="panel-title"> 
  			DÍAS FERIADOS 
  			<!--Boton de la modal para agregar-->
  			<a href="#" data-toggle="modal" data-target="#agregarho" title="Registrar un nuevo día feriado" style="display: none;" id="bpdf_agregar"> 
				<img src="assets/img/iconos/diasf/adddf.svg" style="height: 35px; margin-top: -9px" align="right">
			</a>
		</h3>
  	</div>

  	<!-- Table -->
  	<table class="table table-hover">
		<thead style='background: #f2f3f4  repeat-x center top; font-size:14px;'>
			<tr>
				<th>
					Tipo de día feriado
				</th>
				<th>
					Fecha
				</th>
				<th>
					Descripción
				</th>
				<th class="text-right">
					<!--Modal para agregar dias feriados-->
					<div class="modal fade" id="agregarho" tabindex="-1" role="dialog" aria-labelledby="AgregarHorario" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content text-center">
								<div class="panel panel-default">
									<div class="panel-heading" style="background-color: #e5e8e8;">
									    <h3 class="panel-title">
									    	<b>REGISTRAR DÍA FERIADO</b>
									    </h3>
									</div>
									<div class="panel-body">
									    <form method="POST" action="feriado">
									    	<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
									    	<div class="col-lg-6 text-left">
												<label>Tipo de día feriado</label>
												<select class="form-control" name="tipoferiado">
													<option selected disabled> Seleccione un tipo de día feriado</option>
													@foreach($tipofe as $tf)
														<option value="{{$tf->tife_id}}">{{$tf->tife_tipo}}</option>
													@endforeach
												</select>
											</div>
											<div class="col-lg-6 text-left">
												<label>Indique la fecha del día feriado</label>
												<input type="date" name="fecha" class="form-control">
											</div>
											<div class="col-lg-12 text-left" style="margin-top:3%;">
												<label>Descripción del día feriado</label>
												<input type="text" name="desc" class="form-control" placeholder="Describa el motivo del día feriado">
											</div>
											<div class="col-lg-12" style="border-top: 1px solid #ccc; font-size: 14px; margin-top: 3%;">
												<div class="col-lg-6 text-left"  style="margin-top: 2%;">
													<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
												</div>
												<div class="col-lg-6 text-right" style="margin-top: 2%;">
													<input type="submit" name="registrar" value="Registrar" class="btn" style="background-color: #48c9b0; color:white;"/>
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
		<tbody id="diasfbody">
			@foreach($diasf as $df)
			<?php $fecha = new DateTime($df->diaf_feriado);?>
				<tr>
					<td id="feriado" feriado="{{$df->tife_tipo}}">
						{{$df->tife_tipo}}
					</td>
					<td>
						{{$fecha->format('d-m-Y')}}
					</td>
					<td>
						{{$df->diaf_desc}}
					</td>
					<td class="text-right">
						<a class="editdf"  onclick="editFeriado({{$df->diaf_id}})" href="#" data-toggle="modal" data-target="#editFeriado" title="Editar día feriado" style="display: none;"> 
							<img src="assets/img/iconos//diasf/editdf.svg"" class="imgmenuho" >
						</a>
						<div class="modal fade" id="editFeriado" tabindex="-1" role="dialog" aria-labelledby="EditarPermiso" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content text-center">
									<div class="panel panel-default">
										<div class="panel-heading" style="background-color: #e5e8e8;">
										    <h3 class="panel-title">
										    	<b>EDITAR DÍA FERIADO</b>
										    </h3>
										</div>
										<div class="panel-body">
										    <form method="POST" action="updateFeriado">
										    	<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
												<div class="row">
													<div id="FeriadoEdit">
														
													</div>
													<div class="col-lg-12" style="border-top: 1px solid #ccc; font-size: 14px; margin-top: 3%;">
														<div class="col-lg-6 text-left"  style="margin-top: 2%;">
															<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
														</div>
														<div class="col-lg-6 text-right" style="margin-top: 2%;">
															<input type="submit" name="registrar" value="Modificar" class="btn" style="background-color: #48c9b0; color:white;"/>
														</div>
													</div>										 	
							                    </div>
										    </form>
										</div>
									</div>
								</div>
							</div>
						</div>
						<a href="#" onclick="deleteFeriado({{$df->diaf_id}})" title="Eliminar día feriado" class="eliminardf" style="display: none;"> 
							<img src="assets/img/iconos/diasf/deletedf.svg"" class="imgmenuho">
						</a>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
</div>
<center class="paginacion_f">
	<?php echo $diasf->render(); ?>
</center>
@else
<center>
	<img src="imagenes/denegado.png" style="height: 500px">
	<br>
	<b style="font-size: 50px;text-shadow: 1px 2px 5px red">ACCESO DENEGADO</b><br>
	<b style="margin-bottom: 15px;">Usted no tiene permiso para ingresar a esta pantalla</b>
</center>
@endif
@endsection

	<script src="assets/js/jquery.js"></script>
	<script src="assets/js/Config/diasferiados.js"></script>