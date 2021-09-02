<!--Admin_cliente.blade.php-->
@extends('cliente')
@section('contenido')
<div class="row">
	
	<div class="col-lg-12 cliente">
		
		<div class="panel panel-default">
    		<div class="panel-body"  style="margin-top: 10px;" align="center"><h3>Administrador de Clientes</h3>
    
				 <div class="col-lg-12">
				 	<div class="table-responsive ">
					  <table class="table table-hover ">
							<thead>
							      <tr>
							        <th>Descripción</th>
							        <th>Ip</th>
							        <th>Estatus</th>
							        <th>Modificar</th>
							      </tr>
						    </thead>

						    @if(count($cliente) > 0)
                        		@foreach($cliente as $key)
						    	<tr>

						    		<td>{{$key->mcjacc_descripcion}}</td>
						    		<td>{{$key->mcjacc_ip}}</td>
						    		<td>{{$key->mcjacc_status}}</td>
						    		<td>   <a href="{{url('/editacceso')."/".$key->id}}" class="glyphicon glyphicon-edit"><i class="mdi-editor-border-color"></i></a>   </td>
						    	
						    	</tr>
								@endforeach
						    	@else
                     
                       			<div class="alert alert-success">
  										<strong>No</strong> Hay información para mostrar.
								</div>
                      
                       			@endif

							   

					  </table>

								
				
					</div> 
					</div>
				</div>
    	

    	</div>

			




	
</div>
@endsection

