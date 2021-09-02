@extends('menu')
@section('contenido')
<!DOCTYPE html>
<html>
<head>
	<title> Marcaje Notificaciones</title>
</head>
<body>

<input type="hidden" name="ventana" id="ventana" value="notificaciones">
@if (count($autip)>0)
<div class="card-header card-header-info">
        <!-- colors: "header-primary", "header-info", "header-success", "header-warning", "header-danger" -->
        <div class="nav-tabs-navigation col-md-8" style="margin-left: 1%;">
            <div class="nav-tabs-wrapper">
                <ul class="nav nav-tabs" data-tabs="tabs">
                    <li class="nav-item active">
                        <a class="nav-link" href="#home" data-toggle="tab">
                    		Notificaciones
                    	</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="#activas" data-toggle="tab">
                    		Autorizaciones
                    		<img src="assets/img/iconos/new.png" style="height: 18px;">
                    	</a>
                    </li>
                    <!--<li class="nav-item">
                        <a class="nav-link " href="#finalizadas" data-toggle="tab">
                    		Marcaje
                    	</a>
                    </li>-->
                </ul>
            </div>
        </div>
        <div class="col-md-3 text-right">
        		
        </div>
    </div>
    <div class="card-body " style="margin-left: 2%;margin-right: 2%">
        <div class="tab-content text-center">
            <div class="tab-pane active" id="home">
               	<div class="row">
		            <div class="col-md-12">
		              	<div class="row">
							<div class="col-lg-6" style="margin-top: 4%;">
								<div class="panel panel-success">
									<div class="panel-heading"><b>ENTRADAS</b></div>
									<div class="panel-body">
										<table class="table table-hover">
										    <tbody id="entradas">
										    	<?php foreach ($usersent as $us): 
										    		if ($us->asi_entrada_hora == '') {
										    			$hora_entra = '00:00';
										    		}
										    		else{
										    			$hora_entra = $us->asi_entrada_hora;
										    		}
										    	?>
										    		<tr>
												        <td>
												        	<div style="border-radius: 6px 6px 6px 6px;-moz-border-radius: 6px 6px 6px 6px;-webkit-border-radius: 6px 6px 6px 6px;border: 1px solid #0818ff;width:70px;height:70px">

												        		<img style="height:98%;width:98%;border-radius: 6px 6px 6px 6px;-moz-border-radius: 6px 6px 6px 6px;-webkit-border-radius: 6px 6px 6px 6px;border: 0px solid #0818ff" src="imagenes2/{{$us->us_foto}}">
												        	</div>

												        </td>
												        <td>
												        	<b>{{$us->us_nom}} {{$us->us_ape}}</b><br>
												        	{{$us->dp_nombre}}
												        </td>
														<td>
														Hora: <b>{{$hora_entra}}</b>

														</td>
												     </tr>
										    	<?php endforeach ?>
										    </tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="col-lg-6"  style="position: relative;z-index: 3;margin-top: 4%;" >
								<div class="panel panel-danger ">
									<div class="panel-heading"><b>SALIDAS</b></div>
									<div class="panel-body">
										<table class="table table-hover">
											<tbody id="salidas">
											    <?php foreach ($usersal as $us): ?>
												    <tr>
												        <td>
												        	<div style="border-radius: 6px 6px 6px 6px;-moz-border-radius: 6px 6px 6px 6px;-webkit-border-radius: 6px 6px 6px 6px;border: 1px solid #3f7840;width:70px;height:70px">

												        		<img style="height:98%;width:98%;border-radius: 6px 6px 6px 6px;-moz-border-radius: 6px 6px 6px 6px;-webkit-border-radius: 6px 6px 6px 6px;border: 0px solid #0818ff" src="imagenes2/{{$us->us_foto}}">
												        	</div>
												        </td>
												        <td>
												        	<b>{{$us->us_nom}} {{$us->us_ape}}</b><br>
												        	{{$us->dp_nombre}}
												        </td>
														<td>
														Hora: <b>{{$us->asi_salida_hora}}</b>

														</td>
												  	</tr>
											    <?php endforeach ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
						<?php foreach ($not as $not): ?>
							<div id="alertas" style="z-index: 1000;">
								<div class="col-lg-6" style="margin-left: 50%; position: absolute;z-index: 4;margin-top: 5%" >
									<div class="alert alert-danger  alert-dismissable fade in" id="alertaa">
							    		<a class="close" data-dismiss="alert" aria-label="close" onclick="delete_alerta({{$not->alert_id}})">&times;</a>
										<p><strong>¡Alerta!</strong></p>
										<table class="table">
											<tbody >
													<tr>
														<td>
															<div style="border-radius: 6px 6px 6px 6px;-moz-border-radius: 6px 6px 6px 6px;-webkit-border-radius: 6px 6px 6px 6px;border: 0px solid #0818ff;width:70px;height:70px">
																<img style="height:100%;width:98%;border-radius: 6px 6px 6px 6px;-moz-border-radius: 6px 6px 6px 6px;-webkit-border-radius: 6px 6px 6px 6px;border: 0px solid #0818ff" src="assets/img/iconos/denegado.svg">
															</div>
														</td>
														<td>
															<div>	
																<p>
																	{{$not->alert_alerta}}
																</p>
															</div>
														</td>
														<td>
															Hora:{{$not->alert_hora }}</br>
														</td>
													</tr>
											</tbody>
										</table>	
									</div>
								</div>
							</div>
						<?php endforeach ?>
		            </div>
           		</div>
        	</div>
            <div class="tab-pane" id="activas">
                <div class="row">
                	<div class="col-md-12">
                		<div class="panel panel-default" style="margin-right: 20px; margin-left: 20px; z-index: 1; margin-top: 4%;">
						  	<div class="panel-heading" style="background-color: #e5e8e8;">
						    	<h3 class="panel-title">
						    		AUTORIZACIÓN
									<a href="notificaciones" title="Refrescar pagina">
										<img src="assets/img/iconos/refrescar.svg" style="height: 25px; margin-top: -7px;" align="right">
									</a>
						    	</h3>
						  	</div>
						  	<div class="panel-body">
					    		<div class="col-lg-6">
					    			<label>
					    				<span class="input-group">
											<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i>
										</span>
										<input class="form-control" type="number" min="1000000" name="cedula" id="cedulaNot" placeholder="Por favor ingrese la cedula del trabajador">
										</span>
									</label>
								</div>
								<div class="col-lg-6">
									<label class="form-group">
										<span class="input-group">
											<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i>
										</span>
										<select class="form-control" name="departamento" id="departamentoNot">
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


				        <div class="panel panel-default" style="margin-left: 1%; margin-right: 1%;">
						  	<!-- Default panel contents -->
						  	<div class="panel-heading" style="background-color: #e5e8e8;">
						  		<h3 class="panel-title text-left">
						    		<b>EMPLEADOS</b>
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

			       </div>
                </div>
            </div>
            <div class="tab-pane" id="finalizadas">
                <div class="row">
                	<div class="col-md-12">
				        k
			       </div>
                </div>
            </div>
        </div>
    </div>


</body>
</html>

	<script src="assets/js/jquery.js"></script>

	<script src="{{asset('assets/js/Marcaje/notificaciones.js')}}"></script>
	<script src="{{asset('assets/js/Asistencia/excepciones/autorizacion.js')}}"></script>
@else
<center>
	<img src="imagenes/denegado.png" style="height: 500px">
	<br>
	<b style="font-size: 50px;text-shadow: 1px 2px 5px red">ACCESO DENEGADO</b><br>
	<b style="margin-bottom: 15px;">Esta máquina no está autorizada para ingresar a esta pantalla</b>
</center>

@endif
@endsection