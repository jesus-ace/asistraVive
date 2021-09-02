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

foreach ($datosC as $carnet_empleados):

$sello = $carnet_empleados->sello;

if(($cod_cargo == '0000000407') or ($cod_cargo == '0000000431') or ($cod_cargo == '0000000431') or ($cod_cargo == '0000000232') or ($cod_cargo == '0000000234') or ($cod_cargo == '0000000098') or ($cod_cargo == '0000000357') or ($cod_cargo == '0000000402') or ($cod_cargo == '0000000412') or ($cod_cargo == '0000000104') or ($cod_cargo == '0000000331') or ($cod_cargo == '000000086') or ($cod_cargo == '0000000089') or ($cod_cargo == '000000041') or ($cod_cargo == '0000000414') or ($cod_cargo == '0000000225') or ($cod_cargo == '0000000436') or ($cod_cargo == '0000000097') or ($cod_cargo == '0000000092') or ($cod_cargo == '0000000264') or ($cod_cargo == '0000000270') or ($cod_cargo == '0000000386')){ //Cordinador Tec - Ing -Transporte - Prgoramacion - Prducc I -Serv a la P - Andes / Andes (E) - Oriente - Planificacion - Finanzas - Admin y cont - Serv G - Talento humano - Seguridad - Mercadeo - Img y Prod - Comunicacion P -Centro Occ - Occidente - Orinoco - Llanos- Despacho
        $fondo = ($sello == 'Si') ? "imagenes/COORDINADOR-PRENSA.jpg" : "imagenes/COORDINADOR.jpg";

      }elseif (($cod_cargo == '0000000200') or ($cod_cargo == '0000000399') or ($cod_cargo == '0000000391')){//Vicepresidentes {
      $fondo = ($sello == 'Si') ? "imagenes/PRESIDENCIA-PRENSA.jpg" : "imagenes/PRESIDENCIA.jpg"; 
    
    }else{

      if(($cod_area == '0000-00-04-00-00') or ($cod_area == '0000-00-04-01-00') or ($cod_area == '0000-00-04-03-00') or ($cod_area == '0000-00-04-01-01') or ($cod_area == '0000-00-04-05-00')){ //VPGT - Tec - Ing -Trasporte - operaciones tecnicas
          $fondo = ($sello == 'Si') ? "imagenes/VPGT-PRENSA.jpg" : "imagenes/VPGT.jpg";

      }elseif(($cod_area == '0000-00-03-02-00') or ($cod_area == '0000-00-03-01-00') or ($cod_area == '0000-00-03-05-00') or ($cod_area == '0000-00-04-04-00') or ($cod_area == '0000-00-03-04-00') or ($cod_area == '0000-00-03-00-05') or ($cod_area == '0000-00-03-00-04') or ( $cod_area == '0000-00-03-00-06') or ($cod_area == '0000-00-03-00-02') or ($cod_area == '0000-00-03-00-01') or ($cod_area == '0000-00-03-00-00') or ($cod_area == '0000-00-07-00-00') or ($cod_area == '0000-00-03-00-03') or ($cod_area == '0000-00-03-01-01')){ //VPGP - Programacion - Produccion I - Img y Prodc - Serv a la Prodc - Comunicacion Popular - Andes - Llanos - Occidente - C. Occidente - Oriente - 123Tv -Orinoco - Ficcion y animacion
      $fondo = ($sello == 'Si') ? "imagenes/VPGP-PRENSA.jpg" : "imagenes/VPGP.jpg";

    
      }elseif(($cod_area == '0000-00-02-01-00') or ($cod_area == '0000-00-02-02-00') or ($cod_area == '0000-00-02-05-00') or ($cod_area == '0000-00-02-03-00') or ($cod_area == '0000-00-02-04-00') or ($cod_area == '0000-00-01-03-01') or ($cod_area == '0000-00-01-03-00') or ($cod_area == '0000-00-02-00-00')){ //VPGI -planificacion - Admin y Cont - Finanzas - Servicios G -Mercadeo y Asuntos P - Talento Humano - Talento Humanos Pensionados y Jubilados
          $fondo = ($sello == 'Si') ? "imagenes/VPGI-PRENSA.jpg" : "imagenes/VPGI.jpg";

    
      }elseif($cod_area == '0000-00-01-06-00'){ // Seguridad
          $fondo = ($sello == 'Si') ? "imagenes/SEGURIDAD-PRENSA.jpg" : "imagenes/SEGURIDAD.jpg";

    
      }elseif(($cod_area == '0000-00-01-01-00') or ($cod_area == '0000-00-01-00-00') or ($cod_area == '0000-00-01-02-00') or ($cod_area == '0000-00-01-05-00') or ($cod_area == '0000-00-01-04-00')){ //Presidencia - Despacho - Atencio al C - Consultoria J - Aduditoria I
          $fondo = ($sello == 'Si') ? "imagenes/PRESIDENCIA-PRENSA.jpg" : "imagenes/PRESIDENCIA.jpg";
      }
  }

