function editFeriado(id) {
	$.get(`getTipoDF`,function(response){
		response.forEach(element=>{			
			console.log(response);
			$('#tipodf').append(`<option value="${element.diaf_tife_id}">${element.tife_tipo}</option>`)
		});
	});
	$.get(`editFeriados/${id}`, function(response, feriadosedit){
		console.log(response)
		$('#FeriadoEdit').empty();
		response.forEach(element=>{
			$('#FeriadoEdit').append(`
				<input type="hidden" name="id" value="${element.diaf_id}">
				<div class="form-group">
					<div class="col-lg-6 text-left">
						<label>Tipo de día feriado</label>
						<select class="form-control" name="tipoferiado" id="tipodf">
							<option selected value="${element.diaf_tife_id}">${element.tife_tipo}</option>
						</select>
					</div>
					<div class="col-lg-6 text-left">
						<label>Indique la fecha del día feriado</label>
						<input type="date" name="fecha" class="form-control" value="${element.diaf_feriado}">
					</div>
					<div class="col-lg-12 text-left">
						<br><label>Descripción del día feriado</label>
						<input type="text" name="desc" class="form-control" value="${element.diaf_desc}">
					</div>
				</div>
			`)
		});
	});
}
function deleteFeriado(id) {
	swal ( {
	  title: "¿Estás seguro?",
	  text: "¿Desea eliminar este día feriado?",
	  icon: "warning",
	  buttons: true,
	  dangerMode: true, 
	} )
	.then((willDelete) => {
		if (willDelete) {
	    	$.get(`feriadosdelete/${id}`, function(response, feriadosdelete){
	    		setTimeout('document.location.reload()',1000);
				swal("Poof! Este día feriado ha sido eliminado!", {
	      			icon: "success",
	      		});
			});
		}
	});
}