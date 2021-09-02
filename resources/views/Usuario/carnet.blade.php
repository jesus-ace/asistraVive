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

@if($aco_cr == 'p_cnreporte')

  <input type="hidden" name="ventana" id="ventana" value="carnet">
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

<form class="form-inline pull-left" style="position: relative;"  role="busqueda" action="">
  <div class="form-group" style="">
    <label class="sr-only" for="exampleInputAmount">Buscar</label>
    <div class="input-group">
      <input type="text" class="form-control" id="searchCarnetEmp" name="busqueda" placeholder="Cédula...">
      <div class="input-group-addon"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></div>
    </div>
  <a class="buscaEmpConC" href="#" data-toggle="modal" data-target="#editaEmpl"> 
       <button type="button" class="btn" style="background-color:  #48c9b0; color: white;" name="empleadoBuscar"> Buscar</button>
  </a>
  
  </div>
</form>

<br><br><br>

<!-- Diseño Menu Provisionales Registro de Pasantes -->
<?php 


 ?>

<div class="panel panel-default" style="">
  <!-- Default panel contents -->
  <div class="panel-heading" style="background-color: #e5e8e8;">Listado De Empleados Con Carnet 

      <a href="PDF_Con-Carnet" id="" target="_blank" title="Generar archivo pdf" style="display: none;" class="reporte_cve"> 
        <img src="assets/img/iconos/pdf.svg" align="right" style="height: 35px; margin-top: -8px" title="Imprimir PDF">
      </a>

  </div>




      <!-- Table -->
