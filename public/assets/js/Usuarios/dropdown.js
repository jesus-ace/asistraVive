
document.getElementById("controlUsuario").addEventListener("DOMNodeInserted", handler, true);
 
function handler(){
	$('#sabdomi').click(function(e){
	  if ($(this).is(':checked')) {
	  	$('#asistenciaIC').hide(500);
	  	$('#fxdiai').hide(500);
	    $('.asistenciaesp').prop('checked',true);
	    $('.inasistenciaesp').prop('checked',false);    
	    $('.diasGc').prop('checked',false);
	  	$('#diaCg').prop('disabled', true);
	    $('.rangoGc').prop('checked',true);
	  	$('#desdeCg').prop('disabled', false);
	  	$('#hastaCg').prop('disabled', false);
	  	$('#rangoEspecifico').show(500);
	  }
	  else{
	  	$('#asistenciaIC').show(500);
	  	$('#fxdiai').show(500);
	  	$('#rangoEspecifico').show(500);
	    $('.asistenciaesp').prop('checked',true);
	    $('.inasistenciaesp').prop('checked',false);
	  }
	});
	$('.diaCh').click(function(e){
	  if ($(this).is(':checked')) {
	    $('.rangoCh').prop('checked',false);
	  	$('#diaC').prop('disabled', false);
	  	$('#desdeControl').prop('disabled', true);
	  	$('#hastaControl').prop('disabled', true);
	  }
	});

	$('.rangoCh').click(function(e){
	  if ($(this).is(':checked')) {
	    $('.diaCh').prop('checked',false);
	  	$('#diaC').prop('disabled', true);
	  	$('#desdeControl').prop('disabled', false);
	  	$('#hastaControl').prop('disabled', false);
	  }
	});


	$('.diasGc').click(function(e){
	  if ($(this).is(':checked')) {
	    $('.rangoGc').prop('checked',false);
	  	$('#diaCg').prop('disabled', false);
	  	$('#desdeCg').prop('disabled', true);
	  	$('#hastaCg').prop('disabled', true);
	  }
	});

	$('.rangoGc').click(function(e){
	  if ($(this).is(':checked')) {
	    $('.diasGc').prop('checked',false);
	  	$('#diaCg').prop('disabled', true);
	  	$('#desdeCg').prop('disabled', false);
	  	$('#hastaCg').prop('disabled', false);
	  }
	});

	$('#asistenciag').click(function(e){
		alert('hola')
	  if ($(this).is(':checked')) {
	    $('#asistenciag').prop('checked',true);
	    $('#inasistenciag').prop('checked',false);
	  }
	  else{
	  	$('#asistenciag').prop('checked',false);
	    $('#inasistenciag').prop('checked',true);
	  }
	});

	$('#inasistenciag').click(function(e){
	  if ($(this).is(':checked')) {
	   $('#asistenciag').prop('checked',false);
	    $('#inasistenciag').prop('checked',true);
	  }
	  else{
	  	$('#asistenciag').prop('checked',true);
	    $('#inasistenciag').prop('checked',false);
	  }
	});
}


function chequea_contenido_cntrol(){
	if($('#_token').val() == '') {
        swal('Lo siento existe un error con el token.');
        $('#_token').focus();
        return false;
    }
    else if($('#dpControlg').val()){
    	if ($('#diaC').val() == '' && $('#desdeControl').val() == '') {
	    	swal('Debe elegir una fecha .');
	        $('#diaC').focus();
	        return false;
    	}
    }
}
$('#hastaControl').change(function(e){
	var dsd = $('#desdeControl').val();
	var hst = $('#hastaControl').val();
	var desde = dsd.split("-");
	var hasta = hst.split("-");
	console.log(desde, hasta);
	if (desde[0] > hasta[0]) {
		swal('El año que intenta introducir es menor a el año de la busqueda, por favor intente de nuevo.');
		$('#hastaControl').val('');
	}
	else if(desde[1] > hasta[1]){
		swal('El mes que intenta introducir es menor a el mes de la busqueda, por favor intente de nuevo.');
		$('#hastaControl').val('');
	}
	else if(desde[1] == hasta[1] && desde[2] > hasta[2]){
		swal('La fecha que intenta introducir es menor a La fecha de la busqueda, por favor intente de nuevo.');
		$('#hastaControl').val('');
	}
});

