@extends('footer_Carnet_Extraviado')
@section('contenido')
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<br>
<table class="table" width="550">
  <thead class="">

      <tr>
      <th>#</th>
        <th>Cédula</th>
        <th>Nombres</th>
        <th>Apellidos</th>
        <th>Departamento</th>
        <th>Código</th>
        <th>Fecha</th>
      </tr>
    </thead>

    @if(count($extraviado) > 0)
    <tbody id="">
    <?php 
    $n = 0;
  foreach ($extraviado as $carnet_empleados):
  $n = $n+1;
     $fechas = date("d/m/Y", strtotime($carnet_empleados->fecha));
    ?>
<tr>
<td>{{$n}}</td>
  <td >{{ $carnet_empleados->cedula }}</td>
  <td >{{ $carnet_empleados->nombres }} </td>
  <td >{{ $carnet_empleados->apellidos }}</td> 
  <td >{{ $carnet_empleados->des_uni }}</td>
  <td >{{ $carnet_empleados->codigo }}</td>
  <td>{{ $fechas }}</td>
</tr>
<?php endforeach ?> 
      @else
      <td colspan="12" align="center">
      <div class="alert alert-warning">
          Disculpe, no se encontraron resultados...
     </div>
        </td>              
    @endif
    </tbody>
  </table>
        <div class="paginas">
            Página <span class="pagenum"></span>
        </div>
</body>
</html>

<style type="text/css">
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

@endsection