$('#pres').click(function(evento) {
	if ($(this).is(':checked')) {
		setTimeout(function() {
	      $('.dp_pres').slideDown(500);
	      $('.ve_presi').prop('checked',true);
	    },100);
	}
	else{
	    
      $('.ve_presi').prop('checked',false);
      $('.vp_interna').slideUp(500);
      $('.vp_productiva').slideUp(500);
      $('.sedes_list').slideUp(500);
      $('.vp_des_tec').slideUp(500);
      $('.vp_ope').slideUp(500);
	}
});

$('#vp_gest_inter').click(function(evento) {
	if ($(this).is(':checked')) {
		setTimeout(function() {
	      $('.vp_interna').slideDown(500);
	    },100);
	}
	else{

      $('.ve_vp_interna').prop('checked',false);
      $('.vp_interna').slideUp(500);
	}
});

$('#vp_gest_prod').click(function(evento) {
	if ($(this).is(':checked')) {
		setTimeout(function() {
	      $('.vp_productiva').slideDown(500);
	    },100);
	}
	else{

      $('.ve_vp_produc').prop('checked',false);
      $('.vp_productiva').slideUp(500);
      $('.sedes_list').slideUp(500);
	}
});

		
$('#sedes').click(function(evento) {
	if ($(this).is(':checked')) {
		setTimeout(function() {
	      $('.sedes_list').slideDown(500);
	    },100);
	}
	else{

      $('.ve_sedes').prop('checked',false);
      $('.sedes_list').slideUp(500);
	}
});

$('#vp_gest_des_tec').click(function(evento) {
	if ($(this).is(':checked')) {
		setTimeout(function() {
	      $('.vp_des_tec').slideDown(500);
	    },100);
	}
	else{

      $('.ve_vp_destec').prop('checked',false);
      $('.vp_des_tec').slideUp(500);
	}
});

$('#vp_gest_ope').click(function(evento) {
	if ($(this).is(':checked')) {
		setTimeout(function() {
	      $('.vp_ope').slideDown(500);
	    },100);
	}
	else{

      $('.ve_vp_ope').prop('checked',false);
      $('.vp_ope').slideUp(500);
	}
});

$('#cedulaDp').change(function(e){
	var cedula = $('#cedulaDp').val();
	$.get(`get_ced_dp/${cedula}`,function(response) {
		console.log(response);
		if (response != '') {
			$('#InfEmp').empty();
			response.forEach(element =>{
			$('#InfEmp').slideDown(200);
			$('#depatos').slideDown(200);
			$('#pie_modal').empty();
			$('#InfEmp').append(`
				<img src="imagenes2/${element.us_foto}" style="height: 60px;border-radius: 6px 6px 6px 6px; border:1px solid #B2B2B2;float:left; margin:10px; margin-top: -4px;"> 
                
                <b style="margin-left:35px; margin-right: -80px;font-size:10px;">NOMBRES Y APELLIDOS: ${element.us_nom} ${element.us_ape}
                </b><br>
                <b style="margin-left: 35px;font-size:10px;">CÉDULA:${element.us_ced}</b> <br>
                
                <b style="margin-left: 35px;font-size:10px;"> DEPARTAMENTO:${element.dp_nombre}</b><br>`
            )
            $('#pie_modal').append(`<div class="col-lg-6 text-left">
            	<input type="hidden" name="cedula" class="form-control" value="${cedula}">
					<button type="button" class="btn btn-default" data-dismiss="modal">CERRAR</button>
				</div>
				<div class="col-lg-6 text-right">
					<button style="background-color: #48c9b0; color:white;" class="btn" type="submit" name="guardar" >
						<b>GUARDAR</b>
					</button>
				</div>`
			)
		});
		}
		else{
			swal('Disculpe. ','Esta cédula no se encuentra en nuestro sistema, por favor, intente nuevamente',{
				icon:'warning',
			});
			$('#cedulaDp').val('');
			$('#InfEmp').slideUp(200);
		}
		
	});
});

function editar_dp(id) {
	$.get(`get_info_dp/${id}`,function(response){
		$('#dpto').empty();
		response.forEach(element =>{
			if (element.dp_status == 1) {
				var check = 'checked';
			}
			else{
				check = '';
			}
			if (element.dp_status != 1) {
				var che = 'checked';
			}
			else{
				che = '';
			}
			$('#dpto').append(`

	  			<input type="hidden" name="id" class="form-control" value="${element.dp_id}">
				<div class="col-md-6 form-group text-left">
	  				<label>Nombre del departamento</label>
	  				<input type="text" name="nombre" class="form-control" value="${element.dp_nombre}">
	  			</div>
	  			<div class="col-md-6 form-group text-left">
	  				<label>Código</label>
	  				<input type="text" name="codigo" class="form-control" value="${element.dp_codigo}">
	  			</div>
	  			<div class="col-md-6 form-group text-left">
	  				<label>Teléfono principal</label>
	  				<input type="text" name="principal" class="form-control" value="${element.dp_tlf_ppl}">
	  			</div>
	  			<div class="col-md-6 form-group text-left">
	  				<label>Teléfono secundario</label>
	  				<input type="text" name="secundario" class="form-control" value="${element.dp_tlf_sec}">
	  			</div>
	  			<div class="col-lg-12">
					<div class="form-group">
						<label>Estatus</label>
						<label class="radio-inline">
							<h4><input type="radio" name="estatus" id="activo" value="1" ${check}> Activo</h4>
						</label>
						<label class="radio-inline">
							<h4><input type="radio" name="estatus" id="inactivo" value="2" ${che}>Inactivo</h4>
						</label>
					</div>
				</div>
			`);
		});
	});
}
function eliminarDp(id) {
	swal ( {
	  	title: "¿Estas seguro?",
	  	text: "¿Desea eliminar este horario? Este horario puede pertenecer a varios usuarios",
	  	icon: "warning",
	  	buttons: true,
	  	dangerMode: true, 
	} )
	.then((willDelete) => {
	  	if (willDelete) {
	    	$.get(`dptodelete/${id}`, function(response, eliminarhorario){
	    		if (response == 1) {
	    			setTimeout('document.location.reload()',1000);
					swal("Poof! Este horario ha sido eliminado!", {
		        	icon: "success",

		    		});
	    		}
	    		else{
	    			swal("Error al eliminar el departamento", {
		        	icon: "danger",

		    		});
	    		}
	    		
			});
	  	} 
	});
}

