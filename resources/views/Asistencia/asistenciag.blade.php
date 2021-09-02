@extends('layout')
@section('contenido')

@if (count($autip)>0)

<center>
		<div style="font-size: 38px; color: white;font-weight:bold; /*text-shadow: -4px 3px 8px #000000; */ margin-top: 5%; margin-bottom: -70px;">
			CONTROL DE ASISTENCIA
			
			<span id="liveclockk"></span>
		</div>
		<input type="hidden" name="token" id="_token" value="{{csrf_token()}}">

	<div id="asistencia">
		<div style="margin-top: 5%;" class="col-lg-6 col-md-offset-3 block_foto" id="block_foto">
			<div style="background-color: #B8125A; /*rgba(0, 9, 33, 0.7)*/border-radius: 6px; margin-bottom: 12px; width: 430px; height: 350px;">
		  		<div class="panel-body">
		    		<div class="default" >
						<img src="imagenes/boss.svg" style="height: 67%;margin-top: 11%;" alt="">
					</div>
		  		</div>
			</div>
		</div>
		<div class="col-lg-8 col-md-offset-2 block_codigo"  id="block_codigo">
			<div class="panel" style="/*background-color: rgba(0, 9, 33, 0.7)*/;border-radius: 10px; width: 446px; height: 60px; opacity:0.5;">
		  		<div class="panel-body">
		    		<div class="dataC">
			  			<b>
			  				<!--<form method="post" action="asistencia">-->
			  					
			  					<div class="col-lg-12" >
			  						<input 
			  						autofocus 
									type="password" 
									name="codigo"
									id="codigo" 
									class="form-control codigo"
									placeholder=" Introduzca su código de carnet" 
									title="Por favor ingrese los 6 dígitos numéricos de su carnet" 
									pattern="[0-9]{4-6}"
									style="width: 88%;" 
									onchange="prueba()" 
									/>
			  					</div>
			  					
			  				</form>
			  			</b>
			  		</div>
		  		</div>
			</div>
		</div>
	</div>
</center>
@else
<center>
	<img src="imagenes/denegado.png" style="height: 500px">
	<br>
	<b style="font-size: 50px;text-shadow: 1px 2px 5px red">ACCESO DENEGADO</b><br>
	<b style="margin-bottom: 15px;">Esta máquina no está autorizada para ingresar a esta pantalla</b>
</center>
@endif
@endsection

	<script src="assets/js/jquery.js"></script>
	<script src="assets/js/Asistencia/hora_marcaje.js"></script>