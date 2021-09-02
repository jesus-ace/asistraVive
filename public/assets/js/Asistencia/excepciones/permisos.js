$("#cedulaper").change(function(event){ 
	var cedula = $('#cedulaper').val();
	$.get(`regper/${cedula}`, function(response, cedulaper){ 
		console.log(response);
		if (response == '') { 
			swal("¡Disculpe!", "Usuario no encontrado.",{icon: "warning"});
			$('#cedulaper').val('');
			$('#pe_name').val('');
			$('#pe_ape').val('');
			$('#pe_ced').val('');
			$('#pe_dp').val('');
		}
		else{
		$("#formper").empty();
		response.forEach(usuario=> { 
			$("#formper").append(`
				<input type="hidden" name="id" value="${usuario.us_ced}">
				<div class="col-lg-6 text-left">
	            	<div class="form-group">
						<label for="inputNombre">Nombre</label>
						<input type="text" name="nombre" class="form-control" id="pe_name" disabled value="${usuario.us_nom}">
		                
		            </div>
	            </div>
	            <div class="col-lg-6 text-left">
	            	<div class="form-group">
						<label for="inputApellido">Apellido</label>
						<input type="text" name="apellido" class="form-control" id="pe_ape"disabled value="${usuario.us_ape}">
		                
		            </div>
	            </div>
	            <div class="col-lg-6 text-left">
	            	<div class="form-group">
						<label for="inputCedula">Cédula</label>
						<input type="nomber" name="cedula" class="form-control" id="pe_ced" disabled value="${usuario.us_ced	}">
		                
		            </div>
	            </div>
	            <div class="col-lg-6 text-left">
	            	<div class="form-group">
						<label for="inputDepartamento">Departamento</label>
		                <select class="form-control" name="reposo" id="pe_dp">
		                	<option  disabled selected>${usuario.dp_nombre}</option>
		                	
		                </select>
		            </div>
	            </div>
                <div class="col-lg-6 text-left">
                	<div class="form-group">
						<label for="inputFechaini">Fecha de inicio</label>
		            	<input type="date" name="desde" class="form-control" id="fechaIniP" required>   
		            </div>
                </div>
                <div class="col-lg-6 text-left">
                	<div class="form-group">
						<label for="inputFechafin">Fecha de culmunacion</label>
		            	<input type="date" name="hasta" class="form-control"  id="fechaFinP" required>   
		            </div>
                </div>
                <div class="col-lg-6 text-left">
                	<div class="form-group">
						<label for="inputAdjunto">Añadir archivo adjunto</label>
		            	<input type="file" name="file" class="form-control"  accept="application/pdf" id="adjunto">   
		            </div>
                </div>
                <div class="col-lg-6 text-left">
                	<div class="form-group">
						<label for="inputDiag">Descripción</label>
							<input type="text" name="descripcion" class="form-control" placeholder="Indique la descripción del permiso" maxlength="250" title="Por favor ingrese una descripción no mayor a 250 palabras">								            	  
		            </div>
                </div>
				<div class="col-lg-12">
					<div class="form-group">
						<label>¿El permiso es remunerado?</label>
						<label class="radio-inline">
							<h4><input type="radio" name="remunerado" id="siP" value="1" checked> Si</h4>
						</label>
						<label class="radio-inline">
							<h4><input type="radio" name="remunerado" id="noP" value="2" >No</h4>
						</label>
					</div>
				</div>
				<!-- Modal Footer -->
			    <div class="col-lg-12" style="border-top: 1px solid #ccc; font-size: 14px;">
			    	<div class="col-lg-6 text-left"   style="margin-top: 10px;">
			        	<button type="button" data-dismiss=modal class="btn btn-default">CERRAR</button>
			    	</div> 
					<div class="col-lg-6 text-right"  style="margin-top: 10px;">
						<button type="submit" name="Registrar" value="Registrar" class="btn" style="background-color: #48c9b0; color:white;" > <b>REGISTRAR</b> </button>												
					</div>												
				</div>`
				)
			var hoy = moment().format("YYYY-MM-DD").split("-");
			$('#fechaIniP').change(function(event){
				var us = usuario.us_ced;
				var desde = $('#fechaIniP').val();
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
									var inicio = $('#fechaIniP').val().split("-");	
									console.log(hoy[1]-1)
									if (inicio[0] >= hoy [0] && inicio[1] >= hoy[1]-1) {
										return true;
									}
									else{
										swal("¡Disculpe!", "La fecha que intenta introducir no pertenece al mes actual, por favor, seleccione la fecha correcta.",{icon: "warning"});
										$('#fechaIniP').val('');
									}
								}
								else{
									swal("Disculpe, ya existe una excepción para esta fecha");
									$('#fechaIniP').val('');
								}
							});
						});
					});
				});
			
			});
			$('#fechaFinP').change(function(event){
				var inicio = $('#fechaIniP').val().split("-");
				var culminacion = $('#fechaFinP').val().split("-");
				max = parseInt(inicio[1])+3;
				console.log(culminacion[1] , max);
				if ($('#fechaIniP').val() == '') {
					swal("¡Disculpe!", "Debe seleccionar una fecha de inicio.", {icon: "warning"});
					$('#fechaFinP').val('');
				}
				if (culminacion[0] < inicio[0] || culminacion[1] < inicio[1]) {
					swal("¡Disculpe!", "La fecha que intenta introducir es  menor a la fecha de inicio, por favor, intente nuevamente.", {icon: "warning"});
					$('#fechaFinP').val('');
				}
				else{
					if (culminacion[1] == inicio[1] ) {
						if (culminacion[2] < inicio[2]) {
							swal("¡Disculpe!", "La fecha que intenta introducir es  menor a la fecha de inicio, por favor, intente nuevamente.", {icon: "warning"});
							$('#fechaFinP').val('');
						}
					}
					else{
						if (culminacion[1] > max) {
							swal("¡Disculpe!", "No puede registrar un permiso mayor a tres meses, por favor, intente nuevamente.", {icon: "warning"});
							$('#fechaFinP').val('');
						}
					}
				}
			});

		});
		}
	});
});


