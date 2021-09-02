function chequea_contenido_aud() {
	if($('#_token').val() == '' ){
        swal('Lo siento existe un error con el token.');
        $('#_token').focus();
        return false;
    }
    else if($('#diaAud').val() == '' && $('#desdeAud').val() == ''){
        swal('Por favor ingrese un rango de fecha');
        $('#diaAud').focus();
        return false;
    }
    else{
    	return true;
    }
}


$('.diaCh').click(function(e){
  	if ($(this).is(':checked')) {
	    $('.rangoCh').prop('checked',false);
	  	$('#diaAud').prop('disabled', false);
	  	$('#desdeAud').prop('disabled', true);
	  	$('#hastaAud').prop('disabled', true);
 	}
});

$('.rangoCh').click(function(e){
  	if ($(this).is(':checked')) {
	    $('.diaCh').prop('checked',false);
	  	$('#diaAud').prop('disabled', true);
	  	$('#desdeAud').prop('disabled', false);
	  	$('#hastaAud').prop('disabled', false);
  	}
});




//Para mostrar las ventanas que tiene el modulo usuarios
$('#aud_us').click(function(e){
 	$('#us_aud').slideDown(500);
  
	$('#ip_aud').val('');
	$('#acc_aud').val('');
	$('#name_machine_aud').val('');
	$('#explorer_aud').val('');

  	$('#ip_aud').slideUp(500);
  	$('#acc_aud').slideUp(500);
  	$('#name_machine_aud').slideUp(500);
  	$('#explorer_aud').slideUp(500);
});

$('#aud_ip').click(function(e){

	$('#us_aud').val('');
  	$('#acc_aud').val('');
  	$('#name_machine_aud').val('');
  	$('#explorer_aud').val('');


  	$('#us_aud').slideUp(500);
  	$('#ip_aud').slideDown(500);
  	$('#acc_aud').slideUp(500);
  	$('#name_machine_aud').slideUp(500);
  	$('#explorer_aud').slideUp(500);
});

$('#aud_acc').click(function(e){

	$('#us_aud').val('');
  	$('#ip_aud').val('');
  	$('#name_machine_aud').val('');
  	$('#explorer_aud').val('');

  	$('#us_aud').slideUp(500);
  	$('#ip_aud').slideUp(500);
  	$('#acc_aud').slideDown(500);
  	$('#name_machine_aud').slideUp(500);
  	$('#explorer_aud').slideUp(500);
});

$('#aud_name_machine').click(function(e){


  	$('#us_aud').val('');
  	$('#ip_aud').val('');
  	$('#acc_aud').val('');
  	$('#explorer_aud').val('');

  	$('#us_aud').slideUp(500);
  	$('#ip_aud').slideUp(500);
  	$('#acc_aud').slideUp(500);
  	$('#name_machine_aud').slideDown(500);
  	$('#explorer_aud').slideUp(500);
});

$('#aud_naveg').click(function(e){

 	$('#us_aud').val('');
  	$('#ip_aud').val('');
  	$('#acc_aud').val('');
  	$('#name_machine_aud').val('');


  	$('#us_aud').slideUp(500);
  	$('#ip_aud').slideUp(500);
  	$('#acc_aud').slideUp(500);
  	$('#name_machine_aud').slideUp(500);
  	$('#explorer_aud').slideDown(500);
});