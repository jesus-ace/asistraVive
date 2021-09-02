
//Muestra el menú
function mostrar() {
    document.getElementById("sidebar").style.width = "220px";
    document.getElementById("contenido").style.marginLeft = "220px";
    document.getElementById("abrir").style.display = "none";
    document.getElementById("cerrar").style.display = "inline";
    document.getElementById("mimenu").style.marginTop = "45px";

}
// Oculta el menu
function ocultar() {
    document.getElementById("sidebar").style.width = "50px";
    document.getElementById("contenido").style.marginLeft = "50px";
    document.getElementById("abrir").style.display = "inline";
    document.getElementById("cerrar").style.display = "none";
    document.getElementById("mimenu").style.marginTop = "15px";
    document.getElementsByClassName("imgmenu5").style.marginLeft = "5px";
}



//Muestra las opciones de cambiar contraseña y sesión
function mostrarr() {
    document.getElementById("sidebara").style.width = "220px";
    document.getElementById("crr").style.display = "block";
    document.getElementById("ab").style.display = "none";
}
//Oculta las opciones de cambiar pass y cerrar session
function ocultarr() {
    document.getElementById("sidebara").style.width = "0px";
    document.getElementById("crr").style.display = "none";
    document.getElementById("ab").style.display = "block";
    document.getElementById("pasa1-c").style.display = "none";
    document.getElementById("logoutt").style.display = "block";
    document.getElementsByClassName("sidebara")[0].style.height = "110px";
}
//Muestra el formulario de cambiar contraseña
function cambiar_pass() {
    document.getElementsByClassName("sidebara")[0].style.height = "200px";
    document.getElementById("pasa1-c").style.display = "block";
    document.getElementById("logoutt").style.display = "none";
}


function chequea_pass() {
    if($('#pass1h').val() == '' ){
        swal('Por favor ingrese la nueva contraseña.');
        $('#pass1h').focus();
        return false;
    }
    else if($('#pass2h').val() == '' ){
        swal('Por favor vuelva a escribir la nueva contraseña.');
        $('#pass2h').focus();
        return false;
    }
}

$('#pass1h').change(function(evento){
    if ($('pass1h') != '') {
        if ($('#pass1h').val().length < 8 ) {
            swal('La contraseña debe tener mas de 8 caracteres. Por favor, intente de nuevo.');
            $('#pass1h').val('');
        }
    }
});

$('#pass2h').change(function(evento){
    if ($('#pass1h').val() == '') {
        swal('Debe ingresar primero una contraseña. Por favor, intente de nuevo.');
        $('#pass2h').val('');
    }
    else{
        if ( $('#pass1h').val() != $('#pass2h').val()) {
            swal('Las contraseñas no coinciden. Por favor, intente de nuevo.')
            $('#pass1h').val('');
            $('#pass2h').val('');
        }
    }
})


//Oculta el formulario de cambiar contraseña
function cancelar_cc() {
    document.getElementById("pasa1-c").style.display = "none";
    document.getElementById("logoutt").style.display = "block";
    document.getElementsByClassName("sidebara")[0].style.height = "110px";
}



function mostrar_editar_empleados() {

    $('.bpe_modificar').show();

}
$(document).ready(function() {
    $('#Alerta').slideUp(1500);
});