$('#hastaCg').change(function(e){
	var dsd = $('#desdeCg').val();
	var hst = $('#hastaCg').val();
	var desde = dsd.split("-");
	var hasta = hst.split("-");
	console.log(desde, hasta);
	if (desde[0] > hasta[0]) {
		swal('El año que intenta introducir es menor a el año de la busqueda, por favor intente de nuevo.');
		$('#hastaCg').val('');
	}
	else if(desde[1] > hasta[1]){
		swal('El mes que intenta introducir es menor a el mes de la busqueda, por favor intente de nuevo.');
		$('#hastaCg').val('');
	}
	else if(desde[1] == hasta[1] && desde[2] > hasta[2]){
		swal('La fecha que intenta introducir es menor a La fecha de la busqueda, por favor intente de nuevo.');
		$('#hastaCg').val('');
	}
});


var token = $('#_token').val();
   /* $("#dpControl").change(function(event){
    	$('#center').empty();
		var token = $('#_token').val();
        var departamento = $("#dpControl").val();
        console.log(departamento);
    	$.get(`controldp/${departamento}`, function(response, departamentosUs){
    		console.log(response);
    		$("#controlUsuario").empty();
            $("#cedulac").val('');
            
            if (response == '') { $('#controlUsuario').append(`<tr>
							<td>
								<strong>Disculpe,</strong> no se encontraron resultados...
							</td>
						</tr>`)}
    		response.forEach(element=> {
                if (element.us_foto != '') {
                    var foto = element.us_foto;
                }
                else{
                    var foto = 'mafalda.jpg';
                }
						                		
                $("#controlUsuario").append(`<tr value="${element.us_ced}">		
						<td>
							<img src="imagenes2/${element.us_foto}" style="height: 60px;border-radius: 6px 6px 6px 6px; border:1px solid #B2B2B2;">
						</td>				
						<td>
							${element.dp_nombre}
						</td>				
						<td>
							<label>Nombre y Apellido</label> 
								${element.us_nom}
								${element.us_ape}<br>
							<label>Cédula </label>
								${element.us_ced}
								
						</td>
						<td>
							<a href="#" data-toggle="modal" data-target="#modalAsi" onclick="AsistenciaEmp(${element.us_ced})">
						    	<img src="assets/img/iconos/asistenciac.svg" class="imgmenuus">
							</a>
							<div class="modal fade" id="modalAsi" role="dialog">
							    <div class="modal-dialog">
							        <div class="modal-content text-center">
							        	<div class="panel panel-default">
										  	<div class="panel-heading" style="background-color: #e5e8e8;">
										    	<h3 class="panel-title">
										    		<b>RESUMEN DE ASISTENCIA</b>
						    						<button type="button" class="close" data-dismiss="modal">
						    							<span aria-hidden="true">×</span>
											        </button>
										    	</h3>
										  	</div>
										  	<div class="panel-body">
								           		<form method="post" action="buscarfecha">
							           			<input type="hidden" name="_token" id="_token" value="${token}">
											    	<ul class="list-group text-left">
								  						<li class="list-group-item text-left">
								  							<label>
								  								Indique la fecha que desea consultar
								  							</label>
								  						</li>
								  						<li class="list-group-item text-center">
													  		<label>
													  			Sábados y Domingos del mess
													  			<input type="checkbox" name="sabdomi" id="sabdomi" style="margin-right: 10px;" class="sabdomi">
													  		</label>
													  	</li>								  	
								  						<li class="list-group-item text-center" id="asistenciaIC">
													  		<label class="v">
													  			ASISTENCIA
													  			<input type="checkbox" name="asistencia" id="asistenciaesp" checked="" style="margin-right: 10px;" class="asistenciaesp">
													  		</label>
													  		<label>
													  			INASISTENCIA
																<input type="checkbox" name="inasistencia" id="inasistenciaesp" class="inasistenciaesp">
													  		</label>
													  	</li>
								  						<li class="list-group-item text-left" id="fxdiai">
								  							<label>
								  								<input type="checkbox" name="dias" class="diasGc" checked>
								  								DÍA
								  							</label>
								  							<label style="margin-left:40px">
																<input class="form-control form-inline" type="date" name="dia" id="diaCg" placeholder="yyyy-mm-dd">
															</label>
								  						</li>
								  						<li class="list-group-item text-left" id="rangoEspecificoo">
								  							<label>
								  								<input type="checkbox" name="rango" class="rangoGc">
								  								RANGO
								  							</label>
								  							<label style="margin-left: 15px">
																Desde:
																<input class="form-control form-inline" type="date" name="desde" id="desdeCg" disabled="disabled" placeholder="yyyy-mm-dd">
															</label>
															<label style="margin-left: 15px">
																Hasta:
																<input class="form-control form-inline" type="date" name="hasta" disabled="disabled" id="hastaCg" placeholder="yyyy-mm-dd">
															</label>
															<div id="id-emp">
														
															</div>
								  						</li>
								  					</ul>
								  					<div class="col-lg-6 text-left">
												        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
												    </div>
								            		<div class="col-lg-6 text-right">
														<button id="botonc" type="submit" name="buscar" value="Buscar"  class="btn" style="background-color: #48c9b0; color:white;">
															<b>BUSCAR</b>
														</button>												
													</div>
								  				</form>
										  	</div>
										</div>
							        </div>
								</div>
							</div>	
						</td>
					</tr>`
				)

			    $('#diaCg').datepicker({
			        format: "yyyy-mm-dd",
			        language: "es",
			        autoclose: true
			    });

			    $('#desdeCg').datepicker({
			        format: "yyyy-mm-dd",
			        language: "es",
			        autoclose: true
			    });
			    $('#hastaCg').datepicker({
			        format: "yyyy-mm-dd",
			        language: "es",
			        autoclose: true
			    });
				$('.diasGc').click(function(e){
				  if ($(this).is(':checked')) {
				    $('.rangoGc').prop('checked',false);
				  	$('#diaCg').prop('disabled', false);
				  	$('#desdeCg').prop('disabled', true);
				  	$('#hastaCg').prop('disabled', true);
				  }
				});

				$('.rangoGc').click(function(e){
				  if ($(this).is(':checked')) {
				    $('.diasGc').prop('checked',false);
				  	$('#diaCg').prop('disabled', true);
				  	$('#desdeCg').prop('disabled', false);
				  	$('#hastaCg').prop('disabled', false);
				  }
				});


				$('.asistenciaesp').click(function(e){
				  if ($(this).is(':checked')) {
				    $('.asistenciaesp').prop('checked',true);
				    $('.inasistenciaesp').prop('checked',false);
				    $('#rangoEspecifico').slideDown(500);
				    $('#rangoEspecificoo').slideDown(500);
				  }
				  else{
				  	$('.asistenciaesp').prop('checked',false);
				    $('.inasistenciaesp').prop('checked',true);
				    $('#rangoEspecifico').slideUp(500);
				    $('#rangoEspecificoo').slideUp(500);
				    $('.diasGc').prop('checked',true);
				    $('.rangoGc').prop('checked',false);
				  	$('#diaCg').prop('disabled', false);
				  	$('#desdeCg').prop('disabled', true);
				  	$('#hastaCg').prop('disabled', true);
				  }
				});

				$('.inasistenciaesp').click(function(e){
					if ($(this).is(':checked')) {
					   $('.asistenciaesp').prop('checked',false);
					    $('.inasistenciaesp').prop('checked',true);
					    $('#rangoEspecifico').slideUp(500);
					    $('#rangoEspecificoo').slideUp(500);
					    $('.diasGc').prop('checked',true);
					    $('.rangoGc').prop('checked',false);
					  	$('#diaCg').prop('disabled', false);
					  	$('#desdeCg').prop('disabled', true);
					  	$('#hastaCg').prop('disabled', true);
					}
					else{
					  	$('.asistenciaesp').prop('checked',true);
					    $('.inasistenciaesp').prop('checked',false);
					    $('#rangoEspecifico').slideDown(500);
					    $('#rangoEspecificoo').slideDown(500);
					}
				});      
            });
    	});
    });*/