document.getElementById("permisobody").addEventListener("DOMNodeInserted", handler, true);
 
function handler(){
    var rol = $('#id_rol').val();
    $.get(`accesos/${rol}`,function(response){
        console.log(response);
        response.forEach(element=>{
            if (element.mod_nom == 'm_excepciones') {
                if (element.pnt_nombre == 'p_permisos') {
                    if (element.aco_ac_id == 2) {
                        $('.editPermiso').show();
                    }
                    if (element.aco_ac_id == 3) {
                        $('.deletePermiso').show();
                    }
                }
            }
        });
    });
}
 function CambiaDepBody() {
 	$("#CedulaPermisoBody").val('');
	var token = $("#_token").val();
	$.get(`getdepp/${event.target.value}`, function(response) {
		console.log(response);
		$("#permisobody").empty();
		if (response == '') {
			$("#permisobody").append(`<tr>
						<td>
							<strong>Disculpe,</strong> no se encontraron resultados...
						</td>
					</tr>`)
		}
		else{
			response.forEach(usuario=> { 
				fechaIni = moment(usuario.per_fecha_ini).format('DD-MM-YYYY');
				fechaFin = moment(usuario.per_fecha_fin).format('DD-MM-YYYY')
			$("#permisobody").append(`<tr>
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
						${fechaIni} / ${fechaFin}
					</td>
					<td class="text-right">
						<a class="showPermiso"  onclick="showPermiso(${usuario.per_id},${usuario.us_ced})" href="#" data-toggle="modal" data-target="#showPermiso"> 
							<img src="assets/img/iconos/ver.svg" class="imgmenuho" >
						</a>
						<div class="modal fade" id="showPermiso" tabindex="-1" role="dialog" aria-labelledby="EditarPermiso" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="panel panel-default">
										<div class="panel-heading text-center" style="background-color: #e5e8e8;">
											<h3 class="panel-title">
												<b>
													DETALLES DEL PERMISO
												</b>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
											</h3>
											</div>
											<div class="panel-body">
											<ul class="list-group text-left" id="permisoShow">
		
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
						<a class="editPermiso" style="display: none;" onclick="editPermiso(${usuario.per_id},${usuario.us_ced})" href="#" data-toggle="modal" data-target="#editPermiso"> 
							<img src="assets/img/iconos/editar.svg" class="imgmenuho" >
						</a>
						<div class="modal fade" id="editPermiso" tabindex="-1" role="dialog" aria-labelledby="EditarPermiso" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content text-center">
									<div class="panel panel-default">
											<div class="panel-heading" style="background-color: #e5e8e8;">
												<h3 class="panel-title">
													<b>EDITAR PERMISO</b>
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												</h3>
											</div>
  										<div class="panel-body">
    										<form method="POST" action="updatePermiso" >
												<input type="hidden" name="_token" id="_token" value="${token}">
												<div class="row">
													<div id="PermisoEdit">
														
													</div>										 	
										        </div>
												<div class="col-lg-12" style="border-top: 1px solid #ccc; font-size: 14px;">
													<div class="col-lg-6 text-left" style="margin-top: 10px;">
														<button class="btn btn-default" data-dismiss="modal">CERRAR</button>
													</div>
													<div class=" col-lg-6 text-right" style="margin-top: 10px;">
														<button type="submit" name="registrar" id="submitP" value="Modificar" class="btn"  style="background-color: #48c9b0; color:white;" >
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
					</td>
					<td>
						<a href="#" class="deletePermiso" style="display: none;" onclick="eliminarR(${usuario.pe_id})" title="Eliminar reposo">
							<img src="assets/img/iconos/eliminar.svg" style="height: 25px; margin-top: 5px" align="right">
						</a>
					</td>
				</tr>`)
			});
		}
	});
 }


