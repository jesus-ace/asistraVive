/*
  /////////////////////////////////////
 //        MODULO DE USUARIOS      ///
/////////////////////////////////////
*/



//Para mostrar las ventanas que tiene el modulo usuarios
$('#m_usuarios').click(function(e){
  //Muestra
  if ($(this).is(':checked')) {
    setTimeout(function() {
      $('.pm_usuarios').slideDown(500);
    },100);
  }
  else{
    //Les quita la seleccion
    $('.cm_usuarios').prop('checked',false);
    $('.psm_carnet').slideUp(500);

    $('.pm_usuarios').slideUp(500);
    $('.bp_empleados').slideUp(500);
     $('.psm_carnet').slideUp(500);
    $('.bp_horarios').slideUp(500);
    $('.p_new_carnet').slideUp(500);
    $('.p_pro_carnet').slideUp(500);
    $('.psm_histo').slideUp(500);
  }
});


//Para mostrar las ventanas que tiene el modulo usuarios
$('#sm_carnet').click(function(e){
  //Muestra
  if ($(this).is(':checked')) {
    setTimeout(function() {
      $('.psm_carnet').slideDown(500);
    },100);
  }
  else{
    //Les quita la seleccion
    $('.p_carnet').prop('checked',false);
    $('.psm_carnet').slideUp  (500);
    $('.p_new_carnet').slideUp  (500);
    $('.p_pro_carnet').slideUp  (500);
  }
});

//Para mostrar las ventanas que tiene el modulo usuarios
$('#b_carnet_nuevo').click(function(e){
  //Muestra
  if ($(this).is(':checked')) {
    setTimeout(function() {
      $('.p_new_carnet').slideDown(500);
    },100);
  }
  else{
    //Les quita la seleccion
    $('.pp_carnet').prop('checked',false);
    $('.p_new_carnet').slideUp  (500);
  }
});

//Para mostrar las ventanas que tiene el modulo usuarios
$('#b_carnet_prov').click(function(e){
  //Muestra
  if ($(this).is(':checked')) {
    setTimeout(function() {
      $('.p_pro_carnet').slideDown(500);
    },100);
  }
  else{
    //Les quita la seleccion
    $('.cp_pro').prop('checked',false);
    $('.p_pro_carnet').slideUp  (500);
  }
});
$('#b_hist_c').click(function(e){
  if ($(this).is(':checked')) {
    setTimeout(function() {
      $('.psm_histo').slideDown(500);
    },100);
  }
  else{
    //Les quita la seleccion
    $('.cp_histo').prop('checked',false);
    $('.psm_histo').slideUp  (500);
  }
});



//para mostrar los botones que tiene la ventana Empleados
$('#p_empleados').click(function(e){
  //Muestra
  if ($(this).is(':checked')) {
    setTimeout(function() {
      $('.bp_empleados').slideDown(500);
    },100);
    $('#imagen_candado').empty();
    $('#imagen_candado').append(`<img src="assets/img/iconos/candado_a.svg" style="height: 55px;">`);
  }
  else{
    //Les quita la seleccion
    $('.bp_empleados').slideUp (500);
    $('.cp_empleados').prop('checked',false);
    $('#imagen_candado').empty();
    $('#imagen_candado').append(`<img src="assets/img/iconos/candado_c.svg" style="height: 55px;">`);
  }
});

$('#p_horarios').click(function(e){
  //Muestra
  if ($(this).is(':checked')) {
    setTimeout(function() {
      $('.bp_horarios').slideDown(500);
    },100);
    $('#imagen_candado_h').empty();
    $('#imagen_candado_h').append(`<img src="assets/img/iconos/candado_a.svg" style="height: 55px;">`);
  }
  else{
    //Les quita la seleccion
    $('.bp_horarios').slideUp (500);
    $('.cp_horarios').prop('checked',false);
    $('#imagen_candado_h').empty();
    $('#imagen_candado_h').append(`<img src="assets/img/iconos/candado_c.svg" style="height: 55px;">`);
  }
});
/*
  /////////////////////////////////////
 //      FIN MODULO DE USUARIOS    ///
/////////////////////////////////////
*/


