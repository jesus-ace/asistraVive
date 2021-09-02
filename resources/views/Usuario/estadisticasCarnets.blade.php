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
@if($aco_car == 'p_carnet')
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

<div class="col-lg-12" style="">
<div class="panel panel-default">
  <div class="panel-body"  style="background-color: #e5e8e8;">
    <b>ESTADÍSTICAS</b>
  </div>
</div>

 <div class="col-xs-4">
    <div class="well" style="background-color: #FF8000; border-color: #FACC2E;">
      <div style="margin-top: -10px;">
        <img class="vds" src="assets/img/iconos/porvencerse.svg" style="height: 10%; width: 50%;" >
        <b style="color: #ffff;">
          Por Vencer
          {{$porVencer}}
        </b>
        <p align="right" style="margin-bottom: -12px; margin-top: -20px;"> 
        <a class="verMas" href="#" data-toggle="modal" data-target="#vencerseCarnet" id="href" style="color: #ffff">Ver más</a> 
        <div class="modal fade" id="vencerseCarnet" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  
                  <div class="modal-body text-center">
                    <form method="POST" action="" >
                      <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                      <div class="panel panel-default" style="">
                        <div class="panel-heading" style="background-color: #e5e8e8;">Carnets por Vencerse <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button></div>

                      <div id="carnetPorVencerse">
                      
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer" id="panel1" style="display: none;">
                    <a href={{ URL::to('Por_vencerse') }} >Ver más</a>
                  </div>
                </div>
              </div>
            </div>
        </p>
      </div>
    </div>
  </div> 


