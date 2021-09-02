<html>
	<head>
		<meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="csrf-token" content="{{ csrf_token() }}"/>
	    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <link rel="stylesheet" href="assets/css/bootstrap.css">
	    <link rel="stylesheet" href="assets/css/mycss.css">

		<title>Inicio</title>
	</head>
	<body style="background-image: url(imagenes/background2.jpg);-webkit-background-size: cover; -moz-background-size: cover;-o-background-size: cover;background-size: cover;">
		<div class="container-fluid">
			<div style="font-size: 500%; color: white;font-weight:bold; font-family: Intro Black;/*text-shadow: -4px 3px 8px #000000;*/ margin-top: 3%;">
								<center>
									ASISTRA
								</center>
							</div>
			<div class="col-lg-12 col-xs-12 col-sm-12 col-md-12">
				<div class="row">
					<div class="col-lg-6 col-lg-offset-3">
						<div style="margin-top: 25%; margin-left: 10%">
							<div class="fondo_login"></div>
							<div class="col-xs-5 col-md-5 col-lg-5 col-sm-5">
								<img src="assets/img/iconos/Login/login1.svg" style="height: 20%;margin-top: 1%;">
							</div>
							<form method="Post" action="{{route('ingresar')}}" style="margin-top: 20px;">
								<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

								<div class="form-group col-xs-7 col-lg-7 col-sm-7 col-md-7" style="margin-top: 17px;">
									<label style="color: white;">Usuario</label>
										<input 
											autofocus 
											class="form-control" 
											type="text"
											name="login"
											value="{{ old('name') }}" 
											placeholder="Por favor ingrese su usuario" required
										/>
								</div>
								<div class="form-group col-xs-7 col-lg-7 col-sm-7 col-md-7">
									<label style="color: white;">Contraseña</label>
									<input 
										type="password" 
										name="pass" 
										class="form-control" 
										placeholder="Ingresa tu contraseña"
										required
									/>
								</div>
								<div class="form-group col-xs-12 col-lg-12 col-sm-12 col-md-12 text-center">
									<br>
									<button 
										type="submit" 
										class="btn" 
										style="background-color: #ac0044; color: #FFFFFF " 
										onmouseover="this.style.background='#e34c7d'; this.style.color='#FFFFFF '"
										onmouseout="this.style.background='#ac0044'; this.style.color='#FFFFFF'">
										<b>Ingresar</b>
									</button><br><br>
								</div>
							</form>
						</div>
					</div>	
				</div>
				<?php if (! $errors->isEmpty()): ?>
					<div class="col-lg-4">
						<div class="alert alert-danger" ng-disable>
							<p><strong>Opps!! </strong>
							@foreach ($errors->all() as $error)
								{{ $error}}</p>
							@endforeach	
						</div>
					</div>
				<?php endif ?>
				@if(Session::has('session'))
					<div class="col-lg-3" style="margin-left: 78%; margin-top: 13%;" id="error_login">
						<div class="alert alert-danger">
						{{Session::get('session')}}
						</div>	
					</div>
				@endif
			</div>
		</div>
		<script src="assets/js/jquery.js"></script>
		<script>
			$(document).ready(function() {
			    setTimeout(function() {
		            $("#error_login").slideUp(1000);
		        },1500);
			});
		</script>
	 	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	 	<script src="assets/js/bootstrap.min.js"></script>
	 	<script src="assets/js/angular.min.js"></script>
	 	<script src="assets/js/ui-bootstrap-tpls-2.5.0.min.js"> </script>
	 	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.6/angular.min.js"></script>
	 	<script type="text/javascript"> $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });</script>
	</body>
</html>