$("#refrescarc").click(function(event){
	$('#cedulac').val('');
	var token = $('#_token').val();
	$.get(`todoscontrol`, function(response, refrescarc){ 
	console.log(response);
	$("#cedulac").empty();
	$("#controlUsuario").empty();$('#center').empty();
		response.forEach(element=> {
			$("#controlUsuario").append(`
				<tr value="${element.us_id}">		
					<td>
						<img src="imagenes2/${element.us_foto}" style="height: 60px;border-radius: 6px 6px 6px 6px; border:1px solid #B2B2B2;">
					</td>		
					<td>
						${element.dp_nombre}
					</td>				
					<td>
						<label>Nombre y Apellido</label> 
							${element.us_nom}
							${element.us_ape}<br>
						<label>Cédula </label>
							${element.us_ced}
					</td>
					<td>
						<a href="#" data-toggle="modal" data-target="#modalAsi" onclick="AsistenciaEmp(${element.us_ced})">
					    	<img src="assets/img/iconos/asistenciac.svg" class="imgmenuus">
						</a>
						<div class="modal fade" id="modalAsi" role="dialog">
						    <div class="modal-dialog">
						        <div class="modal-content text-center">
						        	<div class="panel panel-default">
									  	<div class="panel-heading" style="background-color: #e5e8e8;">
									    	<h3 class="panel-title">
									    		<b>RESUMEN DE ASISTENCIA</b>
					    						<button type="button" class="close" data-dismiss="modal">
					    							<span aria-hidden="true">×</span>
										        </button>
									    	</h3>
									  	</div>
									  	<div class="panel-body">
							           		<form method="post" action="buscarfecha">
							           			<input type="hidden" name="_token" id="_token" value="${token}">
										    	<ul class="list-group text-left">
							  						<li class="list-group-item text-left">
							  							<label>
							  								Indique la fecha que desea consultar
							  							</label>
							  						</li>
							  						<li class="list-group-item text-left">
							  							<label>
							  								<input type="checkbox" name="dias" class="diasGc" checked>
							  								DÍA
							  							</label>
							  							<label style="margin-left:40px">
															<input class="form-control form-inline" type="date" name="dia" id="diaCg" placeholder="yyyy-mm-dd">
														</label>
							  						</li>
							  						<li class="list-group-item text-left">
							  							<label>
							  								<input type="checkbox" name="rango" class="rangoGc">
							  								RANGO
							  							</label>
							  							<label style="margin-left: 15px">
															Desde:
															<input class="form-control form-inline" type="date" name="desde" id="desdeCg" disabled="disabled" placeholder="yyyy-mm-dd">
														</label>
														<label style="margin-left: 15px">
															Hasta:
															<input class="form-control form-inline" type="date" name="hasta" disabled="disabled" id="hastaCg" placeholder="yyyy-mm-dd">
														</label>
														<div id="id-emp">
													
														</div>
							  						</li>
							  					</ul>
							  					<div class="col-lg-6 text-left">
											        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
											    </div>
							            		<div class="col-lg-6 text-right">
													<button id="botonc" type="submit" name="buscar" value="Buscar"  class="btn" style="background-color: #48c9b0; color:white;">
														<b>BUSCAR</b>
													</button>												
												</div>
							  				</form>
									  	</div>
									</div>
						        </div>
							</div>
						</div>	
					</td>
				</tr>`
			)
		});
	});
});
function AsistenciaEmp(id) {
	$.get(`asistencia/${id}`, function(response){ 
		console.log(response);
		$("#id-emp").empty();
		response.forEach(element=>{
			$("#id-emp").append(`<input type="hidden" value="${element.us_ced}" name="cedula" id="idC">`)
		});
	});
}
$("#cedulac").change(function(event){ 
	var cedula = $('#cedulac').val();
	var token = $('#_token').val();$('#center').empty();
	$.get(`controlcedula/${cedula}`, function(response, cedula){
		console.log(response)
	$("#controlUsuario").empty();
		if (response == '') {
			$("#controlUsuario").append(`<<tr>
							<td>
								<strong>Disculpe,</strong> no se encontraron resultados...
							</td>
						</tr>`)
		}
		response.forEach(element=> {
			$("#controlUsuario").append(`
				<tr value="${element.us_id}">	
					<td>
						<img src="imagenes2/${element.us_foto}" style="height: 60px;border-radius: 6px 6px 6px 6px; border:1px solid #B2B2B2;">
					</td>	
					<td>
						${element.dp_nombre}
					</td>				
					<td>
						<label>Nombre y Apellido</label> 
							${element.us_nom}
							${element.us_ape}<br>
						<label>Cédula </label>
							${element.us_ced}
					</td>
					<td>
						<a href="#" data-toggle="modal" data-target="#modalAsi" onclick="AsistenciaEmp(${element.us_ced})">
					    	<img src="assets/img/iconos/asistenciac.svg" class="imgmenuus">
						</a>
						<div class="modal fade" id="modalAsi" role="dialog">
						    <div class="modal-dialog">
						        <div class="modal-content text-center">
						        	<div class="panel panel-default">
									  	<div class="panel-heading" style="background-color: #e5e8e8;">
									    	<h3 class="panel-title">
									    		<b>RESUMEN DE ASISTENCIA</b>
					    						<button type="button" class="close" data-dismiss="modal">
					    							<span aria-hidden="true">×</span>
										        </button>
									    	</h3>
									  	</div>
									  	<div class="panel-body">
							           		<form method="post" action="buscarfecha">
						           			<input type="hidden" name="_token" id="_token" value="${token}">
										    	<ul class="list-group text-left">
							  						<li class="list-group-item text-left">
									  							<label>
									  								Indique la fecha que desea consultar
									  							</label>
									  						</li>
									  						<li class="list-group-item text-center">
														  		<label>
														  			Sábados y Domingos del mess
														  			<input type="checkbox" name="sabdomi" id="sabdomi" style="margin-right: 10px;" class="sabdomi">
														  		</label>
														  	</li>								  	
									  						<li class="list-group-item text-center" id="asistenciaIC">
														  		<label class="v">
														  			ASISTENCIA
														  			<input type="checkbox" name="asistencia" id="asistenciaesp" checked="" style="margin-right: 10px;" class="asistenciaesp">
														  		</label>
														  		<label>
														  			INASISTENCIA
																	<input type="checkbox" name="inasistencia" id="inasistenciaesp" class="inasistenciaesp">
														  		</label>
														  	</li>
									  						<li class="list-group-item text-left" id="fxdiai">
							  							<label>
							  								<input type="checkbox" name="dias" class="diasGc" checked>
							  								DÍA
							  							</label>
							  							<label style="margin-left:40px">
															<input class="form-control form-inline" type="date" name="dia" id="diaCg" placeholder="yyyy-mm-dd">
														</label>
							  						</li>
							  						<li class="list-group-item text-left" id="rangoEspecificoo">
							  							<label>
							  								<input type="checkbox" name="rango" class="rangoGc">
							  								RANGO
							  							</label>
							  							<label style="margin-left: 15px">
															Desde:
															<input class="form-control form-inline" type="date" name="desde" id="desdeCg" disabled="disabled" placeholder="yyyy-mm-dd">
														</label>
														<label style="margin-left: 15px">
															Hasta:
															<input class="form-control form-inline" type="date" name="hasta" disabled="disabled" id="hastaCg" placeholder="yyyy-mm-dd">
														</label>
														<div id="id-emp">
													
														</div>
							  						</li>
							  					</ul>
							  					<div class="col-lg-6 text-left">
											        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
											    </div>
							            		<div class="col-lg-6 text-right">
													<button id="botonc" type="submit" name="buscar" value="Buscar"  class="btn" style="background-color: #48c9b0; color:white;">
														<b>BUSCAR</b>
													</button>												
												</div>
							  				</form>
									  	</div>
									</div>
						        </div>
							</div>
						</div>	
					</td>
				</tr>`
			)
			

			    $('#diaCg').datepicker({
			        format: "yyyy-mm-dd",
			        language: "es",
			        autoclose: true
			    });
			    
			    $('#desdeCg').datepicker({
			        format: "yyyy-mm-dd",
			        language: "es",
			        autoclose: true
			    });
			    $('#hastaCg').datepicker({
			        format: "yyyy-mm-dd",
			        language: "es",
			        autoclose: true
			    });
			$('.diasGc').click(function(e){
			  if ($(this).is(':checked')) {
			    $('.rangoGc').prop('checked',false);
			  	$('#diaCg').prop('disabled', false);
			  	$('#desdeCg').prop('disabled', true);
			  	$('#hastaCg').prop('disabled', true);
			  }
			});

			$('.rangoGc').click(function(e){
			  if ($(this).is(':checked')) {
			    $('.diasGc').prop('checked',false);
			  	$('#diaCg').prop('disabled', true);
			  	$('#desdeCg').prop('disabled', false);
			  	$('#hastaCg').prop('disabled', false);
			  }
			});


			$('.asistenciaesp').click(function(e){
			  if ($(this).is(':checked')) {
			    $('.asistenciaesp').prop('checked',true);
			    $('.inasistenciaesp').prop('checked',false);
			    $('#rangoEspecifico').slideDown(500);
			    $('#rangoEspecificoo').slideDown(500);
			  }
			  else{
			  	$('.asistenciaesp').prop('checked',false);
			    $('.inasistenciaesp').prop('checked',true);
			    $('#rangoEspecifico').slideUp(500);
			    $('#rangoEspecificoo').slideUp(500);
			    $('.diasGc').prop('checked',true);
			    $('.rangoGc').prop('checked',false);
			  	$('#diaCg').prop('disabled', false);
			  	$('#desdeCg').prop('disabled', true);
			  	$('#hastaCg').prop('disabled', true);
			  }
			});

			$('.inasistenciaesp').click(function(e){
			  if ($(this).is(':checked')) {
			   $('.asistenciaesp').prop('checked',false);
			    $('.inasistenciaesp').prop('checked',true);
			    $('#rangoEspecifico').slideUp(500);
			    $('#rangoEspecificoo').slideUp(500);
			    $('.diasGc').prop('checked',true);
			    $('.rangoGc').prop('checked',false);
			  	$('#diaCg').prop('disabled', false);
			  	$('#desdeCg').prop('disabled', true);
			  	$('#hastaCg').prop('disabled', true);
			  }
			  else{
			  	$('.asistenciaesp').prop('checked',true);
			    $('.inasistenciaesp').prop('checked',false);
			    $('#rangoEspecifico').slideDown(500);
			    $('#rangoEspecificoo').slideDown(500);
			  }
			});
		});
	});
});