/*
  /////////////////////////////////////
 //       MODULO DE ASISTENCIA     ///
/////////////////////////////////////
*/
$('#m_asistencia').click(function(e){
  //Muestra
  if ($(this).is(':checked')) {
    setTimeout(function() {
      $('.pm_asistencias').slideDown(500);
    },100);
  }
  else{
    //Les quita la seleccion
    $('.pm_asistencias').slideUp (500);
    $('.psm_excepciones').slideUp (500);
    $('.bp_reposos').slideUp (500);
    $('.bp_permisos').slideUp (500);
    $('.bp_vacaciones').slideUp (500);
    $('.bp_autorización').slideUp (500);
    $('.cm_asistencia').prop('checked',false);


  }
});
$('#p_control').click(function(e){
  //Muestra
  if ($(this).is(':checked')) {
    setTimeout(function() {
      $('.bc_imprimir').slideDown(500);
    },100);
    $('#imagen_candado_con').empty();
    $('#imagen_candado_con').append(`<img src="assets/img/iconos/candado_a.svg" style="height: 55px;">`);
  }
  else{
    //Les quita la seleccion
    $('.bc_imprimir').slideUp (500);
    $('.cp_control').prop('checked',false);
    $('#imagen_candado_con').empty();
    $('#imagen_candado_con').append(`<img src="assets/img/iconos/candado_c.svg" style="height: 55px;">`);
  }
});
$('#p_marcaje').click(function(e){
  //Muestra
  if ($(this).is(':checked')) {
    
    $('#imagen_candado_mar').empty();
    $('#imagen_candado_mar').append(`<img src="assets/img/iconos/candado_a.svg" style="height: 55px;">`);
  }
  else{
    
    $('#imagen_candado_mar').empty();
    $('#imagen_candado_mar').append(`<img src="assets/img/iconos/candado_c.svg" style="height: 55px;">`);
  }
});
$('#p_asistenciae').click(function(e){
  //Muestra
  if ($(this).is(':checked')) {
    
    $('#imagen_candado_ae').empty();
    $('#imagen_candado_ae').append(`<img src="assets/img/iconos/candado_a.svg" style="height: 55px;">`);
  }
  else{
    
    $('#imagen_candado_ae').empty();
    $('#imagen_candado_ae').append(`<img src="assets/img/iconos/candado_c.svg" style="height: 55px;">`);
  }
});
/*
  /////////////////////////////////////
 //    SUBMODULO DE EXCEPCIONES    ///
/////////////////////////////////////
*/

$('#sm_excepciones').click(function(e){
  //Muestra
  if ($(this).is(':checked')) {
    setTimeout(function() {
      $('.psm_excepciones').slideDown(500);
    },100);
  }
  else{
    //Les quita la seleccion
    $('.psm_excepciones').slideUp (500);
    $('.bp_reposos').slideUp (500);
    $('.bp_permisos').slideUp (500);
    $('.bp_vacaciones').slideUp (500);
    $('.bp_autorización').slideUp (500);
    $('.csm_exc').prop('checked',false);
  }
});
//Pantalla de reposos
$('#p_reposos').click(function(e){
  //Muestra
  if ($(this).is(':checked')) {
    setTimeout(function() {
      $('.bp_reposos').slideDown(500);
    },100);

    $('#imagen_candado_rep').empty();
    $('#imagen_candado_rep').append(`<img src="assets/img/iconos/candado_a.svg" style="height: 55px;">`);
  }
  else{
    //Les quita la seleccion
    $('.bp_reposos').slideUp (500);
    $('.cp_reposos').prop('checked',false);

    $('#imagen_candado_rep').empty();
    $('#imagen_candado_rep').append(`<img src="assets/img/iconos/candado_c.svg" style="height: 55px;">`);
  }
});

