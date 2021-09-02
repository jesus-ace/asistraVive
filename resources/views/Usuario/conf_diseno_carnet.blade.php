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
@if($aco_ccd == 'p_ccof_diseño')
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

<!-- Diseño Vista Menu Conf Diseño-->

<!-- Diseño Carnets -->

    <div class="form-group col-xs-8 col-md-offset-2">
    <div class="panel panel-info">
      <div class="panel-heading"><a id="imgDelantera">Gestionar imagen delantera del carnet</a> <a style="float: right;" id="imgPosterior" >Gestionar imagen Posterior del carnet</a></div>
        <div class="panel-body" id="imagenDelantera">
          <form method="POST" action="gestionarCarnet" accept-charset="UTF-8" enctype="multipart/form-data" class="form-horizontal" id="nueva_imagen_carnet" files="true">
            
            <input type="hidden" name="_token" id="_token"  value="<?= csrf_token(); ?>"> 

            <div class="form-group">
              <label for="SelloCarnet" class="col-xs-2 control-label">Tipo</label>
              <div class="col-xs-4">
                <select name="tipoCarnet" class="form-control" id="SelloCarnet" >
                      <option disabled selected value="">Elige el tipo</option>
                      <option value="PRENSA"> SELLO DE PRENSA </option>
                      <option value="NORMAL"> FONDO NORMAL </option>  
                    </select>
              </div>
            </div>

            <div class="form-group" id="selloDePrensa" style="display: none;">
                <label for="DepartamentoCarnet" class="col-xs-2 control-label">Área</label>
                <div class="col-xs-4">

                    <select name="carnetArea" class="form-control" id="DepartamentoCarnet">
                      <option disabled selected value="">Elige el área</option>
                      <option value="COORDINADOR-PRENSA"> COORDINADOR-PRENSA </option>
                      <option value="PRESIDENCIA-PRENSA"> PRESIDENCIA-PRENSA </option>  
                      <option value="VPGT-PRENSA"> VPGT-PRENSA </option>  
                      <option value="VPGP-PRENSA"> VPGP-PRENSA </option> 
                      <option value="VPGI-PRENSA">VPGI-PRENSA</option> 
                      <option value="SEGURIDAD-PRENSA">SEGURIDAD-PRENSA</option> 
                    </select>
              </div>
              </div>

              <div class="form-group" id="AREANORMAL" style="display: none;">
                <label for="DepartamentoCarnet" class="col-xs-2 control-label">Área</label>
                <div class="col-xs-4">
                  <select name="carnetArea" class="form-control" id="DepartamentoCarnet">
                      <option disabled selected value="">Elige el área</option>
                      <option value="COORDINADOR"> COORDINADOR </option>
                      <option value="PRESIDENCIA"> PRESIDENCIA </option>  
                      <option value="VPGT"> VPGT </option>  
                      <option value="VPGP"> VPGP </option> 
                      <option value="VPGI">VPGI</option> 
                      <option value="SEGURIDAD">SEGURIDAD</option> 
                    </select>
                </div>
              </div>

            <div class="form-group">
              <label for="foto" class="col-xs-2 control-label">Foto</label>
              <div class="col-xs-4">
                <input type="file" class="form-control" name="image" id="foto" accept="image/*">
              </div>
            </div>

            
 @foreach ($fondo as $carnet_imagen)

              <div id='vista_previa' style='float: right;' class="form-group form-inline">
                  
                <div id='previo_carnet' style='border-radius:5px; margin:-110px 85px 0 20px; border:1px solid #999; height:323px; width:205px; display:block; line-height:11px; overflow: hidden;  background-size: 205px, 323px; background-repeat: no-repeat;'>
                    <div align='center' style='padding-top:1px; height: 40px;'>
                      <img id="preview" class="img-responsive img-thumbnail center-block" style="border-radius:5px; border:1px solid #999; height:323px; width:205px;" src="imagenes/{{ $carnet_imagen->fondo_carnet }}"> 
                    </div>
            <div id='foto_info_previa' style='padding:28px 0 0px 5px;'>
              <div id='foto_previa' style="width: 115px; overflow:hidden;">
                <img id='foto_carnet_previa' src='imagenes/usericonos.JPG'  width='115' height='87' />
              </div>
              <div id='info_previa' style='margin-top:3px;'>
                <span id='nombre_previo' style='font-size:13px; color:#000; display:block; font-weight: bold; line-height:15px; '>
                  NOMBRES
                </span>
                <span style='font-size:12px; color:#000; display:block; padding-top:3px; font-weight: bold; line-height:15px;'>
                  D.I.N&deg;:&nbsp;
                  <span id='din_previo' style='font-size:12px; color:#000;'>
                    
                  </span>
                </span>
                <span id='cargo_previo' style='font-size:9px; color:#000; display:block; padding-top:3px; line-height:15px;'>
                  CARGO
                </span>
                <span id='dpto_previo' style='font-size:9px; color:#000; display:block; padding-top:3px;line-height:15px;'>
                  COORDINACI&Oacute;N
                </span>
                <span id='area' style='font-size:9px; color:#000; display:block; padding-top:3px;  line-height:12px;'>
                  UNIDAD DE &Aacute;DSCRIPCION
                </span>
              </div>
            </div>
          </div>
        </div>

        @endforeach 

        

      </div>
        <div class="panel-footer" align="right" id="footerImgDelantera" >
            <button type="submit" class="btn" name="saveFondoCarnet" style="background-color:  #48c9b0; color: white;">Guardar</button>

        </div> 

        </form>






        <div class="panel-body" id="imagenPosteriorCarnet" style="display: none;">
           <form method="POST" action="encabezadoSave" accept-charset="UTF-8" enctype="multipart/form-data" class="form-horizontal" id="postCarnetsE" files="true">


            
            <input type="hidden" name="_token" id="token"  value="<?= csrf_token(); ?>"> 

            <div class="form-group">
              <label for="tipoDiseno" class="col-xs-2 control-label">Tipo</label>
              <div class="col-xs-4">
                <select name="tipoCarnet" class="form-control" id="tipo" >
                      <option disabled selected value="">Elige el tipo</option>
                      <option value="FIRMA"> Firma </option>
                      <option value="Encabezado"> Encabezado </option> 
                      <option value="Empresa"> Empresa </option> 
                      <option value="Pagina"> Página </option> 
                      <option value="Agradecimiento"> Agradecimiento </option> 
                      <option value="telefono"> Teléfono </option>
                      <option value="codigo"> Código </option>    
                    </select>
              </div>
            </div>

             <div class="form-group" id="espacio" >
              <label for="foto" class="col-xs-2 control-label"></label>
              <div class="col-xs-4">
                <input type="text" name="" value="" hidden>
              </div>
            </div>

            <div class="form-group" id="presidenteNombre" style="display: none;">
              <label for="foto" class="col-xs-2 control-label">Presidente</label>
              <div class="col-xs-4">
                <input type="text" name="nombreFoto" id="fotoNombre" value="FIRMA" hidden>
                <input type="text" name="presidente" id="president" class="form-control">
              </div>
            </div>

            <div class="form-group" id="fotoFirmaPresi" style="display: none;">
              <label for="foto" class="col-xs-2 control-label">Foto</label>
              <div class="col-xs-4">
                <input type="file" class="form-control" name="image" id="fotoFirma" accept="image/*">
              </div>
            </div>

            <div class="form-group" id="codigoSeleccion" style="display: none;">
              <label for="tipoCodigo" class="col-xs-2 control-label">Códigos</label>
              <div class="col-xs-4">
                <select name="codigoCarnet" class="form-control" id="tipoCodigo" >
                      <option disabled selected value="">Seleccione</option>
                      <option value="1"> Código de Barras </option> 
                      <option value="2"> Código QR </option>
                      <option value="3"> Código de Barras y QR </option>  
                    </select>
              </div>
            </div>

          <div class="form-group" id="encabezadoCar" style="display: none;">
              <label for="foto" class="col-xs-2 control-label">Encabezado</label>
              <div class="col-xs-4">
                <input type="text" name="encabezado" class="form-control" id="enkbezado">        
              </div>
            </div>

          <div class="form-group" id="empresaCar" style="display: none;">
            <label for="foto" class="col-xs-2 control-label">Empresa</label>
            <div class="col-xs-4">
              <input type="text" name="empresa" id="empresas" class="form-control">        
            </div>
          </div>

          <div class="form-group" id="paginaCar" style="display: none;">
            <label for="foto" class="col-xs-2 control-label">Página</label>
            <div class="col-xs-4">
              <input type="text" name="pagina" id="paginaC" class="form-control">        
            </div>
          </div>

          <div class="form-group" id="agradeceCar" style="display: none;">
            <label for="foto" class="col-xs-2 control-label">Agradecimiento</label>
            <div class="col-xs-4">
              <textarea class="form-control" name="agradecimient" id="agradecimiento" rows="2"></textarea>        
            </div>
          </div>

          <div class="form-group" id="desTelef" style="display: none;">
            <label for="foto" class="col-xs-2 control-label">Descripción</label>
            <div class="col-xs-4">
              <input type="text" name="tlfDescripcion" id="telfDescrip" class="form-control">        
            </div>
          </div>

          <div class="form-group" id="telefonoCar" style="display: none;">
            <label for="foto" class="col-xs-2 control-label">Teléfono</label>
            <div class="col-xs-4">
              <input type="text" name="telefono" id="telephone" class="form-control">        
            </div>
          </div>

            
 @foreach ($encabezado as $encabezados)

 <?php $encabezado = $encabezados->encabezado; ?>

  @foreach ($empresa as $empresas)

