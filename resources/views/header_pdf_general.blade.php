 <html>
    <head>
    </head>
    <body>
        <div>
            <div>
                <img src="assets/img/logo.png" style="float:left; height: 45px; margin-right: 75px; border-color: #d0dafd">
                <label>
                    <h3 style="font-size: 20px;">
                        RESUMEN DE {{$tipo}}
                    </h3>
                </label>
            </div>
            <?php foreach ($usuario as $usu):  ?>
                <div style="margin-top: -15px;" >
                    <div>
                        <label>
                            <h4  style="margin-bottom: -15px;">EMITIDO POR EL EMPLEADO : 
                                <b>{{$usu->us_nom}}</b>
                                <b style="margin-right: 15px;">{{$usu->us_ape}}</b> 
                                CÉDULA : 
                                <b >{{$usu->us_ced}}</b>
                            </h4>
                            <h4 style="margin-bottom: -15px;">DEPARTAMENTO : 
                                <b>{{$usu->dp_nombre}}</b>
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