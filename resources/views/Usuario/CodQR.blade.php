<html>
    <head>
        <title>Carnet</title>
    </head>
<body>

@foreach ($datosCod as $empleado)

<?php 

$style = array(
            'border' => 2,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );
$cod_qr = $empleado->carus_codigo;

?>



<img src="data:image/png;base64,{{$barraC->getBarcodePNG('$cod_qr', 'QRCODE,L', 25, 20, 30, 30, $style, 'N')}}" alt="barcode" style="width: 232px; height: 232px; margin-left: 250px;" />


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


				