$('#estado_ldap').click(function(e){
  //Muestra
  if ($(this).is(':checked')) {
    $('#estado_l').empty();
    $('#estado_l').append(`ACTIVO`);
  }
  else{
    $('#estado_l').empty();
    $('#estado_l').append(`INACTIVO`);
  }
});
$('#estado_sigesp').click(function(e){
  //Muestra
  if ($(this).is(':checked')) {
    $('#estado_s').empty();
    $('#estado_s').append(`ACTIVO`);
  }
  else{
    $('#estado_s').empty();
    $('#estado_s').append(`INACTIVO`);
  }
});

// Activar autorización;
$(".activar_auto").click(function(event){
  var id = $(this).attr("auto");
  swal ( {
      title: "¿Estás seguro?",
      text: "¿Deseas activar nuevamente esta autorización?",
      icon: "warning",
      buttons: true,
      dangerMode: true, 
  } )
  .then((willDelete) => {
      if (willDelete) {
        $.get(`autoActive/${id}`, function(response){
          if (response == 1) {
            setTimeout('document.location.reload()',1100);
            swal("Ha activado esta autorización satisfactoriamente", {
                icon: "success",

            });
          }
          else{
            swal("Existió un error al realizar la actualización, por favor intente nuevamente.", {
                icon: "info",

            });
          }
          
        });
      } 
  });
});


// Activar autorización;
$(".activar_diaf").click(function(event){
  var id = $(this).attr("diaf");
  swal ( {
      title: "¿Estás seguro?",
      text: "¿Deseas activar nuevamente este día feriado?",
      icon: "warning",
      buttons: true,
      dangerMode: true, 
  } )
  .then((willDelete) => {
      if (willDelete) {
        $.get(`diafActive/${id}`, function(response){
          if (response == 1) {
            setTimeout('document.location.reload()',1100);
            swal("Ha activado este día feriado satisfactoriamente", {
                icon: "success",

            });
          }
          else{
            swal("Existió un error al realizar la actualización, por favor intente nuevamente.", {
                icon: "info",

            });
          }
          
        });
      } 
  });
});

//Activar horarios;
$(".activar_hora").click(function(event){
  var id = $(this).attr("hora");
  swal ( {
      title: "¿Estás seguro?",
      text: "¿Deseas activar nuevamente este horario?",
      icon: "warning",
      buttons: true,
      dangerMode: true, 
  } )
  .then((willDelete) => {
      if (willDelete) {
        $.get(`horaActive/${id}`, function(response){
          if (response == 1) {
            setTimeout('document.location.reload()',1100);
            swal("Ha activado este horario satisfactoriamente", {
                icon: "success",

            });
          }
          else{
            swal("Existió un error al realizar la actualización, por favor intente nuevamente.", {
                icon: "info",

            });
          }
          
        });
      } 
  });
});

//Activar permisos;
$(".activar_per").click(function(event){
  var id = $(this).attr("per");
  swal ( {
      title: "¿Estás seguro?",
      text: "¿Deseas activar nuevamente este permiso?",
      icon: "warning",
      buttons: true,
      dangerMode: true, 
  } )
  .then((willDelete) => {
      if (willDelete) {
        $.get(`perActive/${id}`, function(response){
          if (response == 1) {
            setTimeout('document.location.reload()',1100);
            swal("Ha activado este permiso satisfactoriamente", {
                icon: "success",

            });
          }
          else{
            swal("Existió un error al realizar la actualización, por favor intente nuevamente.", {
                icon: "info",

            });
          }
          
        });
      } 
  });
});


//Activar reposo;
$(".activar_rep").click(function(event){
  var id = $(this).attr("rep");
  swal ( {
      title: "¿Estás seguro?",
      text: "¿Deseas activar nuevamente este reposo?",
      icon: "warning",
      buttons: true,
      dangerMode: true, 
  } )
  .then((willDelete) => {
      if (willDelete) {
        $.get(`repActive/${id}`, function(response){
          if (response == 1) {
            setTimeout('document.location.reload()',1100);
            swal("Ha activado este reposo satisfactoriamente", {
                icon: "success",

            });
          }
          else{
            swal("Existió un error al realizar la actualización, por favor intente nuevamente.", {
                icon: "info",

            });
          }
          
        });
      } 
  });
});

function editPrg(id) {
  $.get(`get_preg/${id}`,function(response){
    response.forEach(element =>{
      $('#prg_id').val(element.prg_id);
      $('#prg_prg').val(element.prg_pregunta);
    });
  });
}

function edit_tiau(id) {
  $.get(`get_tiau/${id}`,function(response){
    response.forEach(element =>{
      $('#tauEdit_id').val(element.tiau_id);
      $('#tauEdit').val(element.tiau_tipo);
    });
  });
}

function editTdf(id) {
  $.get(`get_tdf/${id}`,function(response){
    response.forEach(element =>{
      $('#tdiasf_id').val(element.tife_id);
      $('#tdiasf').val(element.tife_tipo);
    });
  });
}

function edit_rep(id) {
  $.get(`get_tire/${id}`,function(response){
    response.forEach(element =>{
      $('#tire_id').val(element.tire_id);
      $('#tire_tipo').val(element.tire_tipo);
    });
  });
}

function edit_tdu(id) {
  $.get(`get_tdu/${id}`,function(response){
    response.forEach(element =>{
      $('#tdu_id').val(element.tdu_id);
      $('#tdu_tipo').val(element.tdu_tipo);
    });
  });
}


