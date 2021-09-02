$("#departamentoAsi").change(function(event){
	$('#cedulaextra').val('');
	var token =  $('#_token').val();
	$.get(`buscaasi/${event.target.value}`, function(response){
		$("#empleadosAsi").empty();
		$("#centerasi").empty();
		
		if (response == '') { $('#empleadosAsi').append(`<tr>
						<td>
							<strong>Disculpe,</strong> no se encontraron resultados...
						</td>
					</tr>`)}
		response.forEach(element=> {
			$("#empleadosAsi").append(`<tr>				
					
					<td>
						<img src="imagenes2/${element.us_foto}" style="height: 60px;border-radius: 6px 6px 6px 6px; border:1px solid #B2B2B2;">
					</td>
					<td>
						<label>Nombre y Apellido:</label> 

							${element.us_nom}    						
							${element.us_ape}

						<label>Cédula:</label>  

							${element.us_ced}

						<br>
						<label>Departamento: </label> 

							${element.dp_nombre}

						<br>
						<label>Tipo de usuario:	</label>

							${element.tdu_tipo}
					</td>
					<td>
						<a href="#" data-toggle="modal" data-target="#modalEntrada" class="registraEntrada" onclick="registraEntrada(${element.us_ced})">
							<img src="assets/img/iconos/entrada.svg" class="imgmenuus">
						</a>
						<div class="modal fade" id="modalEntrada" role="dialog">
						    <div class="modal-dialog">
						        <div class="modal-content text-center">
					            	<div class="panel panel-default">
										<div class="panel-heading" style="background-color: #e5e8e8;">
										    <h3 class="panel-title">
										    	<b>REGISTRAR ENTRADA</b>
										    </h3>
										</div>
										<div class="panel-body">
											<form action="registrarentrada" method="post">
												<div class="row">
										        	<input type="hidden" name="_token" id="_token" value="${token}">
										        	<div class="col-lg-12" id="entrada">
										        		
										        	</div>
										        	<div class="col-lg-12">
										            	<div class="form-group">
										        			<div class="col-lg-6 text-left">
										        				<label>SELECCIONE LA FECHA SIN HORA DE ENTRADA</label>
										        			</div>
										        			<div class="col-lg-6 form-group">
										        				<select name="salidasinentrada" class="form-control" id="Fentrada" required onchange="agregar_hora()">
																</select>
															</div>	
										        		</div>
										    		</div>	
										    		<div id="hora_e">
											        	<div class="col-lg-12">
											        		<div class="form-group">
											        			<div class="col-lg-6 text-left">
											        				<label>INDIQUE LA FECHA DE ENTRADA</label>
											        			</div>
											        			<div class="col-lg-6">
											        				<input type="date" name="fechaentrada" class="form-control" required id="fechaEntrada" placeholder="yyyy-mm-dd">
											        			</div>	
											        		</div>							                		
											        	</div>
											        	<div class="col-lg-12">
											        		<div class="form-group">
											        			<div class="col-lg-6 text-left" style="margin-top: 15px;">
											        				<label>HORA DE ENTRADA</label>
											        				<label>FORMATO DE 24 HORAS</label>
											        			</div>
											        			<div class="col-lg-6" style="margin-top: 20px;">
											        				<input type="time" name="horaentrada" class="form-control" id="horaEnt" placeholder="hh:mm:ss" required/><br>
											        			</div>
											        		</div>
											        	</div>	
										        	</div>				                	
											    </div>
											    <div class="col-lg-12"  style="border-top: 1px solid #ccc; font-size: 14px;">
												    <div class="col-lg-6 text-left" style="margin-top: 10px;">
												        <button type="button" class="btn btn-default" data-dismiss="modal">
												        	CERRAR
												        </button>
												    </div>
													<div class="col-lg-6" style="margin-top: 10px;">
														<div class="form-group text-right">
															<label>
																<button type="submit" name="Registrar" class="btn" value="Registrar entrada" style="background-color: #48c9b0; color:white;">
																	<b>REGISTRAR</b>
																</button>
															</label>
														</div>
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
						<a href="#" data-toggle="modal" data-target="#modalSalida" onclick="registraSalida(${element.us_ced})" >
							<img src="assets/img/iconos/salida.svg" class="imgmenuus">
						</a>
						<div class="modal fade" id="modalSalida" role="dialog">
						    <div class="modal-dialog">
						        <div class="modal-content">
							        <div class="panel panel-default">
										<div class="panel-heading" style="background-color: #e5e8e8;">
											<h3 class="panel-title">
												<b>REGISTRAR SALIDA</b>
											</h3>
										</div>
										<div class="panel-body">
											<div class="row">
										        <form action="registrarsalida" method="post">
										        	<input type="hidden" name="_token" id="_token" value="${token}">

												        	<input type="hidden" name="hentrada" id="hentrada">
												        	<input type="hidden" name="asi_idd" id="asi_idd">
										        	<div class="col-lg-12" id="salida">
										        		
										        	</div>
										        	<div class="col-lg-12">
										            	<div class="form-group">
										        			<div class="col-lg-6 text-left">
										        				<label>SELECCIONE LA FECHA DE ENTRADA</label>
										        			</div>
										        			<div class="col-lg-6 form-group">
										        				<select name="entradasinsalida" class="form-control" id="Fsalida" required>
																</select>
															</div>	
										        		</div>
										    		</div>	
										        	<div class="col-lg-12">
										        		<div class="form-group">
										        			<div class="col-lg-6 text-left">
										        				<br><label>INDIQUE LA FECHA DE SALIDA</label>
										        			</div>
										        			<div class="col-lg-6">
										        				<br><input type="date" name="fechasalida"  id="fechaSa" class="form-control" required  placeholder="yyyy-mm-dd">
										        			</div>	
										        		</div>							                		
										        	</div>
										        	<div class="col-lg-12">
										        		<div class="form-group">
										        			<div class="col-lg-6 text-left">
										        				<br>
										        				<label>HORA DE SALIDA</label>
										        				<label>FORMATO DE 24 HORAS</label>
										        			</div>
										        			<div class="col-lg-6" style="margin-top: 20px;">
										        				<input type="time" name="horasalida" class="form-control" placeholder="hh:mm:ss" required/><br>
										        			</div>
										        		</div>
										        	</div>
										        	<div class="col-lg-12"  style="border-top: 1px solid #ccc; font-size: 14px;">
													    <div class="col-lg-6 text-left" style="margin-top: 10px;">
													        <button type="button" class="btn btn-default" data-dismiss="modal">
													        	CERRAR
													        </button>
													    </div>
														<div class="col-lg-6" style="margin-top: 10px;">
															<div class="form-group text-right">
																<label>
																	<button  type="submit" name="Registrar" class="btn" value="Registrar salida" style="background-color: #48c9b0; color:white;">
																		<b>REGISTRAR SALIDA</b>
																	</button>
																</label>
															</div>
														</div>
													</div>
										        </form>						                	
										    </div>
										</div>
									</div>
						        </div>
						    </div>
						</div>
					</td>
				</tr>` )

			    $('#fechaEntrada').datepicker({
			        format: "yyyy-mm-dd",
			        language: "es",
			        autoclose: true
			    });
			    
			    $('#fechaSa').datepicker({
			        format: "yyyy-mm-dd",
			        language: "es",
			        autoclose: true
			    });
		});
	});
});
$(document).ready(function(){
	var token = $("#_token").val();
$("#cedulaextra").change(function(event){
	var cedula =  $('#cedulaextra').val();
	$("#departamentoAsi").val('');
	$.get(`buscarcedulaextra/${cedula}`, function(response, cedulaextra){
		console.log(response);
		$("#empleadosAsi").empty();
		$("#centerasi").empty();
		if (response == '') {
			$("#empleadosAsi").append(`<tr>
						<td>
							<strong>Disculpe,</strong> no se encontraron resultados...
						</td>
					</tr>`)
		}
		$("#center").empty();

		response.forEach(element=> {
			$("#empleadosAsi").append(`<tr id="empleadosAsi">				
					
					<td>
						<img src="imagenes2/${element.us_foto}" style="height: 60px;border-radius: 6px 6px 6px 6px; border:1px solid #B2B2B2;">
					</td>
					<td>
						<label>Nombre y Apellido:</label> 

							${element.us_nom}    						
							${element.us_ape}

						<label>Cédula:</label>  

							${element.us_ced}

						<br>
						<label>Departamento: </label> 

							${element.dp_nombre}

						<br>
						<label>Tipo de usuario:	</label>

							${element.tdu_tipo}
					</td>
					<td>
						<a href="#" data-toggle="modal" data-target="#modalEntrada" onclick="registraEntrada(${element.us_ced})">
							<img src="assets/img/iconos/entrada.svg" class="imgmenuus">
						</a>
						<div class="modal fade" id="modalEntrada" role="dialog">
						    <div class="modal-dialog">
						        <div class="modal-content text-center">
					            	<div class="panel panel-default">
										<div class="panel-heading" style="background-color: #e5e8e8;">
										    <h3 class="panel-title">
										    	<b>REGISTRAR ENTRADA</b>
										    </h3>
										</div>
										<div class="panel-body">
											<form action="registrarentrada" method="post">
												<div class="row">
										        	<input type="hidden" name="_token" id="_token" value="${token}">
										        	<div class="col-lg-12" id="entrada">
										        		
										        	</div>
										        	<div class="col-lg-12">
										            	<div class="form-group">
										        			<div class="col-lg-6 text-left">
										        				<label>SELECCIONE LA FECHA SIN HORA DE ENTRADA</label>
										        			</div>
										        			<div class="col-lg-6 form-group">
										        				<select name="salidasinentrada" class="form-control" id="Fentrada" required onchange="agregar_hora()">
																</select>
															</div>	
										        		</div>
										    		</div>	
										    		<div id="hora_e">
											        	<div class="col-lg-12">
											        		<div class="form-group">
											        			<div class="col-lg-6 text-left">
											        				<label>INDIQUE LA FECHA DE ENTRADA</label>
											        			</div>
											        			<div class="col-lg-6">
											        				<input type="date" name="fechaentrada" class="form-control" required id="fechaEntrada" placeholder="yyyy-mm-dd"
											        			</div>	
											        		</div>							                		
											        	</div>
											        	<div class="col-lg-12">
											        		<div class="form-group">
											        			<div class="col-lg-6 text-left" style="margin-top: 15px;">
											        				<label>HORA DE ENTRADA</label>
											        				<label>FORMATO DE 24 HORAS</label>
											        			</div>
											        			<div class="col-lg-6" style="margin-top: 20px;">
											        				<input type="time" name="horaentrada" class="form-control" id="horaEnt" placeholder="hh:mm:ss" required/><br>
											        			</div>
											        		</div>
											        	</div>	
										        	</div>				                	
											    </div>
											    <div class="col-lg-12"  style="border-top: 1px solid #ccc; font-size: 14px;">
												    <div class="col-lg-6 text-left" style="margin-top: 10px;">
												        <button type="button" class="btn btn-default" data-dismiss="modal">
												        	CERRAR
												        </button>
												    </div>
													<div class="col-lg-6" style="margin-top: 10px;">
														<div class="form-group text-right">
															<label>
																<button type="submit" name="Registrar" class="btn" value="Registrar entrada" style="background-color: #48c9b0; color:white;">
																	<b>REGISTRAR</b>
																</button>
															</label>
														</div>
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
						<a href="#" data-toggle="modal" data-target="#modalSalida" onclick="registraSalida(${element.us_ced})" >
							<img src="assets/img/iconos/salida.svg" class="imgmenuus">
						</a>
						<div class="modal fade" id="modalSalida" role="dialog">
						    <div class="modal-dialog">
						        <div class="modal-content">
							        <div class="panel panel-default">
										<div class="panel-heading" style="background-color: #e5e8e8;">
											<h3 class="panel-title">
												<b>REGISTRAR SALIDA</b>
											</h3>
										</div>
										<div class="panel-body">
											<div class="row">
										        <form action="registrarsalida" method="post">
										        	<input type="hidden" name="_token" id="_token" value="${token}">
										        	

												        	<input type="hidden" name="hentrada" id="hentrada">
												        	<input type="hidden" name="asi_idd" id="asi_idd">
										        	<div class="col-lg-12" id="salida">
										        		
										        	</div>
										        	<div class="col-lg-12">
										            	<div class="form-group">
										        			<div class="col-lg-6 text-left">
										        				<label>SELECCIONE LA FECHA DE ENTRADA</label>
										        			</div>
										        			<div class="col-lg-6 form-group">
										        				<select name="entradasinsalida" class="form-control" id="Fsalida" >
																</select>
															</div>	
										        		</div>
										    		</div>	
										        	<div class="col-lg-12">
										        		<div class="form-group">
										        			<div class="col-lg-6 text-left">
										        				<br><label>INDIQUE LA FECHA DE SALIDA</label>
										        			</div>
										        			<div class="col-lg-6">
										        				<br><input type="date" name="fechasalida"  id="fechaSa" class="form-control" required  placeholder="yyyy-mm-dd">
										        			</div>	
										        		</div>							                		
										        	</div>
										        	<div class="col-lg-12">
										        		<div class="form-group">
										        			<div class="col-lg-6 text-left">
										        				<br>
										        				<label>HORA DE SALIDA</label>
										        				<label>FORMATO DE 24 HORAS</label>
										        			</div>
										        			<div class="col-lg-6" style="margin-top: 20px;">
										        				<input type="time" name="horasalida" class="form-control"  placeholder="hh:mm:ss"required/><br>
										        			</div>
										        		</div>
										        	</div>
										        	<div class="col-lg-12"  style="border-top: 1px solid #ccc; font-size: 14px;">
													    <div class="col-lg-6 text-left" style="margin-top: 10px;">
													        <button type="button" class="btn btn-default" data-dismiss="modal">
													        	CERRAR
													        </button>
													    </div>
														<div class="col-lg-6" style="margin-top: 10px;">
															<div class="form-group text-right">
																<label>
																	<button  type="submit" name="Registrar" class="btn" value="Registrar salida" style="background-color: #48c9b0; color:white;">
																		<b>REGISTRAR SALIDA</b>
																	</button>
																</label>
															</div>
														</div>
													</div>
										        </form>						                	
										    </div>
										</div>
									</div>
						        </div>
						    </div>
						</div>
					</td>
				</tr>` )


			    $('#fechaEntrada').datepicker({
			        format: "yyyy-mm-dd",
			        language: "es",
			        autoclose: true
			    });
			    
			    $('#fechaSa').datepicker({
			        format: "yyyy-mm-dd",
			        language: "es",
			        autoclose: true
			    });
		});
	});
});
});
function agregar_hora(argument) {
	$('#hora_e').empty();
	$('#hora_e').append(`	
    	<div class="col-lg-12">
    		<div class="form-group">
    			<div class="col-lg-6 text-left" style="margin-top: 15px;">
    				<label>HORA DE ENTRADA</label>
    				<label>FORMATO DE 24 HORAS</label>
    			</div>
    			<div class="col-lg-6" style="margin-top: 20px;">
    				<input type="time" name="horaentrada" class="form-control" id="horaEnt" required/><br>
    			</div>
    		</div>
    	</div>	`
    )
}

