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

<ul class="nav nav-pills">
  <li role="presentation" class=""><a href={{ URL::to('new_carnet') }} >Carnets</a></li>
  <li role="presentation" id="provisionales"><a href={{ URL::to('carnet_provisionales') }} >Provisionales</a></li>
  <li role="presentation" id="change_diseno_carnet"><a href={{ URL::to('conf_diseno_carnet') }} >Configuración Diseño</a></li>
  <li role="presentation" id="historico"><a href={{ URL::to('carnet_historico') }} >Histórico</a></li>
</ul>

<br>

<form class="form-inline pull-left" style="position: relative;"  role="busqueda" action="buscaCarnet">
  <div class="form-group" style="">
    <label class="sr-only" for="exampleInputAmount">Buscar</label>
    <div class="input-group">
      <input type="text" class="form-control" id="search" name="busqueda" placeholder="Cédula...">
      <div class="input-group-addon"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></div>
    </div>
 <button type="submit" class="btn" style="background-color:  #48c9b0; color: white;" name="empleadoBuscarC">Buscar</button>
  
  </div>
</form>

<br><br><br>

<h3>Resultado de la búsqueda: {{$buscarC}}</h3>

<div class="panel panel-default" style="">
  <!-- Default panel contents -->
  <div class="panel-heading" style="background-color: #e5e8e8;">Listado De Empleados Con Carnet </div>
      <!-- Table -->
<table class="table table-striped table-hover table-bordered table-condensed" style="" >
  <thead style='background: #f2f3f4  repeat-x center top; font-size:14px;'>

      <tr>
        <th>Cédula</th>
        <th>Nombres</th>
        <th>Apellidos</th>
        <th>Cargo</th>
        <th>Editar</th>
        <th>Ver</th>
      </tr>
    </thead>
    <tbody id="listadoEmpleados">
    <?php foreach ($empleados as $carnet_empleados):?>
      <tr>
        
        <td >{{ $carnet_empleados->cedula }}</td>
        <td >{{ $carnet_empleados->nombres }} </td>
        <td >{{ $carnet_empleados->apellidos }}</td> 
        <td >{{ $carnet_empleados->des_car }}</td>
        <td>
            <a class="editEmpleado" empleadoId="{{$carnet_empleados->cedula}}" href="#" data-toggle="modal" data-target="#editaEmpl"> 
              <img src="assets/img/iconos/editar.svg" class="imgmenuho" >
            </a>
            <div class="modal fade" id="editaEmpl" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
              <div class="modal-dialog modal-lg"">
                <div class="modal-content">
                  
                  <div class="modal-body text-center">
                    <form method="POST" action="updateEmpleado" enctype="multipart/form-data">
                      <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

                      <div class="panel panel-default" style="">

                          <div class="panel-heading" style="background-color: #e5e8e8;">Datos del Empleado <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button></div>
                          <div class="panel-body">

                      <div class="form-group col-xs-4">
                        <label>Motivo: </label>

                        <select name="carnet_usu_motivo" class="form-control" id="motivoReporte">
                          <option disabled selected value="">Elige el Motivo</option>
                          @foreach($tipo_reportes as $category)
                          <option value="{{$category->ctr_id}}">{{$category->ctr_descripcion}}</option>
                          @endforeach
                      </select>
                    </div>

                      <div class="form-group col-xs-4">
                         <label>¿Desea Reportar Solamente? </label>

                          <select name="imprimirReportar" class="form-control" id="imprimirReportarC" >
                           <option disabled selected value="">Seleccione </option>
                           <option value="Si"> Si </option>
                           <option value="No"> No </option>     
                          </select>
                     </div>

                    <div class="form-group col-xs-4" id="selloM" style="display: none;">
                         <label>Sello de Prensa: </label>

                          <select name="selloPrensa" class="form-control" id="selloPrensaM">
                           <option disabled selected value="">Seleccione </option>
                           <option value="Si"> Si </option>
                           <option value="No"> No </option>     
                          </select>
                     </div>
                    
                      <div id="empleadoCarnet">
                      
                      
                      </div>
                      

                  </div>
                  <div class="modal-footer">
                    <button type="button" data-dismiss=modal class="btn btn-primary">Cerrar</button>
                    <input type="submit" name="registrar" value="Registrar" class="btn btn-success"/>
                  </div>
                </div>
              </div>
            </div>
          </td>
          <td>
            <a class="verCarnet" fotoCarnet="{{$carnet_empleados->cedula}}" href="#" data-toggle="modal" data-target="#vistaCarnet"> 
              <img src="assets/img/iconos/ver.png" class="imgmenuho" >
            </a>
            <div class="modal fade" id="vistaCarnet" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  
                  <div class="modal-body text-center">
                    <form method="POST" action="vistaPreviaEmpleado" >
                      <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                      <div id="vistaPreviaCarnet">
                      
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
    </tbody>
  </table>

  @if (isset($message))
    <div class='bg-warning' style='padding: 20px'>
        {{$message}}
    </div>
  @endif

</div>





<!-- Diseño Menu Provisionales Registro de Pasantes -->

<!--Scripts-->
  <script src="assets/js/jquery.js"></script>
  <script src="assets/js/Carnets/empleadoCarnet.js"></script>


@if(Session::has('flash_message_Rep'))

<script type="text/javascript">

$(document).ready(function(){
    window.open('disenoCarnet','_blank');
});

</script>

<div class="alert alert-success" ng-disabled id="alerta_pdf">
  <a href="disenoCarnet" title="Imprimir" target="_blank"> 
   <img src="assets/img/iconos/marcaje.png" style="height: 30px;" > 
</a>
{{Session::get('flash_message_Rep')}}
</div>

@endif





<style type="text/css">
  
.table{
  background-color: #fff;
}

#tabla_empleados{
  background-color:#3F88D3;
}
#alerta_pdf{
position: absolute;
top: 70;
right: 10px;
margin: 0 auto;
width: 510px;
height: 55px;
}

</style>
@endsection 