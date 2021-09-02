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
</div>
<?php
	if($sigespSt == 1){
		$stSigesp = 'checked';
		$estadoS = 'ACTIVO';
	}
	else{
		$stSigesp = '';
		$estadoS = 'INACTIVO';
	}
	if($ldapSt == 1){
		$stLdap = 'checked';
		$estadoL = 'ACTIVO';
	}
	else{
		$stLdap = '';
		$estadoL = 'INACTIVO';
	}
?>

@if($aco_conex == 'p_conexion')
<div class="panel panel-default">
	<div class="panel-heading" style="background-color: #e5e8e8;">	
    	<h3 class="panel-title">
    		<b>CONFIGURACIÓN DEL SISTEMA</b>
    		<a>
    			<img src="assets/img/iconos/save.svg" style="height: 30px; margin-top: -7px;" align="right">
    		</a>
    	</h3>
	</div>
	<div class="panel-body">
		<form action="update_cof" method="post">
			{{ csrf_field() }}
			<!--LDAP-->
			<div class="col-lg-12">
	  			<hr>
	  		</div>												
  			<div class="form-group col-lg-4">
			    <label for="inputsm">NOMBRE DE LA CONEXIÓN</label>
			    <p>LDAP</p>
			</div>
  			<div class="form-group col-lg-4">
			    <label for="inputsm">TIPO DE CONEXIÓN SISTEMA</label>
			    <p> DIRECTORIO LDAP </p>
			</div>
			<div class="col-lg-2">
				ESTADO
                <input id="estado_ldap" name="estado_ldap" type="checkbox" value="LDAP" {{$stLdap}}>
			</div>
			<div class="form-group col-lg-2" id="estado_l">
			    {{$estadoL}}
			</div>	
			<!--SIGESP-->
			<div class="col-lg-12">
	  			<hr>
	  		</div>												
  			<div class="form-group col-lg-4">
			    <label for="inputsm">NOMBRE DE LA CONEXIÓN</label>
			    <p>SIGESP</p>
			</div>
  			<div class="form-group col-lg-4">
			    <label for="inputsm">TIPO DE CONEXIÓN SISTEMA</label>
			    <p> SISTEMA ADMINISTRATIVO SIGESP </p>
			</div>
			<div class="col-lg-2">
				ESTADO
                <input id="estado_sigesp" name="estado_sigesp" type="checkbox" value="SIGESP" {{$stSigesp}}>
			</div>
			<div class="form-group col-lg-2" id="estado_s">
				{{$estadoS}}
			</div>

	    	<div class="col-lg-12 text-right">
	    		<hr>
				<button class="btn" type="submit" style="background-color: #48c9b0; color: white;">
					GUARDAR
				</button>
			</div>
  		</form>
	</div>
</div>
<div class="panel panel-default">
	<div class="panel-heading" style="background-color: #e5e8e8;">	
    	<h3 class="panel-title">
    		<b>DATOS DE LA EMPRESA</b>
    	</h3>
	</div>
	<div class="panel-body">
		<form action="update_datos_emp" method="post">
			{{ csrf_field() }}
			<div class="col-lg-6">
				<label>Presidente</label>
				<input class="form-control" type="text" name="presidente" style="margin: 1%;" value="{{$presidente}}">
			</div>
				
			<div class="col-lg-6">
				<label>Empresa</label>
				<input class="form-control" type="text" name="empresa" style="margin: 1%;" value="{{$empresa}}">
			</div>

			<div class="col-lg-6">
				<label>Página</label>
				<input class="form-control" type="text" name="pagina" style="margin: 1%;" value="{{$pagina}}">
			</div>

			<div class="col-lg-6">
				<label>Agradecimiento</label>
				<input class="form-control" type="text" name="agradecimiento" style="margin: 1%;" value="{{$agradecimiento}}">
			</div>

			<div class="col-lg-6">
				<label>Encabezado</label>
				<input class="form-control" type="text" name="encabezado" style="margin: 1%;" value="{{$encabezado}}">
			</div>

			<div class="col-lg-6">
				<label>Teléfono</label>
				<input class="form-control" type="text" name="telefono" style="margin: 1%;" value="{{$telefono}}">
			</div>

			<div class="col-lg-6">
				<label>Descripción</label>
				<input class="form-control" type="text" name="descripcion" style="margin: 1%;" value="{{$descripcion}}">
			</div>

	    	<div class="col-lg-12 text-right">
	    		<hr>
				<button class="btn" type="submit" style="background-color: #48c9b0; color: white;">
					GUARDAR
				</button>
			</div>
  		</form>
	</div>
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