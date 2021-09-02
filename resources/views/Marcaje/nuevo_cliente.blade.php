<!--Admin_cliente.blade.php-->
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

@if($aco_acc == 'p_newcliente')
<div class="row">
	<div class="col-lg-12 cliente">
		<div class="panel panel-default">
  			<div class="panel-heading" style="background-color: #e5e8e8;">
    			<h3 class="panel-title">
    				<b>
    					ADMINISTRADOR DE CLIENTES
    				</b>
    				<a href="#" data-toggle="modal" data-target="#modalCliente" title="Agregar un nuevo cliente" style="display: none;" class="nclient">
    					<img src="assets/img/iconos/addCliente.svg" style="height: 5%; margin-top: -1%;" align="right">
    				</a>
    			</h3>
    			<div class="modal fade" id="modalCliente" role="dialog">
				    <div class="modal-dialog">
				        <div class="modal-content text-center">
				            <div class="panel panel-default">
			  					<div class="panel-heading"  style="background-color: #e5e8e8;">
			    					<h3 class="panel-title">
			    						<b>
			    							Agregar un nuevo cliente
			    						</b>
			    						<button type="button" class="close" data-dismiss="modal">
			    							<span aria-hidden="true">×</span>
								        </button>
			    					</h3>
			  					</div>
			  					<div class="panel-body">
			  						<form method="post" action="guardacliente">
			  							{{csrf_field()}}
				  						<div class="row" 
											@if (isset($info))

												@if(count($info)>0)
												
												<div class="alert alert-danger">
										 			 <strong>Importante!</strong> {{$info}}
												</div>
												@endif
											@endif
												{!! csrf_field() !!}
												<div class="col-lg-6" >

									    			<label for="mcjacc_descripcion">Descripción</label>
									    			<input type="text" class="form-control" name="mcjacc_descripcion" placeholder="Marcaje Recepción">

							 					</div>
							 					<div class="col-lg-6">
									    			<label for="mcjacc_ip">Ip</label>
									    			<input type="text" class="form-control"  name="mcjacc_ip" placeholder="192.000.000.000">

							 					</div>
							 					<div class="col-lg-6"  style="margin-top: 10px;">
			   										<label for="mcjacc_pantalla">Pantalla</label>
											      	<select name="mcjacc_pantalla" class="form-control">
											        	<option selected>Seleccione la pantalla </option>
											        	<option value="Marcaje">Marcaje</option>
											       		<option value="Seguridad">Seguridad</option>
											      	</select>
							 					</div>
							 					<div class="col-lg-6"  style="margin-top: 10px;">
								    				<label for="mcjacc_status">Estatus</label>
												    <select name="mcjacc_status" class="form-control">
												        <option selected value="1">Activo </option>
												        <option value="0">Inactivo</option>
												    </select>
							 					</div>
										</div>
										<div class="row" style="margin-top:20px;">
						    				<div class="col-lg-1"></div>
											<div class="col-lg-10">
												<div class="alert alert-warning" role="alert">
						 							<b>Importante!</b>
						 							Sólo se mostrará la pantalla seleccionada, para el cliente indicado.
												</div>
											</div>
											<div class="col-lg-1"></div>
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
  			</div>
  			<div class="panel-body">
    			<div class="table-responsive ">
				  	<table class="table table-hover ">
						<thead>
						      <tr>
						      	<th>Foto</th>
						      	<th>Registrado por</th>
						        <th>Descripción</th>
						        <th>Pantalla</th>
						        <th>IP</th>
						        <th>Estatus</th>
						        <th></th>
						      </tr>
					    </thead>
					    <tbody>
						    @if(count($cliente) > 0)
							    <?php foreach ($cliente as $key ): 
								    if ($key->mcjacc_status == 1) {
								    	$estatus = 'Activo';
								    }
								    else{
								    	$estatus = 'Inactivo';
								    } 
							    ?>
							    	<tr>
							    		<td>
							    			<img src="imagenes2/{{$key->us_foto}}" style="height: 60px;border-radius: 6px 6px 6px 6px; border:1px solid #B2B2B2;" align="center">
							    		</td>
							    		<td>{{$key->us_nom}} {{$key->us_ape}}<br>
							    			<b>C.I.:</b> {{$key->us_ced}}</td>
							    		<td>{{$key->mcjacc_descripcion}}</td>
							    		<td>{{$key->mcjacc_pantalla}}</td>
							    		<td>{{$key->mcjacc_ip}}</td>
							    		<td>{{$estatus}}</td>
							    		<td>   
							    			<!--Quedamos haciendo el js para editar cliente-->
							    			<a href="#" data-toggle="modal" data-target="#modalEditCliente" title="Agregar un nuevo cliente" onclick="edit_acc({{$key->mcjacc_id}})" style="display: none;" class="eddcl">
							    				<img src="assets/img/iconos/editar.svg" style="height: 30px;" align="center">
							    			</a>
							    			<div class="modal fade" id="modalEditCliente" role="dialog">
											    <div class="modal-dialog">
											        <div class="modal-content text-center">
											            <div class="panel panel-default">
										  					<div class="panel-heading"  style="background-color: #e5e8e8;">
										    					<h3 class="panel-title">
										    						<b>
										    							Editar cliente
										    						</b>
										    						<button type="button" class="close" data-dismiss="modal">
										    							<span aria-hidden="true">×</span>
															        </button>
										    					</h3>
										  					</div>
										  					<div class="panel-body">
										  						<form action="updatecliente" method="post">
  																	{{ csrf_field() }}
  																	<input type="hidden" name="id" id="eaco_id" class="form-control">
  																	<div class="col-lg-6 form-group">
  																		<label class="text-left" for="mcjacc_descripcion">
  																			Descripción
  																		</label>
		    															<input type="text" class="form-control" name="mcjacc_descripcion" id="e_descripcion">
  																	</div>	
  																	<div class="form-group col-lg-6">
														    			<label class="text-left" for="mcjacc_ip">
														    				IP
														    			</label>
														    			<input type="text" class="form-control"  name="mcjacc_ip" id="e_ip" >

												 					</div>
												 					<div class="form-group col-lg-6">
					   
																      <label for="mcjacc_pantalla">Pantalla</label>
																      <select name="mcjacc_pantalla" class="form-control" id="e_pantalla">
																	      <option selected disabled>Seleccione la pantalla </option>
																	      <option value="Marcaje">Marcaje</option>
																	      <option value="Seguridad">Seguridad</option>

																      </select>

												 					</div>
												 					<div class="form-group col-lg-6">
													    				<label for="mcjacc_status">Estatus</label>

																	    <select name="mcjacc_status" class="form-control">
																	      <option selected disabled>Seleccione un estatus </option>
																	        <option value="1">Activo </option>
																	        <option value="0">Inactivo</option>
																	    </select>
												 					</div>
																	<div class="row" style="margin-top:20px;">
													    				<div class="col-lg-1"></div>
																		<div class="col-lg-10">
																			<div class="alert alert-warning" role="alert">
													 							<b>Importante!</b>
													 							Sólo se mostrará la pantalla seleccionada, para el cliente indicado.
																			</div>
																		</div>
																		<div class="col-lg-1"></div>
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
							    		</td>
							    	</tr>
							    <?php endforeach ?>
						    @else
                       			<div>
  									<strong>No</strong> Hay información para mostrar.
								</div>
                      
                       		@endif
                   		</tbody>
				  	</table>
				</div>
  			</div>
		</div>
	</div>
</div>
@else
<center>
	<img src="imagenes/denegado.png" style="height: 500px">
	<br>
	<b style="font-size: 50px;text-shadow: 1px 2px 5px red">ACCESO DENEGADO</b><br>
	<b style="margin-bottom: 15px;">Usted no tiene permiso para ingresar a esta pantalla </b>
</center>
@endif
	<script src="assets/js/jquery.js"></script>
	<script src="{{asset('assets/js/Marcaje/acceso_cliente.js')}}"></script>
@endsection
