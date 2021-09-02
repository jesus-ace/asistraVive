function readURL1(input) {
  if(input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function (e) {
      $('#preview').attr('src', e.target.result);
     // $('#previewFirma').attr('src', e.target.result);
    }
      reader.readAsDataURL(input.files[0]);
  }
}


$("#foto").change(function() {
  readURL1(this);
});



$("#SelloCarnet").click(function(){

	var tipo = $("#SelloCarnet").val();

	if (tipo == "PRENSA") {
		$("#selloDePrensa").fadeIn("slow");
		$("#AREANORMAL").fadeOut("slow");
		$("#PRESI").fadeOut("slow");
		$("#vistaCarnet").fadeIn("slow");
		$("#vistaFirma").fadeOut("slow");
	}else if (tipo == "NORMAL") {
		$("#AREANORMAL").fadeIn("slow");
		$("#selloDePrensa").fadeOut("slow");		
		$("#PRESI").fadeOut("slow");
		$("#vistaCarnet").fadeIn("slow");
		$("#vistaFirma").fadeOut("slow");
	}else if (tipo == "FIRMA") {
		$("#AREANORMAL").fadeOut("slow");
		$("#selloDePrensa").fadeOut("slow");
		$("#PRESI").fadeIn("slow");
		$("#vistaFirma").fadeIn("slow");
		$("#vistaCarnet").fadeOut("slow");
	}

	});



$("#tipoCodigo").click(function(){

	var codigo = $("#tipoCodigo").val();

	if (codigo == "1") {
		$(".barra").fadeIn("slow");
		$(".qr").fadeOut("slow");
	}else if (codigo == "2") {
		$(".qr").fadeIn("slow");
		$(".barra").fadeOut("slow");
	}

});

function readURL(input) {
  if(input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function (e) {
     // $('#preview').attr('src', e.target.result);
      $('#previewFirma').attr('src', e.target.result);
    }
      reader.readAsDataURL(input.files[0]);
  }
}


$("#fotoFirma").change(function() {
  readURL(this);
});



$("#tipo").click(function(){

	var tipo = $("#tipo").val();

	if (tipo == "FIRMA") {
		$("#presidenteNombre").fadeIn("slow");
		$("#fotoFirmaPresi").fadeIn("slow");
		$("#codigoSeleccion").fadeOut("slow");
		$("#encabezadoCar").fadeOut("slow");
		$("#empresaCar").fadeOut("slow");
		$("#paginaCar").fadeOut("slow");
		$("#agradeceCar").fadeOut("slow");
		$("#telefonoCar").fadeOut("slow");
		$("#desTelef").fadeOut("slow");
		$("#espacio").fadeOut("slow");
		
	}else if (tipo == "Encabezado") {

		$("#presidenteNombre").fadeOut("slow");
		$("#fotoFirmaPresi").fadeOut("slow");
		$("#codigoSeleccion").fadeOut("slow");
		$("#encabezadoCar").fadeIn("slow");
		$("#empresaCar").fadeOut("slow");
		$("#paginaCar").fadeOut("slow");
		$("#agradeceCar").fadeOut("slow");
		$("#telefonoCar").fadeOut("slow");
		$("#desTelef").fadeOut("slow");

	}else if(tipo == "Empresa"){

		$("#presidenteNombre").fadeOut("slow");
		$("#fotoFirmaPresi").fadeOut("slow");
		$("#codigoSeleccion").fadeOut("slow");
		$("#encabezadoCar").fadeOut("slow");
		$("#empresaCar").fadeIn("slow");
		$("#paginaCar").fadeOut("slow");
		$("#agradeceCar").fadeOut("slow");
		$("#telefonoCar").fadeOut("slow");
		$("#desTelef").fadeOut("slow");
		
	}else if(tipo == "Pagina"){

		$("#presidenteNombre").fadeOut("slow");
		$("#fotoFirmaPresi").fadeOut("slow");
		$("#codigoSeleccion").fadeOut("slow");
		$("#encabezadoCar").fadeOut("slow");
		$("#empresaCar").fadeOut("slow");
		$("#paginaCar").fadeIn("slow");
		$("#agradeceCar").fadeOut("slow");
		$("#telefonoCar").fadeOut("slow");
		$("#desTelef").fadeOut("slow");
		
	}else if(tipo == "Agradecimiento"){

		$("#presidenteNombre").fadeOut("slow");
		$("#fotoFirmaPresi").fadeOut("slow");
		$("#codigoSeleccion").fadeOut("slow");
		$("#encabezadoCar").fadeOut("slow");
		$("#empresaCar").fadeOut("slow");
		$("#paginaCar").fadeOut("slow");
		$("#agradeceCar").fadeIn("slow");
		$("#telefonoCar").fadeOut("slow");
		$("#desTelef").fadeOut("slow");
		$("#espacio").fadeOut("slow");
		
	}else if(tipo == "telefono"){

		$("#presidenteNombre").fadeOut("slow");
		$("#fotoFirmaPresi").fadeOut("slow");
		$("#codigoSeleccion").fadeOut("slow");
		$("#encabezadoCar").fadeOut("slow");
		$("#empresaCar").fadeOut("slow");
		$("#paginaCar").fadeOut("slow");
		$("#agradeceCar").fadeOut("slow");
		$("#telefonoCar").fadeIn("slow");
		$("#desTelef").fadeIn("slow");
		$("#espacio").fadeOut("slow");
		
	}else if(tipo == "codigo"){

		$("#presidenteNombre").fadeOut("slow");
		$("#fotoFirmaPresi").fadeOut("slow");
		$("#codigoSeleccion").fadeIn("slow");
		$("#encabezadoCar").fadeOut("slow");
		$("#empresaCar").fadeOut("slow");
		$("#paginaCar").fadeOut("slow");
		$("#agradeceCar").fadeOut("slow");
		$("#telefonoCar").fadeOut("slow");
		$("#desTelef").fadeOut("slow");
		
	}

});




