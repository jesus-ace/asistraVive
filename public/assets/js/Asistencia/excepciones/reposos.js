$("#cedula").change(function(event){ 
	var cedula = $('#cedula').val();
$.get(`reposo/${cedula}`, function(response, cedula){ 
	if (response == '') { 
		swal("¡Disculpe!", "Usuario no encontrado.",{icon: "warning"});
		$('#cedula').val('');
		$('#re_name').val('');
		$('#re_ape').val('');
		$('#re_ced').val('');
		$('#re_dp').val('');
	}
	else{
		$('.rtdu').show();
		console.log(response);
		response.forEach(usuario=> {
			$('#re_name').val(usuario.us_nom);
			$('#re_ape').val(usuario.us_ape);
			$('#re_ced').val(usuario.us_ced);
			$('#re_dp').append(`<option disabled selected>${usuario.dp_nombre}</option>`);
				var hoy = moment().format("YYYY-MM-DD").split("-");
				$('#inicioR').change(function(event){
					var us = usuario.us_ced;
					var desde = $('#inicioR').val();
					$.get(`buscaPer/${desde}/${us}`,function(response, excepcion){
						var permiso = response;
						$.get(`buscaRep/${desde}/${us}`,function(response, excepcion){
							var reposo = response;
							$.get(`buscaVac/${desde}/${us}`,function(response, excepcion){
								var vacaciones = response;
								$.get(`buscaAu/${desde}/${us}`,function(response, excepcion){
									var autorizacion = response;
									console.log(permiso, reposo, vacaciones, autorizacion);
									if (permiso == 0 && reposo == 0 && vacaciones == 0 && autorizacion == 0) {						
										var inicio = $('#inicioR').val().split("-");	
										if (inicio[0] >= hoy [0] && inicio[1] >= hoy[1]-1) {
											return true;
										}
										else{
											swal("¡Disculpe!", "La fecha que intenta introducir no pertenece al mes actual, por favor, seleccione la fecha correcta.",{icon: "warning"});
											$('#inicioR').val('');
										}
									}
									else{
										swal("Disculpe, ya existe una excepción para esta fecha");
										$('#inicioR').val('');
									}
								});
							});
						});
					});
				});
				$('#culmunacionR').change(function(event){

					var inicio = $('#inicioR').val().split("-");
					var culminacion = $('#culmunacionR').val().split("-");
					max = parseInt(inicio[1])+3;
					console.log(culminacion[1] , max);
					if ($('#inicioR').val() == '') {
						swal("¡Disculpe!", "Debe seleccionar una fecha de inicio.", {icon: "warning"});
						$('#culmunacionR').val('');
					}
					if (culminacion[0] < inicio[0] || culminacion[1] < inicio[1]) {
						swal("¡Disculpe!", "La fecha que intenta introducir es  menor a la fecha de inicio, por favor, intente nuevamente.", {icon: "warning"});
						$('#culmunacionR').val('');
					}
					else{
						if (culminacion[1] == inicio[1] ) {
							if (culminacion[2] < inicio[2]) {
								swal("¡Disculpe!", "La fecha que intenta introducir es  menor a la fecha de inicio, por favor, intente nuevamente.", {icon: "warning"});
								$('#culmunacionR').val('');
							}
						}
						else{
							if (culminacion[1] > max) {
								swal("¡Disculpe!", "No puede registrar un reposo mayor a tres meses, por favor, intente nuevamente.", {icon: "warning"});
								$('#culmunacionR').val('');
							}
						}
					}
				});
			});
		}
	});
});

function chequea_contenido(){
	if($('#_token').val() == '' ){
        swal('Lo siento existe un error con el token.');
        $('#_token').focus();
        return false;
    }
    else if($('#re_centro').val() == '' ){
        swal('Por favor ingrese el centro médico');
        $('#re_centro').focus();
        return false;
    }
    else if($('#inicioR').val() == '' ){
        swal('Por favor ingrese la fecha de inicio.');
        $('#inicioR').focus();
        return false;
    }
    else if($('#culmunacionR').val() == '' ){
        swal('Por favor ingrese la fecha de culminación.');
        $('#culmunacionR').focus();
        return false;
    }
    else if($('#re_diag').val() == '' ){
        swal('Por favor ingrese el diagnóstico.');
        $('#re_diag').focus();
        return false;
    }
    else if($('#re_tdr').val() == '' ){
        swal('Por favor ingrese el tipo de reposo.');
        $('#re_tdr').focus();
        return false;
    }
}

