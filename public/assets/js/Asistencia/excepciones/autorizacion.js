$("#cedulaau").change(function(event){ 
	var cedula = $('#cedulaau').val();
	$.get(`autorizacion1/${cedula}`, function(response, cedulaau){ 
	console.log(response);
	if (response == '') {

		swal("¡Disculpe!", "Usuario no encontrado.",
		{icon: "warning"});
		$('#cedulaau').val('');
		$('#au_name').val('');
		$('#au_ape').val('');
		$('#au_dp').val('');
	}
	else{
		$("#formAut").empty();
			response.forEach(element=> { 
				$("#formAut").append(`
					<input type="hidden" name="empleado" value="${element.us_ced}">
					<div class="col-lg-6 text-left">
	                	<div class="form-group">
							<label for="inputNombre">Nombre</label>
							<input type="text" name="nombre" class="form-control" id="au_name" disabled value="${element.us_nom}">
			                
			            </div>
	                </div>
	                <div class="col-lg-6 text-left">
	                	<div class="form-group">
							<label for="inputApellido">Apellido</label>
							<input type="text" name="apellido" class="form-control" id="au_ape" disabled value="${element.us_ape}">
			                
			            </div>
	                </div>
	                <div class="col-lg-6 text-left">
	                	<div class="form-group">
							<label for="inputDepartamento">Departamento</label>
			                <select class="form-control" name="departamento" required id="au_dp">
			                	<option disabled selected>
			                		${element.dp_nombre}
			                	</option>
			                </select>
			            </div>
	                </div>
	                <div class="col-lg-6 text-left">
	                	<div class="form-group">
							<label for="inputFechaini">Fecha de Ingreso</label>
			            	<input type="date" name="fecha" class="form-control" id="permiso" required>   
			            </div>
	                </div>

					<div class="col-lg-12 text-left">
	                	<div class="form-group">
							<label for="inputDiag">Motivo de la Autorización</label>
								<input type="text" name="motivo" class="form-control" placeholder="ingrese el motivo" maxlength="250" title="Por favor ingrese una descripción no mayor a 250 palabras" required>									            	  
			            </div>
	                </div>
	                <div class="col-lg-12" style="border-top: 1px solid #ccc; font-size: 14px;">
				    	<div class="col-lg-6 text-left" style="margin-top:10px;">
							<div class="form-group">
								<button type="button" class="btn btn-default" data-dismiss="modal">CERRAR</button>													
							</div>												
						</div>
				        <div class="col-lg-6 text-right" style="margin-top:10px;">
							<div class="form-group">
								<button type="submit" name="Registrar" value="Registrar" class="btn" style="background-color: #48c9b0; color:white;">
									<b>REGISTRAR</b>
								</button>													
							</div>												
						</div>
				    </div>`
				)
				var hoy = moment().format("YYYY-MM-DD").split("-");
				$('#permiso').change(function(event){
					var us = $('#cedulaau').val();
					var desde = $('#permiso').val();
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
										var inicio = $('#permiso').val().split("-");	
										if (inicio[0] >= hoy [0] && inicio[1] >= hoy[1]) {
											return true;
										}
										else{
											swal("¡Disculpe!", "La fecha que intenta introducir no pertenece al mes actual, por favor, seleccione la fecha correcta.",{icon: "warning"});
											$('#permiso').val('');
										}
									}
									else{
										swal("Disculpe, ya existe una excepción para esta fecha");
										$('#permiso').val('');
									}
								});
							});
						});
					});
				});
			});
		}
	});
});
$(document).ready(function(){

});
$('#dia_au').click(function(e){
  if ($(this).is(':checked')) {
    $('#rango_au').prop('checked',false);
  	$('#diasAu').prop('disabled', false);
  	$('#desdeAu').prop('disabled', true);
  	$('#hastaAu').prop('disabled', true);
  }
});

