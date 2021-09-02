function habilita(form)
{ 
form.dia.disabled = false;
form.rango.disabled = false;
}

function deshabilita(form)
{ 
	if (form.dias.disabled = true) {

		form.dia.disabled = false;
		form.rango.disabled = true;
		form.desde.disabled = true;
		form.hasta.disabled = true;

	}

	if (form.rango.disabled = true) {

		form.dia.disabled = true;
		form.rango.disabled = false;
		form.desde.disabled = false;
		form.hasta.disabled = false;

	}
}
$("#recuperar").click(function(event){
	swal("Por favor comuniquese con el departamento de soporte",{
		icon: "warning"

	});
});
$(document).ready(function() {
        setTimeout(function() {
            $("#error").fadeOut(6000);
        },6000);
    });