function registraEntrada(id){

	$.get(`buscadatos3/${id}`, function(response, registraSalida){
		console.log(response);
		$("#Fentrada").empty();
		$("#Fentrada").append(`<option disabled selected>Seleccione la fecha</option>`)
		$("#centerasi").empty();
		response.forEach(element=> {
			fechaEntrada = element.asi_entrada.split("-"),
			fechae =fechaEntrada[0]+"-"+fechaEntrada[1]+"-"+fechaEntrada[2]
			console.log(fechae)
			$("#Fentrada").append(`
				<option value="${fechae}">${fechaEntrada[2]+"-"+fechaEntrada[1]+"-"+fechaEntrada[0]}</option>`)
		});
	});

	$.get(`buscadatos/${id}`, function(response, registraEntrada){
		$("#entrada").empty();
		$("#centerasi").empty();
		response.forEach(element=> {
		console.log(element.us_nom)
			var idH = element.tiho_id
			console.log(element.hxu_tiho_id);
			$("#entrada").append(`
				<div class="col-lg-6 text-left">
            		<div class="form-group">
            			<label>NOMBRE</label>
            			<input type="hidden" name="id" class="form-control" disabled value="${element.us_ced}">
            			<input type="hidden" name="codigo" class="form-control" value="${element.carus_codigo}">
            			<input type="text" name="nombre" class="form-control" disabled value="${element.us_nom}">
            		</div>
            	</div>
            	<div class="col-lg-6 text-left">
            		<div class="form-group">
            			<label>APELLIDO</label>
            			<input type="text" name="apellido" class="form-control" disabled value="${element.us_ape}">
            		</div>
            	</div>
            	<div class="col-lg-6 text-left">
            		<div class="form-group">
            			<label>CÉDULA</label>
            			<input type="number" name="cedula" class="form-control" disabled value="${element.us_ced}">
            		</div>
            	</div>
            	<div class="col-lg-6 text-left">
            		<div class="form-group">
            			<label>DEPARTAMENTO</label>
            			<input type="text" name="departamento" class="form-control" disabled value="${element.dp_nombre}">
            		</div>
            	</div>`)
			$("#fechaEntrada").change(function(event){
				//Tomamos la fecha y la pasamos a tipo date para poder realizar las validaciones
				var fecha = new Date($('#fechaEntrada').val());
				var fechaV = $('#fechaEntrada').val().split("-");
				var f = new Date();
				var anio = fechaV[0];
				var mes = fechaV[1];
				var dia = fechaV[2];

				console.log(anio);
				console.log(f.getFullYear());
				console.log(mes);
				console.log(f.getMonth()+1);
				console.log(f.getDate());
				console.log(dia)
				if(f.getFullYear() > anio ){
					swal("Disculpe esta introduciendo una fecha que no pertenece al mes actual, por favor, vuelva a intentarlo",{
							icon: "error"
					});
					$('#fechaEntrada').val('');
				}
				else{					
					if (f.getFullYear() < anio) {
						swal("Disculpe esta introduciendo una fecha que no pertenece al mes actual, por favor, vuelva a intentarlo",{
							icon: "error"
						});
						$('#fechaEntrada').val('');
					}
					else{
						if ((f.getMonth() +1) < mes) {
							swal("Disculpe esta introduciendo una fecha que no pertenece al mes actual, por favor, vuelva a intentarlo",{
							icon: "error"
							});
							$('#fechaEntrada').val('');
						}
						else{
							if ((f.getMonth() +1) > mes) {
								swal("Disculpe esta introduciendo una fecha que no pertenece al mes actual, por favor, vuelva a intentarlo",{
								icon: "error"
								});
								$('#fechaEntrada').val('');
							}
							else{
								if (f.getDate() < dia) {
									swal("Disculpe, esta introduciendo una fecha mayor a la fecha actual, por favor verifique.",{
									icon: "error"
									});
									$('#fechaEntrada').val('');
								}
								else{
									var dias = ["Domingo","Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"];
									var getDia = dias[fecha.getUTCDay()];
								}
							}
						}
					}
				}		
			});
		});
	});
}
function registraSalida(id) {
	$.get(`buscadatos2/${id}`, function(response, registraSalida){
		console.log(response);
		$("#Fsalida").empty();
		$("#Fsalida").append(`<option disabled selected>Seleccione la fecha</option>`)
		$("#centerasi").empty();
		response.forEach(element=> {
			fechaEntrada = element.asi_entrada.split("-"),
			fechae =fechaEntrada[0]+"-"+fechaEntrada[1]+"-"+fechaEntrada[2]
			$("#Fsalida").append(`
				<option value="${fechae}">${fechaEntrada[2]+"-"+fechaEntrada[1]+"-"+fechaEntrada[0]}</option>`)
			$('#Fsalida').change(function(e) {
				var fecha_entrada = $("#Fsalida").val();
				$.get(`obtener_hora_fechaE/${fecha_entrada}/${id}`,function(response){
					response.forEach(element=>{
						$('#hentrada').empty();
						$('#hentrada').val(element.asi_entrada_hora);
						$('#asi_idd').val(element.asi_id)
					})
				});
			})
		});
	});
	$.get(`buscadatos/${id}`, function(response, registraSalida){
		console.log(response);
		$("#salida").empty();
		$("#centerasi").empty();
		response.forEach(element=> {
			$("#salida").append(`
				<div class="col-lg-6 text-left">
            		<div class="form-group">
            			<label>NOMBRE</label>
            			<input type="hidden" name="id" class="form-control" value="${element.us_ced}">
            			<input type="hidden" name="codigo" class="form-control" value="${element.carus_codigo}">
            			<input type="text" name="nombre" class="form-control" disabled value="${element.us_nom}">
            		</div>
            	</div>
            	<div class="col-lg-6 text-left">
            		<div class="form-group">
            			<label>APELLIDO</label>
            			<input type="text" name="apellido" class="form-control" disabled value="${element.us_ape}">
            		</div>
            	</div>
            	<div class="col-lg-6 text-left">
            		<div class="form-group">
            			<label>CÉDULA</label>
            			<input type="number" name="cedula" class="form-control" disabled value="${element.us_ced}">
            		</div>
            	</div>
            	<div class="col-lg-6 text-left">
            		<div class="form-group">
            			<label>DEPARTAMENTO</label>
            			<input type="text" name="departamento" class="form-control" disabled value="${element.dp_nombre}">
            		</div>
            	</div>`)

			$("#Fsalida").change(function(event){
				$("#fechaSa").change(function(event){
					
					//Fecha de salida ingresada por el empleado
					var fechaS = $('#fechaSa').val();
					//Fecha de entrada seleccionada
					var fechaE = $('#Fsalida').val();
					var fecha1 = moment(fechaE).format('DD-MM-YY');
					var fecha2 = moment(fechaS).format('DD-MM-YY');
					/*var fecha1 = fechaS.split("-"),
						fecha2 = fechaE.split("-"),
						fechaS = fecha1[0]+"-"+fecha1[1]+"-"+fecha1[2],
						fechaE = fecha2[0]+"-"+fecha2[1]+"-"+fecha2[2];*/
						
					/*var fechaLimite = new Date(fechaE); //fecha actual
				    fechaLimite.setHours(0, 0, 0, 0); //llevar a hora 00:00:00
				    fechaLimite.setDate(fechaLimite.getDate() + 4); //sumarle 10 días*/
				    var fechaa = moment(fechaE).add(2, 'days');
				    fechaMax = fechaa.format('DD-MM-YY')

				    console.log(fecha1,fecha2,fechaMax);
				    if (fecha1 == fecha2) {
				    	$('#hsalida').change(function(event){
					    	var hora_entrada = $('#hentrada').val().split(':');
							var hora_salida= $('#hsalida').val().split(':');
					    	if (hora_entrada[0] > hora_salida[0] ) {
					    		swal("La hora de salida no puede ser menor a la hora de entrada, por favor verifique.",{
									icon: "error"

								});
								$('#hsalida').val('');
					    	}
					    	else{
						    	var hora_entrada = $('#hentrada').val().split(':');
								var hora_salida= $('#hsalida').val().split(':');
					    		if (hora_entrada[0] == hora_salida[0] ) {
					    			if (hora_entrada[1] > hora_salida[1]) {
					    				swal("La hora de salida no puede ser menor a la hora de entrada, por favor verifique.",{
										icon: "error"

										});
										$('#hsalida').val('');	
					    			}
					    			
					    		}
					    	}
				    	});	
				    }
				    else{

				    	if (fecha2<fecha1) {
							swal("La fecha que intenta introducir es menor a la fecha de entrada, por favor, seleccione la fecha correcta",{
								icon: "error"

							});
							$('#fechaSa').val('');
							console.log(fecha2 , fecha1)
						} 
						else {
				    		console.log(fecha2,fechaMax);
							if (fecha2>=fechaMax)   {
								swal("Disculpe, un empleado no puede permanecer mas de dos días seguidos dentro de las instalaciones, por favor, seleccione una fecha menor a "+ fechaMax,{ 
								icon: "warning"
								});
								$('#fechaSa').val('');							
							}
				    	}
					}	
				});
			});
		});
	});
}
$(document).ready(function() {
    $('#successAE').slideUp(1500);
    $('#dangerAE').slideUp(1500);
    $('#erroresFAE').slideUp(2500);
});