//BUSQUEDA DE ASISTENCIA GENERAL

$('#sabdom').click(function(e){
  if ($(this).is(':checked')) {
  	$('#asistenciaGC').hide(500);
  	$('#fxdia').hide(500);
    $('.asistenciag').prop('checked',true);
    $('.inasistenciag').prop('checked',false);    
    $('.diaCh').prop('checked',false);
  	$('#diaC').prop('disabled', true);
    $('.rangoCh').prop('checked',true);
  	$('#desdeControl').prop('disabled', false);
  	$('#hastaControl').prop('disabled', false);
  	$('#rangoGeneral').show(500);
  }
  else{
  	$('#asistenciaGC').show(500);
  	$('#fxdia').show(500);
  	$('#rangoGeneral').show(500);
    $('.asistenciag').prop('checked',true);
    $('.inasistenciag').prop('checked',false);
  }
});
$('.diaCh').click(function(e){
  if ($(this).is(':checked')) {
    $('.rangoCh').prop('checked',false);
  	$('#diaC').prop('disabled', false);
  	$('#desdeControl').prop('disabled', true);
  	$('#hastaControl').prop('disabled', true);
  }
});

$('.rangoCh').click(function(e){
  if ($(this).is(':checked')) {
    $('.diaCh').prop('checked',false);
  	$('#diaC').prop('disabled', true);
  	$('#desdeControl').prop('disabled', false);
  	$('#hastaControl').prop('disabled', false);
  }
});