$('#rango_au').click(function(e){
  if ($(this).is(':checked')) {
    $('#dia_au').prop('checked',false);
  	$('#diasAu').prop('disabled', true);
  	$('#desdeAu').prop('disabled', false);
  	$('#hastaAu').prop('disabled', false);
  }
});


$("#diasAu").change(function(event){ 
	$("#desdeAu").val('');
	$("#hastaAu").val('');
	$("#cedulaAut").val('');
	var token = $('#_token').val();
	var fechaDia = $('#diasAu').val();
	$.get(`Bdia/${fechaDia}`, function(response, cedulaau){
		console.log(response);
	$("#autorizacionBody").empty();
	if (response == '') { $("#autorizacionBody").append(`<br>Disculpe, hay registrada ninguna autorización`)}
		response.forEach(usuario=> {
			$("#autorizacionBody").append(`
				<tr>
					<td>
						<img src="imagenes2/${usuario.us_foto}"  style="height: 60px;border-radius: 6px 6px 6px 6px; border:1px solid #B2B2B2;">
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

						<label>Rol:</label>   

							${usuario.ro_nom}
					</td>
					<td>
						${moment(usuario.au_permiso).format('DD-MM-YYYY')}
					</td>
					<td class="text-right">
						<a class="showAuto"  onclick="showAutorizacion(${usuario.au_id},${usuario.us_ced})" href="#" data-toggle="modal" data-target="#showAuto"> 
							<img src="assets/img/iconos/ver.svg" class="imgmenuho" >
						</a>
						<div class="modal fade" id="showAuto" tabindex="-1" role="dialog" aria-labelledby="EditarPermiso" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content text-center">
									<div class="panel panel-default">
										<div class="panel-heading">
											<h3 class="panel-title">
												<b>DETALLES DE LA AUTORIZACIÓN</b>
												<button type="button" class="close" data-dismiss="modal">
					    							<span aria-hidden="true">×</span>
										        </button>
											</h3>
										</div>
										<div class="panel-body">
											<ul class="list-group text-left" id="autorizacionShow">
		
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
						<a class="editAutorizacion" style="display: none;" onclick="editAutorizacion(${usuario.au_id},${usuario.us_ced})" href="#" data-toggle="modal" data-target="#editAutorizacion"> 
							<img src="assets/img/iconos/editar.svg" class="imgmenuho" >
						</a>
						<div class="modal fade" id="editAutorizacion" tabindex="-1" role="dialog" aria-labelledby="EditarReposo" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content text-center">
									<div class="panel panel-default">
										<div class="panel-heading">
											<h3 class="panel-title">
												<b>EDITAR AUTORIZACIÓN</b>
												<button type="button" class="close" data-dismiss="modal">
					    							<span aria-hidden="true">×</span>
										        </button>
											</h3>
											</div>
											<div class="panel-body">
												<form method="POST" action="updateAutorizacion" >
													<input type="hidden" name="_token" id="_token" value="${token}">
    											<div class="col-lg-6 text-left">
									            	<div class="form-group">
									            		<label>Tipo de autorización</label>
														<select class="form-control" name="autorizacion" id="tipoaut" required>
										                	
										                </select>		                
										            </div>
									            </div>
												<div id="AutorizacionEdit">
													
												</div>
												<div class="col-lg-12" style="border-top: 1px solid #ccc; font-size: 14px;">
													<div class="col-lg-6 text-left" style="margin-top:10px;">
														<button type="button" data-dismiss="modal" class="btn btn-default">
															CERRAR
														</button>
													</div>
													<div class="col-lg-6 text-right" style="margin-top:10px;">
														<button type="submit" name="registrar" id="submitP" class="btn" style="background-color: #48c9b0; color:white;">
															MODIFICAR
														</button>
													</div>
												</div>	
											</form>
										</div>
									</div>
								</div>
							</div>
						</div>
						<a href="#" style="display: none;" class="deleteAuto" onclick="eliminarA(${usuario.au_id})" title="Eliminar reposo">
							<img src="assets/img/iconos/eliminar.svg" style="height: 25px; margin-top: 5px" align="right">
						</a>
					</td>
				</tr>`)
		});
	});
});

