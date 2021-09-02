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
@if($aco_cpn == 'p_cnprovisional')
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

<form class="form-inline pull-left" style="position: relative;"  role="buscaPasantes" action="">
  <div class="form-group" style="">
    <label class="sr-only" for="exampleInputAmount">Buscar</label>
    <div class="input-group">
      <input type="text" class="form-control" id="busCarnetPas" name="buscaPasantes" placeholder="Cédula...">
      <div class="input-group-addon"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></div>
    </div>
  <a class="buscaPasSinC" href="#" data-toggle="modal" data-target="#createProv"> 
       <button type="button" class="btn empleaSinCarnet" style="background-color:  #48c9b0; color: white;" name="pasanteBusc"> Buscar</button>
  </a>
  
  </div>
</form>

<br><br><br>

    <div class="modal fade" id="creaSerial" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
                  
          <div class="modal-body text-center">
            <form method="POST" action="createSerial" enctype="multipart/form-data">
              <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

              <div class="panel panel-default" style="">

                  <div class="panel-heading" style="background-color: #e5e8e8;"> Crear Seriales <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button></div>
                  <div class="panel-body">

                    <div class="form-group col-xs-6">
                      <label>Disponibilidad: </label>
                      <input type="text" name="cantidad" value="{{$cp}}"> 
                    </div>

                    <div class="form-group col-xs-6">
                        <label>¿Desea crear nuevo serial?: </label>

                        <select name="newSerial" class="form-control" id="">
                          <option disabled selected value="">Seleccione</option>
                          <option value="SI">SI</option>
                          <option value="NO">NO</option>
                      </select>
                    </div>
  
                  </div>
          </div>
          <div class="modal-footer">
            <button type="button" data-dismiss=modal class="btn btn-default">Cerrar</button>
            <input type="submit" name="registrar" value="Registrar" class="btn" style="background-color:  #48c9b0; color: white;"/> 
          </div>
                
              </div>
            </div>
          </form>
        </div>
      </div>


<!-- Diseño Menu Provisionales Registro de Pasantes -->

<div class="panel panel-warning" style="">
  <!-- Default panel contents -->
  <div class="panel-heading" style="background-color: #e5e8e8;">Listado De Pasantes
      <a href="#">
        <img src="assets/img/iconos/add.svg" align="right"  style="height: 3%" onclick="$('#headPasantes').toggle(500);$('#listadoPasantes').toggle(500);">
      </a>
  </div>
      <!-- Table -->
<table class="table table-striped table-hover table-bordered table-condensed" style="" >
  <thead style='background: #f2f3f4  repeat-x center top; font-size:14px;display: none;' id="headPasantes" >

      <tr>
        <th>#</th>
        <th>Cédula</th>
        <th>Nombres</th>
        <th>Apellidos</th>
        <th>Departamento</th>
        <th>Asignar</th>
      </tr>
    </thead>
    @if(count($pasantes) > 0)
    <tbody id="listadoPasantes" style="display: none;">
    <?php 
    $n = 0;
     foreach ($pasantes as $carnet_pasantes):
      $n = $n+1;
    ?>
      <tr>
        <td>{{$n}}</td>
        <td >{{ $carnet_pasantes->cedula }}</td>
        <td >{{ $carnet_pasantes->nombres }}</td>
        <td >{{ $carnet_pasantes->apellidos }} </td>
        <td >{{ $carnet_pasantes->des_uni }}</td> 
        <td>
            <!--<a class="creaPasante" style="display: none;" pasanteId="{{$carnet_pasantes->cedula}}" href="#" data-toggle="modal" data-target="#createPas"> 
              <img src="assets/img/iconos/editar.svg" class="imgmenuho" >
            </a>-->

            <a class="creaProv" pasanteId="{{$carnet_pasantes->cedula}}" href="#" data-toggle="modal" data-target="#createProv"> 
              <img src="assets/img/iconos/editar.svg" class="imgmenuho" >
            </a>
            <div class="modal fade" id="createPas" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  
                  <div class="modal-body text-center">
                    <form method="POST" action="createPasante" enctype="multipart/form-data">
                      <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

                      <div class="panel panel-default" style="">

                          <div class="panel-heading" style="background-color: #e5e8e8;">Datos del Pasante <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button></div>
                          <div class="panel-body">

                      <div id="pasante"></div>
                      </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" data-dismiss=modal class="btn btn-warning">Cerrar</button>
                    <input type="submit" name="registrar" value="Asignar" class="btn" style="background-color:  #48c9b0; color: white;"/>
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
<div class="panel panel-warning" style="">
  <!-- Default panel contents -->
  <div class="panel-heading" style="background-color: #e5e8e8;">Listado De Empleados sin Carnet
      <a href="#">
        <img src="assets/img/iconos/add.svg" align="right"  style="height: 3%" onclick="$('#headEmpsc').toggle(500);$('#listadoEmpsc').toggle(500);$('#center').toggle(500);">
      </a>
  </div>
      <!-- Table -->
