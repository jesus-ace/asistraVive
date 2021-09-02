@extends('layout')
@section('contenido')
<!DOCTYPE html>
<html>
<head>
	<title> Marcar</title>
</head>
<body>

<div class="cuadro" style="border-radius: 10px 10px 10px 10px;-moz-border-radius: 10px 10px 10px 10px;-webkit-border-radius: 10px 10px 10px 10px; border: 1px solid #000000; width:50%; height:50%;">
	@if(count($users) > 0)
                    @foreach($users as $key)
                          
                          <div>{{$key->carus_id}}</div>
                          <div>{{$hora}}</div> 
                          <div>{{$mensaje}}</div>

                    @endforeach
	@else

			 <div>{{$mensaje}}</div>



       @endif  
       

</div>

</body>
</html>


@endsection