document.getElementById("autorizacionBody").addEventListener("DOMNodeInserted", handler, true);
 
function handler(){
    var rol = $('#id_rol').val();
    $.get(`accesos/${rol}`,function(response){
        console.log(response);
        response.forEach(element=>{
            if (element.mod_nom == 'm_excepciones') {
                if (element.pnt_nombre == 'p_autorizacion') {
                    if (element.aco_ac_id == 2) {
                        $('.editAutorizacion').show()
                    }
                    if (element.aco_ac_id == 3) {
                        $('.deleteAuto').show();
                    }
                }
            }
        });
    });
}

$("#desdeAu").change(function(event){ 
	$("#diasAu").val('');
	$('#hastaAu').val('');
	$("#hastaAu").change(function(event){ 
		$("#cedulaAut").val('');
		var token = $('#_token').val();
		var fechaDesde = $('#desdeAu').val();
		var token = $('#_token').val();
		var fechaHasta = $('#hastaAu').val();
		$.get(`Brango/${fechaDesde}/${fechaHasta}`, function(response, cedulaau){ 

		console.log(response)	
		$("#autorizacionBody").empty();

		if (response == '') { $("#autorizacionBody").append(`<br>Disculpe, hay registrada ninguna autorización`)}
		response.forEach(usuario=> {
			$("#autorizacionBody").append(`
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

						<label>Rol:</label>   

							${usuario.ro_nom}
					</td>
					<td>
						${moment(usuario.au_permiso).format('DD-MM-YYYY')}
					</td>
					<td class="text-right">
						<a class="showAuto"  onclick="showAutorizacion(${usuario.au_id},${usuario.us_ced})" href="#" data-toggle="modal" data-target="#showAuto"> 
							<img src="assets/img/iconos/ver.svg" class="imgmenuho" >
						</a>
						<div class="modal fade" id="showAuto" tabindex="-1" role="dialog" aria-labelledby="EditarPermiso" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content text-center">
									<div class="panel panel-default">
										<div class="panel-heading">
											<h3 class="panel-title">
												<b>DETALLES DE LA AUTORIZACIÓN</b>
												<button type="button" class="close" data-dismiss="modal">
					    							<span aria-hidden="true">×</span>
										        </button>
											</h3>
										</div>
										<div class="panel-body">
											<ul class="list-group text-left" id="autorizacionShow">
		
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
						<a class="editAutorizacion" style="display: none;" onclick="editAutorizacion(${usuario.au_id},${usuario.us_ced})" href="#" data-toggle="modal" data-target="#editAutorizacion"> 
							<img src="assets/img/iconos/editar.svg" class="imgmenuho" >
						</a>
						<div class="modal fade" id="editAutorizacion" tabindex="-1" role="dialog" aria-labelledby="EditarReposo" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content text-center">
									<div class="panel panel-default">
										<div class="panel-heading">
											<h3 class="panel-title">
												<b>EDITAR AUTORIZACIÓN</b>
												<button type="button" class="close" data-dismiss="modal">
					    							<span aria-hidden="true">×</span>
										        </button>
											</h3>
											</div>
											<div class="panel-body">
												<form method="POST" action="updateAutorizacion" >
													<input type="hidden" name="_token" id="_token" value="${token}">
    											<div class="col-lg-6 text-left">
									            	<div class="form-group">
									            		<label>Tipo de autorización</label>
														<select class="form-control" name="autorizacion" id="tipoaut" required>
										                	
										                </select>		                
										            </div>
									            </div>
												<div id="AutorizacionEdit">
													
												</div>
												<div class="col-lg-12" style="border-top: 1px solid #ccc; font-size: 14px;">
													<div class="col-lg-6 text-left" style="margin-top:10px;">
														<button type="button" data-dismiss="modal" class="btn btn-default">
															CERRAR
														</button>
													</div>
													<div class="col-lg-6 text-right" style="margin-top:10px;">
														<button type="submit" name="registrar" id="submitP" class="btn" style="background-color: #48c9b0; color:white;">
															MODIFICAR
														</button>
													</div>
												</div>	
											</form>
										</div>
									</div>
								</div>
							</div>
						</div>
						<a href="#" style="display: none;" class="deleteAuto" onclick="eliminarA(${usuario.au_id})" title="Eliminar reposo">
							<img src="assets/img/iconos/eliminar.svg" style="height: 25px; margin-top: 5px" align="right">
						</a>
					</td>
				</tr>`)
		});
		});
	});
});


