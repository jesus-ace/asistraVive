<html>
    <head>
    </head>
    <body>
    <?php  $fechas = date("d/m/Y", strtotime($fecha)); ?>
        <div>
            <div>
                <img src="assets/img/logo.png" style="float:left; height: 70px; margin-right: 75px; border-color: #d0dafd">
                <label>
                    <h3 style="font-size: 18px;" align="right">
                        HISTÓRICO DE CARNETS PROVISIONALES
                    </h3>
                    <div style="" align="right">FECHA: {{$fechas}}</div>
                </label>
            </div>
            

            
        	<div id="contenido">
        				
    			<table class="table">
                <thead>
                  <tr>

                    <th>Responsable</th>       
                    <th>Carnet</th>
                    <th>Motivo</th>
                    <th>Nombres y Apellidos</th>       
                    <th>Cédula</th>
                    <th>Área</th>
                    <th>Fecha</th>

                  </tr>
                </thead>
                <tbody id="historicosProv">
                 <?php foreach ($historicoProvisional as $historico):
                 $fechas = date("d/m/Y", strtotime($historico->fecha));
                 ?>
                  <tr>

                    <td>{{ $historico->responsable }}</td>              
                    <td>{{ $historico->codigo}}</td>
                    <td>{{ $historico->tipoReporte}}</td>
                    <td>{{ $historico->nombres}} {{ $historico->apellidos}}</td>
                    <td>{{ $historico->cedula }}</td>
                    <td>{{ $historico->des_uni}}</td>
                    <td>{{ $fechas}}</td>
                  </tr>
                  <?php endforeach ?> 
                </tbody>
              </table>

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

 @page :left {
        margin-left: 1cm;
        margin-right: 1cm;
    }
    @page :right {
        margin-left: 1cm;
        margin-right: 1cm;
    }

    th{
        background-color: #E5F0FA;
    }
    td {
        margin: 6px;
        padding: 6px;
    }
    .table {
        border: 1px solid #B5B5B5;
        border-collapse: collapse;
    }
    .table th, .table td {
        border:1px solid #EDEDED;
    }
    .pagenum:before {
        content: counter(page);
    }
    .paginas{
      position:fixed;
     left:0px;
     bottom:10px;
     height:30px;
     width:100%;
    }
</style>

