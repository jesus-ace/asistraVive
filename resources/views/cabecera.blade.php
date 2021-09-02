
<!DOCTYPE html>
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
<body class="fondob">
	<div class="container-fluid">
		<div class="col-lg-12 col-xs-12 col-sm-12 col-md-12">
				<div class="franjas">
				<button type="sumbit" class="butn btn-cabecera"><label>Login</label></button>
				</div>
		</div>
				@yield('content')
	</div>



 	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
 	<script src="assets/js/bootstrap.min.js"></script>
 	<script src="assets/js/angular.min.js"></script>
 	<script src="assets/js/ui-bootstrap-tpls-2.5.0.min.js"> </script>
 	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.6/angular.min.js"></script>
 	<script type="text/javascript"> $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });</script>
</body>
</html>
