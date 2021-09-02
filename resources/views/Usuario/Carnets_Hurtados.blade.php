@extends('menu')
@section('contenido')
<?php if (! $errors->isEmpty()): ?>
  <div class="row">
    <div class="col-lg-4" style="margin-left: 750px;" id="Alertaerror">
      <div class="alert alert-danger">
        <p><strong>Lo sentimos </strong> Por favor corrige los siguientes errores</p>
        <?php foreach ($errors->all() as $error): ?>
          <li>{{ $error}}</li>
        <?php endforeach ?> 
      </div>
    </div>
  </div>
<?php endif ?> 

@if(Session::has('flash_message'))
<div class="alert alert-success" ng-disabled>
{{Session::get('flash_message')}}
  </div>
@endif
@if($aco_chu == 'p_churtados')
<ul class="nav nav-pills">
  <li role="presentation" id="estadisticas">
    <a href={{ URL::to('estadisticasCarnets') }} >
      Inicio
    </a>
  </li>
  <li class="dropdown carnet_1" style="display: none;">
    <a href={{ URL::to('carnet') }} class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Carnets <span class="caret"></span></a>
    <ul class="dropdown-menu">
      
      <li style="display: none;" class="new_carnet"><a href={{ URL::to('new_carnet') }}><b>Nuevos</b></a></li>            
      <li style="display: none;" id="separador_cn" role="separator" class="divider"></li>
      <li style="display: none;" class="rep_carnet"><a href={{ URL::to('carnet') }} ><b>Reportar</b></a></li>
      
    </ul>
  </li>
  <li class="dropdown carnetp1" style="display: none;">
    <a href={{ URL::to('carnet_provisionales') }} class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Provisionales <span class="caret"></span></a>
    <ul class="dropdown-menu">
      
      <li class="newProv" style="display: none;"><a href={{ URL::to('carnet_provisionales_new') }}><b>Nuevos</b></a></li>
      
      <li role="separator"  id="separadorpv1" style="display: none;" class="divider"></li>
      <li style="display: none;" class="cnprep" ><a href={{ URL::to('carnet_provisionales') }} ><b>Reportar</b></a></li>
      <li role="separator"  id="separadorpv2" style="display: none;" class="divider"></li>
      <li style="display: none;" class="cnpseri" ><a href={{ URL::to('carnet_seriales') }} ><b>Seriales</b></a></li>
    </ul>
  </li>
  <li class="conf_carnet" style="display: none;" role="presentation" id="change_diseno_carnet">
    <a href={{ URL::to('conf_diseno_carnet') }} >
      Conf Diseño
    </a>
  </li>
  <li class="dropdown histo_carnet" style="display: none;">
    <a href={{ URL::to('carnet_historico') }} class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Histórico <span class="caret"></span></a>
    <ul class="dropdown-menu">
      
      <li style="display: none;" class="emphisto"><a href={{ URL::to('carnet_historico') }}><b>Carnets Empleados</b></a></li>
      
      <li style="display: none;" id="separadorpv3" role="separator" class="divider"></li>
      <li style="display: none;" class="prohisto"><a href={{ URL::to('historico_carnet_provisional') }} ><b>Carnets Provisionales</b></a></li>
    </ul>
  </li>
</ul>

<br>

  <div class="form-group col-xs-3" style="">
    <label class="sr-only" for="exampleInputAmount">Busca</label>
    <div class="input-group">
      <input type="text" class="form-control" id="buscaHurtados" placeholder="Buscar... Serial, Cédula">
      <div class="input-group-addon"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></div>
    </div>
  </div>

<form method="post" action="buscaFechasHurtados" name="" >
 {{ csrf_field() }}
  <div class="form-group col-xs-4" style="">
    <label class="sr-only" for="exampleInputAmount">Desde</label>
    <div class="input-group">
      <input type="text" class="form-control" name="fechadesdeH" id="desde" placeholder="Fecha...Desde" required="">
      <div class="input-group-addon"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></div>
    </div>
  </div>

    <div class="form-group col-xs-4" style="">
    <label class="sr-only" for="exampleInputAmount">Hasta</label>
    <div class="input-group">
      <input type="text" class="form-control" name="fechahastaH" id="hasta" placeholder="Fecha...Hasta" required="">
      <div class="input-group-addon"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></div>
    </div>
  </div>
<div class="">
  <button name="buscar" class="btn" value="Buscar" style="background-color: #48c9b0; color:white;">
    <b>PDF</b>
  </button>
</div>  
</form>

<div class="panel panel-default" style="">
  <!-- Default panel contents -->
      <div class="panel-heading" style="background-color: #e5e8e8;">Carnets Hurtados 
    <a href="PDF_Carnet_Hurtado" id="" target="_blank" title="Generar archivo pdf" style="display: none;" class="reporte_chu"> 
      <img src="assets/img/iconos/pdf.svg" align="right" style="height: 35px; margin-top: -8px" title="Imprimir PDF">
    </a>
  </div>
      <!-- Table -->
<table class="table table-striped table-hover table-bordered table-condensed" style="" >
  <thead style='background: #f2f3f4  repeat-x center top; font-size:14px;'>

  <tr>
        <th>Cédula</th>
        <th>Nombres</th>
        <th>Apellidos</th>
        <th>Departamento</th>
        <th>Código</th>
        <th>Fecha</th>
      </tr>
    </thead>
    @if(count($hurtados) > 0)
<tbody id="buscarHurtado">
<?php foreach ($hurtados as $carnet_empleados):
$fechas = date("d/m/Y", strtotime($carnet_empleados->fecha));
?>
<tr>
  <td >{{ $carnet_empleados->cedula }}</td>
  <td >{{ $carnet_empleados->nombres }} </td>
  <td >{{ $carnet_empleados->apellidos }}</td> 
  <td >{{ $carnet_empleados->des_uni }}</td> 
  <td >{{ $carnet_empleados->codigo }}</td>
  <td>{{ $fechas }}</td>
</tr>
<?php endforeach ?> 
@else
      <td colspan="12" align="center">
      <div class="alert alert-warning">
          Disculpe, no se encontraron resultados...
     </div>
        </td>              
    @endif
    </tbody>
  </table>

  <center>
    <?php echo $hurtados->render(); ?>
  </center>

</div>



  <script src="assets/js/jquery.js"></script>
  <script src="assets/js/Carnets/estadisticasCarnets.js"></script>

@else
<center>
  <img src="imagenes/denegado.png" style="height: 500px">
  <br>
  <b style="font-size: 50px;text-shadow: 1px 2px 5px red">ACCESO DENEGADO</b><br>
  <b style="margin-bottom: 15px;">Usted no tiene permiso para ingresar a esta pantalla</b>
</center>
@endif
  @endsection 