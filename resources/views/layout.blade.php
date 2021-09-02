<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="I14KMFPhosSaHwjSeIYEAvpolp6cTkVqYBpYiTov">
    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/marcaje/asistenciag.css')}}">

	<title>Marcaje</title>
</head>
<body style="background-image: url(imagenes/background2.jpg);background-position: 100% 100%; background-repeat: no-repeat; ">
	
			<div id="contenido">
				
				@yield('contenido')
			</div>
		</div>
	</div>

	<script src="{{asset('assets/js/jquery.js')}}"></script>



	<script src="{{asset('assets/js/moment.js')}}"></script>

	<script src="{{asset('assets/js/Asistencia/hora_marcaje.js')}}"></script>

	<script src="{{asset('assets/js/Asistencia/asistenciag.js')}}"></script>

	<script src="{{asset('assets/js/Marcaje/marcaje.js')}}"></script>
	
	<script src="{{asset('assets/js/Marcaje/notificaciones.js')}}"></script>

	<script src="{{asset('assets/js/funciones.js')}}"></script>

 	<script src="{{asset('assets/js/bootstrap.min.js')}}"></script>

 	<script src="{{asset('assets/js/angular.min.js')}}"></script>

	<script src="{{asset('assets/js/config.js')}}"></script>
	
	<!--Sweet Alert-->
	<script src="{{asset('assets/js/sweetalert.min.js')}}"></script>

 	<script src="{{asset('assets/js/ui-bootstrap-tpls-2.5.0.min.js')}}"> </script>

 	<script type="text/javascript"> $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });</script>

</body></html>