function CedulaPermisoBody() {
	var token = $("#_token").val();
	var cedula = $("#CedulaPermisoBody").val();
	$.get(`buscaCedPer/${cedula}`,function(response, permiso){
		console.log(response);
		$("#permisobody").empty();
		$('#departamentoP').val('');
		if (response == '') {
			$("#permisobody").append(`<tr>
						<td>
							<strong>Disculpe,</strong> no se encontraron resultados...
						</td>
					</tr>`)
		}
		else{
			response.forEach(usuario=> { 
				fechaIni = moment(usuario.per_fecha_ini).format('DD-MM-YYYY');
				fechaFin = moment(usuario.per_fecha_fin).format('DD-MM-YYYY')
			$("#permisobody").append(`<tr>
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
						${fechaIni} / ${fechaFin}
					</td>
					<td class="text-right">
						<a class="showPermiso"  onclick="showPermiso(${usuario.per_id},${usuario.us_ced})" href="#" data-toggle="modal" data-target="#showPermiso"> 
							<img src="assets/img/iconos/ver.svg" class="imgmenuho" >
						</a>
						<div class="modal fade" id="showPermiso" tabindex="-1" role="dialog" aria-labelledby="EditarPermiso" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="panel panel-default">
										<div class="panel-heading text-center" style="background-color: #e5e8e8;">
											<h3 class="panel-title">
												<b>
													DETALLES DEL PERMISO
												</b>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
											</h3>
											</div>
											<div class="panel-body">
											<ul class="list-group text-left" id="permisoShow">
		
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
						<a class="editPermiso" style="display: none;" onclick="editPermiso(${usuario.per_id},${usuario.us_ced})" href="#" data-toggle="modal" data-target="#editPermiso"> 
							<img src="assets/img/iconos/editar.svg" class="imgmenuho" >
						</a>
						<div class="modal fade" id="editPermiso" tabindex="-1" role="dialog" aria-labelledby="EditarPermiso" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content text-center">
									<div class="panel panel-default">
											<div class="panel-heading" style="background-color: #e5e8e8;">
												<h3 class="panel-title">
													<b>EDITAR PERMISO</b>
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												</h3>
											</div>
  										<div class="panel-body">
    										<form method="POST" action="updatePermiso" >
												<input type="hidden" name="_token" id="_token" value="${token}">
												<div class="row">
													<div id="PermisoEdit">
														
													</div>										 	
										        </div>
												<div class="col-lg-12" style="border-top: 1px solid #ccc; font-size: 14px;">
													<div class="col-lg-6 text-left" style="margin-top: 10px;">
														<button class="btn btn-default" data-dismiss="modal">CERRAR</button>
													</div>
													<div class=" col-lg-6 text-right" style="margin-top: 10px;">
														<button type="submit" name="registrar" id="submitP" value="Modificar" class="btn"  style="background-color: #48c9b0; color:white;" >
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
					</td>
					<td>
						<a href="#" class="deletePermiso" style="display: none;" onclick="eliminarR(${usuario.pe_id})" title="Eliminar reposo">
							<img src="assets/img/iconos/eliminar.svg" style="height: 25px; margin-top: 5px" align="right">
						</a>
					</td>
				</tr>`)
			});
		}		
	});
}

