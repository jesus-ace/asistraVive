<!--Admin_cliente.blade.php-->
@extends('cliente')
@section('contenido')
<div class="row">

	<div class="col-lg-12 cliente">

		<div class="panel panel-default" >

			<div class="panel-body"  style="margin-top: 10px;" align="center"><h3>Editar Cliente</h3>
    		

			<form action="{{url('/updatecliente')}}" method="post">
  					{{ csrf_field() }}
                    <input type="hidden" name="id" value="{{$id}}">

                    <div class="col-lg-12" style="margin-top: 20px;">
    		
	    			<div class="row">


	    				<div class="form-group col-lg-5 col-lg-offset-1" style="margin-top: 10px;">

			    			<label for="mcjacc_descripcion">Descripción</label>
			    			<input type="text" class="form-control" name="mcjacc_descripcion" value="{{$cliente->mcjacc_descripcion}}">
	 					</div>

	    				<div class="form-group col-lg-5"  style="margin-top: 10px;">

			    			<label for="mcjacc_ip">Ip</label>
			    			<input type="text" class="form-control"  name="mcjacc_ip" value="{{$cliente->mcjacc_ip}}" >

	 					</div>


	 
	    			</div>
	    			<div class="row">


	    				<div class="form-group col-lg-5 col-lg-offset-1"  style="margin-top: 10px;">
					   
					      <label for="mcjacc_pantalla">Pantalla</label>
					      <select name="mcjacc_pantalla" class="form-control" selected value="{{$cliente->mcjacc_pantalla}}">

						      <option selected>Seleccione la pantalla </option>
						      <option value="Marcaje">Marcaje</option>
						      <option value="Seguridad">Seguridad</option>

					      </select>

	 					</div>
	    				<div class="form-group col-lg-5"  style="margin-top: 10px;">

		    				<label for="mcjacc_status">Estatus</label>

						    <select name="mcjacc_status" class="form-control"  selected value="{{$cliente->mcjacc_status}}">

						        <option value="1">Activo </option>
						        <option value="0">Inactivo</option>

						    </select>

	 					</div>

	    			</div>


	    			<div class="row" style="margin-top:20px;">

	    				
						<div class="col-lg-10 col-lg-offset-1">
							
							<div class="alert alert-info" role="alert">
	 							<b>Importante!</b>
	 							Sólo se mostrará la pantalla seleccionada, para el cliente indicado.
							
							</div>

						</div>

	    			</div>

	 				<div class="row" align="center">
	    				

	    				<div class="form-group col-lg-5 col-lg-offset-1"  style="margin-top: 10px;">
					   
	    					<button  type="submit"  class="btn btn-info">Modificar</button>
	    					

	 					</div>
	    				<div class="form-group col-lg-5"  style="margin-top: 10px;">

	    					<button type="button" href="{{url('/admin_cliente')}}"class="btn btn-secondary">Salir</button>

	 					</div>

					

	    			</div>

    		

			</form>

    		</div>
    	</div>
		

    </div>
  	
</div>
@endsection