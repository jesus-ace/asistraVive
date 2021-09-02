<html>
    <head>
        <title>Carnet</title>
    </head>
<body>

@foreach ($empleados as $empleado)

<?php

$cod_area = $empleado->cod_uni;
$cod_cargo = $empleado->cod_car;
$cedula = $empleado->cedula;



$nombres = $empleado->nombres; 
$nombre = explode(" ",$nombres);
$elnombre = $nombre[0];

$apellido = $empleado->apellidos;

$apellidos = explode(" ",$apellido);
        
$el_apellido = $apellidos[0];
  if(($el_apellido == "DE") or ($el_apellido == "DEL")){
    $el_apellido = $apellidos[0]." ".$apellidos[1];
    if($el_apellido == "DE LA"){
      $el_apellido = $apellidos[0]." ".$apellidos[1]." ".$apellidos[2];
    }
  }
  
  if($el_apellido == 'SANTA'){
    $el_apellido = $apellidos[0]." ".$apellidos[1];
  }

  $totalNombres = count($nombre);
  $totalApellidos = count($apellidos);

  if ($totalNombres == 2) {
    $nombre2 = $nombre[1];
    $inicialN = $nombre2[0].".
    ";
  }else{
     $inicialN = "";
  }

  if ($totalApellidos == 2) {
    $apellido2 = $apellidos[1];
    $inicialA = $apellido2[0].".";
  }else{
    $inicialA = "";
  }