<div class="col-xs-4">
    <div class="well" style="background-color: #04B45F; border-color: #086A87;">
      <div style="margin-top: -10px;">
        <img class="vds" src="assets/img/iconos/robado.svg" style="height: 10%; width: 50%;" >
        <b style="color: #ffff;">
          Robados
          {{$robados}}
        </b>
        <p align="right" style="margin-bottom: -12px; margin-top: -20px;"> 
        <a class="verMasR" href="#" data-toggle="modal" data-target="#RobadosCarnet" id="href" style="color: #ffff">Ver más</a> 
        <div class="modal fade" id="RobadosCarnet" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  
                  <div class="modal-body text-center">
                    <form method="POST" action="" >
                      <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                      <div class="panel panel-default" style="">
                        <div class="panel-heading" style="background-color: #e5e8e8;">Carnets Robados <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button></div>

                      <div id="carnetRobado">
                      
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer" id="panel2" style="display: none;">
                    <a href={{ URL::to('Carnets_Robados') }} >Ver más</a>
                  </div>
                </div>
              </div>
            </div>
        </p>
      </div>
    </div>
  </div>


  <div class="col-xs-4">
    <div class="well" style="background-color: #0489B1; border-color: #088A4B;">
      <div style="margin-top: -10px;">
        <img class="vds" src="assets/img/iconos/hurtado.svg" style="height: 10%; width: 50%;" >
        <b style="color: #ffff;">
          Hurtados
          {{$hurtados}}
        </b>
        <p align="right" style="margin-bottom: -12px; margin-top: -20px;"> 
        <a class="verMasH" href="#" data-toggle="modal" data-target="#HurtadosCarnet" id="href" style="color: #ffff">Ver más</a> 
        <div class="modal fade" id="HurtadosCarnet" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  
                  <div class="modal-body text-center">
                    <form method="POST" action="" >
                      <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                      <div class="panel panel-default" style="">
                        <div class="panel-heading" style="background-color: #e5e8e8;">Carnets Hurtados <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button></div>

                      <div id="carnetHurtado">
                      
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer" id="panel3" style="display: none;">
                    <a href={{ URL::to('Carnets_Hurtados') }} >Ver más</a>
                  </div>
                </div>
              </div>
            </div>
        </p>
      </div>
    </div>
  </div>



  <div class="col-xs-4">
    <div class="well" style="background-color: #00BFFF; border-color: #2ECCFA;">
      <div style="margin-top: -10px;">
        <img class="vds" src="assets/img/iconos/extraviado.svg" style="height: 10%; width: 50%; " >
        <b style="color: #ffff; font-size: 15px;">
          Extravíados
          {{$extraviados}}
        </b>
        <p align="right" style="margin-bottom: -12px; margin-top: -20px;"> 
        
         <a class="verMasE" href="#" data-toggle="modal" data-target="#ExtraviadosCarnet" id="href" style="color: #ffff">Ver más</a>
        <div class="modal fade" id="ExtraviadosCarnet" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  
                  <div class="modal-body text-center">
                    <form method="POST" action="" >
                      <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                      <div class="panel panel-default" style="">
                        <div class="panel-heading" style="background-color: #e5e8e8;">Carnets Extravíados <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button></div>

                      <div id="carnetExtraviado">
                      
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer" id="panel4" style="display: none;">
                    <a href={{ URL::to('Carnets_Extraviados') }} >Ver más</a>
                  </div>
                </div>
              </div>
            </div>
        </p>
      </div>
    </div>
  </div>

  <div class="col-xs-4">
    <div class="well" style="background-color: #FFFF00; border-color: #F4FA58;">
      <div style="margin-top: -10px;">
        <img class="vds" src="assets/img/iconos/vencidos.svg" style="height: 10%; width: 50%; " >
        <b style="color: black; font-size: 15px;">
          Vencidos
          {{$vencidos}}
        </b>
        <p align="right" style="margin-bottom: -12px; margin-top: -20px;"> 
        
         <a class="verMasV" href="#" data-toggle="modal" data-target="#VencidosCarnet" id="href" style="color: black;">Ver más</a> 
        <div class="modal fade" id="VencidosCarnet" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
               <div class="modal-dialog">
                <div class="modal-content">
                  
                  <div class="modal-body text-center">
                    <form method="POST" action="" >
                      <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                      <div class="panel panel-default" style="">
                        <div class="panel-heading" style="background-color: #e5e8e8;">Carnets Vencidos <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button></div>

                      <div id="carnetVencido">
                      
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer" id="panel5" style="display: none;">
                    <a href={{ URL::to('Carnets_Vencidos') }} >Ver más</a>
                  </div>
                </div>
              </div>
            </div>
        </p>
      </div>
    </div>
  </div>

  <div class="col-xs-4" style="/*float: right;*/">
    <div class="well" style="background-color: #DF000E; border-color: #A20000;">
      <div style="margin-top: -10px;">
        <img class="vds" src="assets/img/iconos/sincarnet.svg" style="height: 10%; width: 50%;" >
        <b style="color: #ffff;font-size: 15px;">
          Sin Carnets 
          {{$sinCarnet}}
        </b>
        <p align="right" style="margin-bottom: -12px; margin-top: -20px;"> 
          
          <a class="verMasSC" href="#" data-toggle="modal" data-target="#Sin_Carnet" id="href" style="color: #ffff">Ver más</a>
          <div class="modal fade" id="Sin_Carnet" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  
                  <div class="modal-body text-center">
                    <form method="POST" action="" >
                      <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                      <div class="panel panel-default" style="">
                        <div class="panel-heading" style="background-color: #e5e8e8;">Empleados sin Carnets <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button></div>

                      <div id="Sincarnet">
                      
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer" id="panel6" style="display: none;">
                    <a href={{ URL::to('new_carnet') }} >Ver más</a>
                  </div>
                </div>
              </div>
            </div>
        </p>
      </div>
    </div>
  </div>



  </div>






<div class="col-xs-12" style="margin-top: 20px; " >
  <div class="panel panel-default">
      <div class="panel-heading" style="background-color: #e5e8e8;">
        <h3 class="panel-title"><b>GRÁFICO DE BARRAS</b></h3>
      </div>
      <div class="panel-body" id="">
        <div class="responsive">
        <canvas class="col-xs-12" id="densityChart" width="1000" height="480" style="/*width: 870px;*/ margin-top: 30px;"></canvas>
      </div>
      </div>
  </div>
</div>






<style type="text/css">

.cantidad{
  font-size: 30px; 
  text-align: center; 
  margin-bottom: 0px;
}
.divisor{
  margin-top: 0; 
  margin-bottom: 0;
}
#href{
  float: right;
  margin-right: 10px;
  font-size: 15px;
}
#lupa {
  height: 20px;
}
</style>

<script src="assets/js/jquery.js"></script>
  <script src="assets/js/Chart.min.js"></script>
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