<table class="table table-striped table-hover table-bordered table-condensed" style="" >
  <thead style='background: #f2f3f4  repeat-x center top; font-size:14px;'>

      <tr>
        <th style="text-align: center;">Cédula</th>
        <th style="text-align: center;">Nombres</th>
        <th style="text-align: center;">Apellidos</th>
        <th style="text-align: center;">Departamento</th>
        <th style="text-align: center;">Gestionar</th>
        <th style="text-align: center; font-size: 10px;">Reportar<br>Reimprimir</th>
      </tr>
    </thead>
    @if(count($empleados) > 0)
    <tbody id="listadoEmpleados">
    <?php foreach ($empleados as $carnet_empleados):?>
      <tr>
        
        <td >{{ $carnet_empleados->cedula }}</td>
        <td >{{ $carnet_empleados->nombres }} </td>
        <td >{{ $carnet_empleados->apellidos }}</td> 
        <td >{{ $carnet_empleados->des_uni }}</td>
        <td>
            <a class="editEmpleado" empleadoId="{{$carnet_empleados->cedula}}" href="#" data-toggle="modal" data-target="#editaEmpl" style="display: none;" id="editar_c"> 
              <img src="assets/img/iconos/editar.svg" class="imgmenuho" >
            </a>
            <div class="modal fade" id="editaEmpl" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  
                  <div class="modal-body text-center">
                    <form method="POST" action="updateEmpleado" enctype="multipart/form-data"
                     onsubmit="return confirm('¿Seguro que quiere imprimir este carnet?')" >
                      <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

                      <div class="panel panel-default" style="">

                          <div class="panel-heading" style="background-color: #e5e8e8;">Reportar e Imprimir Carnet <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button></div>
                          <div class="panel-body">
                          

                      <div class="form-group col-xs-4" id="motivoC">
                        <label>Motivo: </label>

                        <select name="carnet_usu_motivo" class="form-control" id="motivoReporte" required="">
                          <option disabled selected value="">Elige el Motivo</option>
                          @foreach($tipo_reportes as $category)
                          <option value="{{$category->ctr_id}}">{{$category->ctr_descripcion}}</option>
                          @endforeach
                      </select>
                    </div>

                    <!--  <div class="form-group col-xs-4" id="reportaSINO">
                         <label>¿Desea Reportar Solamente? </label>

                          <select name="imprimirReportar" class="form-control" id="imprimirReportarC" >
                           <option disabled selected value="">Seleccione </option>
                           <option value="Si"> Si </option>
                           <option value="No"> No </option>     
                          </select>
                     </div> -->

                    <div class="form-group col-xs-4" id="selloM" style="">
                         <label>Sello: </label>

                          <select name="selloPrensaC" class="form-control" id="selloPrensaM" required="">
                             <option selected value="">Seleccione </option>
                             <option value="Si"> Si </option>
                             <option value="No"> No </option>     
                          </select>
                     </div>

                     <div class="form-group col-xs-4" id="fecha_vec_e" >
                       <label for="date">Fecha de Vencimiento Nueva: </label>
                        <div class="input-group col-lg-12">
                          <input type="text" class="form-control datepicker" name="empleadoFechaVec" id="empleadoFechaVecim" value="" placeholder="dd/mm/yyyy" required="">
                              <div class="input-group-addon">
                                  <span class="glyphicon glyphicon-th"></span>
                              </div>
                        </div>
                    </div>

                    <div class="form-group col-xs-4" id="changeFoto">
                         <label>¿Desea Cambiar Fotografía? </label>

                          <select name="" class="form-control" id="cambioFoto" >
                           <option disabled selected value="">Seleccione </option>
                           <option value="Si"> Si </option>
                           <option value="No"> No </option>     
                          </select>
                     </div> 

                     <div class="col-xs-8 nuevaFoto" id="foto" style="display: none;">
                        <label>Foto</label>
                        <input type="file" class="form-control" name="image" id="image" accept="image/*" >
                    </div>
                    
                    

                      <div id="empleadoCarnet" class="form-group col-xs-12">  </div>


                      <br><br>

                     <!-- <a class="verCarnet" fotoCarnet="{{$carnet_empleados->cedula}}" href="#" data-toggle="modal" data-target="#vistaCarnet" style="display: none;" id="modalReportes"> 
                       Reportar-Reimprimir Solamente <img src="assets/img/iconos/editar.svg" class="imgmenuho" >
                      </a>-->
                      
                      
                      </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" data-dismiss=modal class="btn btn-warning">Cerrar</button>
                    <input type="submit" name="registrar" id="imprimirCarnet" value="Imprimir" class="btn" style="background-color:  #48c9b0; color: white;" />
                  </div>
                  
                  </form>
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

                      <div class="panel panel-default" style="">
                          <div class="panel-heading" style="background-color: #e5e8e8;"> Reportar-Reimprimir Carnet</div>
                        <div class="panel-body">

                      <div class="form-group col-xs-6" id="motivoCa">
                        <label>Motivo: </label>

                        <select name="carnet_motivo_report" class="form-control" id="motivoReportes">
                          <option disabled selected value="">Elige el Motivo</option>
                          @foreach($tipo_reportes2 as $category)
                          <option value="{{$category->ctr_id}}">{{$category->ctr_descripcion}}</option>
                          @endforeach
                      </select>
                    </div>

                    <div class="form-group col-xs-6" id="tipoCarnet" style="display: none;">
                        <label>Reimprimir: </label>

                        <select name="VistaCod" class="form-control" id="selectCodigo">
                             <option selected value="">Seleccione </option>
                             <option value="QR"> Código QR </option>
                             <option value="Barra"> Código de Barra </option> 
                             <option value="Completo">Carnet Completo</option>    
                        </select>

                    </div>

                    
                      <div id="vistaPreviaCarnet">
                      
                      </div>

                      <div id="getCarnet"> </div>

                      <div id="fondoCarnet"> </div>

                  </div>
                  <div class="modal-footer">
                    <button type="button" data-dismiss=modal class="btn btn-warning">Cerrar</button>
                    <input type="submit" name="reportar" id="reportarCarnet" value="Reportar" class="btn" style="background-color:  #48c9b0; color: white;"/>
                    <input type="submit" name="reportar" id="ImprimeCod" value="Imprimir" class="btn" style="background-color:  #48c9b0; color: white; display: none;"/>
                  </div>
                </div>
              </div>
              </form>
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
    <?php echo $empleados->render(); ?>
  </center>

</div>