$('#session').click(function(e){
    $('#logout').toggle(500);
    $('#nombre_usuario').toggle(500);
});
$(document).ready(function(){
    
    var rol = $('#id_rol').val();
    $.get(`accesos/${rol}`,function(response){
        response.forEach(element=>{
            if (element.ro_nom == 'SUPER ADMINISTRADOR' || element.ro_nom == 'VICEPRESIDENTE' || element.ro_nom == 'ADMINISTRADOR') {
                $('#deusu').show(10);
                $('#depPer').show(10);
                $('#dpResp').show(10);
                $('#dpvac').show(10);
                $('#departamentoAsi').show(10);
            }
            else{
                if (element.ro_nom == 'SEGURIDAD' || element.ro_nom == 'OFICIAL DE SEGURIDAD') {
                    $('#inicioLateral').hide(10)
                }
                else{
                    $('#inicioLateral').show(10)
                }
                $('#deusu').hide(10);
                $('#depPer').hide(10);
                $('#dpResp').hide(10);
                $('#dpvac').hide(10);
                $('#departamentoAsi').hide(10);
            }
            if (element.ro_nom == 'SUPER ADMINISTRADOR' || element.ro_nom == 'VICEPRESIDENTE' || element.ro_nom == 'ADMINISTRADOR') {
                $('#deusu').show(10);
                $('#depPer').show(10);
                $('#dpResp').show(10);
                $('#dpvac').show(10);
                $('#departamentoAsi').show(10);
            }

            if (element.ro_nom == 'COORDINADOR' || element.ro_nom == 'EMPLEADO' || element.ro_nom == 'VICEPRESIDENTE' || element.ro_nom == 'SUPERVISOR' || element.ro_nom == 'RESPONSABLE' || element.ro_nom == 'ANALISTA ADMINISTRATIVO' || element.ro_nom == 'ADMINISTRADOR') {
                $('#notificacionesLateral').hide();
            }

            if (element.mod_nom == 'm_usuarios') {
                $('#usuariosLateral').show();
                if (element.pnt_nombre == 'p_empleados') {
                    $('#empleadosLateral').show();
                    if (element.aco_ac_id == 1) {
                        $('#bpe_agregar').show();
                    }
                    if (element.aco_ac_id == 2) {
                        $("div").removeClass("boton_oculto");
                    }
                }
                if (element.pnt_nombre == 'p_horarios') {
                    $('#horariosLateral').show();
                    if (element.aco_ac_id == 1) {
                        $('#bph_agregar').show();
                        $('#bph_asig_masa').show();
                    }
                    if (element.aco_ac_id == 2) {
                        $('.bph_modificar').show();
                    }
                    if (element.aco_ac_id == 3) {
                        $('.bph_eliminar').show();
                    }
                }
            }


           if (element.mod_nom == 'sm_carnet') {
                $('#usuariosLateral').show();
                $('#CarnetLateral').show();
                if (element.pnt_nombre == 'p_ccof_diseño') {
                    $('.conf_carnet').show();
                }
                if (element.pnt_nombre == 'p_cvencer') {
                    $('#panel1').show();
                    if (element.aco_ac_id == 4) {
                        $('.reporte_pv').show();
                    }
                    if (element.aco_ac_id == 2) {
                        $('.editEmpleadoPV').show();
                    }
                }
                if (element.pnt_nombre == 'p_crobados') {
                    $('#panel2').show();
                    if (element.aco_ac_id == 4) {
                        $('.reporte_cr').show();
                    }
                }
                if (element.pnt_nombre == 'p_churtados') {
                    $('#panel3').show();
                    if (element.aco_ac_id == 4 ) {
                        $('.reporte_chu').show();
                    }
                }
                if (element.pnt_nombre == 'p_cextraviados') {
                    $('#panel4').show();
                    if (element.aco_ac_id == 4 ) {
                        $('.reporte_ce').show();
                    }
                }
                if (element.pnt_nombre == 'p_cvencidos') {
                    $('#panel5').show();
                    $('.reporte_cve').show();
                    if (element.aco_ac_id == 2) {
                        $('.editEmpleado').show();
                    }
                }
            }//console.log(element.mod_nom+'-')


            if (element.mod_nom == 'sm_historicos') {
                $('.histo_carnet').show();
                if (element.pnt_nombre == 'p_chistoricoe') {
                    $('.emphisto').show();
                }
                if (element.pnt_nombre == 'p_chistoricop') {
                    $('#separadorpv3').show()
                    $('.prohisto').show()
                }
            }

            if (element.mod_nom == 'sm_cempleado') {
                $('.carnet_1').show();
                if (element.pnt_nombre == 'p_cnempleado') {
                    $('.new_carnet').show();
                    $('#panel6').show();
                    if (element.aco_ac_id == 2) {
                        $('.newCarEmpleado').show();
                        $('.editProvisional').show();
                    }
                }
                if (element.pnt_nombre == 'p_cnreporte') {
                    $('#separador_cn').show();
                    $('.rep_carnet').show();
                    if (element.aco_ac_id == 2) {
                        $('.editEmpleado').show();
                    }
                }
            }

            if (element.mod_nom == 'sm_cprovisional') {
                $('.carnetp1').show();
                if (element.pnt_nombre == 'p_cnprovisional') {
                    $('.newProv').show();
                    if (element.aco_ac_id == 2) {
                        $('.creaPasante').show();
                    }
                }
                if (element.pnt_nombre == 'p_cnpreportes') {
                    $('.cnprep').show();
                    $('#separadorpv1').show();
                    if (element.aco_ac_id == 2) {
                        $('.editPasante').show();
                    }
                }
                if (element.pnt_nombre == 'p_cnpseriales') {
                    $('.cnpseri').show();
                    $('#separadorpv2').show();
                    if (element.aco_ac_id == 1) {
                        $('.crearCod').show();
                    }
                }
            }

            if (element.mod_nom == 'm_asistencia') {
                $('#asistenciaLateral').show();
                if (element.pnt_nombre == 'p_control') {
                    $('#controlLateral').show();
                    if (element.aco_ac_id == 4) {
                        $('.imprimir_excel').show()
                    }
                }
                if (element.pnt_nombre == 'p_marcaje') {
                    $('#marcajeLateral').show();
                }
                if (element.pnt_nombre == 'p_notificaciones') {
                    $('#notificacionesLateral').show();
                }
            }

            if (element.mod_nom == 'm_config' ) {
                
                $('#configLateral').show();
                if (element.pnt_nombre == 'p_roles') {
                    $('#rolesLateral').show();
                    
                    if (element.aco_ac_id == 2) {
                        $('.editRol').show()
                    }
                    if (element.aco_ac_id == 3) {
                        $('.eliminarRol').show();
                    }
                }

                if (element.pnt_nombre =='p_diasf') {
                    $('#diasfLateral').show();
                    if (element.aco_ac_id == 1) {
                        $('#bpdf_agregar').show();
                    }
                    if (element.aco_ac_id == 2) {
                        $('.editdf').show()
                    }
                    if (element.aco_ac_id == 3) {
                        $('.eliminardf').show();
                    }
                }

                if (element.pnt_nombre == 'p_actualizacion') {
                    $('#acttablasLateral').show();

                    if (element.aco_ac_id == 1) {
                        $('.add_dataa').show();
                    }
                    if (element.aco_ac_id == 2) {
                        $('.activa_datos').show();
                    }
                    
                }

                if (element.pnt_nombre == 'p_newcliente') {
                    $('#nClienteLateral').show();
                    if (element.aco_ac_id == 1) {
                        $('.nclient').show();
                    }
                    if (element.aco_ac_id == 2) {
                        $('.eddcl').show();
                    }
                    
                }

                if (element.pnt_nombre =='p_audit') {
                    $('#auditoriaLateral').show();
                }

                if (element.aco_pnt_id ==14) {
                    $('#conexionLateral').show();
                }
            }

            if (element.mod_nom == 'm_excepciones') {
                if (element.pnt_nombre == 'p_reposos') {
                    $('#excepcionesLateral').show();
                    $('#repososLateral').show();
                    if (element.aco_ac_id == 1) {
                        $('#bpr_agregar').show();
                    }
                    if (element.aco_ac_id == 2) {
                        $('.editReposo').show()
                    }
                    if (element.aco_ac_id == 3) {
                        $('.deleteReposo').show();
                    }
                }
                if (element.pnt_nombre == 'p_permisos') {
                    $('#excepcionesLateral').show();
                    $('#permisosLateral').show();
                    if (element.aco_ac_id == 1) {
                        $('#bpp_agregar').show();
                    }
                    if (element.aco_ac_id == 2) {
                        $('.editPermiso').show()
                    }
                    if (element.aco_ac_id == 3) {
                        $('.deletePermiso').show();
                    }
                }
                if (element.pnt_nombre == 'p_vacaciones') {
                    $('#excepcionesLateral').show();
                    $('#vacacionesLateral').show();
                    if (element.aco_ac_id == 1) {
                        $('#bpv_agregar').show();
                    }
                    if (element.aco_ac_id == 2) {
                        $('.editVacaciones').show()
                    }
                    if (element.aco_ac_id == 3) {
                        $('.deleteVac').show();
                    }
                }
                if (element.pnt_nombre == 'p_autorizacion') {
                    $('#excepcionesLateral').show();
                    $('#autoLateral').show();
                    if (element.aco_ac_id == 1) {
                        $('#bpa_agregar').show();
                    }
                    if (element.aco_ac_id == 2) {
                        $('.editAutorizacion').show()
                    }
                    if (element.aco_ac_id == 3) {
                        $('.deleteAuto').show();
                    }
                }
            }
        });
    });
    var ventana = $('#ventana').val();
    if (ventana == 'empleados') {
        $('#usuarios').show();
        $('#empleados').show();
    }
    if (ventana == 'horarios') {
        $('#usuarios').show();
        $('#horarios').show();
    }
    if (ventana == 'control') {
        $('#asistencia').show();
        $('#control').show();
    }
    if (ventana == 'resumen') {
        
        $('#asistencia').show();       
        $('#resumen').show();
        
    }
    if (ventana == 'asistenciaextra') {
        
        $('#asistencia').show();
        $('#asistenciaextra').show();
        
    }
    if (ventana == 'asistenciag') {
        
        $('#asistencia').show();
        $('#asistenciag').show();
       
    }

    if (ventana == 'notificaciones') {
        $('#asistencia').show();
        $('#notificaciones').show();
       
    }
    if (ventana == 'permiso') {
        
        $('#asistencia').show();
        $('#excepciones').show();
        $('#permisos').show();
        
    }
    if (ventana == 'reposos') {
       
        $('#asistencia').show();
        $('#excepciones').show();
        $('#reposos').show();
        
    }
    if (ventana == 'vacaciones') {
        
        $('#asistencia').show();
        $('#excepciones').show();
        $('#vacaciones').show();
    }
    if (ventana == 'autorizacion') {
        
        $('#asistencia').show();
        $('#excepciones').show();
        $('#autorizacion').show();
        
    }
    if (ventana == 'roles') {
       
        $('#config').show();
        $('#roles').show();
        
    } 
    if (ventana == 'departamentos') {
       
        $('#config').show();
        $('#departamentos').show();
        
    } 
    if (ventana == 'diasferiados') {
        $('#config').show();
        $('#diasf').show();
        
    } 
    if (ventana == 'conexion') {
        $('#config').show();
        $('#conexion').show();
       
    } 
    if (ventana == 'acttablas') {
        $('#config').show();
        $('#act').show();
    } 
    if (ventana == 'auditoria') {
        
        $('#config').show();
        $('#auditoria').show();
        
    }  
    if (ventana == 'respaldo') {
        
        $('#config').show();
        $('#respaldo').show();
    } 
});
