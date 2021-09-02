@extends('menu')
@section('contenido')
<input type="hidden" name="ventana" id="ventana" value="home">
<div class="row">
	<?php if (! $errors->isEmpty()): ?>
		<div class="alert alert-danger" id="">
			<p><strong>Lo sentimos</strong>Por favor corrige los siguientes errores</p>
			<?php foreach ($errors->all() as $error): ?>
				<li>{{ $error}}</li>
			<?php endforeach ?>	
		</div>
	<?php endif ?>
	@if(Session::has('flash_message'))
		<div class="col-lg-3 text-center" style="margin-left: 880px;" id="Alerta">
			<div class="alert alert-success">
			{{Session::get('flash_message')}}
			</div>	
		</div>
	@endif
</div>
<div class="panel panel-default">
  <div class="panel-body"  style="background-color: #e5e8e8;">
    <b>INICIO</b>
  </div>
</div>
<div class="col-xs-12" style="margin-top: 30px;">
	<div class="col-xs-3">
		<div class="well" style="background-color: #00E503; border-color: #00AA24;">
			<div style="margin-top: -10px;">
				<img class="vds" src="assets/img/iconos/bar-code.svg" style="height: 10%; width: 50%;" >
				<b style="color: #ffff;font-size: 12px;">
					Asistencias
					{{count($asistencia)}}
				</b>
				<p align="right" style="margin-bottom: -12px; margin-top: -20px;"> <a href="asi_actual" style="color: #ffff">Ver más</a></p>
			</div>
		</div>
	</div>
	<div class="col-xs-3">
		<div class="well" style="background-color: #DF000E; border-color: #A20000;">
			<div style="margin-top: -10px;">
				<img class="vds" src="assets/img/iconos/simbolo_alert.svg" style="height: 10%; width: 50%;" >
				<b style="color: #ffff;font-size: 12px;">
					Inasistencia
					@if($inasis<=0)
						0
					@else
						{{$inasis}}
					@endif
				</b>
				<p align="right" style="margin-bottom: -12px; margin-top: -20px;"> <a href="inasis_act" style="color: #ffff">Ver más</a></p>
			</div>
		</div>
	</div>
	<div class="col-xs-3">
		<div class="well" style="background-color: #8300FF; border-color: #E0E0E0;">
			<div style="margin-top: -10px;">
				<img class="vds" src="assets/img/iconos/perm.svg" style="height: 10%; width: 50%;" >
				<b style="color: #ffff; font-size: 12px;">
					Permisos
					{{count($permisosnr) + count($permisosr)}}
				</b>
				<p align="right" style="margin-bottom: -12px; margin-top: -20px;"> <a href="permi_act" style="color: #ffff">Ver más</a></p>
			</div>
		</div>
	</div>
	<div class="col-xs-3">
		<div class="well" style="background-color: #D500FF; border-color: #E0E0E0;">
			<div style="margin-top: -10px;">
				<img class="vds" src="assets/img/iconos/medical.svg" style="height: 10%; width: 50%;" >
				<b style="color: #ffff; font-size: 12px;">
					Reposos
					{{count($reposo)}}
				</b>
				<p align="right" style="margin-bottom: -12px; margin-top: -20px;"> <a href="repo_act" style="color: #ffff">Ver más</a></p>
			</div>
		</div>
	</div>
</div>