foreach ($datosC as $carnet_empleados){

$sello = $carnet_empleados->sello;


/*if(($cod_cargo == '0000000407') or ($cod_cargo == '0000000431') or ($cod_cargo == '0000000431') or ($cod_cargo == '0000000232') or ($cod_cargo == '0000000234') or ($cod_cargo == '0000000098') or ($cod_cargo == '0000000357') or ($cod_cargo == '0000000402') or ($cod_cargo == '0000000412') or ($cod_cargo == '0000000104') or ($cod_cargo == '0000000331') or ($cod_cargo == '000000086') or ($cod_cargo == '0000000089') or ($cod_cargo == '000000041') or ($cod_cargo == '0000000414') or ($cod_cargo == '0000000225') or ($cod_cargo == '0000000436') or ($cod_cargo == '0000000097') or ($cod_cargo == '0000000092') or ($cod_cargo == '0000000264') or ($cod_cargo == '0000000270') or ($cod_cargo == '0000000386')){ //Cordinador Tec - Ing -Transporte - Prgoramacion - Prducc I -Serv a la P - Andes / Andes (E) - Oriente - Planificacion - Finanzas - Admin y cont - Serv G - Talento humano - Seguridad - Mercadeo - Img y Prod - Comunicacion P -Centro Occ - Occidente - Orinoco - Llanos- Despacho
        $fondo = ($sello == 'Si') ? "imagenes/COORDINADOR-PRENSA.jpg" : "imagenes/COORDINADOR.jpg";

      }elseif (($cod_cargo == '0000000200') or ($cod_cargo == '0000000399') or ($cod_cargo == '0000000391')){//Vicepresidentes {
      $fondo = ($sello == 'Si') ? "imagenes/PRESIDENCIA-PRENSA.jpg" : "imagenes/PRESIDENCIA.jpg"; 
    
    }else{*/

      if(($cod_area == '0000-00-04-01-01') or ($cod_area == '0000-00-04-01-00') or ($cod_area == '0000-00-04-04-00') or ($cod_area == '0000-00-04-04-01') or ($cod_area == '0000-00-04-02-00') or ($cod_area == '0000-00-05-01-00') or ($cod_area == '0000-00-04-05-00') or ($cod_area == '0000-00-03-00-01') or ($cod_area == '0000-00-03-00-02') or ($cod_area == '0000-00-03-00-03') or ($cod_area == '0000-00-03-00-04') or ($cod_area == '0000-00-03-00-05') or ($cod_area == '0000-00-03-00-06') or ($cod_area == '0000-00-04-00-00') or ($cod_area == '0000-00-05-00-00') or ($cod_area == '0000-00-05-02-00')
        ){ 
//VPGT - Tec - Ing -servicios a la produccion - operaciones tecnicas -gerencia de comunicaciones - sedes
          $fondo = ($sello == 'Si') ? "imagenes/VPGT-PRENSA.jpg" : "imagenes/VPGT.jpg";

      }elseif(($cod_area == '0000-00-07-00-00') or ($cod_area == '0000-00-03-02-00') or ($cod_area == '0000-00-03-02-01') or ($cod_area == '0000-00-03-01-00') or ($cod_area == '0000-00-03-01-01') or ($cod_area == '0000-00-03-05-00') or ($cod_area == '0000-00-03-04-00') or ($cod_area == '0000-00-03-00-00') or ($cod_area == '0000-00-06-00-00')){//VPGP - Programacion - Produccion I - Img y Prodc - produccion integral - Comunicacion Popular - 123Tv  - Ficcion y animacion
      $fondo = ($sello == 'Si') ? "imagenes/VPGP-PRENSA.jpg" : "imagenes/VPGP.jpg";

    
      }elseif(($cod_area == '0000-00-02-01-00') or ($cod_area == '0000-00-02-02-00') or ($cod_area == '0000-00-02-05-00') or ($cod_area == '0000-00-02-03-00') or ($cod_area == '0000-00-02-04-00') or ($cod_area == '0000-00-01-03-01') or ($cod_area == '0000-00-01-03-00') or ($cod_area == '0000-00-02-00-00')){ //VPGI -planificacion - Admin y Cont - Finanzas - Servicios G -Mercadeo y Asuntos P - Talento Humano - Talento Humanos Pensionados y Jubilados
          $fondo = ($sello == 'Si') ? "imagenes/VPGI-PRENSA.jpg" : "imagenes/VPGI.jpg";

    
      }/*elseif($cod_area == '0000-00-01-06-00'){ // Seguridad
          $fondo = ($sello == 'Si') ? "imagenes/SEGURIDAD-PRENSA.jpg" : "imagenes/SEGURIDAD.jpg";

    
      }*/elseif(($cod_area == '0000-00-01-00-00') or ($cod_area == '0000-00-01-01-00') or ($cod_area == '0000-00-01-02-00') or ($cod_area == '0000-00-01-02-01') or ($cod_area == '0000-00-01-04-00') or ($cod_area == '0000-00-01-05-00') or ($cod_area == '0000-00-01-06-00') or ($cod_area == '0000-00-04-03-00') or ($cod_area == '0000-00-01-06-00') or ($cod_area == '0000-00-30-00-11')){ //Presidencia - Despacho - Atencio al C - Consultoria J - Aduditoria I
          $fondo = ($sello == 'Si') ? "imagenes/PRESIDENCIA-PRENSA.jpg" : "imagenes/PRESIDENCIA.jpg";


      }
//  }

?>



<div class="general">
<div class="fondo">
  
<img id="fondo_carnet" class="img-responsive img-thumbnail center-block" style="" src="{{$fondo}}"> 

<?php 

$cedula = $empleado->cedula;

$image_path = "imagenes2/".$cedula.".jpg" ;  // Dirección de la imagen
     
$imagen = getimagesize($image_path);    //Sacamos la información
$ancho = $imagen[0];              //Ancho
$alto = $imagen[1];               //Alto
     

if(File::exists(public_path("imagenes2/".$cedula.".jpg"))) { 

  if (($ancho <= 651) and ($alto <= 500)) { ?>

        <div id="rombo2">
                  <div class="ima" style="">
                    <img id='foto_carnet_previa' src="{{$image_path}}" style="vertical-align:middle;" />
                  </div>
              </div>

  <?php 

  }elseif (($ancho >= 650) and ($alto >= 500)) { ?>

      <div id="rombo2">
          <div class="ima" style="">
            <img id='foto_carnet_previa' src="{{$image_path}}" style="vertical-align:middle;" />
          </div>
      </div>
  

    <?php           

      }elseif (($ancho >= 650) and ($alto >= 900)) { ?>
        
        <div id="rombo3">
                  <div class="ima2" style="">
                    <img id='foto_carnet_previa' src="{{$image_path}}" style="vertical-align:middle;" />
                  </div>
              </div>

             
 <?php           

      }elseif (($ancho >= 600) and ($alto >= 700)) { ?>
        
        <div id="rombo3">
                  <div class="ima2" style="">
                    <img id='foto_carnet_previa' src="{{$image_path}}" style="vertical-align:middle;" />
                  </div>
              </div>

             

              <?php
     
      }

?>

<!--div id="rombo2">
    <div class="ima">
      <img id='foto_carnet_previa' src="{{$image_path}}" style="vertical-align:middle;" />
    </div>
  </div-->





<?php

}else{

    ?>

      <div id="rombo2">
        <div class="ima" style="display: flex; justify-content: center; align-items: center;">
          <img id='foto_carnet_previa' src='imagenes/usericonos.JPG'/>
        </div>
      </div>


<?php

}

 ?>

<div class="FondodatosP" style="">
<p class="datosP">

<span id='nombre_previo' style='font-size:30px; color:#000; display:block; font-weight: bold; line-height:40px; padding-right: 40px;'>{{$elnombre}} {{$inicialN}}</span>

<span id='nombre_previo' style='font-size:30px; color:#000; display:block; font-weight: bold; line-height:40px; padding-right: 40px;'>{{$el_apellido}} {{$inicialA}}</span>

      <span style='font-size:29px; color:#000; display:block; padding-top:3px; font-weight: bold; line-height:35px; '>
          D.I.N&deg;:&nbsp;
        <span id='din_previo' style='font-size:30px; color:#000; padding-right: 40px;'>
            {{ $empleado->cedula }} 
        </span>
      </span>
      <span id='cargo_previo' style='font-size:24px; color:#000; display:block; padding-top:3px; line-height:40px; width: 500px; float: right; padding-right: 40px;'>
         {{ $empleado->des_car }}
      </span>
      <br><br><br>

      <span id='dpto_previo' style='font-size:26px; color:#000; display:block; padding-top:3px;line-height:35px; width: 620px; float: right; padding-right: 40px;'>
        {{ $empleado->des_uni }}
      </span>


</p>

</div>



</div>
</div>


 <div style="page-break-after:always;"> </div>


   <div class="back" style=" position: absolute;">

   <br>

            
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

@foreach ($presidente as $presidente)

<?php $presidente = $presidente->presidente; $foto = "imagenes/FIRMA.jpg";?>

@foreach ($codigo as $codigo)

<?php $codigo = $codigo->tipoCodigo; ?>
<div class="letras">

<div id="encabezado" style="font-size:2.1em; color:#000; text-align: center; display:block; line-height:40px;"> {{$encabezado}}</div>

<div id="empresa" style="font-size:2.2em; color:#000; text-align: left; display:block; line-height:40px;margin-left: 55px;"> <img height="50" width="300" src='imagenes/logocovetela.png'/>  </div>

<div id="pagina" style="font-size:2.3em; color:#000; text-align: left; display:block; line-height:40px;margin-left: 55px;"> <img style="margin-top: 7px;" height="35" width="35" src='imagenes/iconoWeb.jpg'/> {{ $pagina }} </div>

<div class="fotopresi" style="margin-top: 0px; padding-left: 100px;" >
  <img id="fondo_firma" class="img-responsive img-thumbnail center-block" style="" src="{{$foto}}" height="305" style=""> 
  <br>
</div>

<div class="mensaje">
  <div id="namePresi" style="font-size:1.9em; color:#000; text-align: center; display:block; line-height:50px;  padding-top: 22px; font-weight: bold;" >{{$presidente}}</div>

<div class="presi" style="font-size:1.6em; color:#000; text-align: center; display:block; line-height:40px;">PRESIDENTE</div>

<br> <br>

<div id="agradecimiento" style="font-size:2.6em; margin-left: 85px;  text-align: center; align-content: center; color:#000; display:block; line-height:50px; width: 600px; margin-top: -30px;"> {{$agradecimiento}} </div>

<br> <br>
<div id="descripcion" style="font-size:2.6em; text-align: center; display:block; line-height:50px;"> {{$descripcion}} <br> <img style="margin-top: 6px;"" height="35" width="35" src='imagenes/llamada.jpg'/>  {{$telefono}}</div>

<br>
<?php  $fechas = date("d/m/Y", strtotime($carnet_empleados->carus_fecha_vencimiento)); ?>

<div id="fecha" style="font-size:2.3em; color:#000; text-align: center; display:block; line-height:60px; font-weight: bold;">Vence {{$fechas}}</div>

</div>

<div class="codigo" style="margin-left: 90px; margin-top: -30px;" > 
<?php 
 //echo $barra->getBarcodeHTML("$carnet_empleados->carus_codigo", "C39");
 //echo $barraC->getBarcodeHTML("$carnet_empleados->carus_codigo", "QRCODE");
if ($codigo == 1) { 
   ?> <br> <br><?php

   echo $barra->getBarcodeHTML("$carnet_empleados->carus_codigo","C39",4,70);

}elseif ($codigo == 2) {

  $style = array(
            'border' => 2,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );
$cod_qr = $carnet_empleados->carus_codigo;

?>

<img src="data:image/png;base64,{{$barraC->getBarcodePNG('$cod_qr', 'QRCODE,L', 25, 20, 30, 30, $style, 'N')}}" alt="barcode" style="width: 150px; height: 140px; margin-left: 50px;" />
<?php
  
}elseif ($codigo == 3) {
  echo "barra y qr";
}



?>

        @endforeach 

        @endforeach 

        @endforeach 

        @endforeach 
        
        @endforeach 
        
        @endforeach 

        @endforeach 

        @endforeach 


 </div>

</div>
  
</div>




<?php 
}
?>