$('#departamentoAu').change(function(event){
	$('#cedulaAut').val('');
	$.get(`getDepAut/${event.target.value}`, function(response){
		var token = $('#_token').val();
		$("#autorizacionBody").empty();
		if (response == '') { $("#autorizacionBody").append(`<br>Disculpe, hay registrada ninguna autorización`)}
		response.forEach(usuario=> {
			$("#autorizacionBody").append(`
				<tr>
					<td>
						<img src="imagenes2/${usuario.us_foto}"  style="height: 60px;border-radius: 6px 6px 6px 6px; border:1px solid #B2B2B2;">
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

						<label>Rol:</label>   

							${usuario.ro_nom}
					</td>
					<td>
						${moment(usuario.au_permiso).format('DD-MM-YYYY')}
					</td>
					<td class="text-right">
						<a class="showAuto"  onclick="showAutorizacion(${usuario.au_id},${usuario.us_ced})" href="#" data-toggle="modal" data-target="#showAuto"> 
							<img src="assets/img/iconos/ver.svg" class="imgmenuho" >
						</a>
						<div class="modal fade" id="showAuto" tabindex="-1" role="dialog" aria-labelledby="EditarPermiso" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content text-center">
									<div class="panel panel-default">
										<div class="panel-heading">
											<h3 class="panel-title">
												<b>DETALLES DE LA AUTORIZACIÓN</b>
												<button type="button" class="close" data-dismiss="modal">
					    							<span aria-hidden="true">×</span>
										        </button>
											</h3>
										</div>
										<div class="panel-body">
											<ul class="list-group text-left" id="autorizacionShow">
		
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
						<a class="editAutorizacion" style="display: none;" onclick="editAutorizacion(${usuario.au_id},${usuario.us_ced})" href="#" data-toggle="modal" data-target="#editAutorizacion"> 
							<img src="assets/img/iconos/editar.svg" class="imgmenuho" >
						</a>
						<div class="modal fade" id="editAutorizacion" tabindex="-1" role="dialog" aria-labelledby="EditarReposo" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content text-center">
									<div class="panel panel-default">
										<div class="panel-heading">
											<h3 class="panel-title">
												<b>EDITAR AUTORIZACIÓN</b>
												<button type="button" class="close" data-dismiss="modal">
					    							<span aria-hidden="true">×</span>
										        </button>
											</h3>
											</div>
											<div class="panel-body">
												<form method="POST" action="updateAutorizacion" >
													<input type="hidden" name="_token" id="_token" value="${token}">
    											<div class="col-lg-6 text-left">
									            	<div class="form-group">
									            		<label>Tipo de autorización</label>
														<select class="form-control" name="autorizacion" id="tipoaut" required>
										                	
										                </select>		                
										            </div>
									            </div>
												<div id="AutorizacionEdit">
													
												</div>
												<div class="col-lg-12" style="border-top: 1px solid #ccc; font-size: 14px;">
													<div class="col-lg-6 text-left" style="margin-top:10px;">
														<button type="button" data-dismiss="modal" class="btn btn-default">
															CERRAR
														</button>
													</div>
													<div class="col-lg-6 text-right" style="margin-top:10px;">
														<button type="submit" name="registrar" id="submitP" class="btn" style="background-color: #48c9b0; color:white;">
															MODIFICAR
														</button>
													</div>
												</div>	
											</form>
										</div>
									</div>
								</div>
							</div>
						</div>
						<a href="#" style="display: none;" class="deleteAuto" onclick="eliminarA(${usuario.au_id})" title="Eliminar reposo">
							<img src="assets/img/iconos/eliminar.svg" style="height: 25px; margin-top: 5px" align="right">
						</a>
					</td>
				</tr>`)
		});
	});
});
$("#cedulaAut").change(function(event){ 
	var cedula = $('#cedulaAut').val();
	var token = $('#_token').val();
	$.get(`Bcedula/${cedula}`, function(response, cedulaau){ 
		$("#autorizacionBody").empty();
		console.log(response);
		if (response == '') { $("#autorizacionBody").append(`<br>Disculpe, este usuario no tiene registrada ninguna autorización`)}
		response.forEach(usuario=> {
			$("#autorizacionBody").append(`
				<tr>
					<td>
						<img src="imagenes2/${usuario.us_foto}"  style="height: 60px;border-radius: 6px 6px 6px 6px; border:1px solid #B2B2B2;">
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

						<label>Rol:</label>   

							${usuario.ro_nom}
					</td>
					<td>
						${moment(usuario.au_permiso).format('DD-MM-YYYY')}
					</td>
					<td class="text-right">
						<a class="showAuto"  onclick="showAutorizacion(${usuario.au_id},${usuario.us_ced})" href="#" data-toggle="modal" data-target="#showAuto"> 
							<img src="assets/img/iconos/ver.svg" class="imgmenuho" >
						</a>
						<div class="modal fade" id="showAuto" tabindex="-1" role="dialog" aria-labelledby="EditarPermiso" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content text-center">
									<div class="panel panel-default">
										<div class="panel-heading">
											<h3 class="panel-title">
												<b>DETALLES DE LA AUTORIZACIÓN</b>
												<button type="button" class="close" data-dismiss="modal">
					    							<span aria-hidden="true">×</span>
										        </button>
											</h3>
										</div>
										<div class="panel-body">
											<ul class="list-group text-left" id="autorizacionShow">
		
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
						<a class="editAutorizacion" style="display: none;" onclick="editAutorizacion(${usuario.au_id},${usuario.us_ced})" href="#" data-toggle="modal" data-target="#editAutorizacion"> 
							<img src="assets/img/iconos/editar.svg" class="imgmenuho" >
						</a>
						<div class="modal fade" id="editAutorizacion" tabindex="-1" role="dialog" aria-labelledby="EditarReposo" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content text-center">
									<div class="panel panel-default">
										<div class="panel-heading">
											<h3 class="panel-title">
												<b>EDITAR AUTORIZACIÓN</b>
												<button type="button" class="close" data-dismiss="modal">
					    							<span aria-hidden="true">×</span>
										        </button>
											</h3>
											</div>
											<div class="panel-body">
												<form method="POST" action="updateAutorizacion" >
													<input type="hidden" name="_token" id="_token" value="${token}">
    											<div class="col-lg-6 text-left">
									            	<div class="form-group">
									            		<label>Tipo de autorización</label>
														<select class="form-control" name="autorizacion" id="tipoaut" required>
										                	
										                </select>		                
										            </div>
									            </div>
												<div id="AutorizacionEdit">
													
												</div>
												<div class="col-lg-12" style="border-top: 1px solid #ccc; font-size: 14px;">
													<div class="col-lg-6 text-left" style="margin-top:10px;">
														<button type="button" data-dismiss="modal" class="btn btn-default">
															CERRAR
														</button>
													</div>
													<div class="col-lg-6 text-right" style="margin-top:10px;">
														<button type="submit" name="registrar" id="submitP" class="btn" style="background-color: #48c9b0; color:white;">
															MODIFICAR
														</button>
													</div>
												</div>	
											</form>
										</div>
									</div>
								</div>
							</div>
						</div>
						<a href="#" style="display: none;" class="deleteAuto" onclick="eliminarA(${usuario.au_id})" title="Eliminar reposo">
							<img src="assets/img/iconos/eliminar.svg" style="height: 25px; margin-top: 5px" align="right">
						</a>
					</td>
				</tr>`)
		});
	});
});