$("#imgDelantera").click(function(){

	$("#imagenDelantera").fadeIn("slow");
	$("#imagenPosteriorCarnet").fadeOut("slow");
	$("#footerImgPosterior").fadeOut("slow");
	$("#footerImgDelantera").fadeIn("slow");

});


$("#imgPosterior").click(function(){

	$("#imagenPosteriorCarnet").fadeIn("slow");
	$("#imagenDelantera").fadeOut("slow");
	$("#footerImgDelantera").fadeOut("slow");
	$("#footerImgPosterior").fadeIn("slow");

});













$('#registrose').click(function(){

	var tipo = $("#tipo").val();

	if (tipo == "Encabezado") {

    	encabezado();

	}else if(tipo == "Empresa"){

		empresa();
		
	}else if (tipo == "Pagina") {
		
		pagina();

	}else if (tipo == "Agradecimiento") {

		agradecimiento();

	}else if (tipo == "telefono") {

		telefono();

	}else if (tipo == "codigo") {

		codigo();

	}else if (tipo == "FIRMA") {

		foto();

	}

});

function encabezado(){

    var tipo = $('#tipo').val();
    var encabezado = $('#enkbezado').val();
   // var route = 'conf';
    var token = $('#token').val();

    var form = '#postCarnetsE';

    if ((encabezado == "") || (tipo == null) ) {
    swal("Debe llenar ambos campos");
  }else{

		$.ajax({
			url: $(form).attr('action'),
			type: $(form).attr('method'),
			//data: $(formId).serialize(),
       // url:        route,
        headers:    {'X-CSRF-TOKEN':token},
        type:       'POST',
        dataType:   'json',
        data:       {tipo:tipo, enkbezado: encabezado},

        success:function(){
        	swal("registro exitoso");
            $("#msj-success").fadeIn();
            $("#msj-danger").fadeOut();

        },
        error:function(msj) {
          //  $("#msj").html(msj.responseJSON.caja);
            $("#msj-danger").fadeIn();
            $('#caja').focus();
            $('#msj-success').fadeOut();

        }
    });
  }
};

function empresa(){

    var tipo = $('#tipo').val();
    var empresa = $('#empresas').val();
   // var route = 'conf';
    var token = $('#token').val();

    var form = '#postCarnetsE';

    if ((empresa == "") || (tipo == null) ) {
    swal("Debe llenar ambos campos");
  }else{

		$.ajax({
			url: $(form).attr('action'),
			type: $(form).attr('method'),
			//data: $(formId).serialize(),
       // url:        route,
        headers:    {'X-CSRF-TOKEN':token},
        type:       'POST',
        dataType:   'json',
        data:       {tipo:tipo, empresas: empresa},

        success:function(){
        	swal("registro exitoso");
            $("#msj-success").fadeIn();
            $("#msj-danger").fadeOut();

        },
        error:function(msj) {
          //  $("#msj").html(msj.responseJSON.caja);
            $("#msj-danger").fadeIn();
            $('#caja').focus();
            $('#msj-success').fadeOut();

        }
    });
  }
};


function pagina(){

    var tipo = $('#tipo').val();
    var pagina = $('#paginaC').val();
   // var route = 'conf';
    var token = $('#token').val();

    var form = '#postCarnetsE';

    if ((pagina == "") || (tipo == null) ) {
    swal("Debe llenar ambos campos");
  }else{

		$.ajax({
			url: $(form).attr('action'),
			type: $(form).attr('method'),
			//data: $(formId).serialize(),
       // url:        route,
        headers:    {'X-CSRF-TOKEN':token},
        type:       'POST',
        dataType:   'json',
        data:       {tipo:tipo, paginaC: pagina},

        success:function(){
        	swal("registro exitoso");
            $("#msj-success").fadeIn();
            $("#msj-danger").fadeOut();

        },
        error:function(msj) {
          //  $("#msj").html(msj.responseJSON.caja);
            $("#msj-danger").fadeIn();
            $('#caja').focus();
            $('#msj-success').fadeOut();

        }
    });
  }
};