<?php $empresa = $empresas->empresa; ?>

  @foreach ($pagina as $paginas)

<?php $pagina = $paginas->pagina; ?>

@foreach ($agradecimiento as $agradecimientos)

<?php $agradecimiento = $agradecimientos->agradecimiento; ?>

@foreach ($telefono as $telefonos)

<?php $telefono = $telefonos->telefono; ?>

@foreach ($descripcion as $descripcion)

<?php $descripcion = $descripcion->descripcion; ?>

         <div id='vista_previa' style='float: right;' class="form-group form-inline">                
          <div id='previo_carnet' style='border-radius:5px; margin:-85px 60px 0 20px; border:1px solid #999; height:375px; width:230px; display:block; line-height:11px; overflow: hidden;  background-size: 205px, 323px; background-repeat: no-repeat;'>
<div id="textos" style="text-align: center; line-height: 1;">
          <div id="encabezado">{{ $encabezado }} </div>
           <div> {{ $empresa }} </div>
          <div> {{ $pagina }} </div>
</div>
          <img id="previewFirma" class="img-responsive img-thumbnail center-block" style="border-radius:5px; border:0px solid #999; height:100px; width:230px; text-align: center;" src="imagenes/firma_presidente2.jpg"> 

          <p class="texto" align="center">PRESIDENTE</p>
          <div id="textos" style="height: 130px; width: 230px; text-align: center; font-size: 14px; line-height: 1; ">

