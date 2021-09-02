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

@if($aco_ser == 'p_cnpseriales')
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

<div class="panel panel-default" style="">
<div class="panel-heading"  style="background-color: #e5e8e8;">   <!-- Default panel contents -->
      <label>Carnets Provisionales</label><!-- Boton de la modal -->
    <a class="crearCod" href="#" data-toggle="modal" data-target="#creaSerial" style="display: none;"> 
      <img src="assets/img/iconos/add.svg" align="right" style="height: 35px; margin-top: -8px" title="Agregar un nuevo serial">
    </a>
  </div>
  <div class="modal fade" id="creaSerial" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
                  
          <div class="modal-body text-center">
            <form method="POST" action="createSerial" enctype="multipart/form-data">
              <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

              <div class="panel panel-default" style="">

                  <div class="panel-heading" style="background-color: #e5e8e8;"> Crear Seriales <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button></div>
                  <div class="panel-body">

                    <div class="form-group col-xs-12">
                      <label>Cantidad de Seriales disponibles: </label>
                      <br>
                      <strong>{{$cp}}</strong>
                      <!--<input type="text" name="cantidad" value="{{$cp}}"> -->
                    </div>

                    <div class="form-group col-xs-6" id="">
                        <label>¿Desea crear nuevos seriales?: </label>

                        <select name="newSerial" class="form-control" id="totalSerial">
                          <option selected value="SI">Seleccione</option>
                          <option value="SI">SI</option>
                         <!-- <option value="NO">NO</option>-->
                      </select>
                    </div>

                    <div class="form-group col-xs-6" id="totales">
                        <label>Cantidad: </label>

                        <select name="cantidadSerial" class="form-control">
                          <option disabled selected value="">Seleccione</option>
                          <option value="5">5</option>
                          <option value="10">10</option>
                          <option value="15">15</option>
                          <option value="20">20</option>
                      </select>
                    </div>
  
                  </div>
          </div>
          <div class="modal-footer">
            <button type="button" data-dismiss=modal class="btn btn-default">Cerrar</button>
            <input type="submit" name="registrar" value="Crear" class="btn" style="background-color:  #48c9b0; color: white;"/> 
          </div>
                
              </div>
            </div>
          </form>
        </div>
      </div>
      <!-- Table -->
<table class="table table-striped table-hover table-bordered table-condensed" style="" >
  <thead style='background: #f2f3f4  repeat-x center top; font-size:14px;'>

  <tr>
  <th style="text-align: center;">#</th>
    <th width="15%" style="text-align: center;">Disponibilidad</th>
    <th style="text-align: center;">Código</th>
    <th style="text-align: center;">Nro Carnet</th>
    <th style="text-align: center;">Status</th>
    <th width="10%" style="text-align: center;">Ver</th>
  </tr>
  
  </thead>

  @if(count($provisional) > 0)

  <tbody id="provisionalesCarnets">
  <?php 
    $n = 0;

     foreach ($provisional as $carnet_provisional):

     $n = $n+1;

     $status = $carnet_provisional->status;

     if ($status == 1) {
       $status = "Inactivo";
     }elseif ($status == 2){
      $status = "Activo";
     }else{
      $status = "Inutilizado";
     }

     $cedula = $carnet_provisional->cedula;

     if ( ($cedula == 0) || ($cedula == "") ){
       $cedula = "No ha sido asignado";
     }else{
      $cedula = "Asignado";
     }

     ?>
      <tr>
      <td style="text-align: center;">{{$n}}</td>
        <td cedula="{{$carnet_provisional->cedula}}" style="text-align: center;" >{{$cedula}}</td>
        <td style="text-align: center;">{{$carnet_provisional->codigo}}</td>
        <td style="text-align: center;">{{$carnet_provisional->id}}</td>
        <td style="text-align: center;">{{$status}}</td>
        <td>
            <a class="verCarnetHis" codigoCarnet="{{$carnet_provisional->codigo}}" href="#" data-toggle="modal" data-target="#provHistorico"> 
              <img src="assets/img/iconos/ver.png" class="imgmenuho" >
            </a>
            <div class="modal fade" id="provHistorico" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  
                  <div class="modal-body text-center">
                    <form method="POST" action="vistaPreviaHistory" >
                      <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                      <div id="vistaHistory">
                      
                      </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" data-dismiss=modal class="btn" style="color:  #85c1e9 ;">Cerrar</button>
                  </div>
                </div>
              </div>
            </div>
          </td>
          
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
    <?php echo $provisional->render(); ?>
  </center>

  </div>


<!--Scripts-->
  <script src="assets/js/jquery.js"></script>
  <script src="assets/js/Carnets/provisionales.js"></script>

@else
<center>
  <img src="imagenes/denegado.png" style="height: 500px">
  <br>
  <b style="font-size: 50px;text-shadow: 1px 2px 5px red">ACCESO DENEGADO</b><br>
  <b style="margin-bottom: 15px;">Usted no tiene permiso para ingresar a esta pantalla</b>
</center>
@endif

@endsection 