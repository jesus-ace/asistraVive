$(".edithorario").click(function(event){
	var id = $(this).attr("tiho");
	$.get(`horarioedit/${id}`, function(response, edithorario){
		$("#horas").empty();
		response.forEach(element=> {

			var diasH = element.tiho_dias.split(",");
			var dias = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado","Domingo"];
        	for (var i = 0; i < diasH.length; i++) {
        		for (var j = 0; j < dias.length; j++) {
        			
        		}
        	}
			$("#horas").append(`
				<input type="hidden" name="id" id="id" value="${element.tiho_id}">
				<li class="list-group-item text-center">
			  		DÍAS DE LA SEMANA
			  	</li>
			  	<li class="list-group-item">
			  		<label class="v">
			  			LUNES 
			  			<input type="checkbox" name="dias[]" id="Lunes" value="Lunes" style="margin-right: 10px;"> 
			  		</label>
			  		<label class="v">
				  		MARTES
				  		<input type="checkbox" name="dias[]" id="Martes" value="Martes" style="margin-right: 10px;">
			  		</label>
			  		<label class="v">
				  		MIÉRCOLES
				  		<input type="checkbox" name="dias[]" id="Miercoles" value="Miercoles" style="margin-right: 10px;">
			  		</label>
			  		<label class="v">
			  			JUEVES
			  			<input type="checkbox" name="dias[]" id="Jueves" value="Jueves" style="margin-right: 10px;">
			  		</label>
			  		<label>
			  			VIERNES
			  			<input type="checkbox" name="dias[]" id="Viernes" value="Viernes">
			  		</label>
			  	</li>
			  	<li class="list-group-item text-center">
			  		FIN DE SEMANA
			  	</li>
			  	<li class="list-group-item text-center">
			  		<label class="v">
			  			SÁBADO
			  			<input type="checkbox" name="dias[]" id="Sabado" value="Sabado" style="margin-right: 10px;">
			  		</label>
			  		<label>
			  			DOMINGO
						<input type="checkbox" name="dias[]" id="Domingo" value="Domingo">
			  		</label>
			  	</li>
			  	<li class="list-group-item">
			  		<label class="form-inline">
			  			HORA DE ENTRADA
			  			<input type="time" name="time1" class="hora_entrada_add v form-control" value="${element.tiho_hora_en}" required id="hora_entrada_add">
			  		</label>
			  		<label class="form-inline" style="border-left: 1px solid #ccc;   font-size: 14px;">
			  			<p style=" margin-left: 10px;">
			  			HORA DE SALIDA
			  			<input type="time" name="time2" class="hora_salida_add form-control" id="hora_salida_add" value="${element.tiho_hora_sa}" required>
			  			</p>
			  		</label>
			  	</li>

			  	<li class="list-group-item">
			  		<label class="form-inline" style="font-size: 12px;">
			  			HOLGURA DE ENTRADA
			  			<input type="time" name="holgura_en" id="holgura_en" class=" v holgura_en form-control" title="Tiempo de holgura antes de la hora de entrada" required value="${element.tiho_holgura_entrada}">
			  		</label>
			  		<label class="form-inline" style="border-left: 1px solid #ccc; font-size: 12px;">
			  			<p style=" margin-left: 10px;">
			  			HOLGURA DE SALIDA
			  			<input type="time" name="holgura_sa" id="holgura_sa" class="holgura_sa form-control" title="Tiempo de holgura despues de la hora de salida" required value="${element.tiho_holgura_salida}">
			  			</p>
			  		</label>
			  	</li>
			`)
			$('#hora_entrada_add').change(function(e){

				var hora_entrada= $('#hora_entrada_add').val().split(":");

				var holgura_en = $('#holgura_en').val().split(":");

				var holgura_d = hora_entrada[0] - 1;

				if (holgura_d < 10) {
					holgura_default = '0'+holgura_d+':00:00';
				}
				else{
					holgura_default = holgura_d+':00:00';
				}
				$('.holgura_en').val(holgura_default);
			});




			$('#holgura_en').change(function(e){
				var hora_entrada= $('#hora_entrada_add').val().split(":");

				var holgura_en = $('#holgura_en').val().split(":");

				console.log(holgura_en[0]+'----'+ hora_entrada[0])
				if (holgura_en[0] > hora_entrada) {

					swal('La hora de holgura es mayor a la hora de entrada, por favor intente de nuevo.');

					holgura_default1 = hora_entrada[0] - 1;
					if (holgura_default1 < 10) {
						holgura_default = '0'+holgura_default1+':00:00';
					
					}
					else{
						holgura_default = holgura_default1+':00:00';
					}
					$('#holgura_en').val(holgura_default);
				}
				else{
					return true;
				}

			});

			$('#hora_salida_add').change(function(e){
				var hora_salida = $('#hora_salida_add').val().split(":");

				var holgura_sa = $('#holgura_sa').val().split(":");

				var holgura_deftl_sa = parseInt(hora_salida)+ parseInt(1);

				if (holgura_deftl_sa < 10) {
					holgura_default = '0'+holgura_deftl_sa+':00:00';
				
				}
				else{
					holgura_default = holgura_deftl_sa+':00:00';
				}

				$('#holgura_sa').val(holgura_default);
			});


			$('#holgura_sa').change(function(e){
				var hora_salida = $('#hora_salida_add').val().split(":");

				var holgura_sa = $('#holgura_sa').val().split(":");


				if (holgura_sa[0] < hora_salida[0]) {

					swal('La hora de holgura es menor a la hora de salida, por favor intente de nuevo.');

					
					var holgura_deftl_sa = parseInt(hora_salida)+ parseInt(1);

					if (holgura_deftl_sa < 10) {

						holgura_default = '0'+holgura_deftl_sa+':00:00';
					
					}
					else{
						holgura_default = holgura_deftl_sa+':00:00';
					}
					$('#holgura_sa').val(holgura_default);
				}
				else{
					return true;
				}

				$('#holgura_sa').val(holgura_default);
			});
		});
	});
});

