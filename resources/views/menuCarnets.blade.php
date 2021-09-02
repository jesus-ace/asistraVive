
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="I14KMFPhosSaHwjSeIYEAvpolp6cTkVqYBpYiTov">
    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/mycss.css">
    <!--<link rel="stylesheet" href="assets/css/sweetalert.css">-->

	<title>ASISTRA</title>
</head>
<body>
<ul class="nav nav-pills">
<li role="presentation" id="estadisticas"><a href={{ URL::to('estadisticasCarnets') }} >Estadísticas</a></li>
<li class="dropdown">
          <a href={{ URL::to('carnet') }} class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Carnets <span class="caret"></span></a>
          <ul class="dropdown-menu">
            
            <li><a href={{ URL::to('new_carnet') }}><b>Nuevos</b></a></li>            
            <li role="separator" class="divider"></li>
            <li><a href={{ URL::to('carnet') }} ><b>Reportes</b></a></li>
            
          </ul>
        </li>
  <li class="dropdown">
          <a href={{ URL::to('carnet_provisionales') }} class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Provisionales <span class="caret"></span></a>
          <ul class="dropdown-menu">
            
            <li><a href={{ URL::to('carnet_provisionales_new') }}><b>Nuevos</b></a></li>
            
            <li role="separator" class="divider"></li>
            <li><a href={{ URL::to('carnet_provisionales') }} ><b>Reportes</b></a></li>
            <li role="separator" class="divider"></li>
            <li><a href={{ URL::to('carnet_seriales') }} ><b>Seriales</b></a></li>
          </ul>
        </li>
  <li role="presentation" id="change_diseno_carnet"><a href={{ URL::to('conf_diseno_carnet') }} >Conf Diseño</a></li>
  <li class="dropdown">
          <a href={{ URL::to('carnet_historico') }} class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Histórico <span class="caret"></span></a>
          <ul class="dropdown-menu">
            
            <li><a href={{ URL::to('carnet_historico') }}><b>Carnets Empleados</b></a></li>
            
            <li role="separator" class="divider"></li>
            <li><a href={{ URL::to('historico_carnet_provisional') }} ><b>Carnets Provisionales</b></a></li>
          </ul>
        </li>
</ul>
</body>

</html>

