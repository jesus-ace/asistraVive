setInterval(function(){
	$.get(`get_entradas_dia`,function(response) {
		var entradas = response;
		$.get(`get_salidas_dia`,function(response) {
			var salidas = response;
			$('#entradas').empty();
			entradas.forEach(element =>{
				if (element.asi_entrada_hora == null) {
					entrada = '00:00';
				}
				else{
					entrada = element.asi_entrada_hora;
				}
				$('#entradas').append(`
					<tr>
				        <td>
				        	<div style="border-radius: 6px 6px 6px 6px;-moz-border-radius: 6px 6px 6px 6px;-webkit-border-radius: 6px 6px 6px 6px;border: 1px solid #0818ff;width:70px;height:70px">

				        		<img style="height:98%;width:98%;border-radius: 6px 6px 6px 6px;-moz-border-radius: 6px 6px 6px 6px;-webkit-border-radius: 6px 6px 6px 6px;border: 0px solid #0818ff" src="imagenes2/${element.us_foto}">
				        	</div>

				        </td>
				        <td>
				        	<b>${element.us_nom} ${element.us_ape}</b><br>
				        	${element.dp_nombre}
				        </td>
						<td>
						Hora: <b>${entrada}</b>

						</td>
				     </tr>
				`);
			});
			$('#salidas').empty();
			salidas.forEach(element =>{
				$('#salidas').append(`
					<tr>
				        <td>
				        	<div style="border-radius: 6px 6px 6px 6px;-moz-border-radius: 6px 6px 6px 6px;-webkit-border-radius: 6px 6px 6px 6px;border: 1px solid #0818ff;width:70px;height:70px">

				        		<img style="height:98%;width:98%;border-radius: 6px 6px 6px 6px;-moz-border-radius: 6px 6px 6px 6px;-webkit-border-radius: 6px 6px 6px 6px;border: 0px solid #0818ff" src="imagenes2/${element.us_foto}">
				        	</div>

				        </td>
				        <td>
				        	<b>${element.us_nom} ${element.us_ape}</b><br>
				        	${element.dp_nombre}
				        </td>
						<td>
						Hora: <b>${element.asi_salida_hora}</b>

						</td>
				     </tr>
				`);
			})
		})
	})
},2000); 

setInterval(function(){
	$.get(`get_not_dia`,function(response){
		console.log(response)
		if (response != '') {
			response.forEach(element =>{
				$('#alertas').empty();
				$('#alertas').append(`
				<div class="col-lg-6" style="margin-left: 50%; position: absolute;z-index: 4;margin-top: 5%" >
					<div class="alert alert-danger  alert-dismissable fade in">
			    		<a class="close" data-dismiss="alert" aria-label="close" onclick="delete_alerta(${element.alert_id})">&times;</a>
						<p><strong>¡Alerta!</strong></p>
						<table class="table">
							<tbody >
									<tr>
										<td>
											<div style="border-radius: 6px 6px 6px 6px;-moz-border-radius: 6px 6px 6px 6px;-webkit-border-radius: 6px 6px 6px 6px;border: 0px solid #0818ff;width:70px;height:70px">
												<img style="height:100%;width:98%;border-radius: 6px 6px 6px 6px;-moz-border-radius: 6px 6px 6px 6px;-webkit-border-radius: 6px 6px 6px 6px;border: 0px solid #0818ff" src="assets/img/iconos/denegado.svg">
											</div>
										</td>
										<td>
											<div>	
												<p>
													${element.alert_alerta}
												</p>
											</div>
										</td>
										<td>
											Hora:${element.alert_hora}</br>
										</td>
									</tr>
							</tbody>
						</table>	
					</div>
				</div>`)
			});
		}
		else{
			return false;
		}
	});
},2000)




$('#departamentoNot').change(function(event){
	$('#cedulaAut').val('');
	$.get(`getDepNot/${event.target.value}`, function(response){
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
					</td>
				</tr>`)
		});
	});
});

$("#cedulaNot").change(function(event){ 
	var cedula = $('#cedulaNot').val();
	var token = $('#_token').val();
	$.get(`BcedulaNot/${cedula}`, function(response, cedulaau){ 
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
					</td>
				</tr>`)
		});
	});
});
function delete_alerta(id) {
	$.get(`delete_alerta/${id}`,function(response){
		return true;
	});
}