$('#asig_masa_lista').click(function(e){
  	//Muestra
 	 if ($(this).is(':checked')) {
    	$('.all_asig_ma').prop('checked',true);
 	}
 	 else{
    	//Les quita la seleccion
    	$('.all_asig_ma').prop('checked',false);
  	}
});


$('#horarioAsig').change(function(e){
	var horario = $('#horarioAsig').val();
	$('#dpMasa').slideDown(500);

	$("#dptoHorario").change(function(event){
	
		$("#listaAM").slideDown(500);
		$('#btn_cancelar_asg').slideDown(500);
		$('#btn_guardar_asg').slideDown(500);

		var depa = $('#dptoHorario').val();

		$.get(`dptoHorario/${depa}`, function(response, edithorario){ 

			$('#asigUs').empty();
			if (response == '') {
				$("#listaAM").slideUp(500);
				$('#btn_cancelar_asg').slideUp(500);
				$('#btn_guardar_asg').slideUp(500);
				swal('Este departamento no tiene empleados asignados');
				return false;
			}
			else{
				response.forEach(element=> {
					if (element.tiho_id == horario) {
						var ch_horario = 'checked';
					}
					else{
						ch_horario ='';
					}
					if (element.us_foto != '') {

			            var foto = element.us_foto;

			        }
			        else{

			            var foto = 'usericono.JPG';

			        }
					$("#asigUs").append(`

						<tr>
							<th>
								<img src="imagenes2/${foto}" style="height: 50px; width:50px;">
							</th>
							<th style="margin-top:10px;">
								${element.us_nom} ${element.us_ape}
							</th>
							<th style="margin-top:10px;">
								${element.us_ced}
							</th>
							<th style="margin-top:10px;">
								<input type="checkbox" name="empleados[]" value="${element.us_ced}" aling="right" class="all_asig_ma" ${ch_horario}> 
							</th>
					</tr>`)
					$('.all_asig_ma').change(function(e){
						console.log(element.us_ced)
					});
				});
			}
		});
	});

});
$('.hora_entrada_add').change(function(e){

	var hora_entrada= $('.hora_entrada_add').val().split(":");

	var holgura_en = $('.holgura_en').val().split(":");

	var holgura_d = hora_entrada[0] - 1;
	if (holgura_d < 10) {
		holgura_default = '0'+holgura_d+':00:00';
	
	}
	else{
		holgura_default = holgura_d+':00:00';
	}

	$('.holgura_en').val(holgura_default);
});