document.getElementById("reposoM").addEventListener("DOMNodeInserted", handler, true);
 
function handler(){
    var rol = $('#id_rol').val();
    $.get(`accesos/${rol}`,function(response){
        console.log(response);
        response.forEach(element=>{
            if (element.mod_nom == 'm_excepciones') {
                if (element.pnt_nombre == 'p_reposos') {
                    if (element.aco_ac_id == 2) {
                        $('.editReposo').show()
                    }
                    if (element.aco_ac_id == 3) {
                        $('.deleteReposo').show();
                    }
                }
            }
        });
    });
}

$('#departamentoR').change(function(event){
	var token = $('#_token').val();
	$.get(`getdpReposos/${event.target.value}`,function(response){
		console.log(response);
		$("#reposoM").empty();
		$("#centerasi").empty();
		if (response == '') {
			$("#reposoM").append(`<br>Disculpe, no se encontraron resultados...`)
		}
		$("#cedulaR").val('');
		response.forEach(usuario=> {
			$("#reposoM").append(`
				<tr>
					<td>
						<img src="imagenes2/${usuario.us_foto}"style="height: 60px;border-radius: 6px 6px 6px 6px; border:1px solid #B2B2B2;">
					</td>
					<td>						
						<label>Nombre y Apellido:</label> 

							${usuario.us_nom} 						
							${usuario.us_ape}

						<label>Cédula:</label>  

							${usuario.us_ced}

						<br>
						<label>Departamento: </label> 

							${usuario.dp_nombre}
					</td>
					<td>
						${usuario.tire_tipo}
					</td>
					<td class="text-right">
						<a class="showReposo"  onclick="showReposo(${usuario.re_id},${usuario.us_ced})" href="#" data-toggle="modal" data-target="#showReposo"> 
							<img src="assets/img/iconos/ver.svg" class="imgmenuho" >
						</a>
						<div class="modal fade" id="showReposo" tabindex="-1" role="dialog" aria-labelledby="EditarPermiso" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content text-center">
									<div class="panel panel-default">
  										<div class="panel-heading" style="background-color: #e5e8e8;">
    										<h3 class="panel-title">
    											<b>DETALLES DEL REPOSO</b>
    											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    										</h3>
  										</div>
  										<div class="panel-body">
    										<ul class="list-group text-left" id="reposoShow">
    											
    										</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
						<a class="editReposo" style="display:none;" onclick="editReposo(${usuario.re_id},${usuario.us_ced})" href="#" data-toggle="modal" data-target="#editarRE"> 
							<img src="assets/img/iconos/editar.svg" class="imgmenuho" >
						</a>
						<div class="modal fade" id="editarRE" tabindex="-1" role="dialog" aria-labelledby="EditarReposo" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content text-center">
									<div class="panel panel-default">
										<div class="panel-heading" style="background-color: #e5e8e8;">
    										<h3 class="panel-title">
    											<b>EDITAR REPOSO</b>
    										</h3>
  										</div>
	  									<div class="panel-body">
	  										<form method="POST" action="updateReposo" >
												<div class="row">
		    										<div class="col-lg-12">
														<div class="col-lg-6 text-left">
										                	<div class="form-group">

																<input type="hidden" name="_token" id="_token" value="${token}">
																<label for="inputTipoRep">Tipo de reposo</label>
												                <select class="form-control" name="tireposo" id="treposo" required>
												                	<option  disabled selected>Seleccione un tipo de reposo</option>
												                	<?php foreach ($tiporeposo as $tr): ?>
												                		<option value="${usuario.tire_id}">${usuario.tire_tipo}</option>
												                	<?php endforeach ?>
												                </select>
												            </div>
										                </div>
														<div id="editReposo">
															
														</div>
														<div class="col-lg-12" style="border-top: 1px solid #ccc; font-size: 14px;">
															<div class="col-lg-6 text-left" style="margin-top: 10px;">
													    		<button type="button" class="btn btn-default" data-dismiss="modal">CERRAR
													    		</button>
															</div>
															<div class="col-lg-6 text-right" style="margin-top: 10px;">
																<button type="submit" class="btn" style="background-color: #48c9b0; color:white;">
																	<b>MODIFICAR</b>
																</button>
															</div>
														</div>
													</div>										 	
										        </div>
									    	</form>
	 									</div>
									</div>
							</div>
						</div>
					</td>
					<td>
						<a href="#" onclick="eliminarR(${usuario.re_id})" title="Eliminar reposo" class="deleteReposo" style="display:none;">
							<img src="assets/img/iconos/eliminar.svg" style="height: 25px; margin-top: 5px" align="right">
						</a>
					</td>
				</tr>`)
		});
	});
});