$('.asistenciag').click(function(e){
  if ($(this).is(':checked')) {
    $('.asistenciag').prop('checked',true);
    $('.inasistenciag').prop('checked',false);
    $('#rangoGeneral').slideDown(500);
  }
  else{
  	$('.asistenciag').prop('checked',false);
    $('.inasistenciag').prop('checked',true);
    $('#rangoGeneral').slideUp(500);
    $('.diaCh').prop('checked',true);
    $('.rangoCh').prop('checked',false);
    $('#diaC').prop('disabled', false);
  	$('#desdeControl').prop('disabled', true);
  	$('#hastaControl').prop('disabled', true);
  }
});

$('.inasistenciag').click(function(e){
  if ($(this).is(':checked')) {
   $('.asistenciag').prop('checked',false);
    $('.inasistenciag').prop('checked',true);
    $('.diaCh').prop('checked',true);
    $('.rangoCh').prop('checked',false);
    $('#rangoGeneral').slideUp(500);
    $('#diaC').prop('disabled', false);
  	$('#desdeControl').prop('disabled', true);
  	$('#hastaControl').prop('disabled', true);
  }
  else{
  	$('.asistenciag').prop('checked',true);
    $('.inasistenciag').prop('checked',false);
    $('#rangoGeneral').slideDown(500);
  }
});


