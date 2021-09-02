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
@if($aco_cpv == 'p_cvencer')
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

<form class="form-inline pull-left" role="busVencerse" action="">
  <div class="form-group" style="">
    <label class="sr-only" for="exampleInputAmount">Buscar</label>
    <div class="input-group">
      <input type="text" class="form-control" id="buscaVencerse" name="busVencerse" placeholder="Cédula...">
      <div class="input-group-addon"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></div>
    </div>
  <a class="searchPorVencerse" href="#" data-toggle="modal" data-target="#editaEmpl"> 
       <button type="button" class="btn" style="background-color:  #48c9b0; color: white;" name=""> Buscar</button>
  </a>
  
  </div>
</form>


  <form method="post" action="buscaFechasPorVencerse" name="" >
                  {{ csrf_field() }}
  <div class="form-group col-xs-4" style="">
    <label class="sr-only" for="exampleInputAmount">Desde</label>
    <div class="input-group">
      <!--<input type="text" class="form-control" name="fechadesde" id="desde" placeholder="Fecha...Desde" required="">-->
      <input type="text" class="form-control" name="fechadesdeV" id="desde" placeholder="Fecha...Desde" >
      <div class="input-group-addon"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></div>
    </div>
  </div>

    <div class="form-group col-xs-4" style="">
    <label class="sr-only" for="exampleInputAmount">Hasta</label>
    <div class="input-group">
    <input type="text" class="form-control" name="fechahastaV" id="hasta" placeholder="Fecha...Hasta">
     <!-- <input type="text" class="form-control" name="fechahasta" id="hasta" placeholder="Fecha...Hasta" required="">-->
      <div class="input-group-addon"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></div>
    </div>
  </div>
<div class="">
  <button name="buscar" class="btn" value="Buscar" style="background-color: #48c9b0; color:white;">
    <b>PDF</b>
  </button>
</div>  
</form>


<br>

<div class="panel panel-default" style="">
  <!-- Default panel contents -->
  <div class="panel-heading" style="background-color: #e5e8e8;">Carnets por vencerse <!-- Boton de la modal -->
    <a href="PDF_Por_Vencerse" id="" target="_blank" title="Generar archivo pdf" style="display: none;" class="reporte_pv"> 
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
        <th>Editar</th>        
      </tr>
    </thead>

    @if(count($empleados) > 0)
    <tbody id="listCarnetPorVencerse">
    <?php foreach ($empleados as $carnet_empleados):?>
      <tr>
        
        <td >{{ $carnet_empleados->cedula }}</td>
        <td >{{ $carnet_empleados->nombres }} </td>
        <td >{{ $carnet_empleados->apellidos }}</td> 
        <td >{{ $carnet_empleados->des_uni }}</td>
        <td>
            <a class="editEmpleadoPV" empleadoIdPV="{{$carnet_empleados->cedula}}" href="#" data-toggle="modal" data-target="#editaEmpl" style="display: none;"> 
              <img src="assets/img/iconos/editar.svg" class="imgmenuho" >
            </a>
            <div class="modal fade" id="editaEmpl" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  
                  <div class="modal-body text-center">
                    <form method="POST" action="EmpleadoPorVencerse" enctype="multipart/form-data">
                      <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

                      <div class="panel panel-default" style="">

                          <div class="panel-heading" style="background-color: #e5e8e8;">Renovar Fecha de Vencimiento <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button></div>
                          <div class="panel-body">

                      <div class="form-group col-xs-4" id="motivoC">
                        <label>Motivo: </label>

                        <select name="carnet_usu_motivo" class="form-control" id="motivoReporte">
                          <option selected value="4">Elige el Motivo</option>
                          @foreach($tipo_reportes as $category)
                          <option value="{{$category->ctr_id}}">{{$category->ctr_descripcion}}</option>
                          @endforeach
                      </select>
                    </div>

                    <div class="form-group col-xs-4" id="selloM" >
                         <label>Sello de Prensa: </label>

                          <select name="selloPrensa" class="form-control" id="selloPrensaM">
                             <option selected value="No">Seleccione </option>
                             <option value="Si"> Si </option>
                             <option value="No"> No </option>     
                          </select>
                    </div>

                    <div class="form-group col-xs-4" id="fecha_vec_e">
                       <label for="date">Fecha de Vencimiento Nueva: </label>
                        <div class="input-group col-lg-12">
                          <input type="date" class="form-control datepicker" name="empleadoFechaVec" id="empleadoFechaVecim" value="" placeholder="dd/mm/yyyy" required="">
                              <div class="input-group-addon">
                                  <span class="glyphicon glyphicon-th"></span>
                              </div>
                        </div>
                    </div>
                          
                      <div id="empleadoCarnet">
                      
                      </div>

                  </div>

                  </div>

                  </div>

                  <div class="modal-footer">
                    <button type="button" data-dismiss=modal class="btn btn-warning">Cerrar</button>
                    <input type="submit" name="registrar" value="Imprimir" class="btn" style="background-color:  #48c9b0; color: white;" id="porVencerBtn" />
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
  </div>

  <center>
  <?php echo $empleados->render(); ?>
  </center>

  <script src="assets/js/jquery.js"></script>
  <script src="assets/js/Carnets/estadisticasCarnets.js"></script>

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

@if(Session::has('flash_message'))
<div class="alert alert-danger" ng-disabled id="alerta_pdf">
{{Session::get('flash_message')}}
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