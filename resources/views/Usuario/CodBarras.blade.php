<html>
    <head>
        <title>Carnet</title>
    </head>
<body>


@foreach ($empleados as $empleado)

<span id='nombre_previo' style='font-size:18px; color:#000; display:block; font-weight: bold; line-height:35px; '>
         {{ $empleado->nombres }} {{ $empleado->apellidos }}
      </span>
      <span style='font-size:18px; color:#000; display:block; padding-top:3px; font-weight: bold; line-height:35px;'>
          D.I.N&deg;:&nbsp;
        <span id='din_previo' style='font-size:18px; color:#000;'>
           {{ $empleado->cedula }} 
        </span>
      </span>
      <span id='cargo_previo' style='font-size:18px; color:#000; display:block; padding-top:3px; line-height:35px;'>
         {{ $empleado->des_car }}
      </span>

      <span id='dpto_previo' style='font-size:18px; color:#000; display:block; padding-top:3px;line-height:35px;'>
        {{ $empleado->des_uni }}
      </span>


 
 @endforeach


 <div id='barra' style='display:block; padding-top:3px; width: 260px; height: 50px; margin-left: 250px; top: 170px;
  position: absolute;'>

@foreach ($datosC as $empleado)

<?php
 echo $barra->getBarcodeHTML("$empleado->carus_codigo", "C39");
 ?>

  </div>

  @endforeach

  <footer id="footer">
          <?php foreach ($usuario as $usu): ?>
                <div style="" >
                    <div class="form-group col-xs-12" align="center">

                     <span  style=" font-size: 10px;">Emitido por: 
                                <b>{{$usu->us_nom}}</b>
                                <b style="font-size: 10px; ">{{$usu->us_ape}}</b> 
                                Cédula: 
                                <b >{{$usu->us_ced}}</b>
                            </span>
                            <span style=" font-size: 10px;">Departamento: 
                                <b>{{$usu->dp_nombre}}</b>
                            </span>

                    </div>
                </div>
            <?php endforeach ?>
        </footer>

</body>
</html>


<style type="text/css">
  #footer {
   position:fixed;
   left:0px;
   bottom:0px;
   height:30px;
   width:100%;
}
</style>        