function agradecimiento(){

    var tipo = $('#tipo').val();
    var agradecimientos = $('#agradecimiento').val();
   // var route = 'conf';
    var token = $('#token').val();

    var form = '#postCarnetsE';

    if ((agradecimientos == "") || (tipo == null) ) {
    swal("Debe llenar ambos campos");
  }else{

		$.ajax({
			url: $(form).attr('action'),
			type: $(form).attr('method'),
			//data: $(formId).serialize(),
       // url:        route,
        headers:    {'X-CSRF-TOKEN':token},
        type:       'POST',
        dataType:   'json',
        data:       {tipo:tipo, agradecimiento: agradecimientos},

        success:function(){
        	swal("registro exitoso");
            $("#msj-success").fadeIn();
            $("#msj-danger").fadeOut();

        },
        error:function(msj) {
          //  $("#msj").html(msj.responseJSON.caja);
            $("#msj-danger").fadeIn();
            $('#caja').focus();
            $('#msj-success').fadeOut();

        }
    });
  }
};


function telefono(){

    var tipo = $('#tipo').val();
    var telef = $('#telephone').val();
    var descrip = $('#telfDescrip').val();
   // var route = 'conf';
    var token = $('#token').val();

    var form = '#postCarnetsE';

    if ((telef == "") || (tipo == null) || (descrip == null)) {
    swal("Debe llenar ambos campos");
    }else{

		$.ajax({
			url: $(form).attr('action'),
			type: $(form).attr('method'),
			//data: $(formId).serialize(),
       // url:        route,
        headers:    {'X-CSRF-TOKEN':token},
        type:       'POST',
        dataType:   'json',
        data:       {tipo:tipo, telephone: telef, telfDescrip: descrip},

        success:function(){
        	swal("registro exitoso");
            $("#msj-success").fadeIn();
            $("#msj-danger").fadeOut();

        },
        error:function(msj) {
          //  $("#msj").html(msj.responseJSON.caja);
            $("#msj-danger").fadeIn();
            $('#caja').focus();
            $('#msj-success').fadeOut();

        }
    });
  }
};


function codigo(){

    var tipo = $('#tipo').val();
    var codigo = $('#tipoCodigo').val();
   // var route = 'conf';
    var token = $('#token').val();

    var form = '#postCarnetsE';

    if ((codigo == null) || (tipo == null) ) {
    swal("Debe llenar ambos campos");
    }else{

		$.ajax({
			url: $(form).attr('action'),
			type: $(form).attr('method'),
			//data: $(formId).serialize(),
       // url:        route,
	        headers:    {'X-CSRF-TOKEN':token},
	        type:       'POST',
	        dataType:   'json',
	        data:       {tipo:tipo, tipoCodigo: codigo},

        success:function(){
        	swal("registro exitoso");
            $("#msj-success").fadeIn();
            $("#msj-danger").fadeOut();

        },
        error:function(msj) {
          //  $("#msj").html(msj.responseJSON.caja);
            $("#msj-danger").fadeIn();
            $('#caja').focus();
            $('#msj-success').fadeOut();

        }
    });
  }
};



function foto(){

	var token = $('#token').val();

  var form = '#postCarnetsE';

	var nombrePresi = $("#president").val();
	var fotoNombre = $("#fotoNombre").val();
	var tipo = $('#tipo').val();

  if ((nombrePresi == "") || (tipo == null) ) {
    swal("Debe llenar ambos campos");
  }else{

  var file_data = $('#fotoFirma').prop('files')[0];
  var archivos = new FormData();
  archivos.append('fotoFirma', file_data);
  archivos.append('president',nombrePresi);
  archivos.append('fotoNombre',fotoNombre);
  archivos.append('tipo',tipo);

	$.ajax({

    url: $(form).attr('action'),
    type: $(form).attr('method'),

    headers:    {'X-CSRF-TOKEN':token},
    type:       'POST',
    dataType:   'json',
    data: archivos,
    processData: false,
    contentType: false,
    cache       : false,


    success: function(data){
      swal("registro exitoso");
    $("#msj-success").fadeIn();
    $("#msj-danger").fadeOut();  
    console.log(data);           
    },
    error:function(msj) {
    $("#msj-danger").fadeIn();
    $('#caja').focus();
    $('#msj-success').fadeOut();

        }

    });
  }
}







$("#upload_form").on("submit", function(event){
    event.preventDefault();                     
    var form_url = $("form[id='upload_form']").attr("action");
    var CSRF_TOKEN = $('input[name="_token"]').val();  

    var token = $('#token').val();                  

    //Use the 'FormData' Class
    var uploadfile = new FormData($("#upload_media_form")[0]);
                                                   
    $.ajax({
        url:  form_url,
        type: 'POST',
        data: {
            _token: CSRF_TOKEN,
            file: uploadfile
        },
        contentType: false, 
        processData: false,
        dataType: 'JSON',
        success: function (result) {
            console.log(result);
            if(result === "error"){
                // Update HTML status_area with an error message
            }
            if(result === "ok"){
                // Update HTML status_area with an success message
            }
        }
    });                            
});