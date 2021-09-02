<!DOCTYPE html>
<html>
<head>
  <title></title>
</head>
<body>
  <?php

$fondo = "imagenes/PROVISIONAL.jpg";

?>
<div class="fondo">
    
  <img id="fondo_carnet" class="img-responsive img-thumbnail center-block" style="" src="{{$fondo}}"> 

    <div class="FondodatosP">
      
      <?php 
      foreach ($datosC as $carnet_empleados):

        $id = $carnet_empleados->car_prov_id; 

        $nro = "";

        $nro = "
        <div class='nroCarnet'>
        ".$id."
        </div>";

        echo $nro;

        ?>
        </div>

        <div style="width: 260px; height: 40px; margin-left: 80px; margin-top: 510px; position: relative;">

        <?php

          echo $barra->getBarcodeHTML("$carnet_empleados->carus_codigo", "C39");

        ?>

        </div>

        @endforeach 

    </div>
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

.FondodatosP{
  position: absolute;
  margin: 0 auto; 
  top: 100px;
}

.nroCarnet{
  font-size: 400px;
  color: #354C98; 
  width: 260px;
  left: 350px;
}
</style>