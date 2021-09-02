
<div>
    <input type="hidden" name="ventana" id="ventana" value="resumen">
    <label>
        <h3 style="margin-left: 385px;">
            
        </h3>
    </label>
</div>

<?php foreach ($usuario as $us ): 
        
        if ($dia == '') {
            $desde = $desde;
            $hasta = $hasta;
            $dia = '0000-00-00';
        }
        if($desde == ''){
            $dia = $dia;
            $desde = '0000-00-00';
            $hasta = '0000-00-00';
        }
    ?>
        <!-- Recibe las variables enviadas desde el controlador para mandarlas al js dropdown -->
        <input type="hidden" name="id" id="idResumen" value="{{$us->us_ced}}" placeholder="id">
        <input type="hidden" name="dia" id="diaResumen" value="{{$dia}}" placeholder="dia">
        <input type="hidden" name="desde" id="desdeResumen" value="{{$desde}}" placeholder="desde">
        <input type="hidden" name="hasta" id="hastaResumen" value="{{$hasta}}" placeholder="hasta">
<?php endforeach ?>

<table>
    <thead>
        <tr>
            <th>
                
            </th>
            <th>
                Nombre
            </th>
            <th>
                Apellido
            </th>
            <th>
                Cédula
            </th>
            <th>
                Departamento
            </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                
            </td>
            <td>
                
            </td>
            <td>
                
            </td>
            <td>
                
            </td>
        </tr>
        @foreach($usuario as $us)
            <tr>
                <td>
                    
                </td>
                <td>
                    {{$us->us_nom}}
                </td>
                <td>
                    {{$us->us_ape}}
                </td>
                <td>
                    {{$us->us_ced}}
                </td>
                <td>
                    {{$us->dp_nombre}}
                </td>
            </tr>
        @endforeach
        <tr>
            <td>
                
            </td>
            <td>
                
            </td>
            <td>
                
            </td>
            <td>
                
            </td>
        </tr>
    </tbody>
</table>
<div class="panel {{$panel}}">
    <form method="POST" action="guardar_status" id="formId">
        {{csrf_field()}}
        <!-- Default panel contents -->
        <div class="panel-heading" style="background-color: #e5e8e8;">
            <b>
                RESUMEN GENERAL DE {{$tipo}} PARA LA FECHA: {{$fecha}}
                <a href="generarpdfg/{{$dia}}/{{$desde}}/{{$hasta}}/{{$departamento}}/{{$tipo}}" title="Generar archivo pdf" target="_blank" id="imprimir_g">
                    <img src="assets/img/iconos/pdf.svg" style="height: 30px; margin-top: -7px;" align="right">
                </a>
            </b>
                
                <!--<a href="generarexcelg/{{$dia}}/{{$desde}}/{{$hasta}}" title="Generar archivo de excel"> 
                    <img src="assets/img/iconos/excel.svg" style="height:30px;" align="right"> 
                </a>-->
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead style='background: #f2f3f4  repeat-x center top; font-size:14px;'>
                    <tr>
                        <th >
                            
                        </th>
                        <th >
                            Días
                        </th>
                        <th>
                            Fecha de Entrada
                        </th>
                        <th>
                            Hora de Entrada
                        </th>
                        <th>
                            Fecha de Salida
                        </th>
                        <th>
                            Hora de Salida
                        </th>
                        <th>
                            Horas trabajadas
                        </th>
                        <th>
                            Horas Extras Diurnas
                        </th>
                        <th>
                            Horas Extras Nocturnas
                        </th>
                    </tr>
                </thead>
                <tbody>

                    <tr>                            
                        <td>
                            
                        </td>
                        <td>
                            
                        </td>
                        <td>
                            
                        </td>
                        <td>
                            
                        </td>
                        <td>
                           
                        <td>
                            
                        </td>
                        <td>
                            
                        </td>
                        <td>
                            
                        </td>
                        <td>
                           
                        </td>
                        <td>
                            
                        </td>
                    </tr>
                    @if(count($asistencia)>0)
                        <?php foreach ($asistencia as $asi ): 
                            $dias = array('Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado');
                            //Captura que dia de la semana es la fecha
                            $fechaa = $dias[date('w', strtotime($asi->asi_entrada))]; 

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
                        if ($tipo == 'INASISTENCIA') {
                            $entrada = $fecha;
                        }
                        else{
                            if ($asi->asi_entrada== '') {
                                $h_entrada = '--:--:--';
                                $entrada = '--:--:--';

                                $horasT = '--:--:--';
                            }
                            else{
                                $entrada =  date("d/m/Y", strtotime($asi->asi_entrada));
                            }
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
                        if ($fechaa == 'Sabado' || $fechaa == 'Domingo') {
                            echo "<tr>
                                <td>
                                    
                                </td>
                                <td>
                                    $fechaa 
                                </td>
                                <td>
                                    $entrada
                                </td>
                                <td>
                                    $h_entrada
                                </td>
                                <td>
                                    $salida
                                </td>
                                <td>
                                    $h_salida
                                </td>
                                <td>
                                    $horasT Horas
                                </td>
                                <td>
                                   $horasED Horas
                                </td>
                                <td>
                                    $horasEN Horas
                                </td>
                            </tr>";
                        }
                        ?>
                        <?php endforeach ?>
                    @else
                        <tr>
                            <td>
                                <strong>Disculpe,</strong> no se encontraron resultados.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </form>
        <div style="border-top: 1px solid #ccc; font-size: 14px;display: none;" id="guardar_g">
            <button class="btn" style="margin-left:94%;margin-top:7px;margin-bottom: 7px;" onclick="intento()">
                <img src="assets/img/iconos/save.svg" style="height: 30px; margin-top: -7px;" align="right">
            </button>
        </div>
</div>
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/Asistencia/resumeng.js"></script>