//Pantalla de permisos 
$('#p_permisos').click(function(e){
  //Muestra
  if ($(this).is(':checked')) {
    setTimeout(function() {
      $('.bp_permisos').slideDown(500);
    },100);
    $('#imagen_candado_per').empty();
    $('#imagen_candado_per').append(`<img src="assets/img/iconos/candado_a.svg" style="height: 55px;">`);
  }
  else{
    //Les quita la seleccion
    $('.bp_permisos').slideUp (500);
    $('.cp_permisos').prop('checked',false);

    $('#imagen_candado_per').empty();
    $('#imagen_candado_per').append(`<img src="assets/img/iconos/candado_c.svg" style="height: 55px;">`);
  }
});

//Pantalla de vacaciones 
$('#p_vacaciones').click(function(e){
  //Muestra
  if ($(this).is(':checked')) {
    setTimeout(function() {
      $('.bp_vacaciones').slideDown(500);
    },100);
    $('#imagen_candado_vac').empty();
    $('#imagen_candado_vac').append(`<img src="assets/img/iconos/candado_a.svg" style="height: 55px;">`);
  }
  else{
    //Les quita la seleccion
    $('.bp_vacaciones').slideUp (500);
    $('.cp_vacaciones').prop('checked',false);

    $('#imagen_candado_vac').empty();
    $('#imagen_candado_vac').append(`<img src="assets/img/iconos/candado_c.svg" style="height: 55px;">`);
  }
});
//Pantalla de vacaciones
$('#p_autorizacion').click(function(e){
  //Muestra
  if ($(this).is(':checked')) {
    setTimeout(function() {
      $('.bp_autorizacion').slideDown(500);
    },100);
    $('#imagen_candado_au').empty();
    $('#imagen_candado_au').append(`<img src="assets/img/iconos/candado_a.svg" style="height: 55px;">`);
  }
  else{
    //Les quita la seleccion
    $('.bp_autorizacion').slideUp (500);
    $('.cp_autorizacion').prop('checked',false);
    $('#imagen_candado_au').empty();
    $('#imagen_candado_au').append(`<img src="assets/img/iconos/candado_c.svg" style="height: 55px;">`);
  }
});

/*
  /////////////////////////////////////
 //  FIN SUBMODULO DE EXCEPCIONES  ///
/////////////////////////////////////
*/


/*
  /////////////////////////////////////
 //     FIN MODULO DE ASISTENCIA   ///
/////////////////////////////////////
*/

/*
  /////////////////////////////////////
 //     MODULO DE CONFIGURACIÓN    ///
/////////////////////////////////////
*/
$('#m_config').click(function(e){
  //Muestra
  if ($(this).is(':checked')) {
    setTimeout(function() {
      $('.pm_config').slideDown(500);
    },100);
  }
  else{
    //Les quita la seleccion
    $('.pm_config').slideUp (500);

    $('.bp_roles').slideUp (500);
    $('.bp_diasf').slideUp (500);
    $('.cm_config').prop('checked',false);
  }
});

//Pantalla de roles
$('#p_roles').click(function(e){
  //Muestra
  if ($(this).is(':checked')) {
    setTimeout(function() {
      $('.bp_roles').slideDown(500);
    },100);
    $('#imagen_candador').empty();
    $('#imagen_candador').append(`<img src="assets/img/iconos/candado_a.svg" style="height: 55px;">`);
  }
  else{
    //Les quita la seleccion
    $('.bp_roles').slideUp (500);
    $('.cp_roles').prop('checked',false);
    $('#imagen_candador').empty();
    $('#imagen_candador').append(`<img src="assets/img/iconos/candado_c.svg" style="height: 55px;">`);
  }
});
//Pantalla de Días feriados
//Pantalla de roles
$('#p_diasf').click(function(e){
  //Muestra
  if ($(this).is(':checked')) {
    setTimeout(function() {
      $('.bp_diasf').slideDown(500);
    },100);
    $('#imagen_candadodf').empty();
    $('#imagen_candadodf').append(`<img src="assets/img/iconos/candado_a.svg" style="height: 55px;">`);
  }
  else{
    //Les quita la seleccion
    $('.bp_diasf').slideUp (500);
    $('.cp_diasf').prop('checked',false);
    $('#imagen_candadodf').empty();
    $('#imagen_candadodf').append(`<img src="assets/img/iconos/candado_c.svg" style="height: 55px;">`);
  }
});


