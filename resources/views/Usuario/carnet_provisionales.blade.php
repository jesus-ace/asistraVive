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

@if(Session::has('flash_message'))
<div class="alert alert-success" ng-disabled>
{{Session::get('flash_message')}}
  </div>
@endif

@if($aco_rp == 'p_cnpreportes')
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

<form class="form-inline pull-left" style="position: relative;"  role="buscPasant" action="">
  <div class="form-group" style="">
    <label class="sr-only" for="exampleInputAmount">Buscar</label>
    <div class="input-group">
      <input type="text" class="form-control" id="busCarntPast" name="buscPasant" placeholder="Cédula...">
      <div class="input-group-addon"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></div>
    </div>
  <a class="buscaPasConC" href="#" data-toggle="modal" data-target="#editaPasnte"> 
       <button type="button" class="btn" style="background-color:  #48c9b0; color: white;" name="pasanteBusc"> Buscar</button>
  </a>
  
  </div>
</form>

<br><br><br>

<!-- Diseño Menu Provisionales Registro de Pasantes -->

<div class="panel panel-warning" style="">
  <!-- Default panel contents -->
  <div class="panel-heading" style="background-color: #e5e8e8;">Listado De Pasantes con Carnets </div>
      <!-- Table -->
<table class="table table-striped table-hover table-bordered table-condensed" style="" >
  <thead style='background: #f2f3f4  repeat-x center top; font-size:14px;'>

      <tr>
        <th>#</th>
        <th>Cédula</th>
        <th>Nombres</th>
        <th>Apellidos</th>
        <th>Departamento</th>
        <th>Gestionar</th>
      </tr>
    </thead>

    @if(count($pasantes) > 0)
    <tbody id="listadoPasantes">
    <?php 
    $n = 0;
    
    foreach ($pasantes as $carnet_pasantes):
      $n = $n+1; ?>
      <tr>
        <td>{{$n}}</td>
        <td >{{ $carnet_pasantes->cedula }}</td>
        <td >{{ $carnet_pasantes->nombres }}</td>
        <td >{{ $carnet_pasantes->apellidos }} </td>
        <td >{{ $carnet_pasantes->des_uni }}</td> 
        <td>
            <a class="editPasante" style="display: none;" pasanteId="{{$carnet_pasantes->cedula}}" href="#" data-toggle="modal" data-target="#editaPasnte"> 
              <img src="assets/img/iconos/editar.svg" class="imgmenuho" >
            </a>
            <div class="modal fade" id="editaPasnte" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  
                  <div class="modal-body text-center">
                    <form method="POST" action="updatePasante" enctype="multipart/form-data">
                      <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

                      <div class="panel panel-default" style="">

                          <div class="panel-heading" style="background-color: #e5e8e8;">Datos del Pasante <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button></div>
                          <div class="panel-body">

                          <div class="form-group col-xs-4" id="nuevo" style="display: none;">
                            <label>Motivo: </label>

                            <select name="pasanteMotivo" class="form-control" id="motivoPasant">
                              <option disabled selected value="">Elige el Motivo</option>
                              @foreach($nuevo as $tipo_reportes)
                              <option value="{{$tipo_reportes->ctr_id}}">{{$tipo_reportes->ctr_descripcion}}</option>
                              @endforeach
                          </select>
                        </div>

                          <div class="form-group col-xs-4" id="motPasante">
                            <label>Motivo: </label>

                            <select name="pasanteMotivo" class="form-control" id="motivoPasante">
                              <option disabled selected value="">Elige el Motivo</option>
                              @foreach($motivos as $tipo_reportes)
                              <option value="{{$tipo_reportes->ctr_id}}">{{$tipo_reportes->ctr_descripcion}}</option>
                              @endforeach
                          </select>
                        </div>



                    <div class="form-group col-xs-4" id="reporte">
                         <label>¿Desea Reportar Solamente? </label>

                          <select name="ReportaImprimes" class="form-control" id="imprimirReportarC" >
                           <option selected value="No">Seleccione </option>
                           <option value="Si"> Si </option>
                           <option value="No"> No </option>     
                          </select>
                     </div>

                      <div id="pasante">
                      
                      </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" data-dismiss=modal class="btn btn-warning">Cerrar</button>
                    <input type="submit" name="registrar" id="ReasignarP" value="Asignar" class="btn" style="background-color:  #48c9b0; color: white; display: none;"/>
                    <input type="submit" name="registrar" id="asignarP" value="Reportar y Asignar" class="btn" style="background-color:  #48c9b0; color: white;"/>
                     <input type="submit" name="reportar" id="reportarProvisional" value="Reportar" class="btn" style="background-color: #48c9b0; display: none; color: white;"/>
                  </div>
                </div>
              </div>
            </div>
          </td>
      </tr>
      <?php endforeach ?> 
      @else
      <td colspan="12" align="center">
      <div class="alert alert-danger">
          Disculpe, no se encontraron resultados...
     </div>
        </td>              
    @endif
      
    </tbody>
  </table>

</div>

<!-- Diseño Menu Provisionales Registro de Pasantes -->

<!--Scripts-->
  
  <script src="assets/js/jquery.js"></script>
  <script src="assets/js/Carnets/pasantesCarnet.js"></script>


@if(Session::has('flash_message_Prov'))

<script type="text/javascript">

$(document).ready(function(){
    window.open('carnetProvisional','_blank');
});

</script>

<div class="alert alert-success" ng-disabled id="alerta_pdf">
  <a href="carnetProvisional" title="Imprimir" target="_blank"> 
    <img src="assets/img/iconos/marcaje.png" style="height: 30px;" > 
</a>
{{Session::get('flash_message_Prov')}}
</div>

@endif

<style type="text/css">
  #alerta_pdf{
position: absolute;
top: 70;
right: 10px;
margin: 0 auto;
width: 510px;
height: 66px;
}
</style>
@else
<center>
  <img src="imagenes/denegado.png" style="height: 500px">
  <br>
  <b style="font-size: 50px;text-shadow: 1px 2px 5px red">ACCESO DENEGADO</b><br>
  <b style="margin-bottom: 15px;">Usted no tiene permiso para ingresar a esta pantalla</b>
</center>
@endif
@endsection 