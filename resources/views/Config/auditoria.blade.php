@extends('menu')
@section('contenido')
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

@if($aco_aud == 'p_audit')
<div class="panel panel-default" style="margin-right:1%;margin-left:1%;">
  	<!-- Default panel contents -->
  	<div class="panel-heading" style="background-color: #e5e8e8;">
  		<b>AUDITORIA</b>
  		<a href="auditoria_pdf" title="Generar archivo pdf" target="_blank" > 
            <img src="assets/img/iconos/pdf.svg" target="_blank" style="height: 30px; margin-top: -7" align="right"> 
        </a>
  		<!--<a href="#" data-toggle="modal" data-target="#modalControl" title="Busqueda de auditoría por fecha">
		    <img src="assets/img/iconos/ver.svg" align="right" style="margin-top: -10px; height: 35px;">
		</a>-->
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
	  						<form method="post" action="aud_search" name="control" >
	  							{{ csrf_field() }}
		    					<ul class="list-group text-left">
								  	<li class="list-group-item text-left">
								  		<label>
								  			<input type="checkbox" name="diac" class="diaCh" checked>
								  			DÍA
								  		</label>
								  		<label style="margin-left:40px">
											<input class="form-control form-inline" type="date" name="dia" id="diaAud">
										</label>
								  	</li>
								  	<li class="list-group-item text-left">
								  		<label>
								  			<input type="checkbox" name="rango" class="rangoCh">
								  			RANGO
								  		</label>
								  		<label style="margin-left: 15px">
											DESDE:
											<input class="form-control form-inline" type="date" name="desde"  disabled="disabled" id="desdeAud">
										</label>
										<label style="margin-left: 15px">
											HASTA:
											<input class="form-control form-inline" type="date" name="hasta" disabled="disabled" id="hastaAud">
										</label>
								  	</li>
								  	<li class="list-group-item text-left">
								  		<label>
								  			<button type="button" class="btn btn-default"  style="margin-right: 15px;" id="aud_us">USUARIOS</button>
								  		</label>
								  		<label>
								  			<button  type="button" class="btn btn-default" style="margin-right: 15px;" id="aud_ip">IP</button>
								  		</label>
								  		
								  		<label>
								  			<button  type="button" class="btn btn-default" style="margin-right: 15px;" id="aud_name_machine">NOMBRE DE LA MÁQUINA</button>
								  		</label>
								  		<label>
								  			<button  type="button" class="btn btn-default" style="font-size: 12px;" id="aud_naveg">NAVEGADOR</button>
								  		</label>
								  	</li>
								  	<li class="list-group-item text-left">
								  		<label>
								  			<select name="usuario" id="us_aud" class="form-control" style="display: none;">
								  				<option selected disabled>
								  					Seleccione un empleado
								  				</option>
									  			<?php foreach ($user as $us): ?>
									  				<option value="{{$us->us_ced}}">
									  					{{$us->us_nom}}
									  				</option>
									  			<?php endforeach ?>
								  			</select>
								  		</label>
								  		<label>
								  			<select name="ip" id="ip_aud" class="form-control" style="display: none;">
								  				<option selected disabled>
								  					Seleccione una ip
								  				</option>
									  			<?php foreach ($ip as $ip): ?>
									  				<option>
									  					{{$ip->aud_machine_ip}}
									  				</option>
									  			<?php endforeach ?>
								  			</select>
								  		</label>
								  		<label>
								  			<select name="name_machine" id="name_machine_aud" class="form-control" style="display: none;">
								  				<option selected disabled>
								  					Seleccione el nombre de una maquina
								  				</option>
									  			<?php foreach ($name_machine as $name): ?>
									  				<option>
									  					{{$name->aud_machine_name}}
									  				</option>
									  			<?php endforeach ?>
								  			</select>
								  		</label>
								  		<label>
								  			<select name="explorer" id="explorer_aud" class="form-control" style="display: none;">
								  				<option selected disabled>
								  					Seleccione un navegador
								  				</option>
									  			<?php foreach ($explorer_machine as $ex): ?>
									  				<option>
									  					{{$ex->aud_machine_explorer}}
									  				</option>
									  			<?php endforeach ?>
								  			</select>
								  		</label>
								  	</li>
								</ul>
								<div class="col-lg-6 text-left">
							        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
							    </div>
								<div class="col-lg-6 text-right">
									<button name="buscar" class="btn waves-effect" value="Buscar" style="background-color: #48c9b0; color:white;" onclick="chequea_contenido_aud();">
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
  	<div class="table-responsive">
  		<!-- Table -->
	  	<table class="table">
	    	<thead style='background: #f2f3f4  repeat-x center top; font-size:14px;'>
	    		<tr>
	    			<th>FECHA</th>
	    			<th>Usuario</th>
	    		
	    			<th>Accion</th>
	    		
	    			<th>Descripcion</th>
	    		
	    			<th>IP</th>
	    		
	    			<th>Nombre de la maquina</th>
	    			<th>Navegador</th>
	    			
	    		</tr>
	    	</thead>
	    	<tbody>
	    		<?php foreach ($auditoria as $au):  $fecha = new DateTime($au->aud_fecha); ?>
	    			<tr>
	    				<td>{{$fecha->format('d-m-Y')}}</td>
	    				<td>
	    					{{$au->us_nom}} {{$au->us_ape}}
	    				</td>
	    				<td>
	    					{{$au->aud_tipo}}
	    				</td>
	    				<td>
	    					{{$au->aud_desc}}
	    				</td>
	    				<td>
	    					{{$au->aud_machine_ip}}
	    				</td>
	    				<td>
	    					{{$au->aud_machine_name}}
	    				</td>
	    				<td>
	    					{{$au->aud_machine_explorer}}
	    				</td>
	    			</tr>
	    			
	    		<?php endforeach ?>
	    	</tbody>
	  	</table>
  	</div>
</div>
  
<center class="paginacion_f">
	<?php echo $auditoria->render(); ?>
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
	<script src="assets/js/Config/auditoria.js"></script>
@endsection
