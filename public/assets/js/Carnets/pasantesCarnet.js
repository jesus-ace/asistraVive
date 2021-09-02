$(".editPasante").click(function(event){
	var id = $(this).attr("pasanteId");
	$.get(`editarPasante/${id}`, function(response, editPasante){
		console.log(response);

		$("#pasante").empty();

		$("#motPasante").fadeIn("slow");
		$("#reporte").fadeIn("slow");
		
		response.forEach(element=> {

			var status = element.status;

			if (status == 1) {
				var status = "Activo";

				$("#motPasante").fadeIn("slow");
				$("#reporte").fadeIn("slow");
				$("#nuevo").fadeOut("slow");
				$("#ReasignarP").fadeOut("slow");
				$("#asignarP").fadeIn("slow");

			}else{
				var status = "Inactivo";

				$("#motPasante").fadeOut("slow");
				$("#reporte").fadeOut("slow");
				$("#nuevo").fadeIn("slow");
				$("#ReasignarP").fadeIn("slow");
				$("#asignarP").fadeOut("slow");
			}


		$("#pasante").append(`
				<input type="hidden" name="id" id="id" value="${element.cedula}">

				<div id="pasante">



				<div class="form-group col-xs-4">
              		<label>Cédula: </label>
					<input type="text" name="cedulaPasante" class="form-control" value="${element.cedula}" required>
          		</div>

          		<div class="form-group col-xs-6">
              		<label>Nombres: </label>
					<input type="text" name="nombrePasante" class="form-control" value="${element.nombres}" required>
          		</div>

          		<div class="form-group col-xs-6">
          			<label>Apellidos: </label>
					<input type="text" name="apellidoPasante" class="form-control" value="${element.apellidos}" required>
				</div>

				<div class="form-group col-xs-4">
          			<label>Área: </label>
					<input type="text" name="areaPasante" class="form-control" value="${element.des_uni}" required>
				</div>

				<div class="form-group col-xs-4">
          			<label>Status: </label>
					<input type="text" name="status" class="form-control" value="${status}" required>
				</div>

				<div class="form-group col-xs-4">
          			<label>Fecha de Vencimiento: </label>
					<input type="text" name="fechaV" class="form-control" value="${element.carus_fecha_vencimiento}" required>
				</div>

          		</div>

			</form>`)
		});
	});
});


// busca pasantes con carnets--------------------

$(".buscaPasConC").click(function(event){
	var cedula = $('#busCarntPast').val();

	if (cedula == "") {

		$("#motPasante").fadeOut("slow");
		$("#reporte").fadeOut("slow");
      	
      	$("#pasante").empty();

      	$("#pasante").append(`<br>Disculpe, debe ingresar una cédula..`)

	}else{
	$.get(`busPasConCar/${cedula}`, function(response, buscaPasConC){
		console.log(response);
		//alert(cedula);
		$("#pasante").empty();
		if (response == "" ) {

			$("#motPasante").fadeOut("slow");
			$("#reporte").fadeOut("slow");

      		$("#pasante").append(`<br>Disculpe, no se encontraron resultados...`)
      		//alert(cedula);
   		}else{

   			$("#motPasante").fadeIn("slow");
			$("#reporte").fadeIn("slow");

		response.forEach(element=> {

			var status = element.status;

			if (status == 1) {
				var status = "Activo";

				$("#motPasante").fadeIn("slow");
				$("#reporte").fadeIn("slow");
				$("#nuevo").fadeOut("slow");
				$("#ReasignarP").fadeOut("slow");
				$("#asignarP").fadeIn("slow");

			}else{
				var status = "Inactivo";

				$("#motPasante").fadeOut("slow");
				$("#reporte").fadeOut("slow");
				$("#nuevo").fadeIn("slow");
				$("#ReasignarP").fadeIn("slow");
				$("#asignarP").fadeOut("slow");
			}

		$("#pasante").append(`
				<input type="hidden" name="id" id="id" value="${element.cedula}">

				<div id="pasante">



				<div class="form-group col-xs-4">
              		<label>Cédula: </label>
					<input type="text" name="cedulaPasante" class="form-control" value="${element.cedula}" required>
          		</div>

          		<div class="form-group col-xs-6">
              		<label>Nombres: </label>
					<input type="text" name="nombrePasante" class="form-control" value="${element.nombres}" required>
          		</div>

          		<div class="form-group col-xs-6">
          			<label>Apellidos: </label>
					<input type="text" name="apellidoPasante" class="form-control" value="${element.apellidos}" required>
				</div>

				<div class="form-group col-xs-4">
          			<label>Área: </label>
					<input type="text" name="areaPasante" class="form-control" value="${element.des_uni}" required>
				</div>

				<div class="form-group col-xs-4">
          			<label>Status: </label>
					<input type="text" name="status" class="form-control" value="${status}" required>
				</div>

				<div class="form-group col-xs-4">
          			<label>Fecha de Vencimiento: </label>
					<input type="text" name="fechaV" class="form-control" value="${element.carus_fecha_vencimiento}" required>
				</div>

          		</div>

			</form>`)
		});
	}
	});
}
});

$("#motivoPasante").click(function(){

	var motivo = $("#motivoPasante").val();

	if (motivo == 6) {
		$("#reporte").fadeOut("slow");
	}else{
		$("#reporte").fadeIn("slow");
	}


});