?>

<div class="general">
<div class="fondo">
  
<img id="fondo_carnet" class="img-responsive img-thumbnail center-block" style="" src="{{$fondo}}"> 

<div class="foto"> 
<img id='foto_carnet_previa' src='imagenes/usericonos.JPG'  width='400' height='280' />
</div>

<div class="FondodatosP" style=" height: 300px;">
<p class="datosP">

<span id='nombre_previo' style='font-size:30px; color:#000; display:block; font-weight: bold; line-height:35px; '>
         NOMBRES {{ $empleado->nombres }} {{ $empleado->apellidos }}
      </span>
      <span style='font-size:29px; color:#000; display:block; padding-top:3px; font-weight: bold; line-height:35px;'>
                  D.I.N&deg;:&nbsp;
        <span id='din_previo' style='font-size:26px; color:#000;'>
           C&Eacute;DULA {{ $empleado->cedula }}
        </span>
      </span>
      <span id='cargo_previo' style='font-size:26px; color:#000; display:block; padding-top:3px; line-height:35px;'>
                  CARGO {{ $empleado->des_car }}
      </span>
      <span id='dpto_previo' style='font-size:26px; color:#000; display:block; padding-top:3px;line-height:35px;'>
                  COORDINACI&Oacute;N {{ $empleado->des_uni }}
      </span>
      <span id='area' style='font-size:26px; color:#000; display:block; padding-top:3px;  line-height:33px;'>
                  UNIDAD DE &Aacute;DSCRIPCION {{ $empleado->des_uni }}
      </span>


</p>

</div>

</div>
</div>


 <div style="page-break-after:always;"> </div>
   
   <div class="back" style=" position: absolute;">

<p class="texto">Corporación Venezolana de Telecomunicaciones</p>
<p class="texto">COVETEL, S.A</p>
<p class="texto">www.vive.gob.ve</p>

<div class="fotopresi">
  <img id="fondo_firma" class="img-responsive img-thumbnail center-block" style="" src="imagenes/firma_presidente2.jpg"> 
  <p class="texto">Amenin Centeno</p>
<p class="texto">PRESIDENTE</p>

<p class="texto">Quien suscribe, agradece a las</p>

<p class="texto">autoridades civiles y militares, prestar</p>

<p class="texto">la máxima colaboración al titular de</p>

<p class="texto">esta credencial para el mejor desempeño</p>

<p class="texto">de sus funciones.</p>

<p class="texto">En caso de emergencia y/o extravío, por</p>

<p class="texto">favor reportar al teléfono 0212-505.16.11</p>

<p class="texto">Vence</p>

<p class="texto"> {{$carnet_empleados->carus_fecha_vencimiento}} </p>

<div class="codigo">
<?php 
 echo $barra->getBarcodeHTML("$carnet_empleados->carus_codigo", "C39");
?>
 </div>

</div>
  
</div>



@endforeach 
@endforeach 
</body>


</html>
<style type="text/css">

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


.fotopresi{
  position: absolute;
  left: 25px;
}
.texto{
  text-align: center;
  position: relative;
  font-family: 'Arial';
  font-size:20px; 
  color:#000; 
  display:block; 
  font-weight: bold; 
  line-height:25px;

}

.codigo{
  margin: 0 auto; 
  width: 260px;
}

.foto{

  position: absolute; 
  width: 400px; 
  height: 280px; 
  top: 230px;
  overflow:hidden;
  left: 30px;
}

.FondodatosP{
  position: absolute;
}

.datosP{
  top: 560px;
  position: absolute;
  left: 30px;
  font-family: 'Arial';
  font-weight: bold;
}

    </style>