<table class="table table-striped table-hover table-bordered table-condensed" style="" >
  <thead style='background: #f2f3f4  repeat-x center top; font-size:14px;' id="headEmpsc" >

      <tr>
        <th>#</th>
        <th>Cédula</th>
        <th>Nombres</th>
        <th>Apellidos</th>
        <th>Cargo</th>
        <th>Asignar</th>
      </tr>
    </thead>
    @if(count($empleados) > 0)
    <tbody id="listadoEmpsc">
    <?php $n = 0; ?>
    <?php foreach ($empleados as $emp): 
      $n = $n+1;
    ?>
      <tr>
        <td>{{$n}}</td>
        <td >{{$emp->cedula}}</td>
        <td >{{$emp->nombres}} </td>
        <td >{{$emp->apellidos}} </td>
        <td >{{$emp->des_car}}</td> 
        <td>
            <a class="creaProv" pasanteId="{{$emp->cedula}}" href="#" data-toggle="modal" data-target="#createProv"> 
              <img src="assets/img/iconos/editar.svg" class="imgmenuho" >
            </a>
            <div class="modal fade" id="createProv" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  
                  <div class="modal-body text-center">
                    <form method="POST" action="createPasante" enctype="multipart/form-data">
                      <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

                      <div class="panel panel-default" style="">

                        <div class="panel-heading" style="background-color: #e5e8e8;">Datos del empleado <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button></div>
                        <div class="panel-body">

                          <div id="fondoC"> </div>

                          <div id="provS">

                          <div id="provisional">

                            <input type="hidden" name="id" id="id_emp">

                            <div id="provision">

                              

                              <div class="form-group col-xs-4">
                                <label>Cedula: </label>
                                <input type="text" name="cedulaPasante" class="form-control" id="cedula_emp" required>
                              </div>

                              <div class="form-group col-xs-4">
                                <label>Nombre: </label>
                                <input type="text" name="nombrePasante" class="form-control" id="nombre_emp" required>
                              </div>

                              <div class="form-group col-xs-4">
                                <label>Apellido: </label>
                                <input type="text" name="apellidoPasante" class="form-control" id="apellido_emp" required>
                              </div>
                              <div class="col-xs-4" id="foto">
                                <label>Foto</label>
                                  <input type="file" class="form-control" name="image" id="image" accept="image/*" >
                              </div>
                              <div class="form-group col-xs-4">
                                <label>Area: </label>
                                <input type="text" name="areaPasante" class="form-control" id="area_emp" required>
                              </div>

                              <div class="form-group col-xs-4">
                                <label for="date">Fecha de Vencimiento: </label>
                                <div class="input-group col-lg-12">
                                  <input type="date" class="form-control datepicker" name="pasanteFechaVec" id="empFechaVec" required="">
                                  <div class="input-group-addon">
                                      <span class="glyphicon glyphicon-th"></span>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" data-dismiss=modal class="btn btn-warning">Cerrar</button>
                        <input type="submit" name="registrar" value="Asignar" class="btn" style="background-color:  #48c9b0; color: white;"/>
                      </div>   
                    </form>                
                  </div>
                </div>
              </div>
          </td>
      </tr>      
    <?php endforeach ?>
      <area shape="" coords="" href="">
      @else
      <td colspan="12" align="center">
      <div class="alert alert-danger">
          Disculpe, no se encontraron resultados...
     </div>
        </td>              
    @endif
    </tbody>
  </table>

<center class="paginacion_f" id="center">
  <?php echo $empleados->render(); ?>
</center>

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