// busca pasantes sin carnets ------------------------------------
$(".buscaPasSinC").click(function(event){

	var cedula = $('#busCarnetPas').val();

	if (cedula == "") {
      	
      	$("#pasante").empty();

      	$("#pasante").append(`<br>Disculpe, debe ingresar una cédula..`)

	}else{
	$.get(`busPasSinCar/${cedula}`, function(response, buscaPasSinC){
		console.log(response);
		//alert(cedula);
		$("#pasante").empty();
		if (response == "" ) {

      		$("#pasante").append(`<br>Disculpe, no se encontraron resultados...`)
      		//alert(cedula);
   		}else{
		
		response.forEach(element=> {
					$("#pasante").append(`
				<input type="hidden" name="id" id="id" value="${element.cedula}">

				<div id="pasante">

				<div class="form-group col-xs-4">
              		<label>Cedula: </label>
					<input type="text" name="cedulaPasante" class="form-control" value="${element.cedula}" required>
          		</div>

          		<div class="form-group col-xs-4">
              		<label>Nombre: </label>
					<input type="text" name="nombrePasante" class="form-control" value="${element.nombres}" required>
          		</div>

          		<div class="form-group col-xs-4">
          			<label>Apellido: </label>
					<input type="text" name="apellidoPasante" class="form-control" value="${element.apellidos}" required>
				</div>

				<div class="form-group col-xs-8">
          			<label>Area: </label>
					<input type="text" name="areaPasante" class="form-control" value="${element.des_uni}" required>
				</div>

				<div class="form-group col-xs-4">
              	 <label for="date">Fecha de Vencimiento: </label>
                  <div class="input-group col-lg-12">
                    <input type="date" class="form-control datepicker" name="pasanteFechaVec" id="pasanteFechaVec">
                        <div class="input-group-addon">
                            <span class="glyphicon glyphicon-th"></span>
                        </div>
                  </div>
          		</div>

          		</div>
				
				

				
			</form>`)
		});
	}
	});
}

});








// registra los carnets de los pasantes----------------------

$(".creaPasante").click(function(event){
	var id = $(this).attr("pasanteId");
	$.get(`crearPasante/${id}`, function(response, editPasante){
		console.log(response);
		$("#pasante").empty();
		response.forEach(element=> {
		$("#pasante").append(`
				<input type="hidden" name="id" id="id" value="${element.cedula}">

				<div id="pasante">

				<div class="form-group col-xs-4">
              		<label>Cedula: </label>
					<input type="text" name="cedulaPasante" class="form-control" value="${element.cedula}" required>
          		</div>

          		<div class="form-group col-xs-4">
              		<label>Nombre: </label>
					<input type="text" name="nombrePasante" class="form-control" value="${element.nombres}" required>
          		</div>

          		<div class="form-group col-xs-4">
          			<label>Apellido: </label>
					<input type="text" name="apellidoPasante" class="form-control" value="${element.apellidos}" required>
				</div>

				<div class="col-xs-4" id="foto">
                    <label>Foto</label>
                       <input type="file" class="form-control" name="image" id="image" accept="image/*" >
                </div>

				<div class="form-group col-xs-4">
          			<label>Area: </label>
					<input type="text" name="areaPasante" class="form-control" value="${element.des_uni}" required>
				</div>

				<div class="form-group col-xs-4">
              	 <label for="date">Fecha de Vencimiento: </label>
                  <div class="input-group col-lg-12">
                    <input type="date" class="form-control datepicker" name="pasanteFechaVec" id="pasanteFechaVec">
                        <div class="input-group-addon">
                            <span class="glyphicon glyphicon-th"></span>
                        </div>
                  </div>
          		</div>

          		</div>
				
				

				
			</form>`)
		});
	});
});
// registra los carnets de los pasantes----------------------

$(".creaProv").click(function(event){
	var id = $(this).attr("pasanteId");
	$.get(`crearPasante/${id}`, function(response, editPasante){
		console.log(response);
		$("#fondoC").empty();
		$("#provS").fadeIn();

		response.forEach(element=> {
			$('#id_emp').val(element.cedula);
			$('#cedula_emp').val(element.cedula);
			$('#nombre_emp').val(element.nombres);
			$('#apellido_emp').val(element.apellidos);
			$('#area_emp').val(element.des_uni);
		});
	});
});

$(".empleaSinCarnet").click(function(event){
	var id = $("#busCarnetPas").val();
	$.get(`crearPasante/${id}`, function(response, editPasante){
		console.log(response);

		if (response == "" ) {

			//$("#provS").empty();
			$("#provS").fadeOut();

			$("#fondoC").append(`<br>
            <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            Disculpe, no se encontraron resultados... </div>`)          
      
      }else{

      	$("#fondoC").empty();

      	$("#provS").fadeIn();

		response.forEach(element=> {
			$('#id_emp').val(element.cedula);
			$('#cedula_emp').val(element.cedula);
			$('#nombre_emp').val(element.nombres);
			$('#apellido_emp').val(element.apellidos);
			$('#area_emp').val(element.des_uni);
		});

	}

	});
});





$("#imprimirReportarC").click(function(){

  var reportar = $("#imprimirReportarC").val();

  if (reportar == "Si") {

  	$("#asignarP").fadeOut("slow");
  	$("#reportarProvisional").fadeIn("slow");

  }else{
  	$("#reportarProvisional").fadeOut("slow");
  	$("#asignarP").fadeIn("slow");
  }

});



