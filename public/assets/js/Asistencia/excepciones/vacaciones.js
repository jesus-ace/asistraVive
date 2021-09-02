$("#cedulav").change(function(event){ 
	var cedula = $('#cedulav').val();
$.get(`bvacaciones/${cedula}`, function(response, cedula){ 
	
	if (response == '') { 
		swal("¡Disculpe!", "Usuario no encontrado.",{icon: "warning"});
		$('#cedulav').val('');
		$('#vac_name').val('');
		$('#vac_ape').val('');
		$('#vac_dp').val('');
	}
	else{
		$("#formVaca").empty();
		response.forEach(element=> {
			$("#formVaca").append(`
				<input type="hidden" name="us_id" value="${element.us_ced}">		
				<div class="col-lg-6 text-left">
	            	<div class="form-group">
						<label for="inputNombre">Nombre</label>
						<input type="text" 
						name="nombre" 
						class="form-control" 
						id="vac_name"
						disabled
						value="${element.us_nom}"
						placeholder="Por favor ingrese su nombre">
		                
		            </div>
	            </div>
	            <div class="col-lg-6 text-left">
	            	<div class="form-group">
						<label for="inputApellido">Apellido</label>
						<input type="text" 
						name="apellido" 
						id="vac_ape"
						disabled
						value="${element.us_ape}"
						class="form-control"
						placeholder="Por favor ingrese su apellido">
		                
		            </div>
	            </div>
	            <div class="col-lg-6 text-left">
	            	<div class="form-group">
						<label for="inputDepartamento">Departamento</label>
		                <select class="form-control" name="reposo" required id="vac_dp">
		                	<option selected disabled>${element.dp_nombre}</option>
		                </select>
		            </div>
	            </div>
	            <div class="col-lg-6 text-left">
	            	<div class="form-group">
						<label for="inputFechaini">Fecha de inicio</label>
		            	<input type="date" name="desde" class="form-control" id="InicioV"required>   
		            </div>
	            </div>
	            <div class="col-lg-6 text-left">
	            	<div class="form-group">
						<label for="inputFechafin">Fecha de culmunacion</label>
		            	<input type="date" name="hasta" class="form-control" id="FinV" required>   
		            </div>
	            </div>
	            	<div class="form-group">
	            		<div class="col-lg-3 text-left">
	            			<label id="inputCantidad">Cantidad de vacaciones</label>
	            		</div>
	            		<div class="col-lg-3">
	            			<select name="cantidad" id="cantidad" class="form-control" required>
	            				<option disabled selected>Nro.</option>
	            				<option value="1">1</option>
	            				<option value="2">2</option>
	            			</select>
	            		</div>
	            	</div>
				<div class="col-lg-12 text-left">
					<div class="form-group">
						<label>¿Vacaciones pagadas?</label>
						<label class="radio-inline">
							<h4><input type="radio" name="pagadas" id="si" value="1" checked> Si</h4>
						</label>
						<label class="radio-inline">
							<h4><input type="radio" name="pagadas" id="no" value="2" >No</h4>
						</label>
					</div>
				</div>
				<div class="col-lg-12" style="border-top: 1px solid #ccc; font-size: 14px;">
			    	<div class="col-lg-6 text-left" style="margin-top: 10px;">
			    		<button type="button" data-dismiss="modal" class="btn btn-default">Cerrar</button>
			    	</div>
					<div class="col-lg-6 text-right" style="margin-top: 10px;">
						<button type="submit" name="Registrar" value="Registrar" class="btn" style="background-color: #48c9b0; color:white;" >
							<b>REGISTRAR</b>
						</button>
					</div>
			    </div>`
	    		)
				$('#InicioV').change(function(event){
					var hoy = moment().format("YYYY-MM-DD").split("-");
					var inicio = $('#InicioV').val().split("-");
					var us = element.us_ced;
					var desde = $('#InicioV').val();
					$.get(`buscaPerP/${desde}/${us}`,function(response, excepcion){
						var permiso = response;
						$.get(`buscaRepP/${desde}/${us}`,function(response, excepcion){
							var reposo = response;
							$.get(`buscaVacP/${desde}/${us}`,function(response, excepcion){
								var vacaciones = response;
								$.get(`buscaAuP/${desde}/${us}`,function(response, excepcion){
									var autorizacion = response;
									console.log(permiso, reposo, vacaciones, autorizacion);
									if (permiso == 0 && reposo == 0 && vacaciones == 0 && autorizacion == 0) {						
										var inicio = $('#InicioV').val().split("-");	
										if (inicio[0] >= hoy [0] && inicio[1] >= hoy[1]-1) {
											return true;
										}
										else{
											swal("¡Disculpe!", "La fecha que intenta introducir no pertenece al mes actual, por favor, seleccione la fecha correcta.",{icon: "warning"});
											$('#InicioV').val('');
										}
									}
									else{
										swal("Disculpe, ya existe una excepción para esta fecha");
										$('#InicioV').val('');
									}
								});
							});
						});
					});
				});
				$('#FinV').change(function(event){
					var inicio = $('#InicioV').val().split("-");
					var culminacion = $('#FinV').val().split("-");
					max = parseInt(inicio[1])+2;
					console.log(culminacion[1] , max);
					/*if ($('#InicioV').val() == '') {
						swal("¡Disculpe!", "Debe seleccionar una fecha de inicio.", {icon: "warning"});
						$('#FinV').val('');
					}
					if (culminacion[0] < inicio[0] || culminacion[1] < inicio[1]) {
						swal("¡Disculpe!", "La fecha que intenta introducir es  menor a la fecha de inicio, por favor, intente nuevamente.", {icon: "warning"});
						$('#FinV').val('');
					}
					else{
						if (culminacion[1] == inicio[1] ) {
							if (culminacion[2] < inicio[2]) {
								swal("¡Disculpe!", "La fecha que intenta introducir es  menor a la fecha de inicio, por favor, intente nuevamente.", {icon: "warning"});
								$('#FinV').val('');
							}
						}
						else{
							if (culminacion[1] > max) {
								swal("¡Disculpe!", "Las vacaciones no pueden ser mayores a dos meses, por favor, intente nuevamente.", {icon: "warning"});
								$('#FinV').val('');
							}
						}
					}*/
				});
			});
		}
	});
});

