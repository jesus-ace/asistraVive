@extends('menu')
@section('contenido')
<?php if (! $errors->isEmpty()): ?>

  <link rel="stylesheet" type="text/css" href="assets/css/carnets/carnet.css">


  <div class="alert alert-danger" ng-disabled>
    <p><strong>Lo sentimos</strong>Por favor corrige los siguientes errores</p>
    <?php foreach ($errors->all() as $error): ?>
      <li>{{ $error}}</li>
    <?php endforeach ?> 
  </div>
<?php endif ?>
@if($aco_che== 'p_chistoricoe')

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

  <div class="form-group col-xs-4" style="">
    <label class="sr-only" for="exampleInputAmount">Busca</label>
    <div class="input-group">
      <input type="text" class="form-control" id="busHistorico" placeholder="Buscar...Código Carnet, Cédula empleado">
      <div class="input-group-addon"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></div>
    </div>
  </div>

<br><br><br>

<!-- Diseño Vista Menu Historico -->

<div class="panel panel-default" style="">
  <!-- Default panel contents -->
    <div class="panel-heading" style="background-color: #e5e8e8;">Histórico de Impresiones 
    <a href="PDF_Carnet_Historico" id="" target="_blank" title="Generar archivo pdf" style="" class="reporte_pv"> 
      <img src="assets/img/iconos/pdf.svg" align="right" style="height: 35px; margin-top: -8px" title="Imprimir PDF">
    </a>
  </div>
  <div class="panel-body">
      <!-- Table -->
  <table class="table">
    <thead>
      <tr>

        <th>Responsable</th>       
       
        <th>Empleado</th>
        <th>Área Empleado</th>
        <th>Cédula</th>
        <th>Motivo</th>
        <th>Fecha</th>

      </tr>
    </thead>
    @if(count($historicoCarnets) > 0)
    <tbody id="historicos">
     <?php foreach ($historicoCarnets as $historico):
     $fechas = date("d/m/Y", strtotime($historico->fecha));
     ?>
      <tr>

        <td>{{ $historico->responsable }}</td>              
        
        <td>{{ $historico->nombres}} {{ $historico->apellidos}}</td>
        <td>{{ $historico->des_uni}}</td>
        <td>{{ $historico->cedula }}</td>
        <td>{{ $historico->tipoReporte}}</td>
        <td>{{ $fechas}}</td>
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
<div id="results" align="center"></div>
  <center>
    <?php echo $historicoCarnets->render(); ?>
  </center>

  </div>


</div>

<!-- Diseño Vista Menu Historico -->


<style type="text/css">
  .table-responsive{

 /* padding-left: 5%;
  padding-right:5%;*/

}
.table{
  background-color: #fff;
}

#tabla_empleados{
  background-color:#3F88D3;
}



</style>

<!--Scripts-->
  <script src="assets/js/jquery.js"></script>
  <script src="assets/js/Carnets/carnet_historico.js"></script>
      <!-- Datepicker Files -->
    <link rel="stylesheet" href="{{asset('datePicker/css/bootstrap-datepicker3.css')}}">
    <link rel="stylesheet" href="{{asset('datePicker/css/bootstrap-standalone.css')}}">
    <script src="{{asset('datePicker/js/bootstrap-datepicker.js')}}"></script>
    <!-- Languaje -->
    <script src="{{asset('datePicker/locales/bootstrap-datepicker.es.min.js')}}"></script>


  <script>
    $('.datepicker').datepicker({
        format: "dd/mm/yyyy",
        language: "es",
        autoclose: true
    });
</script>
@else
<center>
  <img src="imagenes/denegado.png" style="height: 500px">
  <br>
  <b style="font-size: 50px;text-shadow: 1px 2px 5px red">ACCESO DENEGADO</b><br>
  <b style="margin-bottom: 15px;">Usted no tiene permiso para ingresar a esta pantalla</b>
</center>
@endif

@endsection 