{{$agradecimiento}} {{$descripcion}} {{$telefono}}

<br>Vence 00-00-0000

</div>

<div class="barra" >
<img id="codigo" class="img-responsive img-thumbnail center-block" style="border-radius:5px; border:1px solid #999; height:35px; width:155px; text-align: center;" src="imagenes/codigoBarra.jpg"> 
</div>
<div class="qr" style="display: none;">
<img id="codigo" class="img-responsive img-thumbnail center-block" style="border-radius:5px; border:1px solid #999; height:50px; width:60px; text-align: center;" src="imagenes/codigoQR.jpeg"> 
</div>                    
          </div>
        </div>

        @endforeach 

        @endforeach 

        @endforeach 

        @endforeach 
        
        @endforeach 
        
        @endforeach 
        

      </div>
      <div id="msj-success" class="alert alert-success alert-dismissible" role="alert" style="display: none">
            <strong>Registro Agregado Correctamente.</strong>
        </div>
        <div id="msj-danger" class="alert alert-danger alert-dismissible" role="alert" style="display: none">
            <strong id="msj">ERROR</strong>
        </div>
        <div class="panel-footer" align="right" style="display: none;" id="footerImgPosterior">
              <button type="button" class="btn" name="" style="background-color:  #48c9b0; color: white;" id="registrose">Guardar</button>
        </div> 

        </form>


<!--    ////////////////////////////////////////////////////////////////////////////////////////          -->



<!--    ////////////////////////////////////////////////////////////////////////////////////////          -->














<!--Scripts-->
  <script src="assets/js/jquery.js"></script>
  <script src="assets/js/Carnets/DisenoCarnet.js"></script>


@else
<center>
  <img src="imagenes/denegado.png" style="height: 500px">
  <br>
  <b style="font-size: 50px;text-shadow: 1px 2px 5px red">ACCESO DENEGADO</b><br>
  <b style="margin-bottom: 15px;">Usted no tiene permiso para ingresar a esta pantalla</b>
</center>
@endif
@endsection 