$("#cedulaR").change(function(event){
	var cedula = $('#cedulaR').val();
	var token = $('#_token').val();
	$.get(`reposoG/${cedula}`, function(response, cedula){ 
		console.log(response);
		$("#reposoM").empty();
		if (response == '') {
			$("#reposoM").append(`<br>Disculpe, no se encontraron resultados...`)
		}

		$('#departamentoR').val('');
		$("#centerasi").empty();
		response.forEach(usuario=> {
			$("#reposoM").append(`
				<tr>
					<td>
						<img src="imagenes2/${usuario.us_foto}" style="height: 60px;border-radius: 6px 6px 6px 6px; border:1px solid #B2B2B2;">
					</td>
					<td>						
						<label>Nombre y Apellido:</label> 

							${usuario.us_nom} 						
							${usuario.us_ape}

						<label>Cédula:</label>  

							${usuario.us_ced}

						<br>
						<label>Departamento: </label> 

							${usuario.dp_nombre}
					</td>
					<td>
						${usuario.tire_tipo}
					</td>
					<td class="text-right">
						<a class="showReposo"  onclick="showReposo(${usuario.re_id},${usuario.us_ced})" href="#" data-toggle="modal" data-target="#showReposo"> 
							<img src="assets/img/iconos/ver.svg" class="imgmenuho" >
						</a>
						<div class="modal fade" id="showReposo" tabindex="-1" role="dialog" aria-labelledby="EditarPermiso" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content text-center">
									<div class="panel panel-default">
  										<div class="panel-heading" style="background-color: #e5e8e8;">
    										<h3 class="panel-title">
    											<b>DETALLES DEL REPOSO</b>
    											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    										</h3>
  										</div>
  										<div class="panel-body">
    										<ul class="list-group text-left" id="reposoShow">
    											
    										</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
						<a class="editReposo" style="display:none;" onclick="editReposo(${usuario.re_id},${usuario.us_ced})" href="#" data-toggle="modal" data-target="#editarRE"> 
							<img src="assets/img/iconos/editar.svg" class="imgmenuho" >
						</a>
						<div class="modal fade" id="editarRE" tabindex="-1" role="dialog" aria-labelledby="EditarReposo" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content text-center">
									<div class="panel panel-default">
										<div class="panel-heading" style="background-color: #e5e8e8;">
    										<h3 class="panel-title">
    											<b>EDITAR REPOSO</b>
    										</h3>
  										</div>
	  									<div class="panel-body">
	  										<form method="POST" action="updateReposo" >
												<div class="row">
		    										<div class="col-lg-12">
														<div class="col-lg-6 text-left">
										                	<div class="form-group">

																<input type="hidden" name="_token" id="_token" value="${token}">
																<label for="inputTipoRep">Tipo de reposo</label>
												                <select class="form-control" name="tireposo" id="treposo" required>
												                	<option  disabled selected>Seleccione un tipo de reposo</option>
												                	<?php foreach ($tiporeposo as $tr): ?>
												                		<option value="${usuario.tire_id}">${usuario.tire_tipo}</option>
												                	<?php endforeach ?>
												                </select>
												            </div>
										                </div>
														<div id="editReposo">
															
														</div>
														<div class="col-lg-12" style="border-top: 1px solid #ccc; font-size: 14px;">
															<div class="col-lg-6 text-left" style="margin-top: 10px;">
													    		<button type="button" class="btn btn-default" data-dismiss="modal">CERRAR
													    		</button>
															</div>
															<div class="col-lg-6 text-right" style="margin-top: 10px;">
																<button type="submit" class="btn" style="background-color: #48c9b0; color:white;">
																	<b>MODIFICAR</b>
																</button>
															</div>
														</div>
													</div>										 	
										        </div>
									    	</form>
	 									</div>
									</div>
							</div>
						</div>
					</td>
					<td>
						<a href="#" onclick="eliminarR(${usuario.re_id})" title="Eliminar reposo"  class="deleteReposo" style="display:none;">
							<img src="assets/img/iconos/eliminar.svg" style="height: 25px; margin-top: 5px" align="right">
						</a>
					</td>
				</tr>`)
		});
	});
});
$("#refrescarr").click(function(event){
	$('#cedulaR').val('');
	var token = $('#_token').val();
	$.get(`todosreposo`, function(response, refrescarr){ 
	console.log(response);
	$("#reposoM").empty();
	$('#departamentoR').val('');

	
		response.forEach(usuario=> {
			$("#reposoM").append(`
				<tr>
					<td>
						<img src="imagenes2/${usuario.us_foto}" style="height: 60px;border-radius: 6px 6px 6px 6px; border:1px solid #B2B2B2;">
					</td>
					<td>						
						<label>Nombre y Apellido:</label> 

							${usuario.us_nom} 						
							${usuario.us_ape}

						<label>Cédula:</label>  

							${usuario.us_ced}

						<br>
						<label>Departamento: </label> 

							${usuario.dp_nombre}
					</td>
					<td>
						${usuario.tire_tipo}
					</td>
					<td class="text-right">
						<a class="showReposo"  onclick="showReposo(${usuario.re_id},${usuario.us_ced})" href="#" data-toggle="modal" data-target="#showReposo"> 
							<img src="assets/img/iconos/ver.svg" class="imgmenuho" >
						</a>
						<div class="modal fade" id="showReposo" tabindex="-1" role="dialog" aria-labelledby="EditarPermiso" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header text-center">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										<h3>Reposo</h3>
									</div>
									<div class="modal-body text-center">
										<div class="row">
											<div id="reposoShow">
												
											</div>										 	
					                    </div>
									</div>
								</div>
							</div>
						</div>
						<a class="editReposo" style="display:none" onclick="editReposo(${usuario.re_id},${usuario.us_ced})" href="#" data-toggle="modal" data-target="#editarRE"> 
							<img src="assets/img/iconos/editar.svg" class="imgmenuho" >
						</a>
						<div class="modal fade" id="editarRE" tabindex="-1" role="dialog" aria-labelledby="EditarReposo" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header text-center">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										<h3>Editar Reposo</h3><br>
									</div>
									<div class="modal-body text-center">
										<form method="POST" action="updateReposo" >
										<input type="hidden" name="_token" id="_token" value="${token}">
										<div class="row">
											<div class="col-lg-6 text-left">
							                    	<div class="form-group">
														<label for="inputTipoRep">Tipo de reposo</label>
										                <select class="form-control" name="tireposo" id="treposo" required>
										                	<option  disabled selected>Seleccione un tipo de reposo</option>
										                	<?php foreach ($tiporeposo as $tr): ?>
										                		<option value="${usuario.tire_id}">${usuario.tire_tipo}</option>
										                	<?php endforeach ?>
										                </select>
										            </div>
								                </div>
											<div id="editReposo">
												
											</div>										 	
					                    </div>
										<div class="text-center">
												<br><input type="submit" name="registrar" value="Modificar" class="btn btn-primary"/>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</td>
					<td>
						<a href="#" onclick="eliminarR(${usuario.re_id})" title="Eliminar reposo" class="deleteReposo" style="display:none;">
							<img src="assets/img/iconos/eliminar.svg" style="height: 25px; margin-top: 5px" align="right">
						</a>
					</td>
				</tr>`)
		});
	});
});