document.getElementById("vacacionesBody").addEventListener("DOMNodeInserted", handler, true);
 
function handler(){
    var rol = $('#id_rol').val();
    $.get(`accesos/${rol}`,function(response){
        console.log(response);
        response.forEach(element=>{
            if (element.mod_nom == 'm_excepciones') {
                if (element.pnt_nombre == 'p_vacaciones') {
                    if (element.aco_ac_id == 2) {
                        $('.editVacaciones').show()
                    }
                    if (element.aco_ac_id == 3) {
                        $('.deleteVac').show();
                    }
                }
            }
        });
    });
}

$('#departamentoV').change(function(event){
	$.get(`getdpVac/${event.target.value}`,function(response){
		console.log(response);
		var token = $('#_token').val();
		$("#vacacionesBody").empty();
		if (response == '') { $("#vacacionesBody").append(`<tr><td><br>Disculpe, no se encontraron resultados</tr></td>`)}
		
		response.forEach(usuario=> { 
			$("#vacacionesBody").append(`
				<tr>
					<td>
						<img src="imagenes2/${usuario.us_foto}"   style="height: 60px;border-radius: 6px 6px 6px 6px; border:1px solid #B2B2B2;">
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
						${usuario.vac_fecha_ini}
					</td>
					<td>
						${usuario.vac_fecha_fin}
					</td>
					<td class="text-right">
							<a class="showPermiso"  onclick="showVacaciones(${usuario.vac_id},${usuario.us_ced})" href="#" data-toggle="modal" data-target="#showPermiso"> 
								<img src="assets/img/iconos/ver.svg" class="imgmenuho" >
							</a>
							<div class="modal fade" id="showPermiso" tabindex="-1" role="dialog" aria-labelledby="EditarPermiso" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content text-center">
										<div class="panel panel-default">
  											<div class="panel-heading" style="background-color: #e5e8e8;">
    											<h3 class="panel-title">
    												<b>DETALLES DE LAS VACACIONES</b>
    												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    											</h3>
  											</div>
  											<div class="panel-body">
    											<ul class="list-group text-left" id="vacacionesShow">
													
												</ul>
  											</div>
										</div>
									</div>
								</div>
							</div>
							<a class="editVacaciones" style="display: none;" onclick="editVacaciones(${usuario.vac_id},${usuario.us_ced})" href="#" data-toggle="modal" data-target="#editVacaciones"> 
								<img src="assets/img/iconos/editar.svg" class="imgmenuho" >
							</a>
							<div class="modal fade" id="editVacaciones" tabindex="-1" role="dialog" aria-labelledby="EditarPermiso" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content text-center">
										<div class="panel panel-default">
  											<div class="panel-heading" style="background-color: #e5e8e8;">
    											<h3 class="panel-title">
    												<b>EDITAR VACACIONES</b>
    												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    											</h3>
  											</div>
  											<div class="panel-body">
    											<form method="POST" action="updateVacaciones" >
    												<input type="hidden" name="_token" id="_token" value="${token}">
    												<div id="VacacionesEdit">
				
													</div>
													<div class="col-lg-12" style="border-top: 1px solid #ccc; font-size: 14px;">
														<div class="col-lg-6 text-left" style="margin-top: 10px;">
															<button class="btn btn-default" type="button" data-dismiss="modal">CERRAR</button>
														</div>
														<div class="col-lg-6 text-right" style="margin-top: 10px;">
															<button type="submit" name="registrar" id="submitP" value="Modificar" class="btn" style="background-color: #48c9b0; color:white;">
																<b>MODIFICAR</b>
															</button>
														</div>
													</div>
    											</form>
  											</div>
										</div>
									</div>
								</div>
							</div>
							<a href="#" onclick="eliminarR(${usuario.vac_id})" title="Eliminar vacaciones" class="deleteVac" style="display:none;">
								<img src="assets/img/iconos/eliminar.svg" style="height: 25px; margin-top: 5px" align="right">
							</a>
				</tr>`)
		});
	});
});

