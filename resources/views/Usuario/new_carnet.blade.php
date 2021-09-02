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
@if($aco_cn == 'p_cnempleado')

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

<form class="form-inline pull-left" style="position: relative;"  role="buscaEm" action="">
  <div class="form-group" style="">
    <label class="sr-only" for="exampleInputAmount">Buscar</label>
    <div class="input-group">
      <input type="text" class="form-control" id="busCarnetEmp" name="buscaEm" placeholder="Cédula...">
      <div class="input-group-addon"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></div>
    </div>
  <a class="buscaEmpSinC" href="#" data-toggle="modal" data-target="#newCarnet"> 
       <button type="button" class="btn" style="background-color:  #48c9b0; color: white;" name="empleadoBusc"> Buscar</button>
  </a>
  
  </div>
</form>

<br><br><br>

<!-- Diseño Menu Provisionales Registro de Pasantes -->

<div class="panel panel-default" style="">
  <!-- Default panel contents -->
  <div class="panel-heading" style="background-color: #e5e8e8;">Lista de empleados sin carnet 
      <a href="#">
        <img src="assets/img/iconos/add.svg" align="right"  style="height: 3%" onclick="$('#headEmpd').toggle(500);$('#listadoEmpleadoss').toggle(500);$('#nohayempSin').toggle(500);">
      </a>
      <a href="PDF_Sin-Carnet" id="" target="_blank" title="Generar archivo pdf" style="display: none;" class="reporte_cve"> 
        <img src="assets/img/iconos/pdf.svg" align="right" style="height: 35px; margin-top: -8px" title="Imprimir PDF">
      </a>

  </div>




      <!-- Table -->
<table class="table table-striped table-hover table-bordered table-condensed" style="" >
  <thead id="headEmpd" style='background: #f2f3f4  repeat-x center top; font-size:14px; display: none;'>

      <tr>
        <th>Cédula</th>
        <th>Nombres</th>
        <th>Apellidos</th>
        <th>Cargo</th>
        <th>Asignar</th>
      </tr>
    </thead>
    @if(count($empleados) > 0)
    <tbody id="listadoEmpleadoss" style="display: none;">
    <?php foreach ($empleados as $carnet_empleados):?>
      <tr>
        
        <td >{{ $carnet_empleados->cedula }}</td>
        <td >{{ $carnet_empleados->nombres }} </td>
        <td >{{ $carnet_empleados->apellidos }}</td> 
        <td >{{ $carnet_empleados->des_car }}</td>
        <td>
            <a class="newCarEmpleado" empleadoIdNew="{{$carnet_empleados->cedula}}" href="#" data-toggle="modal" data-target="#newCarnet" style="display: none;"> 
              <img src="assets/img/iconos/editar.svg" class="imgmenuho" >
            </a>
            <div class="modal fade" id="newCarnet" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  
                  <div class="modal-body text-center">

                      <input type="hidden" name="_token" id="_token"  value="<?= csrf_token(); ?>"> 

                      <div class="panel panel-default" style="">

                          <div class="panel-heading" style="background-color: #e5e8e8;">Datos del Empleado <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button></div>
                          <div class="panel-body">
                            
                    <form method="POST" action="crearCarnet" enctype="multipart/form-data" 
                    onsubmit="return confirm('¿Seguro que quiere imprimir este carnet?')" >
                            <div class="form-group col-xs-4" id="selloN">
                               <label>Sello de Prensa: </label>
                                  <select name="selloPrensaN" class="form-control" id="selloPrensa" >
                                    <option disabled selected value="No">Seleccione </option>
                                    <option value="Si"> Si </option>
                                    <option value="No"> No </option>     
                                </select>
                            </div>

                            <div class="col-xs-4" id="foto">
                              <label>Foto</label>
                                <input type="file" class="form-control" name="image" id="image" accept="image/*" >
                            </div>

                           <div class="form-group col-xs-4" id="fecha">
                               <label for="date">Fecha de Vencimiento: </label>
                                <div class="input-group col-lg-12">
                                  <input type="text" class="form-control datepicker" name="empleadoFechaVecN" id="empleadoFechaVecs" placeholder="dd-mm-yyyy" required="">
                                      <div class="input-group-addon">
                                          <span class="glyphicon glyphicon-th"></span>
                                      </div>
                                </div>
                            </div>                    

                          <div id="empleadosCarnetNew"> </div>                   
                      
                   </div>
                  </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" data-dismiss=modal class="btn btn-warning">Cerrar</button>
                    <input type="submit" name="registrar" value="Imprimir" class="btn" style="background-color:  #48c9b0; color: white;"/>
                  </div>
                </form>
              </div>
            </div>
          </td>
          
      </tr>
      <?php endforeach ?> 
      @else
      <td colspan="12" align="center" id="nohayempSin" style="display: none;">
      <div class="alert alert-warning">
          Disculpe, no se encontraron resultados...
     </div>
        </td>              
    @endif
    </tbody>
  </table>
</div>

<form class="form-inline pull-left" style="position: relative;"  role="buscaEmp" action="">
  <div class="form-group" style="">
    <label class="sr-only" for="exampleInputAmount">Buscar</label>
    <div class="input-group">
      <input type="text" class="form-control" id="busCarnetEmpPro" name="buscaEmp" placeholder="Cédula...">
      <div class="input-group-addon"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></div>
    </div>
  <a class="buscaEmpCProv" href="#" data-toggle="modal" data-target="#editaProvisional"> 
       <button type="button" class="btn" style="background-color:  #48c9b0; color: white;" name="empleadoBuscP"> Buscar</button>
  </a>
  
  </div>