function editReposo(idR,idUs){

	$.get(`tipoRe`, function(response, tipo){
		console.log(response);
		$('#treposo').empty();
		response.forEach(element=>{
			$('#treposo').append(`<option value="${element.tire_id}" >${element.tire_tipo}</option>`);
		});
	});
	$.get(`buscaReposo/${idR}/${idUs}`, function(response, reposo){
		console.log(response);
		$("#editReposo").empty();
		response.forEach(element=> { 
			if (element.re_validado == 1) {
				var seleccionado = 'checked';
			}
			else{
				var select = 'checked';
			}
			$('#treposo').append(`<option selected value="${element.tire_id}" >${element.tire_tipo}</option>`);
			$("#editReposo").append(`
				<input type="hidden" name="repId" id="id" value="${element.re_id}"
            	<input type="hidden" name="tipo_aud" id="tipo_aud" value="Registro">
            	<input type="hidden" name="desc_aud" id="desc_aud" value="Registro de reposo">
	                <div class="form-group col-lg-6 text-left">
					<label> Cédula</label>
					<input class="form-control" 
						type="number" 
						id="cedulaa" 
						name="cedulaa"
						disabled
						value="${element.us_ced}""/>
				</div>
				<div class="col-lg-6 text-left">
					<input type="hidden" value="${element.us_id}" name="us_id">
            		<div class="form-group">
						<label for="inputNombre">Nombre</label>
						<input type="text" name="nombre" class="form-control" disabled value="${element.us_nom}">
		            </div>
            	</div>
                <div class="col-lg-6 text-left">
                	<div class="form-group">
						<label for="inputApellido">Apellido</label>
						<input type="text" name="apellido" class="form-control" disabled value="${element.us_ape}">
		                
		            </div>
                </div>
                <div class="col-lg-6 text-left">
                	<div class="form-group">
						<label for="inputCedula">Cédula</label>
						<input type="nomber" name="cedula" class="form-control" disabled value="${element.us_ced}">
		                
		            </div>
                </div>
                <div class="col-lg-6 text-left">
                	<div class="form-group">
						<label for="inputDepartamento">Departamento</label>
		                <select class="form-control" name="reposo" required>
		                	<option disabled selected>${element.dp_nombre}</option>
		                </select>
		            </div>
                </div>
                <div class="col-lg-12 text-left">
                	<div class="form-group">
						<label for="inputDepartamento">Centro médico</label>
		                <input type="text" name="centro" class="form-control" placeholder="Ingrese el centro médico" value="${element.re_ce_med}">
		            </div>
                </div>
                <div class="col-lg-6 text-left">
                	<div class="form-group">
						<label for="inputFechaini">Fecha de inicio</label>
		            	<input type="date" name="desde" class="form-control" id="inicioR" value="${element.re_fecha_ini	}" placeholder="yyyy-mm-dd">   
		            </div>
                </div>
                <div class="col-lg-6 text-left">
                	<div class="form-group">
						<label for="inputFechafin">Fecha de culmunación</label>
		            	<input type="date" name="hasta" class="form-control" id="culmunacionR" value="${element.re_fecha_fin}" placeholder="yyyy-mm-dd">   
		            </div>
                </div>
                <div class="col-lg-6 text-left">
                	<div class="form-group">
						<label for="inputAdjunto">Añadir archivo adjunto</label>
		            	<input type="file" name="file" class="form-control"  id="adjunto" accept="application/pdf">   
		            </div>
                </div>
                <div class="col-lg-6 text-left">
                	<div class="form-group">
						<label for="inputDiag">Diagnostico</label>
						<input type="text" value="${element.re_diagnostico}" name="diagnostico" class="form-control" placeholder="Indique el diagnóstico" maxlength="250" title="Por favor ingrese una descripción no mayor a 250 palabras">							            	  
		            </div>
                </div>
				<div class="col-lg-12">
					<div class="form-group">
						<label>¿Es validado por el IVSS?</label>
						<label class="radio-inline">
							<h4><input type="radio" name="validado" id="siR" value="1" ${seleccionado}> Si</h4>
						</label>
						<label class="radio-inline">
							<h4><input type="radio" name="validado" id="noR" value="2" ${select}>No</h4>
						</label>
					</div>
				</div>
			</div>`)
			var hoy = moment().format("YYYY-MM-DD").split("-");
			$('#inicioR').change(function(event){
				var us = usuario.us_ced;
				var desde = $('#inicioR').val();
				$.get(`buscaPer/${desde}/${us}`,function(response, excepcion){
					var permiso = response;
					$.get(`buscaRep/${desde}/${us}`,function(response, excepcion){
						var reposo = response;
						$.get(`buscaVac/${desde}/${us}`,function(response, excepcion){
							var vacaciones = response;
							$.get(`buscaAu/${desde}/${us}`,function(response, excepcion){
								var autorizacion = response;
								console.log(permiso, reposo, vacaciones, autorizacion);
								if (permiso == 0 && reposo == 0 && vacaciones == 0 && autorizacion == 0) {						
									var inicio = $('#inicioR').val().split("-");	
									if (inicio[0] >= hoy [0] && inicio[1] >= hoy[1]) {
										return true;
									}
									else{
										swal("¡Disculpe!", "La fecha que intenta introducir no pertenece al mes actual, por favor, seleccione la fecha correcta.",{icon: "warning"});
										$('#inicioR').val('');
									}
								}
								else{
									swal("Disculpe, ya existe una excepción para esta fecha");
									$('#inicioR').val('');
								}
							});
						});
					});
				});
			});
			$('#culmunacionR').change(function(event){

				var inicio = $('#inicioR').val().split("-");
				var culminacion = $('#culmunacionR').val().split("-");
				max = parseInt(inicio[1])+3;
				console.log(culminacion[1] , max);
				if ($('#inicioR').val() == '') {
					swal("¡Disculpe!", "Debe seleccionar una fecha de inicio.", {icon: "warning"});
					$('#culmunacionR').val('');
				}
				if (culminacion[0] < inicio[0] || culminacion[1] < inicio[1]) {
					swal("¡Disculpe!", "La fecha que intenta introducir es  menor a la fecha de inicio, por favor, intente nuevamente.", {icon: "warning"});
					$('#culmunacionR').val('');
				}
				else{
					if (culminacion[1] == inicio[1] ) {
						if (culminacion[2] < inicio[2]) {
							swal("¡Disculpe!", "La fecha que intenta introducir es  menor a la fecha de inicio, por favor, intente nuevamente.", {icon: "warning"});
							$('#culmunacionR').val('');
						}
					}
					else{
						if (culminacion[1] > max) {
							swal("¡Disculpe!", "No puede registrar un reposo mayor a tres meses, por favor, intente nuevamente.", {icon: "warning"});
							$('#culmunacionR').val('');
						}
					}
				}
			});
			
			

		    $('#inicioR').datepicker({
		        format: "yyyy-mm-dd",
		        language: "es",
		        autoclose: true
		    });
		    
		    $('#culmunacionR').datepicker({
		        format: "yyyy-mm-dd",
		        language: "es",
		        autoclose: true
		    });
		});
	});
}
function showReposo(idP,idUs) {
	$.get(`buscaReposo/${idP}/${idUs}`,function(response, permiso){
		console.log(response);
		$('#reposoShow').empty();
		response.forEach(usuario =>{
			if (usuario.re_validado == 1) {
				var validado = 'Si';
			}
			else{
				validado = 'No';
			}
			var fechaIni =moment(usuario.re_fecha_ini).format('DD-MM-YYYY');
			var fechaFin = moment(usuario.re_fecha_fin).format('DD-MM-YYYY');

			if (usuario.re_adjunto != null) {
				var archivo = usuario.re_adjunto; 			
			}
			else{
				var archivo = 't.pdf';
			}
			console.log(archivo)
			$('#reposoShow').append(`
				<li class="list-group-item text-left">
					<label style="font-size: 14px;">
						<img src="imagenes2/${usuario.us_foto}" style="height: 80px; float:left; margin:10px;border-radius: 6px 6px 6px 6px; border:1px solid #B2B2B2;" >
						NOMBRE: ${usuario.us_nom} ${usuario.us_ape}<br>
						CÉDULA: ${usuario.us_ced}<br>
						DEPARTAMENTO: ${usuario.dp_nombre}
					</label>
				</li>
				<li class="list-group-item text-left col-lg-6">
					<label>
						FECHA
					</label>
				</li>
				<li class="list-group-item text-right col-lg-6">
					<label>
						${fechaIni} / ${fechaFin}
					</label>
				</li>
				<li class="list-group-item text-left col-lg-6">
					<label>
						CENTRO MÉDICO
					</label>
				</li>
				<li class="list-group-item text-right col-lg-6">
					<label>
						${usuario.re_ce_med}
					</label>
				</li>
				<li class="list-group-item text-left col-lg-6">
					<label>
						DIAGNÓSTICO
					</label>
				</li>
				<li class="list-group-item text-right col-lg-6">
					<label>
						${usuario.re_diagnostico}
					</label>
				</li>
				<li class="list-group-item text-left col-lg-6">
					<label>
						VALIDADO
					</label>
				</li>
				<li class="list-group-item text-right col-lg-6">
					<label>
						${validado}
					</label>
				</li>
				<li class="list-group-item text-right col-lg-12">
					<label>
						<a href="ver_reposo/${archivo}"  target="_blank">DESCARGAR ARCHIVO ADJUNTO</a>
					</label>
				</li>
				`
    		)
		}); 
	});
}
function eliminarR(id) {
	swal ({
		title: "¿Estás seguro?",
		text: "¿Desea eliminar este reposo?",
		icon: "warning",
		buttons: true,
		dangerMode: true, 
	})
	.then((willDelete) => {
	  	if (willDelete) {
	    	$.get(`reposo_delete/${id}`, function(response){
		    	setTimeout('document.location.reload()',1000);
				swal("Poof! Este reposo ha sido eliminado!", {
		        icon: "success",

		    	});
			});
	  	}
	});
}
$(document).ready(function() {
    setTimeout(function() {
        $("#alertaR").slideUp(1500);
        $("#alertaRd").slideUp(1500);
    },3000);
});