@endforeach 
</body>



<style type="text/css">


@font-face {
font-family: Intro Black;
src: url("vendor/dompdf/dompdf/lib/fonts/Intro-Black.otf") format("opentype"); /*intro-book-regular*/
}

@font-face {
font-family: Intro-Light;
src: url("vendor/dompdf/dompdf/lib/fonts/Intro-Light.otf") format("opentype");
}

@font-face {
font-family: Intro Bold;
src: url("vendor/dompdf/dompdf/lib/fonts/Intro-Bold.otf") format("opentype"); /*intro-book-regular*/
}



#fondo_carnet{
  width: 100%; 
  height: 100vh;
  min-height:100%;
  position: absolute;

  
}
.fondo{
    position: relative;
    margin: 0px;
    display: block;

}

@page { 
  margin: 0px; 
}



.ima {

    transform: rotate(-45deg);
  position: relative;
  /*display: flex;
  justify-content: center;
  align-items: center;
  height: 362px;*/
  margin-left: -43px;
  margin-top: -15px;
  
}
.ima img {
  width:435px; 
  height:435px;

}

#rombo2 {
  width: 325px; 
  height: 334px; 
  transform: rotate(45deg); 
  position: absolute; 
 /* outline:1px solid #000; 
  outline-offset:5px;*/
  overflow: hidden; 
  z-index: 999; 
  top:284px; 
  right:140px;
  float: left;
  margin-left: 90px; 

}



/*--------------------------------------*/



.ima2 {

    transform: rotate(-45deg);
  position: relative;
  /*display: flex;
  justify-content: center;
  align-items: center;*/
  height: 362px;

  
}
.ima2 img {
  width:100%; 
  height:auto;

}

#rombo3 {
  width: 325px; 
  height: 334px; 
  transform: rotate(45deg); 
  position: absolute; 
  /*outline:1px solid #000; 
  outline-offset:5px;*/
  overflow: hidden; 
  z-index: 999; 
  top:284px; 
  right:140px;
  float: left;
  margin-left: 90px; 

}




.FondodatosP{
  position: absolute;
}

.datosP{
  top: 715px;
  position: absolute;
  font-family: Intro Black !important;
  font-weight: bold;
  text-align: right;
  margin-right: 20px;
  line-height: 2; 
}



.fotopresi{
  position: absolute;
  margin-top: 100px;
 
}


#fondo_firma{

margin-left: 100px;
}


.mensaje{

  margin-top: 270px;
}

.letras{
  font-family: Intro Bold !important;
}
</style>