$("#cedulavac").change(function(event){ 
	var cedula = $('#cedulavac').val();
	$.get(`busuario/${cedula}`, function(response, cedula){ 
		console.log(response);
		var token = $('#_token').val();
		$("#vacacionesBody").empty();
		if (response == '') { $("#vacacionesBody").append(`<tr><td><br>Disculpe, este usuario no tiene vacaciones registradas</tr></td>`)}
		
		response.forEach(usuario=> { 
			$("#vacacionesBody").append(`
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
						${usuario.vac_fecha_ini}
					</td>
					<td>
						${usuario.vac_fecha_fin}
					</td>
					<td class="text-right">
							<a class="showPermiso"  onclick="showVacaciones(${usuario.vac_id},${usuario.us_ced})" href="#" data-toggle="modal" data-target="#showPermiso"> 
								<img src="assets/img/iconos/ver.svg" class="imgmenuho" >
							</a>
							<div class="modal fade" id="showPermiso" tabindex="-1" role="dialog" aria-labelledby="EditarPermiso" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content text-center">
										<div class="panel panel-default">
  											<div class="panel-heading" style="background-color: #e5e8e8;">
    											<h3 class="panel-title">
    												<b>DETALLES DE LAS VACACIONES</b>
    												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    											</h3>
  											</div>
  											<div class="panel-body">
    											<ul class="list-group text-left" id="vacacionesShow">
													
												</ul>
  											</div>
										</div>
									</div>
								</div>
							</div>
							<a class="editVacaciones" style="display: none;" onclick="editVacaciones(${usuario.vac_id},${usuario.us_ced})" href="#" data-toggle="modal" data-target="#editVacaciones"> 
								<img src="assets/img/iconos/editar.svg" class="imgmenuho" >
							</a>
							<div class="modal fade" id="editVacaciones" tabindex="-1" role="dialog" aria-labelledby="EditarPermiso" aria-hidden="true">
								<div class="modal-dialog">
									div class="modal-content text-center">
										<div class="panel panel-default">
  											<div class="panel-heading" style="background-color: #e5e8e8;">
    											<h3 class="panel-title">
    												<b>EDITAR VACACIONES</b>
    												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    											</h3>
  											</div>
  											<div class="panel-body">
    											<form method="POST" action="updateVacaciones" >
    												<input type="hidden" name="_token" id="_token" value="${token}">
    												<div id="VacacionesEdit">
				
													</div>
													<div class="col-lg-12" style="border-top: 1px solid #ccc; font-size: 14px;">
														<div class="col-lg-6 text-left" style="margin-top: 10px;">
															<button class="btn btn-default" type="button" data-dismiss="modal">CERRAR</button>
														</div>
														<div class="col-lg-6 text-right" style="margin-top: 10px;">
															<button type="submit" name="registrar" id="submitP" value="Modificar" class="btn" style="background-color: #48c9b0; color:white;">
																<b>MODIFICAR</b>
															</button>
														</div>
													</div>
    											</form>
  											</div>
										</div>
									</div>
								</div>
							</div>
							<a href="#" onclick="eliminarR(${usuario.vac_id})" title="Eliminar vacaciones" class="deleteVac" style="display:none;">
								<img src="assets/img/iconos/eliminar.svg" style="height: 25px; margin-top: 5px" align="right">
							</a>
						</td>
				</tr>`)
		});
	});
});

