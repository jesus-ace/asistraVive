@extends('header_auditoria_pdf')
@section('contenido')
<?php 
?>
<style type="text/css">
    @page :left {
        margin-left: 1cm;
        margin-right: 1cm;
    }
    @page :right {
        margin-left: 1cm;
        margin-right: 1cm;
    }
    @page :first {
        margin-top: 0cm;
    }
    .normal {
        border: 1px solid #B5B5B5;
        border-collapse: collapse;
    }
    .normal th, .normal td {
        border:1px solid #EDEDED;
    }
    th{
        background-color: #E5F0FA;
    }
    td {
        margin: 6px;
        padding: 6px;
    }
    html { 
        margin-bottom: 0px;
        margin-right: 10px;
    }
    body {
        padding: .5in;
    }
    tr:hover td { background: #d0dafd; color: #339; }
    .tamano{
        font-size: 14px;}
    }
    .header,
    .foo {
        width: 100%;
        text-align: center;
        position: fixed;
    }
    .header {
        top: 750px;
    }
    .foo {
        bottom: 0px;
    }
    .pagenum:before {
        content: counter(page);
    }
</style>
<html>
    <head>
        <title>PDF DE RESUMEN DE AUDITORÍA</title>
    </head>
    <body>
        <div style="margin-top: 30px">
        <table  class="normal">
		    	<thead>
		    		<tr>
		    			<th>Usuario</th>
		    		
		    			<th>Accion</th>
		    		
		    			<th>Descripcion</th>
		    		
		    			<th>IP</th>
		    		
		    			<th>Nombre de la maquina</th>
		    			<th>Navegador</th>
		    			
		    		</tr>
		    	</thead>
		    	<tbody>
		    		@foreach($auditoria as $au)
		    			<tr>
		    				<td>
		    					{{$au->us_nom}} {{$au->us_ape}}
		    				</td>
		    				<td>
		    					{{$au->aud_tipo}}
		    				</td>
		    				<td>
		    					{{$au->aud_desc}}
		    				</td>
		    				<td>
		    					{{$au->aud_machine_ip}}
		    				</td>
		    				<td>
		    					{{$au->aud_machine_name}}
		    				</td>
		    				<td>
		    					{{$au->aud_machine_explorer}}
		    				</td>
		    			</tr>
		    		@endforeach
		    	</tbody>
		  	</table>
    </div>
        <div class="header">
            Página <span class="pagenum"></span>
        </div>
        <div class="foo">
            Página <span class="pagenum"></span>
        </div>
    </body>
</html>

@endsection