$('.holgura_en').change(function(e){
	var hora_entrada= $('.hora_entrada_add').val().split(":");

	var holgura_en = $('.holgura_en').val().split(":");

	console.log(holgura_en[0]+'----'+ hora_entrada[0])
	if (holgura_en[0] > hora_entrada) {

		swal('La hora de holgura es mayor a la hora de entrada, por favor intente de nuevo.');

		holgura_default1 = hora_entrada[0] - 1;
		if (holgura_default1 < 10) {
			holgura_default = '0'+holgura_default1+':00:00';
		
		}
		else{
			holgura_default = holgura_default1+':00:00';
		}
		$('.holgura_en').val(holgura_default);
	}
	else{
		return true;
	}

});

$('.hora_salida_add').change(function(e){
	var hora_salida = $('.hora_salida_add').val().split(":");

	var holgura_sa = $('.holgura_sa').val().split(":");

	var holgura_deftl_sa = parseInt(hora_salida)+ parseInt(1);

	if (holgura_deftl_sa < 10) {
		holgura_default = '0'+holgura_deftl_sa+':00:00';
	
	}
	else{
		holgura_default = holgura_deftl_sa+':00:00';
	}

	$('.holgura_sa').val(holgura_default);
});


$('.holgura_sa').change(function(e){
	var hora_salida = $('.hora_salida_add').val().split(":");

	var holgura_sa = $('.holgura_sa').val().split(":");


	if (holgura_sa[0] < hora_salida[0]) {

		swal('La hora de holgura es menor a la hora de salida, por favor intente de nuevo.');

		
		var holgura_deftl_sa = parseInt(hora_salida)+ parseInt(1);

		if (holgura_deftl_sa < 10) {

			holgura_default = '0'+holgura_deftl_sa+':00:00';
		
		}
		else{
			holgura_default = holgura_deftl_sa+':00:00';
		}
		$('.holgura_sa').val(holgura_default);
	}
	else{
		return true;
	}

	$('.holgura_sa').val(holgura_default);
});
$(".eliminarhorario").click(function(event){
	var id = $(this).attr("ho_ho");
	swal ( {
	  	title: "¿Estas seguro?",
	  	text: "¿Desea eliminar este horario? Este horario puede pertenecer a varios usuarios",
	  	icon: "warning",
	  	buttons: true,
	  	dangerMode: true, 
	} )
	.then((willDelete) => {
	  	if (willDelete) {
	    	$.get(`horariodelete/${id}`, function(response, eliminarhorario){
	    		setTimeout('document.location.reload()',1000);
				swal("Poof! Este horario ha sido eliminado!", {
	        	icon: "success",

	    		});
			});
	  	} 
	});
});






$('#cancelar_asig_m').click(function(event){
	$('#asigUs').empty();
});

$(document).ready(function() {
    setTimeout(function() {
        $("#alerta").slideUp(5500);
    },1500);
});
$(document).ready(function() {
    setTimeout(function() {
        $("#AlertaHome").slideUp(5500);
    },1500);
});
$(document).ready(function() {
    setTimeout(function() {
        $("#Alertaerror").slideUp(5500);
    },1500);
});
