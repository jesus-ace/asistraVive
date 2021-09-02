@extends('menu')
@section('contenido')

@if($aco_cnt == 'p_control')


<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
<input type="hidden" name="ventana" id="ventana" value="control">
<input type="hidden" name="depto" id="depto" value="{{$depp}}">


<div class="panel panel-default" style="margin-left: 20px; margin-right: 20px;">
	<div class="panel-heading" style="background-color: #e5e8e8;">
	    <h4><b>Control de Asistencia</b4>
	    <a href="control" title="Refrescar página">
			<img src="assets/img/iconos/refrescar.svg"  style="height: 25px;" align="right">
		</a>
		</h4>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-lg-6">
				<label>Cédula</label>
				<span class="input-group">
					<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i>
				</span>
				<input class="form-control" 
				type="number" 
				id="cedulac" 
				name="cedula"  
				min="2000000" 
				placeholder="Ingrese la cédula del empleado" 
				pattern="d/{7}" 
				title="Por favor ingrese una cedula entre 1 millón y 27 millones"/>
			</div>
			<div class="col-lg-6">
				<form action="controldp" method="POST">
					{{csrf_field()}}
					<div>
						<label>Departamentos</label>
						<span class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i>
						</span>
						<select name="departamento" id="dpControl" class="form-control">
							<option selected disabled>Elija un departamento</option>
							<?php foreach ($departamentos as $dp): ?>
								<option value="{{$dp->dp_id}}">{{$dp->dp_nombre}}</option>
							<?php endforeach ?>
						</select>
					</div>
					<div class="col-lg-12 text-right">
						<button id="botonc" type="submit" name="buscar" value="Buscar"  class="btn" style="background-color: #48c9b0; color:white;margin-top: 3%;" title="Este botón solo funciona para la busqueda por departamentos">
							<b>BUSCAR</b>
						</button>												
					</div>	
				</form>	
			</div>
		</div>
	</div>
</div>
	