</form>

<br><br><br>

<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading" style="background-color: #e5e8e8;">
    Lista de empleados con carnet provisional
    <a href="#">
      <img src="assets/img/iconos/add.svg" align="right"  style="height: 3%" onclick="$('#headConprov').toggle(500);$('#bodyConprov').toggle(500);$('#nohayEmpConprov').toggle(500);">
    </a>
  </div>

  <!-- Table -->
  <table  class="table table-striped table-hover table-bordered table-condensed">
    <thead id="headConprov" style='background: #f2f3f4  repeat-x center top; font-size:14px; display: none;'>
      <tr>
        <th>#</th>
        <th>Cédula</th>
        <th>Nombres</th>
        <th>Apellidos</th>
        <th>Cargo</th>
        <th>Gestionar</th>
      </tr>
    </thead>
    @if(count($empleadoss) > 0)
    <tbody id="bodyConprov" style="display: none;">
      <?php $n = 0;  ?>
      <?php foreach ($empleadoss as $emp): $n++?>
        <tr>
          <td> {{$n}} </td>
          <td> {{$emp->cedula}} </td>
          <td> {{$emp->nombres}} </td>
          <td> {{$emp->apellidos}} </td>
          <td> {{ $emp->des_car }} </td>
          <td>  
            <a class="editProvisional"  provisionalId="{{$emp->cedula}}" href="#" data-toggle="modal" data-target="#editaProvisional" onclick="editProvisional({{$emp->cedula}})" style="display: none;"> 
              <img src="assets/img/iconos/editar.svg" class="imgmenuho" >
            </a>
            <div class="modal fade" id="editaProvisional" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-body text-center">
                    <input type="hidden" name="_token" id="_token"  value="<?= csrf_token(); ?>"> 
                    <div class="panel panel-default" style="">
                      <div class="panel-heading" style="background-color: #e5e8e8;">
                        Datos del Empleado
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                      </div>
                      <div class="panel-body">
                        <div class="form-group col-xs-12" id="motPasante">
                          <label>Motivo: </label>

                          <select name="provisionalMotivo" class="form-control" id="motivoProvisional"  >
                            <option disabled selected value="">Elige el Motivo</option>
                            @foreach($motivos as $tipo_reportes)
                            <option value="{{$tipo_reportes->ctr_id}}">{{$tipo_reportes->ctr_descripcion}}</option>
                            @endforeach                              
                            <option value="1">Empresarial</option>
                          </select>
                        </div>  

                       

                        <div id="provisional">
                        
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </td>
        </tr>
      <?php endforeach ?>
    </tbody>
  @else             
    <td colspan="12" align="center" id="nohayEmpConprov" style="display: none;">
      <div class="alert alert-danger">
        Disculpe, no se encontraron resultados...
      </div>
    </td> 
  @endif
  </table>  
  
  <input type="hidden" name="_token" id="_token"  value="<?= csrf_token(); ?>"> 
</div>




<center>
    <?php echo $empleados->render(); ?>
  </center>

</div>





<!-- Diseño Menu Provisionales Registro de Pasantes -->

<!--Scripts-->
  <script src="assets/js/jquery.js"></script>
  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/js/Carnets/empleadoCarnetNew.js"></script>
  <script src="assets/js/Carnets/empleadoSinCarnetBusca.js"></script>


    <link rel="stylesheet" type="text/css" href="assets/datePicker/css/bootstrap-datepicker3.css">
    <link rel="stylesheet" type="text/css" href="assets/datePicker/css/bootstrap-standalone.css">
    <!-- Languaje -->
    <script src="assets/datePicker/js/bootstrap-datepicker.js"></script>
    <script src="assets/datePicker/locales/bootstrap-datepicker.es.min.js"></script>

  <script>
    $('#empleadoFechaVecs').datepicker({
        format: "yyyy-mm-dd",
        language: "es",
        autoclose: true
    });
</script>



@if(Session::has('flash_message'))

<script type="text/javascript">

$(document).ready(function(){
    window.open('disenoCarnetNew','_blank');
});

</script>

<div class="alert alert-success" ng-disabled id="alerta_pdf">
  <a href="disenoCarnetNew" title="Imprimir" target="_blank"> 
    <img src="assets/img/iconos/marcaje.png" style="height: 30px;" > 
</a>
{{Session::get('flash_message')}}
</div>

@endif

    
@if(Session::has('flash_error_Fecha'))
<div class="alert alert-danger" ng-disabled id="alerta_pdf">
{{Session::get('flash_error_Fecha')}}
  </div>
@endif

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
#alerta_pdf{
position: absolute;
top: 70;
right: 10px;
margin: 0 auto;
width: 510px;
height: 55px;
}

/*-------------------rombo foto-----------------------------*/


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
@else
<center>
  <img src="imagenes/denegado.png" style="height: 500px">
  <br>
  <b style="font-size: 50px;text-shadow: 1px 2px 5px red">ACCESO DENEGADO</b><br>
  <b style="margin-bottom: 15px;">Usted no tiene permiso para ingresar a esta pantalla</b>
</center>
@endif
@endsection 