<!-- Diseño Menu Provisionales Registro de Pasantes -->

<!--Scripts-->
  <script src="assets/js/jquery.js"></script>
  <script src="assets/js/Carnets/empleadoCarnet.js"></script>
  <script src="assets/js/Carnets/empleadoConCarnetBusca.js"></script>

    <link rel="stylesheet" type="text/css" href="assets/datePicker/css/bootstrap-datepicker3.css">
    <link rel="stylesheet" type="text/css" href="assets/datePicker/css/bootstrap-standalone.css">
    <!-- Languaje -->
    <script src="assets/datePicker/js/bootstrap-datepicker.js"></script>
    <script src="assets/datePicker/locales/bootstrap-datepicker.es.min.js"></script>

  <script>
    $('#empleadoFechaVecim').datepicker({
        format: "yyyy-mm-dd",
        language: "es",
        autoclose: true
    });
</script>

@if(Session::has('flash_error_Fecha'))
<div class="alert alert-danger" ng-disabled id="alerta_pdf">
{{Session::get('flash_error_Fecha')}}
  </div>
@endif

<!-- carnets vencidos  -->

@if(Session::has('flash_message_Venc'))

<script type="text/javascript">

$(document).ready(function(){
    window.open('disenoCarnet','_blank');
});

</script>

<div class="alert alert-success" ng-disabled id="alerta_pdf">
  <a href="disenoCarnet" title="Imprimir" target="_blank"> 
    <img src="assets/img/iconos/marcaje.png" style="height: 30px;" > 
</a>
{{Session::get('flash_message_Venc')}}
</div>

@endif

<!-- carnets reportados  -->
@if(Session::has('flash_message_Rep'))

<script type="text/javascript">

$(document).ready(function(){
    window.open('disenoCarnetNew','_blank');
});

</script>

<div class="alert alert-success" ng-disabled id="alerta_pdf">
  <a href="disenoCarnetNew" title="Imprimir" target="_blank"> 
    <img src="assets/img/iconos/marcaje.png" style="height: 30px;" > 
</a>
{{Session::get('flash_message_Rep')}}
</div>

@endif

<!-- carnets impresos nuevamente  -->

@if(Session::has('flash_message_Barra'))

<script type="text/javascript">

$(document).ready(function(){
    window.open('CodBarras','_blank');
});

</script>

<div class="alert alert-success" ng-disabled id="alerta_pdf">
  <a href="CodBarras" title="Imprimir" target="_blank"> 
    <img src="assets/img/iconos/marcaje.png" style="height: 30px;" > 
</a>
{{Session::get('flash_message_Barra')}}
</div>

@endif

@if(Session::has('flash_message_Carnet'))

<script type="text/javascript">

$(document).ready(function(){
    window.open('disenoCarnetNew','_blank');
});

</script>

<div class="alert alert-success" ng-disabled id="alerta_pdf">
  <a href="disenoCarnetNew" title="Imprimir" target="_blank"> 
    <img src="assets/img/iconos/marcaje.png" style="height: 30px;" > 
</a>
{{Session::get('flash_message_Carnet')}}
</div>

@endif

@if(Session::has('flash_message_QR'))

<script type="text/javascript">

$(document).ready(function(){
    window.open('CodQR','_blank');
});

</script>

<div class="alert alert-success" ng-disabled id="alerta_pdf">
  <a href="CodQR" title="Imprimir" target="_blank"> 
    <img src="assets/img/iconos/marcaje.png" style="height: 30px;" > 
</a>
{{Session::get('flash_message_QR')}}
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
height: 66px;
}

/*-------------------rombo foto-----------------------------*/


.ima {
  width: 215px; 
  height: 200px; 
  transform: rotate(-45deg); 
  position: relative; 
  top: -24px;
left: -28px;
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
  width: 102px;
height: 97px;
  transform: rotate(45deg); 
  position: absolute; 
 /* outline:2px solid #000; 
  outline-offset:5px; */
  overflow: hidden; 
  z-index: 999; 
  top: 274px;
right: 384px;
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