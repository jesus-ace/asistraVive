@extends('cabecera')
@section('content')
<?php if (! $errors->isEmpty()): ?>
	<div class="alert alert-danger" ng-disable>
		<p><strong>Opps!!</strong>Error al realizar el registro</p>
		<?php foreach ($errors->all() as $error): ?>
			<li>{{ $error}}</li>
		<?php endforeach ?>	
	</div>
<?php endif ?>
<div class="row">
		<div class="col-md-6 col-sm-6 col-lg-6 col-xs-6">
				<div class="panel-body">
					<div class="jumbotron" style="margin-top: 80px">
							<form method="POST" action="horario" novalidate>
							<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
							<h3>Registro de Horario</h3><br>
								<div class="form-group">
									<label>Dias Laborables</label><br>
									<label class="checkbox-inline">
										<input type="checkbox" name="dias[]" id="lunes" value="lunes" required> Lunes
									</label>
									<label class="checkbox-inline">
										<input type="checkbox" name="dias[]" id="martes" value="martes" required>Martes
									</label><label class="checkbox-inline">
										<input type="checkbox" name="dias[]" id="miercoles" value="miercoles">Miércoles
									</label>
									<label class="checkbox-inline">
										<input type="checkbox" name="dias[]" id="jueves" value="jueves">Jueves
									</label>
								</div>
								<div class="form-group">
									<label class="checkbox-inline">
										<input type="checkbox" name="dias[]" id="viernes" value="viernes">Viernes
									</label>
									<label class="checkbox-inline">
										<input type="checkbox" name="dias[]" id="sabado" value="sabado">Sábado
									</label>
									<label class="checkbox-inline">
										<input type="checkbox" name="dias[]" id="domingo" value="domingo">Domingo
									</label>
								</div><div class="form-group">
									<label class="form-inline"> Hora de entrada
										<input type="time" name="time1" class="form-control" required>
									</label>
									<label class="form-inline"> Hora de Salida
										<input type="time" name="time2" class="form-control" required>
									</label>
								</div>
								<div class="form-group">
									<label class="form-inline"> Turno
										<select  name="turnos" id="turno" class="form-control">
											<option value="diurno">Diurno</option>
											<option value="nocturno"> Nocturno</option>
										</select>
									</label>
								<div class="text-center">
									<input type="submit" name="registrar" value="Registrar" class="btn btn-primary" />
								</div>
							</form>
						</div>
				</div>
		</div>
		</div>
</div>
		@endsection
</style>