<div class="col-xs-6" style="margin-top: 20px;">
	<div class="panel panel-default">
  		<div class="panel-heading" style="background-color: #e5e8e8;">
	    	<h3 class="panel-title"><b>JORNADA ACTUAL</b></h3>
	  	</div>
	  	<div class="panel-body" id="panelJa">
	  		<div class="responsive">
	  			<table class="table">
	  				<thead ">
	  					<tr>
		  					<td class="v" style="font-size: 11px;">

		  						<b>Asistencia</b>
		  						<div style="margin-top:-20px; margin-left:120px;height: 20px;width: 20px; background-color: #78FF00;border: 1px solid black;" align="right"></div>
		  					</td>
							<td class="v">
								{{count($asistencia)}}
							</td>
	  						<td class="v" style="font-size: 11px;">
		  						<b>Inasistencia</b>
		  						<div style="margin-top:-20px; margin-left:100px;height: 20px;width: 20px; background-color: #FF0036;border: 1px solid black;" align="right"></div>
		  					</td>
							<td class="v">
								@if($inasis<=0)
									0
								@else
									{{$inasis}}
								@endif
							</td>
		  					<td class="v" style="font-size: 11px;">
		  						<b>Retrasos</b>
		  						<div style="margin-top:-20px; margin-left:90%;height: 20px;width: 20px; background-color: #FFCD00;border: 1px solid black;" align="right"></div>
		  					</td>
							<td>
								{{count($retrasos)}}
							</td>
		  				</tr>
		  				<tr>
	  						<td class="v" style="font-size: 11px;">
	  							<b>Salidas anticipadas</b>
		  						<div style="margin-top:-14px; margin-left:120px;height: 20px;width: 20px; background-color: #FFA200;border: 1px solid black;" align="right"></div>
	  						</td>
							<td class="v">
								{{count($retiros)}}
							</td>
	  						<td class="v" style="font-size: 11px;">
	  							<b>Vacaciones</b>
		  						<div style="margin-top:-14px; margin-left:100px;height: 20px;width: 20px; background-color: #00FFFF;border: 1px solid black;" align="right"></div>
	  						</td>
							<td class="v">
								{{count($vacaciones)}}
							</td>
		  					<td class="v" style="font-size: 11px;">
		  						<b>Permisos remunerados</b>
		  						<div style="margin-top:-14px; margin-left:90%;height: 20px;width: 20px; background-color: #00FFB6;border: 1px solid black;" align="right"></div>
		  					</td>
							<td>
								{{count($permisosr)}}
							</td>
		  				</tr>
	  					<tr>
	  						<td class="v" style="font-size: 11px;">
		  						<b>Permisos no <br>remunerados</b>
		  						<div style="margin-top:-30px; margin-left:120px;height: 20px;width: 20px; background-color: #8300FF;border: 1px solid black;" align="right"></div>
		  					</td>
							<td class="v" >
								{{count($permisosnr)}}
							</td>
		  					<td class="v" style="font-size: 11px;">
		  						<b>Reposos</b>
		  						<div style="margin-top:-14px; margin-left:100px;height: 20px;width: 20px; background-color: #D500FF;border: 1px solid black;" align="right"></div>
		  					</td>
							<td class="v" >
								{{count($reposo)}}
							</td>
		  					<td class="v" style="font-size: 11px;">
		  						<b>Salidas sin marcar</b>
		  						<div style="margin-top:-14px; margin-left:90%;height: 20px;width: 20px; background-color: #FFFF00;border: 1px solid black;" align="right"></div>
		  					</td>
							<td>				
								{{count($salidasm)}}
							</td>
	  					</tr>
	  				</thead>
	  			</table>
	  		</div>
	  	</div>
	</div>
	<div class="panel panel-default">
  		<div class="panel-heading" style="background-color: #e5e8e8;">
	    	<h3 class="panel-title"><b>NOTIFICACIONES</b></h3>
	  	</div>
	  	<div class="panel-body" id="panelNt">
	  		<div class="responsive">
	  			<table class="table">
	  				<thead style="font-size: 13px;">
		  				<tr>
	  						<td class="v">
	  							Cantidad de empleados que laboraron en los días feriados del mes
	  						</td>
							<td>
								{{$conFeriados}}
							</td>
		  				</tr>
	  					<tr>
	  						<td class="v">
		  						Cantidad de retrasos del mes 
		  					</td>
							<td>
								{{$inasistenciaMes}}
							</td>
	  					</tr>
	  				</thead>
	  			</table>
	  		</div>
	  	</div>
	</div>
</div>

<div class="col-xs-6" style="margin-top: 20px;" >
	<div class="panel panel-default">
  		<div class="panel-heading" style="background-color: #e5e8e8;">
	   		<h3 class="panel-title"><b>ESTADÍSTICAS</b></h3>
	  	</div>
	  	<div class="panel-body" id="panelGr">
	  		<div class="responsive">
				<canvas id="oilChart" width="600" height="400" style="height: 5px: font-size:10px;"></canvas>
			</div>
	  	</div>
	</div>
</div>
<div class="col-xs-12" align="right">
	<span id="liveclockk"></span>
</div>



@endsection

	<script src="assets/js/jquery.js"></script>
	<script src="assets/js/Asistencia/hora_marcaje.js"></script>