// BUSQUEDA DE ASISTENCIA ESPECÍFICA
$('#sabdomi').click(function(e){
  if ($(this).is(':checked')) {
  	$('#asistenciaIC').hide(500);
  	$('#fxdiai').hide(500);
    $('.asistenciaesp').prop('checked',true);
    $('.inasistenciaesp').prop('checked',false);    
    $('.diasGc').prop('checked',false);
  	$('#diaCg').prop('disabled', true);
    $('.rangoGc').prop('checked',true);
  	$('#desdeCg').prop('disabled', false);
  	$('#hastaCg').prop('disabled', false);
  	$('#rangoEspecifico').show(500);
  }
  else{
  	$('#asistenciaIC').show(500);
  	$('#fxdiai').show(500);
  	$('#rangoEspecifico').show(500);
    $('.asistenciaesp').prop('checked',true);
    $('.inasistenciaesp').prop('checked',false);
  }
});
$('.diasGc').click(function(e){
  if ($(this).is(':checked')) {
    $('.rangoGc').prop('checked',false);
  	$('#diaCg').prop('disabled', false);
  	$('#desdeCg').prop('disabled', true);
  	$('#hastaCg').prop('disabled', true);
  }
});

$('.rangoGc').click(function(e){
  if ($(this).is(':checked')) {
    $('.diasGc').prop('checked',false);
  	$('#diaCg').prop('disabled', true);
  	$('#desdeCg').prop('disabled', false);
  	$('#hastaCg').prop('disabled', false);
  }
});