<div class="col-lg-12">
	
	<br>
	<div class="panel panel-default">
	  <!-- Default panel contents -->
	  <div class="panel-heading" style="background-color: #e5e8e8;">
	  	<b>Empleados
	  	</b>
	  	<a href="#" data-toggle="modal" data-target="#modalControl" title="Busqueda General de asistencia">
		    <img src="assets/img/iconos/ver.svg" align="right" style="margin-top: -10px; height: 35px;">
		</a>
		<div class="modal fade" id="modalControl" role="dialog">
		    <div class="modal-dialog">
		        <div class="modal-content text-center">
		            <div class="panel panel-default">
	  					<div class="panel-heading"  style="background-color: #e5e8e8;">
	    					<h3 class="panel-title">
	    						<b>
	    							BUSCAR ASISTENCIA GENERAL
	    						</b>
	    						<button type="button" class="close" data-dismiss="modal">
	    							<span aria-hidden="true">×</span>
						        </button>
	    					</h3>
	  					</div>
	  					<div class="panel-body">
	  						<form method="post" action="resumeng" name="control" >
	  							{{ csrf_field() }}
		    					<ul class="list-group text-left">	

								  	<li class="list-group-item text-center">
								  		<label>
								  			Sábados y Domingos del mes
								  			<input type="checkbox" name="sabdom" id="sabdom" style="margin-right: 10px;" class="sabdom">
								  		</label>
								  	</li>
								  	<li class="list-group-item text-center" id="asistenciaGC">
								  		<label class="v">
								  			ASISTENCIA
								  			<input type="checkbox" name="asistencia" id="asistenciag" checked="" style="margin-right: 10px;" class="asistenciag">
								  		</label>
								  		<label>
								  			INASISTENCIA
											<input type="checkbox" name="inasistencia" id="inasistenciag" class="inasistenciag">
								  		</label>
								  	</li>
								  	<li class="list-group-item text-left" id="fxdia">
								  		<label>
								  			<input type="checkbox" name="dia" class="diaCh" checked>
								  			DÍA
								  		</label>
								  		<label style="margin-left:40px">
											<input class="form-control form-inline" type="date" name="dia" id="diaC" placeholder="yyyy-mm-dd">
										</label>
								  	</li>
								  	<li class="list-group-item text-left" id="rangoGeneral">
								  		<label>
								  			<input type="checkbox" name="rango" class="rangoCh">
								  			RANGO
								  		</label>
								  		<label style="margin-left: 15px">
											Desde:
											<input class="form-control form-inline" type="date" name="desde"  disabled="disabled" id="desdeControl" placeholder="yyyy-mm-dd">
										</label>
										<label style="margin-left: 15px">
											Hasta:
											<input class="form-control form-inline" type="date" name="hasta" disabled="disabled" id="hastaControl" placeholder="yyyy-mm-dd">
										</label>
								  	</li>
								  	<li class="list-group-item text-left">
								  		<label>Departamentos</label>
										<span class="input-group">
											<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i>
										</span>
										<select name="departamento" id="dpControlg" class="form-control">
											<option selected disabled>Elija un departamento</option>
											<?php foreach ($departamentos as $dp): ?>
												<option value="{{$dp->dp_id}}">{{$dp->dp_nombre}}</option>
											<?php endforeach ?>
										</select>
								  	</li>
								</ul>
								<div class="col-lg-6 text-left">
							        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
							    </div>
								<div class="col-lg-6 text-right">
									<button name="buscar" class="btn waves-effect" value="Buscar" style="background-color: #48c9b0; color:white;" onclick="chequea_contenido_cntrol();">
										<b>BUSCAR</b>
									</button>
								</div>	
							</form>
	  					</div>
					</div>
				</div>
		    </div>
		</div>        
	  </div>
	  	<div class="responsive">
		  	<table class="table table-hover">
				<thead style='background: #f2f3f4  repeat-x center top; font-size:14px;'>
					<tr>
						<th>
							FOTO
						</th>
						<th class="text-center">				
							TRABAJADOR
						</th>
						<th class="text-center">				
							DEPARTAMENTO
						</th>
						<th>
							BUSCAR
							
						</th>
					</tr>
				</thead>
				<tbody id="controlUsuario">
					@if(count($pdo)>0)
					<?php foreach ($pdo as $control):
						if ($control->us_foto == '') {
					        $foto = 'mafalda.jpg';
					    }
					    else{
					        $foto = $control->us_foto;
					    }
					?>
						<tr value="<?php echo $control->us_ced?>">		
							<td>
								<img src="imagenes2/{{$foto}}" style="height: 60px;border-radius: 6px 6px 6px 6px; border:1px solid #B2B2B2;">
							</td>				
							<td>
								<label>Nombre y Apellido</label> 
									{{$control->us_nom}}
									{{$control->us_ape}}<br>
								<label>Cédula </label>
									{{$control->us_ced}}
									
							</td>				
							<td>
								{{$control->dp_nombre}}
							</td>
							<td>
								<a href="#" data-toggle="modal" data-target="#modalAsi" onclick="AsistenciaEmp({{$control->us_ced}})">
							    	<img src="assets/img/iconos/asistenciac.svg" class="imgmenuus">
								</a>
								<div class="modal fade" id="modalAsi" role="dialog">
								    <div class="modal-dialog">
								        <div class="modal-content text-center">
								        	<div class="panel panel-default">
											  	<div class="panel-heading" style="background-color: #e5e8e8;">
											    	<h3 class="panel-title">
											    		<b>RESUMEN DE ASISTENCIA</b>
							    						<button type="button" class="close" data-dismiss="modal">
							    							<span aria-hidden="true">×</span>
												        </button>
											    	</h3>
											  	</div>
											  	<div class="panel-body">
									           		<form method="post" action="buscarfecha">
									           			{{ csrf_field() }}
												    	<ul class="list-group text-left">
									  						<li class="list-group-item text-left">
									  							<label>
									  								Indique la fecha que desea consultar
									  							</label>
									  						</li>
									  						<li class="list-group-item text-center">
														  		<label>
														  			Sábados y Domingos del mes
														  			<input type="checkbox" name="sabdomi" id="sabdomi" style="margin-right: 10px;" class="sabdomi">
														  		</label>
														  	</li>								  	
									  						<li class="list-group-item text-center" id="asistenciaIC">
														  		<label class="v">
														  			ASISTENCIA
														  			<input type="checkbox" name="asistencia" id="asistenciaesp" checked="" style="margin-right: 10px;" class="asistenciaesp">
														  		</label>
														  		<label>
														  			INASISTENCIA
																	<input type="checkbox" name="inasistencia" id="inasistenciaesp" class="inasistenciaesp">
														  		</label>
														  	</li>
									  						<li class="list-group-item text-left" id="fxdiai">
									  							<label>
									  								<input type="checkbox" name="dias" class="diasGc" checked>
									  								DÍA
									  							</label>
									  							<label style="margin-left:40px">
																	<input class="form-control form-inline" type="date" name="dia" id="diaCg" placeholder="yyyy-mm-dd">
																</label>
									  						</li>
									  						<li class="list-group-item text-left" id="rangoEspecifico">
									  							<label>
									  								<input type="checkbox" name="rango" class="rangoGc">
									  								RANGO
									  							</label>
									  							<label style="margin-left: 15px">
																	Desde:
																	<input class="form-control form-inline" type="date" name="desde" id="desdeCg" disabled="disabled" placeholder="yyyy-mm-dd">
																</label>
																<label style="margin-left: 15px">
																	Hasta:
																	<input class="form-control form-inline" type="date" name="hasta" disabled="disabled" id="hastaCg" placeholder="yyyy-mm-dd">
																</label>
																<div id="id-emp">
															
																</div>
									  						</li>

															<input type="hidden" name="depto" id="deptoo" value="{{$depp}}">
									  					</ul>
									  					<div class="col-lg-6 text-left">
													        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
													    </div>
									            		<div class="col-lg-6 text-right">
															<button id="botonc" type="submit" name="buscar" value="Buscar"  class="btn" style="background-color: #48c9b0; color:white;">
																<b>BUSCAR</b>
															</button>												
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
<center id="center"  class="paginacion_f">
	<?php echo $pdo->render(); ?>
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
	<script src="assets/js/Usuarios/dropdown.js"></script>	

	<link rel="stylesheet" type="text/css" href="assets/datePicker/css/bootstrap-datepicker3.css">
    <link rel="stylesheet" type="text/css" href="assets/datePicker/css/bootstrap-standalone.css">
    <!-- Languaje -->
    <script src="assets/datePicker/js/bootstrap-datepicker.js"></script>
    <script src="assets/datePicker/locales/bootstrap-datepicker.es.min.js"></script>

<script>
	//POR USUARIO
    $('#desdeCg').datepicker({
        format: "yyyy-mm-dd",
        language: "es",
        autoclose: true
    });

    $('#hastaCg').datepicker({
        format: "yyyy-mm-dd",
        language: "es",
        autoclose: true
    });

    $('#diaCg').datepicker({
        format: "yyyy-mm-dd",
        language: "es",
        autoclose: true
    });

    //GENERAL
    $('#desdeControl').datepicker({
        format: "yyyy-mm-dd",
        language: "es",
        autoclose: true
    });

    $('#hastaControl').datepicker({
        format: "yyyy-mm-dd",
        language: "es",
        autoclose: true
    });

    $('#diaC').datepicker({
        format: "yyyy-mm-dd",
        language: "es",
        autoclose: true
    });
</script>
@endsection