function showVacaciones(idV,idU) {
	$.get(`buscarVac/${idV}/${idU}`,function(response, vacaciones){
		$('#vacacionesShow').empty();
		response.forEach(usuario =>{
			if (usuario.vac_pago == 1) {
				var pagadas = 'Si';
			}
			else{
				pagadas = 'No';
			}
			var fechaIni =moment(usuario.vac_fecha_ini).format('DD-MM-YYYY');
			var fechaFin = moment(usuario.vac_fecha_fin).format('DD-MM-YYYY');
			$('#vacacionesShow').append(`
			<li class="list-group-item text-left">
					<label style="font-size: 14px;">
						<img src="imagenes2/${usuario.us_foto}" style="height: 70px; float:left; margin:10px; border-radius: 6px 6px 6px 6px; border:1px solid #B2B2B2;"" >
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
						CANTIDAD DE VACACIONES
					</label>
				</li>
				<li class="list-group-item text-right col-lg-6">
					<label>
						${usuario.vac_cant}
					</label>
				</li>
				<li class="list-group-item text-left col-lg-6">
					<label>
						¿PAGADAS?
					</label>
				</li>
				<li class="list-group-item text-right col-lg-6">
					<label>
						${pagadas}
					</label>
				</li>`
			)
		}); 
	});
}
function editVacaciones(idV,idU) {
	$.get(`buscarVac/${idV}/${idU}`,function(response, vacaciones){
		console.log(response);
		$("#VacacionesEdit").empty();
		response.forEach(element=> {

		if (element.vac_pago == 1) {
				var selected = 'checked';
			}
			else{
				var select = 'checked';
			}
			$("#VacacionesEdit").append(`
				<input type="hidden" name="Vacid" value="${element.vac_id}">		
				<div class="col-lg-6 text-left">
	            	<div class="form-group">
						<label for="inputNombre">Nombre</label>
						<input type="text" 
						name="nombre" class="
						form-control" 
						disabled
						value="${element.us_nom}"
						placeholder="Por favor ingrese su nombre">
		                
		            </div>
	            </div>
	            <div class="col-lg-6 text-left">
	            	<div class="form-group">
						<label for="inputApellido">Apellido</label>
						<input type="text" 
						name="apellido" 
						disabled
						value="${element.us_ape}"
						class="form-control"
						placeholder="Por favor ingrese su apellido">
		                
		            </div>
	            </div>
	            <div class="col-lg-6 text-left">
	            	<div class="form-group">
						<label>Departamento</label>
		                <select class="form-control" name="reposo" required>
		                	<option selected disabled>${element.dp_nombre}</option>
		                </select>
		            </div>
	            </div>
	            <div class="col-lg-6 text-left">
	            	<div class="form-group">
						<label>Fecha de inicio</label>
		            	<input type="date" name="desde" class="form-control" id="InicioV" value="${element.vac_fecha_ini}" required placeholder="yyyy-mm-dd">   
		            </div>
	            </div>
	            <div class="col-lg-6 text-left">
	            	<div class="form-group">
						<label for="inputFechafin">Fecha de culmunación</label>
		            	<input type="date" name="hasta" class="form-control" id="FinV" value="${element.vac_fecha_fin}" required placeholder="yyyy-mm-dd">   
		            </div>
	            </div>
	            	<div class="form-group">
	            		<div class="col-lg-3 text-left">
	            			<label id="inputCantidad">Cantidad de vacaciones</label>
	            		</div>
	            		<div class="col-lg-3">
	            			<select name="cantidad" id="cantidad" class="form-control" required>
	            				<option selected>${element.vac_cant}</option>
	            				<option value="1">1</option>
	            				<option value="2">2</option>
	            			</select>
	            		</div>
	            	</div>
				<div class="col-lg-12 text-left">
					<div class="form-group">
						<label>¿Vacaciones pagadas?</label>
						<label class="radio-inline">
							<h4><input type="radio" name="pagadas" id="si" value="1" ${selected}> Si</h4>
						</label>
						<label class="radio-inline">
							<h4><input type="radio" name="pagadas" id="no" value="2" ${select}>No</h4>
						</label>
					</div>
				</div>`)
				var hoy = moment().format("YYYY-MM-DD").split("-");
				$('#InicioV').change(function(event){
				var hoy = moment().format("YYYY-MM-DD").split("-");
				var inicio = $('#InicioV').val().split("-");
				var us = element.us_ced;
				var desde = $('#InicioV').val();
				$.get(`buscaPerP/${desde}/${us}`,function(response, excepcion){
					var permiso = response;
					$.get(`buscaRepP/${desde}/${us}`,function(response, excepcion){
						var reposo = response;
						$.get(`buscaVacP/${desde}/${us}`,function(response, excepcion){
							var vacaciones = response;
							$.get(`buscaAuP/${desde}/${us}`,function(response, excepcion){
								var autorizacion = response;
								console.log(permiso, reposo, vacaciones, autorizacion);
								if (permiso == 0 && reposo == 0 && vacaciones == 0 && autorizacion == 0) {						
									var inicio = $('#InicioV').val().split("-");	
									if (inicio[0] >= hoy [0] && inicio[1] >= hoy[1]) {
										return true;
									}
									else{
										swal("¡Disculpe!", "La fecha que intenta introducir no pertenece al mes actual, por favor, seleccione la fecha correcta.",{icon: "warning"});
										$('#InicioV').val('');
									}
								}
								else{
									swal("Disculpe, ya existe una excepción para esta fecha");
									$('#InicioV').val('');
								}
							});
						});
					});
				});
			});
			$('#FinV').change(function(event){
				var inicio = $('#InicioV').val().split("-");
				var culminacion = $('#FinV').val().split("-");
				max = parseInt(inicio[1])+2;
				console.log(culminacion[1] , max);
				if ($('#InicioV').val() == '') {
					swal("¡Disculpe!", "Debe seleccionar una fecha de inicio.", {icon: "warning"});
					$('#FinV').val('');
				}
				if (culminacion[0] < inicio[0] || culminacion[1] < inicio[1]) {
					swal("¡Disculpe!", "La fecha que intenta introducir es  menor a la fecha de inicio, por favor, intente nuevamente.", {icon: "warning"});
					$('#FinV').val('');
				}
				else{
					if (culminacion[1] == inicio[1] ) {
						if (culminacion[2] < inicio[2]) {
							swal("¡Disculpe!", "La fecha que intenta introducir es  menor a la fecha de inicio, por favor, intente nuevamente.", {icon: "warning"});
							$('#FinV').val('');
						}
					}
					else{
						if (culminacion[1] > max) {
							swal("¡Disculpe!", "Las vacaciones no pueden ser mayores a dos meses, por favor, intente nuevamente.", {icon: "warning"});
							$('#FinV').val('');
						}
					}
				}
			});
		});
	});
}
$(document).ready(function() {
    setTimeout(function() {
        $("#alerta").slideUp(1500);
    },3000);
});


function eliminaV(id) {
	swal ({
		title: "¿Estás seguro?",
		text: "¿Deseas eliminar estas vacaciones?",
		icon: "warning",
		buttons: true,
		dangerMode: true, 
	})
	.then((willDelete) => {
	  	if (willDelete) {
	    	$.get(`vacaciones_delete/${id}`, function(response){
		    	setTimeout('document.location.reload()',1000);
				swal("Poof! Estas vacaciones han sido eliminadas!", {
		        icon: "success",

		    	});
			});
	  	}
	});
}