$('.asistenciaesp').click(function(e){
  if ($(this).is(':checked')) {
    $('.asistenciaesp').prop('checked',true);
    $('.inasistenciaesp').prop('checked',false);
    $('#rangoEspecifico').slideDown(500);
    $('#rangoEspecificoo').slideDown(500);
  }
  else{
  	$('.asistenciaesp').prop('checked',false);
    $('.inasistenciaesp').prop('checked',true);
    $('#rangoEspecifico').slideUp(500);
    $('#rangoEspecificoo').slideUp(500);
    $('.diasGc').prop('checked',true);
    $('.rangoGc').prop('checked',false);
  	$('#diaCg').prop('disabled', false);
  	$('#desdeCg').prop('disabled', true);
  	$('#hastaCg').prop('disabled', true);
  }
});

$('.inasistenciaesp').click(function(e){
  if ($(this).is(':checked')) {
   $('.asistenciaesp').prop('checked',false);
    $('.inasistenciaesp').prop('checked',true);
    $('#rangoEspecifico').slideUp(500);
    $('#rangoEspecificoo').slideUp(500);
    $('.diasGc').prop('checked',true);
    $('.rangoGc').prop('checked',false);
  	$('#diaCg').prop('disabled', false);
  	$('#desdeCg').prop('disabled', true);
  	$('#hastaCg').prop('disabled', true);
  }
  else{
  	$('.asistenciaesp').prop('checked',true);
    $('.inasistenciaesp').prop('checked',false);
    $('#rangoEspecifico').slideDown(500);
    $('#rangoEspecificoo').slideDown(500);
  }
});