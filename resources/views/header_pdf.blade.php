<html>
    <head>
    </head>
    <body>
        <div>
            <div>
                <img src="assets/img/logo.png" style="float:left; height: 70px; margin-right: 75px; border-color: #d0dafd">
                <label>
                    <h3 style="font-size: 30px;">
                        RESUMEN DE {{$tipo}}
                    </h3>
                </label>
            </div>
            <?php foreach ($usuario as $usu): $dias_lab = strtoupper($usu->tiho_dias)?>
                <div style="margin-top: -15px;" >
                    <div>
                        <label>
                            <h4  style="margin-bottom: -15px;">NOMBRES Y APELLIDOS : 
                                <b>{{$usu->us_nom}}</b>
                                <b style="margin-right: 15px;">{{$usu->us_ape}}</b> 
                                CÉDULA : 
                                <b >{{$usu->us_ced}}</b>
                            </h4>
                            <h4 style="margin-bottom: -15px;">DEPARTAMENTO : 
                                <b>{{$usu->dp_nombre}}</b>
                            </h4>
                            <h4>DÍAS LABORALES : 
                                <b>{{$dias_lab}}</b>
                            </h4>
                        </label>
                    </div>
                </div>
            <?php endforeach ?>
        	<div id="contenido">
        				
    			@yield('contenido')
    		</div>
        </div>
    </body>
</html>