<!--
<html>
<head>
  <style>
    @page { margin: 100px 25px; }
    header { position: fixed; top: -60px; left: 0px; right: 0px; background-color: lightblue; height: 50px; }
    footer { position: fixed; bottom: -60px; left: 0px; right: 0px; background-color: lightblue; height: 50px; }
    p { page-break-after: always; }
    p:last-child { page-break-after: never; }
  </style>
</head>
<body>
  <header>header on each page</header>
  <footer>footer on each page</footer>
  <main>
    <p>page1</p>
    <p>page2></p>
  </main>
</body>
</html>
-->
<html>
    <head>
        <style>
            @page{ 
                margin: 100px 25px;
            }
            header { 
                position: fixed;
                top: -60px; 
                left: 0px; 
                right: 0px; 
                background-color: #FFF; 
                height: 50px; 
                border-bottom: 1px solid #ddd; 
                margin-bottom: 20px; 
            }
            footer{ 
                position: fixed; 
                bottom: -50px; 
                left: 0px; 
                right: 0px; 
                background-color: #FFF; 
                height: 40px; 
                border-top: 1px solid #ddd;
            }
            p{ 
                page-break-after: always;
            }
            p:last-child { 
                page-break-after: never;
            }
            .saltopagina{
                page-break-after:always;
            }
            footer .page:after {
              content: counter(page);
              text-align: right;
            }
            .cont-logo{
                height: 50px;
                margin-top: -15px;
            }

            .img-logo{
                height: 100%;
            }
            .date_rep{
                float: right;
                position: absolute;
                top: 0px;
                font-size: 11px;
                text-align: right;
            }
            .date_repo{
                float: left;
                position: absolute;
                top: 0px;
                font-size: 15px;
                text-align: left;
                margin-left: 20%;
            }
            .normal {
            border: 1px solid #B5B5B5;
            border-collapse: collapse;
        }
        .normal th, .normal td {
            border:1px solid #EDEDED;
        }th{
            background-color: #E5F0FA;
        }
        td {
            margin: 3px;
            padding: 3px;
        }
        </style>
    </head>
    <body>
        <header>
            <div class="cont-logo">
                <img src="assets/img/logo.png" class="img-logo">
            </div>               
       
            <?php foreach ($sess as $usu):  

                $nombre = explode(" ",$usu->us_nom);
                $apellido = explode(" ",$usu->us_ape);
            ?>
                <div class="date_rep">
                    <label for="">Usuario: </label>{{$nombre[0]}} {{$apellido[0]}}<br>
                    <label for="">Fecha: </label><?php echo date("d") . " / " . date("m") . " / " . date("Y"); ?>
                </div>

            <?php endforeach ?>
            <?php foreach ($usuario as $usu):?>
                <div class="date_repo" >
                    <label> <b>EMPLEADO: {{$usu->us_nom}} {{$usu->us_ape}}</b></label><br>
                    <label><b>CÉDULA: {{$usu->us_ced}}</b></label><br>
                </div>

            <?php endforeach ?>
        </header>
        <footer>
            <table width="100%">
                <tr>
                    <td width="94%">
                        <p class="izq">                          
                            <?php foreach ($usuario as $usu):?>
                                <label style="font-size: 13px;color: #555">{{$usu->dp_nombre}} </label><br>
                            <?php endforeach ?>
                        </p>
                    </td>
                    <td>
                      <p class="page" style="color: #555;">
                        Página
                      </p>
                    </td>
                </tr>
            </table>
        </footer>
        <main>             
            <!-- TABLA DE PDF GENERAL -->
            <table class="normal" border="1" cellpadding="5" cellspacing="0" id="dataTable" width="100%" style="font-size: 14px;" >
                <thead>
                    <tr align="center" style="height: 20px; ">}
                        <th>
                            FECHA DE ENTRADA
                        </th>
                        <th>
                            HORA DE ENTRADA
                        </th>
                        <th>
                            FECHA DE SALIDA
                        </th>
                        <th>
                            HORA DE SALIDA
                        </th>
                        <th>
                            HORAS TRABAJADAS
                        </th>
                        <th>
                            HORAS EXTRAS DIURNAS
                        </th>
                        <th>
                            HORAS EXTRAS NOCTURNAS
                        </th>
                    </tr>
                </thead>
                  
                <tbody align="center">
                    <?php foreach ($asistencia as $asi ): 
                        $dias = array('Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado');
                        //Captura que dia de la semana es la fecha
                        $fecha = $dias[date('w', strtotime($asi->asi_entrada))]; 

                        //Separa los dias de trabajo de la persona
                        $explode = explode(",", $asi->tiho_dias);                    
                     

                        //hora de entrada marcada
                        $hora_entrada_m = substr($asi->asi_entrada_hora,0,2);
                        //toma los minnutos de la hora de entrada marcada
                        $hem_minutos = substr($asi->asi_entrada_hora,3,2);
                        //hora de salida marcada
                        $hora_salida_m =  substr($asi->asi_salida_hora,0,2);
                        //toma los minutos de la hora de salida marcada
                        $hsm_minutos = substr($asi->asi_salida_hora,3,2);
                        //hora de entrada horario
                        $hora_entrada_h = substr($asi->tiho_hora_en,0,2);
                        //holgura antes de la hora de salida
                        $holgura_entrada = substr($asi->tiho_holgura_entrada,0,2);
                        //hora de salida horario
                        $hora_salida_h = substr($asi->tiho_hora_sa,0,2);
                        //holgura despues de hora de salida
                        $holgura_salida = substr($asi->tiho_holgura_salida,0,2);
                        
                        $min = $hsm_minutos - $hem_minutos; $horasEDD1 = '';
                        $horasEND1 ='';
                        $ThorasTD1 ='';
                        $horaEND2 = '';
                        $horasEDD2 ='';
                        $horasTD1 = '';
                        $horasTD2 = '';
                        $horasEND2 = '';
                        $horasEN ='';
                        $entrada =date("d/m/Y", strtotime($asi->asi_entrada));

                        if ($asi->tiho_turno == 'Diurno') {
                            if ($asi->asi_salida == '') {
                                $salida = '--/--/--';
                                $h_salida = '--:--:--';
                                $horasED = '--:--:--';
                                $horasEN = '--:--:--';
                            }
                            else{
                                $salida = date("d/m/Y", strtotime($asi->asi_salida));
                                $h_salida = $asi->asi_salida_hora;
                            }
                            if ($asi->asi_entrada_hora == '') {
                                $h_entrada = '--:--:--';

                                $horasT = '--:--:--';
                            }
                            else{
                                $h_entrada = $asi->asi_entrada_hora;
                            }

                            //Si la entrada y la salida pertenecen al mismo día
                            if ($asi->asi_entrada == $asi->asi_salida) {

                              

                                if ($min >= 0) {
                                    $horasT = $hora_salida_m - $hora_entrada_m;
                                }
                                else{
                                    $horasT = ($hora_salida_m - $hora_entrada_m)-1;
                                }
                                if ($hora_salida_m > $hora_salida_h) {
                                        // si la hora de salida marcada es menor a 19 (7 de la noche)
                                        if ($hora_salida_m<=19) {
                                            
                                            $horasED = $hora_salida_m-$hora_salida_h;
                                            //no tiene horas extras nocturnas
                                            $horasEN = 0;
                                        }
                                        else{
                                            $horasEN = $hora_salida_m -19;
                                            $horasED = ($hora_salida_m-$hora_salida_h)-$horasEN;

                                        }
                                }
                                else{
                                    $horasED = '0';

                                    $horasEN = '0';

                                }
                                $turno = $asi->tiho_turno;
                            }
                            //si el dia de entrada es diferente al dia de salida
                            else{

                                
                                //Horas extras diurnas del día 1 
                                $horasEDD1 = 19-$hora_salida_h;
                                //horas extras nocturnas del dia 1
                                $horasEND1 = 24-19;

                                if ($min >= 0) {
                                    //Horas trabajadas en el día 1 (Día de la entrada)
                                    $horasTD1 = 24 - $hora_entrada_m;
                                    //Total de horas trabajadas del dia 1 (sin contar las horas extras )
                                    $ThorasTD1 = $hora_salida_h - $hora_entrada_m;
                                    //Suma de las horas trabajadas en ambos días
                                    $horasT = '';

                                }
                                else {
                                    $horasTD1 = (24 - $hora_entrada_m)-1;
                                    $ThorasTD1 = ($hora_salida_h - $hora_entrada_m)-1;
                                    $horasT = '';

                                }


                                $horasTD2 = $hora_salida_m - $hora_entrada_h;
                                if ($hora_entrada_m < $hora_entrada_h) {
                                    $horasEDD2p1 = $hora_entrada_h - $hora_entrada_m;
                                }
                                else{
                                    $horasEDD2p1 = 0;
                                }

                                if ($hora_salida_m < $hora_entrada_h) {
                                    if ($hora_salida_m < 5) {
                                        $horasEND2 = $hora_salida_m;
                                        $horasEDD2 = 0;
                                    }
                                    else{
                                        $horasEND2 = 5;
                                        $horasEDD2= $hora_salida_m - $horasEND2;
                                    }
                                }
                                else{
                                    //Valida que la hora de la salida sea mayor a la hora de la salida del horario
                                    if ($hora_salida_m > $hora_salida_h) {
                                        //verifica que la hora de la salida marcada sea menor a las 7 de la noche (donde comienzan las horas extras nocturnas)
                                        if ($hora_salida_m <= 19) {
                                            $horasEDD2 = ($hora_salida_m - $hora_salida_h)+$horasEDD2p1;
                                            $horasEND2 = 5;
                                        }
                                        else{
                                            $horasEDD2 = (19 - $hora_salida_h) + $horasEDD2p1;
                                            $horasEND2 = $hora_salida_m - 19;
                                        }
                                    }
                                    else{
                                        $horasEDD2 = 0;
                                        $horasEND2 = 5;
                                    }
                                }

                                $horasT = $horasTD1+$horasTD2;
                                $horasED = $horasEDD1 + $horasEDD2;
                                $horasEN = $horasEND1 + $horasEND2;
                            
                               
                            }
                        }
                        else{
                            if ($asi->asi_entrada == $asi->asi_salida) {
                                
                                if ($hora_entrada_m < $holgura_entrada) {
                                    if ($hora_salida_m < $hora_entrada_h) {
                                        $horasEDp1 = $hora_salida_m - $hora_entrada_m;
                                    }
                                    else{
                                        $horasEDp1 = $hora_entrada_h - $hora_entrada_m;
                                    }
                                }
                                else{
                                    $horasEDp1 = 0;
                                }

                                $horasT = $hora_salida_m - $hora_entrada_m;
                                $horasED=$horasEDp1;
                                $horasEN=0;

                            }
                            else{
                                if ($min >= 0) {
                                    //Horas trabajadas en el día 1 (Día de la entrada)
                                    $horasTD1 = 24 - $hora_entrada_m;

                                }
                                else {
                                    $horasTD1 = (24 - $hora_entrada_m)-1;

                                }
                                if ($holgura_entrada > $hora_entrada_m) {
                                    $horasEdd1p1 = $hora_entrada_h - $hora_entrada_m;
                                }
                                else{
                                    $horasEdd1p1 = 0;
                                }

                                if ($hora_entrada_m < $hora_entrada_h) {
                                    if ($hora_entrada_h < 19) {
                                        $horasEDD1p2 = 19 - $hora_entrada_m;
                                        $horasEND1 = 0; 
                                    }
                                    else{
                                        $horasEDD1p2 = 19 - $hora_entrada_m;
                                        $horasEND1 = $hora_entrada_h - 19;
                                    }
                                }
                                else{
                                    $horasEDD1p2 = 0;
                                    $horasEND1 = 0; 
                                }

                                if ($hora_salida_m < $holgura_salida) {
                                    $horasEDD2 = 0;
                                    $horasEND2 = 0;
                                    $horasTD2 = $hora_salida_m;
                                }
                                else{
                                    $horasTD2 = $hora_salida_m;
                                    if ($hora_entrada_h <= 19) {
                                        if ($hora_salida_m < $hora_entrada_h) {
                                            $horasEDD2 = $hora_salida_m - $hora_salida_h;
                                            $horasEND2 = 0;
                                        }
                                        else{
                                            $horasEDD2 = $hora_entrada_h - $hora_salida_h;
                                            $horasEND2 = 0;
                                        }
                                    }
                                    else{
                                        if ($hora_salida_m <= $hora_entrada_h) {
                                            if ($hora_salida_m < 19) {
                                                $horasEDD2 = $hora_salida_m - $hora_salida_h;
                                                $horasEND2 = 0;
                                            }
                                            else{
                                                $horasEDD2 = $ $hora_entrada_h - $hora_salida_h;
                                                $horasEND2 = 19 - $hora_salida_m;
                                            }
                                        }
                                        else{
                                            $horasEDD2 = 19 - $hora_salida_h;
                                            $horasEND2 = 19 -$hora_salida_m;
                                        }
                                    }
                                }
                                $horasT=$horasTD1 + $horasTD2;
                                $horasED= $horasEdd1p1 + $horasEDD1p2+ $horasEDD2;
                                $horasEN=$horasEND1 + $horasEND2 ;
                            }
                        }
                        if ($asi->asi_entrada== '') {
                            $h_entrada = '--:--:--';
                            $entrada = '--:--:--';

                            $horasT = '--:--:--';
                        }
                        else{
                            $entrada = $salida = date("d/m/Y", strtotime($asi->asi_entrada));
                        }

                        if ($asi->asi_salida == '') {
                            $salida = '--/--/--';
                            $h_salida = '--:--:--';
                            $horasED = '--:--:--';
                            $horasEN = '--:--:--';
                            $horasT = '--:--:--';
                        }
                        else{
                            $salida = date("d/m/Y", strtotime($asi->asi_salida));
                            $h_salida = $asi->asi_salida_hora;
                        }
                        if ($asi->asi_entrada_hora == '') {
                            $h_entrada = '--:--:--';

                            $horasT = '--:--:--';
                        }
                        else{
                            $h_entrada = $asi->asi_entrada_hora;
                        }
                        if ($asi->asi_diaf_id != 0) {
                            $color = '#58D68D';
                            $colorL = '#F8F9F9';
                            $negrita ='bold';
                        }
                        else{
                            $color = '';
                            $colorL ='';
                            $negrita ='';
                        }
                        if ($fecha == 'Sabado' || $fecha == 'Domingo') {
                            $color = '#58D68D';
                            $colorL = '#F8F9F9';
                            $negrita ='bold';
                        }
                        else{
                            $color = '';
                            $colorL ='';
                            $negrita ='';
                        }
                        if ($asi->asi_status == 1) {
                            $status = 'Habilitado';
                            $check = 'checked';
                        }
                        else{
                            $status = 'Inhabilitado';
                            $check = '';
                        }
                        ?>
                        <tr align="center">}
                        <td>
                            {{$entrada}}
                        </td>
                        <td>
                            {{$h_entrada}}
                        </td>
                        <td>
                            {{$salida}}
                        </td>
                        <td>
                            {{$h_salida}}
                        </td>
                        <td>
                            {{$horasT}} Horas
                        </td>
                        <td>
                           {{$horasED}} Horas
                        </td>
                        <td>
                            {{$horasEN}} Horas
                        </td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </main>
    </body>
</html>