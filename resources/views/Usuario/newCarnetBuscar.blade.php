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

<ul class="nav nav-pills">
  <li role="presentation" class=""><a href={{ URL::to('new_carnet') }} >Carnets</a></li>
  <li role="presentation" id="provisionales"><a href={{ URL::to('carnet_provisionales') }} >Provisionales</a></li>
  <li role="presentation" id="change_diseno_carnet"><a href={{ URL::to('conf_diseno_carnet') }} >Conf Diseño</a></li>
  <li role="presentation" id="historico"><a href={{ URL::to('carnet_historico') }} >Histórico</a></li>
</ul>

<br>

<form class="form-inline pull-left" style="position: relative;"  role="busk" action="{{url('searchredirect')}}">
  <div class="form-group" style="">
    <label class="sr-only" for="exampleInputAmount">Buscar</label>
    <div class="input-group">
      <input type="text" class="form-control" id="search" name="busk" placeholder="Cédula...">
      <div class="input-group-addon"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></div>
    </div>
 <button type="submit" class="btn btn-success" name="empleadoBuscar">Buscar</button>
  </div>
</form>

<br><br><br>
<h3>Resultado de la búsqueda: {{$search}}</h3>

<!-- Diseño Menu Provisionales Registro de Pasantes -->

<div class="panel panel-default" style="">
  <!-- Default panel contents -->
  <div class="panel-heading" style="background-color: #e5e8e8;">Empleado Sin Carnet </div>
      <!-- Table -->
<table class="table table-striped table-hover table-bordered table-condensed" style="" >
  <thead style='background: #f2f3f4  repeat-x center top; font-size:14px;'>

      <tr>
        <th>Cédula</th>
        <th>Nombres</th>
        <th>Apellidos</th>
        <th>Cargo</th>
        <th>Editar</th>
      </tr>
    </thead>
    <tbody id="listadoEmpleados">

    <?php foreach ($empleadosBSC as $carnet_empleados):?>
      <tr>
        
        <td >{{ $carnet_empleados->cedula }}</td>
        <td >{{ $carnet_empleados->nombres }} </td>
        <td >{{ $carnet_empleados->apellidos }}</td> 
        <td >{{ $carnet_empleados->des_car }}</td>
        <td>
            <a class="newCarEmpleado" empleadoIdNew="{{$carnet_empleados->cedula}}" href="#" data-toggle="modal" data-target="#newCarnet"> 
              <img src="assets/img/iconos/editar.svg" class="imgmenuho" >
            </a>
            <div class="modal fade" id="newCarnet" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
              <div class="modal-dialog modal-lg"">
                <div class="modal-content">
                  <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3>Datos del Empleado</h3><br>
                  </div>
                  <div class="modal-body text-center">
                    <form method="POST" action="crearCarnet" enctype="multipart/form-data">
                      <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

                      <div class="form-group col-md-4">
                     <label>Sello de Prensa: </label>

                      <select name="selloPrensaN" class="form-control" id="selloPrensa">
                       <option disabled selected value="">Seleccione </option>
                       <option value="Si"> Si </option>
                       <option value="No"> No </option>     
                      </select>
                 </div>
                    
                      <div id="empleadosCarnetNew">
                      
                      
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
  <script src="assets/js/Carnets/empleadoCarnetNew.js"></script>
      <!-- Datepicker Files -->
    <link rel="stylesheet" href="{{asset('datePicker/css/bootstrap-datepicker3.css')}}">
    <link rel="stylesheet" href="{{asset('datePicker/css/bootstrap-standalone.css')}}">
    <script src="{{asset('datePicker/js/bootstrap-datepicker.js')}}"></script>
    <!-- Languaje -->
    <script src="{{asset('datePicker/locales/bootstrap-datepicker.es.min.js')}}"></script>


  <script>
    $('#empleadoFechaVec').datepicker({
        format: "dd/mm/yyyy",
        language: "es",
        autoclose: true
    });
</script>



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

/*---------------rombo foto---------------------*/

.ima {
  width: 150px; 
  height: 150px; 
  transform: rotate(-45deg); 
  position: relative; 
  top: -9px;
left: -7px;
}
.ima img {
  top: -20px;
left: -20px;
}
#timelord {
  width:400px; 
  height:200px; 
  position:relative; 
  margin:0 auto;
}
#rombo2 {
  width: 87px;
height: 85px;
  transform: rotate(45deg); 
  position: absolute; 
 /* outline:2px solid #000; 
  outline-offset:5px; */
  overflow: hidden; 
  z-index: 999; 
  top: 438px;
right: 437px;
}

</style>
@endsection 