function showAutorizacion(idAu, idUs){
	$('#autorizacionShow').empty();

	$.get( `buscaAut/${idAu}/${idUs}`, function(response, auto){
		console.log(response)
		if (response == '') { $('#autorizacionShow').append(`Disculpe, existe un error al buscar los detaller de esta autorización`); }
		response.forEach(usuario=>{
			var fecha =moment(usuario.au_permiso).format('DD-MM-YYYY');
			$('#autorizacionShow').append(`
				<li class="list-group-item text-left">
					<label style="font-size: 14px;">
						<img src="imagenes2/${usuario.us_foto}" style="height: 70px; float:left; margin:10px; border-radius: 6px 6px 6px 6px; border:1px solid #B2B2B2;">
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
						${fecha}
					</label>
				</li>
				<li class="list-group-item text-left col-lg-6">
					<label>
						TIPO DE AUTORIZACIÓN
					</label>
				</li>
				<li class="list-group-item text-right col-lg-6">
					<label>
						${usuario.tiau_tipo}
					</label>
				</li>
				<li class="list-group-item text-left col-lg-6">
					<label>
						MOTIVO DE LA AUTORIZACIÓN
					</label>
				</li>
				<li class="list-group-item text-right col-lg-6">
					<label>
						${usuario.au_desc}
					</label>
				</li>`
			)
		});
	});
}
function editAutorizacion(idAu, idUs){
	$.get( `buscaTipoaut`, function(response, auto){
		$("#tipoaut").empty();
		response.forEach(element=> { 
		$("#tipoaut").append(`<option value="${element.tiau_id}">${element.tiau_tipo}</option>`)
		});
	});
	$.get( `buscaAut/${idAu}/${idUs}`, function(response, auto){
		console.log(response)
		$("#AutorizacionEdit").empty();
		response.forEach(element=> { 
			$("#tipoaut").append(`<option selected value="${element.au_tiau_id}">${element.tiau_tipo}</option>`)
			$("#AutorizacionEdit").append(`
				<input type="hidden" name="idAu" value="${element.au_id}">
                <div class="col-lg-6 text-left">
                	<div class="form-group">
						<label>Cédula</label>
						<input type="text" name="apellido" class="form-control" disabled value="${element.us_ced}">
		                
		            </div>
                </div>


				<div class="col-lg-6 text-left">
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
						<label for="inputDepartamento">Departamento</label>
		                <select class="form-control" name="departamento" required>
		                	<option disabled selected>
		                		${element.dp_nombre}
		                	</option>
		                </select>
		            </div>
                </div>
                <div class="col-lg-6 text-left">
                	<div class="form-group">
						<label for="inputFechaini">Fecha de Ingreso</label>
		            	<input type="date" name="fecha" class="form-control" id="permiso" value="${element.au_permiso}" required  required placeholder="yyyy-mm-dd">   
		            </div>
                </div>

				<div class="col-lg-12 text-left">
                	<div class="form-group">
						<label for="inputDiag">Motivo de la Autorización</label>
							<input type="text" name="motivo" class="form-control" value="${element.au_desc}" maxlength="250" title="Por favor ingrese una descripción no mayor a 250 palabras">									            	  
		            </div>
                </div>`)
		});
	});
}

function eliminarA(id) {
	swal ({
		title: "¿Estás seguro?",
		text: "¿Desea eliminar esta autorización?",
		icon: "warning",
		buttons: true,
		dangerMode: true, 
	})
	.then((willDelete) => {
	  	if (willDelete) {
	    	$.get(`autorizacion_delete/${id}`, function(response){
		    	setTimeout('document.location.reload()',1000);
				swal("Poof! Esta autorización ha sido eliminada!", {
		        icon: "success",

		    	});
			});
	  	}
	});
}