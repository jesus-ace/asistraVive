<html>
    <head>
    </head>
    <body>
    <?php  $fechas = date("d/m/Y", strtotime($fecha)); ?>
        <div>
            <div>
                <img src="assets/img/logo.png" style="float:left; height: 70px; margin-right: 75px; border-color: #d0dafd">
                <label>
                    <h3 style="font-size: 20px;" align="right">
                        CARNETS VENCIDOS
                    </h3>
                    <div style="" align="right">FECHA: {{$fechas}}</div>
                </label>
            </div>

        	<div id="contenido">
        				
    			@yield('contenido')
    		</div>
        </div>
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