$('#refrescarp').click(function(event){
	$("#cedulaP").val('');
	$('#departamentoP').val('');
	var token = $("#_token").val();
	$.get(`refrescarP`,function(response, todos){
		$("#permisobody").empty();
		console.log(response);
		response.forEach(usuario=>{
			fechaIni = moment(usuario.per_fecha_ini).format('DD-MM-YYYY');
				fechaFin = moment(usuario.per_fecha_fin).format('DD-MM-YYYY')
			$("#permisobody").append(`<tr>
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
						${fechaIni} / ${fechaFin}
					</td>
					<td class="text-right">
						<a class="showPermiso"  onclick="showPermiso(${usuario.per_id},${usuario.us_ced})" href="#" data-toggle="modal" data-target="#showPermiso"> 
							<img src="assets/img/iconos/ver.svg" class="imgmenuho" >
						</a>
						<div class="modal fade" id="showPermiso" tabindex="-1" role="dialog" aria-labelledby="EditarPermiso" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="panel panel-default">
										<div class="panel-heading text-center" style="background-color: #e5e8e8;">
											<h3 class="panel-title">
												<b>
													DETALLES DEL PERMISO
												</b>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
											</h3>
											</div>
											<div class="panel-body">
											<ul class="list-group text-left" id="permisoShow">
		
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
						<a class="editPermiso" style="display: none;" onclick="editPermiso(${usuario.per_id},${usuario.us_ced})" href="#" data-toggle="modal" data-target="#editPermiso"> 
							<img src="assets/img/iconos/editar.svg" class="imgmenuho" >
						</a>
						<div class="modal fade" id="editPermiso" tabindex="-1" role="dialog" aria-labelledby="EditarPermiso" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content text-center">
									<div class="panel panel-default">
											<div class="panel-heading" style="background-color: #e5e8e8;">
												<h3 class="panel-title">
													<b>EDITAR PERMISO</b>
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												</h3>
											</div>
  										<div class="panel-body">
    										<form method="POST" action="updatePermiso" >
												<input type="hidden" name="_token" id="_token" value="${token}">
												<div class="row">
													<div id="PermisoEdit">
														
													</div>										 	
										        </div>
												<div class="col-lg-12" style="border-top: 1px solid #ccc; font-size: 14px;">
													<div class="col-lg-6 text-left" style="margin-top: 10px;">
														<button class="btn btn-default" data-dismiss="modal">CERRAR</button>
													</div>
													<div class=" col-lg-6 text-right" style="margin-top: 10px;">
														<button type="submit" name="registrar" id="submitP" value="Modificar" class="btn"  style="background-color: #48c9b0; color:white;" >
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
					</td>
					<td>
						<a href="#" class="deletePermiso" style="display: none;" onclick="eliminarR(${usuario.pe_id})" title="Eliminar reposo">
							<img src="assets/img/iconos/eliminar.svg" style="height: 25px; margin-top: 5px" align="right">
						</a>
					</td>
				</tr>`)
				
		});
	});
});
function editPermiso(idP,idUs) {
	console.log(idP,idUs)
	$.get(`tipoRe`, function(response, tipo){
		console.log(response);
	});
	$.get(`buscaPermiso/${idP}/${idUs}`,function(response, permiso){
		console.log(response);
		$('#PermisoEdit').empty();
		response.forEach(usuario=>{
			if (usuario.per_remunerado == 1) {
				var selected = 'checked';
			}
			else{
				var select ='checked';
			}
			$('#PermisoEdit').append(`<input type="hidden" name="id" value="${usuario.us_id}">
				<input type="hidden" name="perId" value="${usuario.per_id}">
				<div class="col-lg-6 text-left">
	            	<div class="form-group">
						<label for="inputNombre">Nombre</label>
						<input type="text" name="nombre" class="form-control" placeholder="Por favor ingrese su nombre" disabled value="${usuario.us_nom}">
		                
		            </div>
	            </div>
	            <div class="col-lg-6 text-left">
	            	<div class="form-group">
						<label for="inputApellido">Apellido</label>
						<input type="text" name="apellido" class="form-control" placeholder="Por favor ingrese su apellido" disabled value="${usuario.us_ape}">
		                
		            </div>
	            </div>
	            <div class="col-lg-6 text-left">
	            	<div class="form-group">
						<label for="inputCedula">Cédula</label>
						<input type="nomber" name="cedula" class="form-control" placeholder="Por favor ingrese su cédula" disabled value="${usuario.us_nom}">
		                
		            </div>
	            </div>
	            <div class="col-lg-6 text-left">
	            	<div class="form-group">
						<label for="inputDepartamento">Departamento</label>
		                <select class="form-control" name="reposo" >
		                	<option  disabled selected>${usuario.dp_nombre}</option>
		                	
		                </select>
		            </div>
	            </div>
                <div class="col-lg-6 text-left">
                	<div class="form-group">
						<label for="inputFechaini">Fecha de inicio</label>
		            	<input type="date" name="desde" class="form-control" required value="${usuario.per_fecha_ini}" id="fechaIniPE" placeholder="yyyy-mm-dd">   
		            </div>
                </div>
                <div class="col-lg-6 text-left">
                	<div class="form-group">
						<label for="inputFechafin">Fecha de culmunación</label>
		            	<input type="date" name="hasta" class="form-control" required value="${usuario.per_fecha_fin}" id="fechaFinPE" placeholder="yyyy-mm-dd">   
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
						<label for="inputDiag">Descripción</label>
							<input type="text" name="descripcion" class="form-control" placeholder="Indique la descripción del permiso" maxlength="250" title="Por favor ingrese una descripción no mayor a 250 palabras" value="${usuario.per_desc}">								            	  
		            </div>
                </div>
				<div class="col-lg-12">
					<div class="form-group">
						<label>¿El permiso es remunerado?</label>
						<label class="radio-inline">
							<h4><input type="radio" name="remunerado" id="si" value="1" ${selected}> Si</h4>
						</label>
						<label class="radio-inline">
							<h4><input type="radio" name="remunerado" id="no" value="2" ${select}>No</h4>
						</label>
					</div>
				</div>`)
				$('#fechaIniPE').change(function(event){
				var hoy = moment().format('DD-MM-YYYY').split("-");
				var inicio = $('#fechaIniPE').val().split("-");
				var us = usuario.us_ced;
				var desde = $('#fechaIniPE').val();
				console.log(inicio[0],hoy[2])
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
									var inicio = $('#fechaIniPE').val().split("-");	
									if (inicio[0] >= hoy [0] && inicio[1] >= hoy[1]) {
										return true;
									}
									else{
										swal("¡Disculpe!", "La fecha que intenta introducir no pertenece al mes actual, por favor, seleccione la fecha correcta.",{icon: "warning"});
										$('#fechaIniPE').val('');
									}
								}
								else{
									swal("Disculpe, ya existe una excepción para esta fecha");
									$('#fechaIniPE').val('');
								}
							});
						});
					});
				});
			});
			$('#fechaFinPE').change(function(event){
				var inicio = $('#fechaIniPE').val().split("-");
				var culminacion = $('#fechaFinPE').val().split("-");
				max = parseInt(inicio[1])+3;
				console.log(culminacion[1] , max);
				if ($('#fechaIniPE').val() == '') {
					swal("¡Disculpe!", "Debe seleccionar una fecha de inicio.", {icon: "warning"});
					$('#fechaFinPE').val('');
				}
				if (culminacion[0] < inicio[0] || culminacion[1] < inicio[1]) {
					swal("¡Disculpe!", "La fecha que intenta introducir es  menor a la fecha de inicio, por favor, intente nuevamente.", {icon: "warning"});
					$('#fechaFinPE').val('');
				}
				else{
					if (culminacion[1] == inicio[1] ) {
						if (culminacion[2] < inicio[2]) {
							swal("¡Disculpe!", "La fecha que intenta introducir es  menor a la fecha de inicio, por favor, intente nuevamente.", {icon: "warning"});
							$('#fechaFinPE').val('');
						}
					}
					else{
						if (culminacion[1] > max) {
							swal("¡Disculpe!", "No puede registrar un permiso mayor a tres meses, por favor, intente nuevamente.", {icon: "warning"});
							$('#fechaFinPE').val('');
						}
					}
				}
			});
		});
	});
}
function showPermiso(idP,idUs) {
	$.get(`buscaPermiso/${idP}/${idUs}`,function(response, permiso){
		console.log(response);
		$('#permisoShow').empty();
		response.forEach(usuario =>{
			if (usuario.per_remunerado == 1) {
				var remunerado = 'Si';
			}
			else{
				remunerado = 'No';
			}
			if (usuario.per_adjunto != null) {
				var archivo = usuario.per_adjunto; 			
			}
			else{
				var archivo = 't.pdf';
			}
			var fechaIni =moment(usuario.per_fecha_ini).format('DD-MM-YYYY');
			var fechaFin = moment(usuario.per_fecha_fin).format('DD-MM-YYYY');
			$('#permisoShow').append(`
			<li class="list-group-item text-left">
				<label style="font-size: 14px;">
					<img src="imagenes2/${usuario.us_foto}" style="float:left; margin:10px; height: 80px;border-radius: 6px 6px 6px 6px; border:1px solid #B2B2B2;" >
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
					DESCRIPCIÓN
				</label>
			</li>
			<li class="list-group-item text-right col-lg-6">
				<label>
					${usuario.per_desc}
				</label>
			</li>
			<li class="list-group-item text-left col-lg-6">
				<label>
					REMUNERADO
				</label>
			</li>
			<li class="list-group-item text-right col-lg-6">
				<label>
					${remunerado}
				</label>
			</li>
			<li class="list-group-item text-right col-lg-12">
				<label>
					<a href="ver_permiso/${archivo}"  target="_blank">DESCARGAR ARCHIVO ADJUNTO</a>
				</label>
			</li>`
			)
		}); 
	});
}
function eliminaP(id) {
	swal ({
		title: "¿Estás seguro?",
		text: "¿Desea eliminar este permiso?",
		icon: "warning",
		buttons: true,
		dangerMode: true, 
	})
	.then((willDelete) => {
	  	if (willDelete) {
	    	$.get(`permiso_delete/${id}`, function(response){
		    	setTimeout('document.location.reload()',1000);
				swal("Poof! Este permiso ha sido eliminado!", {
		        icon: "success",

		    	});
			});
	  	}
	});
}
$(document).ready(function() {
    setTimeout(function() {
        $("#alertaP").slideUp(1500);
        $("#alertaPd").slideUp(1500);
        $(".alertaP").slideUp(1500);
        $(".alertaPd").slideUp(1500);
    },3000);
});