/*
  ///////////////////
 //   EDITAR ROL  //
///////////////////
*/


function editRol(id) {
  $.get(`rolesedit/${id}`,function(response){
    response.forEach(element => {

      $('#body_roles_edit').empty();
      $('#body_roles_edit').append(`
        <li  class="list-group-item  text-left">
          <label>Nombre del Rol</label>
          <input type="text" name="rol_name" id="rolNamee" value="${element.ro_nom}" class="form-control roln1" placeholder="Ingrese el nombre del rol">
        </li>

        <li  class="list-group-item text-left">
          <label>Descripción del rol</label>
          <input type="text" name="rol_desc" id="rolDese" value="${element.ro_desc}" class="form-control rold1" placeholder="Ingrese la descripción del rol">
        </li>
        <li class="list-group-item list-group-item  text-left">
              Usuarios
              <div class="material-switch pull-right">
                  <input id="me_usuarios" name="me_usuarios" type="checkbox" class="me_usuarios" />
                  <label for="me_usuarios" class="label-success"></label>
              </div>
          </li>
          <!--Pantalla de empleados-->
          <li class="pme_usuarios list-group-item text-left" style="display:none;">
            <label class="v" id="imagen_candado">
              <img src="assets/img/iconos/candado_c.svg" style="height: 55px;">
            </label>
            <label style="margin-top: 10px;" class="v">
              Empleados
              <div class="material-switch pull-right m_derecha_c" style=" margin-right: 3px;">
                    <input 
                      id="pe_empleados" 
                      name="pe_empleados" 
                      type="checkbox" 
                      class="cm_usuarios"
                    />
                    <label for="pe_empleados" class="label-success"></label>
                </div>
            </label>
            <label class="v">
              Agregar
              <div class="material-switch pull-right m_derecha_c">
                    <input 
                      id="bee_agregar" 
                      name="bee_agregar" 
                      type="checkbox" 
                      class="cm_usuarios cp_empleados" 
                    />
                    <label for="bee_agregar" class="label-success"></label>
                </div>
            </label>
            <label class="v">
              Modificar
              <div class="material-switch pull-right m_derecha_c">
                    <input 
                      id="bpee_modificar" 
                      name="bpee_modificar" 
                      type="checkbox"
                      class="cm_usuarios cp_empleados bpee_modificar" 
                    />
                    <label for="bpee_modificar" class="label-success"></label>
                </div>
            </label>
          </li>`)
      if (element.aco_mod_id == 1) {
        //console.log('Rol '+element.ro_nom+' Modulo '+element.aco_mod_id+' Pantalla '+element.aco_pnt_id+' Accion '+element.aco_ac_id)
        console.log('si es '+element.aco_mod_id)
        
        if (element.aco_pnt_id == 1) {
          $('.me_usuarios').prop('checked',true);
          $('.pme_usuarios').slideDown(500);
        }
        //$('.me_usuarios').prop('checked',true)
      }
      else{
        var checkUs = '';
        console.log(element.aco_mod_id)
        //$('.me_usuarios').prop('checked',false)
      }
      if (element.aco_mod_id == 4) {
        //console.log('Rol '+element.ro_nom+' Modulo '+element.aco_mod_id+' Pantalla '+element.aco_pnt_id+' Accion '+element.aco_ac_id)
        //$('.me_usuarios').prop('checked',true)
       
      }
    })
  });
}






function eliminarRol(id){
  swal ({
    title: "¿Estas seguro?",
    text: "¿Desea eliminar este rol? Este rol puede pertenecer a varios usuarios",
    icon: "warning",
    buttons: true,
    dangerMode: true, 
  })
  .then((willDelete) => {
    if (willDelete) {
      $.get(`roldelete/${id}`, function(response, eliminarhorario){
        setTimeout('document.location.reload()',1000);
        swal("Poof! Este horario ha sido eliminado!", {
          icon: "success",

        });

      });
    } 
    else {
      swal("Este rol permanece activo ");
    }
  });
}