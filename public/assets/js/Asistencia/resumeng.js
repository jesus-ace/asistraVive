$(document).ready(function(){
	var rol = $('#id_rol').val();
    $.get(`accesos/${rol}`,function(response){
        response.forEach(element=>{
        	$('#asistenciaLateral').show();
            if (element.pnt_nombre == 'p_control') {
            	if (element.aco_ac_id == 4) {
            		$('#imprimir_g').show()
            	}

            	if (element.aco_ac_id == 2) {
            		$('#guardar_g').show()
            		$(".habilitados_asi").removeAttr("disabled");
				} 
				else {
					$('#guardar_g').hide()
					$(".habilitados_asi").attr("disabled", true);
				}
            }
        });
    });
});

function intento() {

    var selected = '';   
    var selectedd = ''; 
    $('#formId input[type=checkbox]').each(function(){
        if (this.checked) {
            selected += $(this).val()+', ';
        }
        else{
            selectedd += $(this).val()+', ';
        }
    }); 
    data = {
        "select1":selected,
        "select2":selectedd
    }
    varurl = 'guardar_status';
    $.ajax({
        cache: false,
        type: "GET",
        url : varurl,
        datatype:'json',
        data : data,
        success: function(data){
            setTimeout('document.location.reload()',2000);
            swal("¡Los estatus han sido actualizados.!", {
                icon: "success",
                button: false,
            });
        }
    });
    

    return false;

}

function guarda() {
    var selected = '';   
    var selectedd = ''; 
    $('#formu input[type=checkbox]').each(function(){
        if (this.checked) {
            selected += $(this).val()+', ';
        }
        else{
            selectedd += $(this).val()+', ';
        }
    }); 
    data = {
        "select1":selected,
        "select2":selectedd
    }
    varurl = 'guardar_status';
    $.ajax({
        cache: false,
        type: "GET",
        url : varurl,
        datatype:'json',
        data : data,
        success: function(data){
            setTimeout('document.location.reload()',2000);
            swal("¡Los estatus han sido actualizados.!", {
                icon: "success",
                button: false,
            });
        }
    });
    

    return false;

}