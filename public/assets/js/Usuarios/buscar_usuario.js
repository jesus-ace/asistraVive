$(document).ready(function(){
    $('#us_search_name_ldap').hide(500);
    $('#us_search_ced_ldap').hide(500);
    $('#icon_delete').hide(500);
    $('#us_search_name_sigesp').hide(500);
    $('#us_search_ced_sigesp').hide(500);
    $("#ceduUS").change(function(event){
        $('#tipodeusuario').show(400);
        $('#rolesAdd').show(400);
        $('#horariosAdd').show(400);
        $.get(`getRolUs`, function(response , roles){
            console.log(response);
            $("#rolEdit").empty();
            response.forEach(element=> {
                $("#rolEdit").append(`<option value="${element.ro_id}">${element.ro_nom}</option>`)
            });

        });
        $.get(`getHoraUs`, function(response , horarios){
            console.log(response);
            $("#horarioEdit").empty();
            response.forEach(element=> {
                $("#horarioEdit").append(`<option value="${element.tiho_id}">${element.tiho_turno} ${element.tiho_dias} ${element.tiho_hora_en}-${element.tiho_hora_sa}</option>`)
            });
        });
        $.get()
        var cedula = $('#ceduUS').val();
        $.get(`getced/${cedula}`, function(response, Usuarios){
            if (response == 'Este usuario ya existe') {
                $('#ceduUS').val('');
                swal("Este usuario ya existe, por favor, ingrese otro numero de cédula", {
                  icon: "warning",

                    });

            }
            if (response != '') {
                response.forEach(element =>{
                if (element.correo) {
                    var correo = element.correo;
                    var login = element.login;
                    $('#DeptoAdd').show();
                    $('#DeptoAddS').hide();
                }
                else{
                    correo = '';
                    login = '';
                }
                $('#addUser').empty();
                $('#addUser').append(`
                <div class="col-lg-6 text-left">
                    <div class="form-group">                                            
                        <label for="inputFoto">Foto</label> 
                        <input
                            class="form-control"
                            name="foto"
                            id="fotoUs" 
                            type="text"
                            value="${cedula}.jpg"
                            required
                            >
                    </div>
                </div>
                <div class="col-lg-6 text-left">
                    <div class="form-group">
                        <label for="inputNombre">Nombre</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            name="nombre" 
                            id="nombreUs"
                            value="${element.nombres}"
                            placeholder="Escribe tu nombre" 
                            min="3" 
                            title="Por favor llene este campo correctamente para poder continuar"/>
                    </div>
                </div>
                <div class="col-lg-6 text-left">
                    <div class="form-group">
                        <label for="inputApellido">Apellido</label>
                        <input 
                            type="text" 
                            class="form-control"
                            name="apellido" 
                            value="${element.apellidos}"
                            id="apellidoUs"
                            placeholder="Escribe tu nombre"
                            title="Por favor llene este campo correctamente para poder continuar"  />
                    </div>
                </div>
                 <div class="col-lg-6 text-left" id="DeptoAddS">
                    <div class="form-group">
                        <label for="inputDepartamento">Departamento</label>
                        <select class="form-control" id="departamentosAdd" name="departamento" required>
                            <option selected value="${element.cod_uni}">${element.des_uni}</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-6 text-left">
                    <div class="form-group">
                        <label for="inputApellido">Correo Electronico</label>
                        <input 
                            type="email" 
                            class="form-control"
                            name="correo" 
                            id="correoUs"
                            value="${correo}"
                            pattern="[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*@[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{1,5}" 
                            placeholder="Escribe tu correo electronico"
                            title="Por favor llene este campo correctamente para poder continuar. Ejemplo: tucorreo@vive.com" required/>
                    </div>
                </div>
                    <div class="col-lg-6 text-left">
                        <div class="form-group">                                            
                            <label for="inputLogin">Login</label> 
                                <input 
                                    class="form-control loginUs" 
                                    id="loginUs" 
                                    type="text" 
                                    name="login" 
                                    onchange="busca_login()"
                                    placeholder="Ingrese el login" 
                                    required
                                    value="${login}"
                                    />
                        </div>
                    </div>
                    <div class="col-lg-6 text-left">
                        <div class="form-group">                                            
                            <label for="inputContraseña">Contraseña</label> 
                            <input
                                class="form-control passUs"
                                name="pass"
                                id="passUs"  
                                type="password"
                                value="${element.cedula}"
                                placeholder="Ingrese la contraseña" 
                                required
                                >
                        </div>
                    </div>
                    <div class="col-lg-6 text-left">
                        <div class="form-group">                                            
                            <label for="inputContraseña">Repetir contraseña</label> 
                            <input
                                class="form-control repitepass"
                                name="Rpass"
                                id="RpassUs" 
                                type="password"
                                value="${element.cedula}"
                                placeholder="Ingrese la contraseña" 
                                required
                                >
                        </div>
                    </div>`)

                    if (element.correo) {
                        $('#DeptoAddS').hide();
                        $('#DeptoAddd').show();
                    }
                    else{
                        $('#DeptoAddS').show();
                        $('#DeptoAddd').hide();
                    }
                });
            }
            else{
                $('#addUser').empty();
                $('#addUser').append(`
                <div class="col-lg-6 text-left">
                    <div class="form-group">                                            
                        <label for="inputFoto">Foto</label> 
                        <input
                            class="form-control"
                            name="foto"
                            id="fotoUs" 
                            type="text"
                            value="${cedula}.jpg"
                            required
                            >
                    </div>
                </div>
                <div class="col-lg-6 text-left">
                    <div class="form-group">
                        <label for="inputNombre">Nombre</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            name="nombre" 
                            id="nombreUs"
                            placeholder="Escribe tu nombre" 
                            min="3" 
                            title="Por favor llene este campo correctamente para poder continuar"/>
                    </div>
                </div>
                <div class="col-lg-6 text-left">
                    <div class="form-group">
                        <label for="inputApellido">Apellido</label>
                        <input 
                            type="text" 
                            class="form-control"
                            name="apellido" 
                            id="apellidoUs"
                            placeholder="Escribe tu nombre"
                            title="Por favor llene este campo correctamente para poder continuar"  />
                    </div>
                </div>
                <div class="col-lg-6 text-left">
                    <div class="form-group">
                        <label for="inputApellido">Correo Electronico</label>
                        <input 
                            type="email" 
                            class="form-control"
                            name="correo" 
                            id="correoUs"
                            pattern="[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*@[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{1,5}" 
                            placeholder="Escribe tu correo electronico"
                            title="Por favor llene este campo correctamente para poder continuar. Ejemplo: tucorreo@vive.com" required/>
                    </div>
                </div>
                    <div class="col-lg-6 text-left">
                        <div class="form-group">                                            
                            <label for="inputLogin">Login</label> 
                                <input 
                                    class="form-control loginUs" 
                                    id="loginUs" 
                                    type="text" 
                                    name="login"
                                    onchange="busca_login()" 
                                    placeholder="Ingrese el login" 
                                    required
                                    />
                        </div>
                    </div>
                    <div class="col-lg-6 text-left">
                        <div class="form-group">                                            
                            <label for="inputContraseña">Contraseña</label> 
                            <input
                                class="form-control passUs"
                                name="pass"
                                id="passUs" 
                                type="password"
                                placeholder="Ingrese la contraseña" 
                                required
                                >
                        </div>
                    </div>
                    <div class="col-lg-6 text-left">
                        <div class="form-group">                                            
                            <label for="inputContraseña">Repetir contraseña</label> 
                            <input
                                class="form-control repitepass"
                                name="Rpass"
                                id="RpassUs" 
                                type="password"
                                placeholder="Ingrese la contraseña" 
                                required
                                >
                        </div>
                    </div>`)
            }
            
        });
    });
    
    document.getElementById("listaEmpleados").addEventListener("DOMNodeInserted", handler, true);
 
    function handler(){
        var rol = $('#id_rol').val();
        $.get(`accesos/${rol}`,function(response){
            roles = response;
            roles.forEach(element=>{
                if (element.mod_nom == 'm_usuarios') {
                    if (element.pnt_nombre == 'p_empleados') {
                        if (element.aco_ac_id == 1) {
                            $('#bpe_agregar').show();
                        }
                        if (element.aco_ac_id == 2) {
                            var cedld = $('#us_search_ced_ldap').val();
                            var cedsg = $('#us_search_ced_sigesp').val();
                            var cedu = $('#ceduBuscar').val();
                            if (cedld != '' || cedsg != '' || cedu != '') {
                                if (cedld != '') {
                                    ced = cedld;
                                }
                                else if(cedsg != ''){
                                    var ced = cedsg;
                                }
                                else{
                                    ced = cedu;
                                }
                                $.get(`existUs/${ced}`,function(response){
                                    var us_search_ldap = response;
                                    console.log(response);
                                    if(response == 0){
                                        $("#addUserss").show();
                                        $(".editEmpleado").hide();
                                    }
                                    if(response != 0){

                                        $("#addUserss").hide();
                                        $(".editEmpleado").show();
                                    }
                                });
                            }
                            else{
                                $(".editEmpleado").show();
                                $("#addUserss").hide();
                            }
                        }
                    }
                }
            });
        });

    }
    /*document.getElementById("listaEmpleados").addEventListener("DOMNodeInserted", ldap_s, true);

    function ldap_s() {
        var cedld = $('#us_search_ced_ldap').val();
        var cedsg = $('#us_search_ced_sigesp').val();
        if (cedld == '') {
            var ced = cedsg;
        }
        else{
            ced = cedld;
        }
        $.get(`existUs/${ced}`,function(response){
            var us_search_ldap = response;
            console.log(response);
            if(us_search_ldap == 0){
                $("#addUsers").show();
                $(".editEmpleado").hide();
            }
            else{

                $("#addUsers").hide();
                $("#editEmpleado").show();
            }
        });
    }*/

    document.getElementById("addUser").addEventListener("DOMNodeInserted", validapas, true);
 
    function validapas(){
        
        $('.repitepass').change(function(e) {
            var pas1 = $('.passUs').val();
            var pas2 = $('.repitepass').val();
            if (pas1 == pas2) {
                return true;
            }
            else{
                swal('Las contraseñas no coinciden, por favor intente de nuevo.');
                $('.passUs').val('');
                $('.repitepass').val('');
                $('.passUs').focus();
            }
        });
    }
    $('.repitepass').change(function(e) {
        var pas1 = $('.passUs').val();
        var pas2 = $('.repitepass').val();
        if (pas1 == pas2) {
            return true;
        }
        else{
            swal('Las contraseñas no coinciden, por favor intente de nuevo.');
            $('.passUs').val('');
            $('.repitepass').val('');
            $('.passUs').focus();
        }
    });




    var token = $('#_token').val();
    $("#departamentoUs").change(function(event){
        var departamento = $("#departamentoUs").val();
        console.log(departamento);
    	$.get(`usuariosdp/${departamento}`, function(response, departamentosUs){
    		console.log(response);
    		$("#listaEmpleados").empty();
            $("#ceduBuscar").val('');
            $('#nomBuscar').val('');
    		$("#center").empty();
    		$("#cedu").empty();

            
            if (response == '') { $('#listaEmpleados').append(`<td><h4>Disculpe, no se encontraron resultados</h4></td>`)}
    		response.forEach(element=> {
                $("#listaEmpleados").append(`
                <tr value="${element.us_ced}">
                    <td class="text-center">
                        <img src="imagenes2/${element.us_foto}" style="height: 50px;">
                    </td>
                    <td class="text-center">
                        ${element.us_nom}
                    </td>
                    <td class="text-center">
                        ${element.us_ape}<br>
                    </td>
                    <td class="text-center">
                        ${element.us_ced}<br>
                    </td>
                    <td class="text-center">
                        ${element.dp_nombre} 
                    </td>
                    </td>
                    <td class="text-right">
                        <a class="editEmpleado"  onclick="editUsuario(${element.us_ced})" href="#" data-toggle="modal"  data-target="#editarUS" style="display:none;"> 
                            <img src="assets/img/iconos/editar.svg" class="imgmenuho" >
                        </a>
                        <div class="modal fade" id="editarUS" tabindex="-1" role="dialog" aria-labelledby="EditarUsuario" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content text-center">
                                    <div class="panel panel-default">
                                        <div class="panel-heading" style="background-color: #e5e8e8;">
                                            <h3 class="panel-title">
                                                <b>EDITAR USUARIO</b>
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            </h3>
                                        </div>
                                        <div class="panel-body">
                                            <form method="POST" action="updateE">
                                                <input type="hidden" name="_token" id="_token" value="${token}">
                                                <div class="col-lg-6 text-left">
                                                    <div class="form-group">
                                                        <label for="inputTipous">Tipo de tipousuario</label>
                                                        <select class="form-control" id="tipodeusuarioedit" name="tipousuario" >
                                                            
                                                        </select>
                                                    </div>
                                                </div>
                                                <div id="empleadosEdit">

                                                </div>
                                                <div class="col-lg-12 text-left">
                                                    <div class="form-group">
                                                        <label for="inputHorario">Horario</label>
                                                        <select class="form-control" id="horarioEdit" name="horarioEdit" required>
                                                            
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12" style="border-top: 1px solid #ccc; font-size: 14px;">
                                                    <div class="col-lg-6 text-left" style="margin-top: 10px;">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                                    </div>
                                                    <div class="col-lg-6 text-right" style="margin-top: 10px;">
                                                        <button type="submit" name="registrar" value="Modificar" class="btn"  style="background-color: #48c9b0; color:white;" >
                                                            <b>MODIFICAR</b>
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>` )      
            });
    	});
    });
    $("#ceduBuscar").change(function(event){ 
    var cedula = $('#ceduBuscar').val();
    var token = $('#_token').val();
    $.get(`getcedu/${cedula}`, function(response, cedula){
    	$("#listaEmpleados").empty();
        $("#departamentoUs").val('');
        $('#nomBuscar').val('');
    	$("#center").empty();
        if (response == '') { $('#listaEmpleados').append(`<td><h4>Disculpe, no se encontraron resultados</h4></td>`)}
        console.log(response);
    		response.forEach(element=> {
                if (element.us_foto != '') {
                    var foto = element.us_foto;
                }
                else{
                    var foto = "mafalda.jpg";
                }
    			$("#listaEmpleados").append(`<tr value="${element.us_ced}">
                    <td class="text-center">
                        <img src="imagenes2/${element.us_foto}" style="height: 50px;">
                    </td>
                    <td class="text-center">
                        ${element.us_nom}
                    </td>
                    <td class="text-center">
                        ${element.us_ape}<br>
                    </td>
                    <td class="text-center">
                        ${element.us_ced}<br>
                    </td>
                    <td class="text-center">
                        ${element.dp_nombre} 
                    </td>
                    </td>
                    <td class="text-right">
                        <a class="editEmpleado"  onclick="editUsuario(${element.us_ced})" href="#" data-toggle="modal"  data-target="#editarUS" style="display:none;"> 
                            <img src="assets/img/iconos/editar.svg" class="imgmenuho" >
                        </a>
                        <div class="modal fade" id="editarUS" tabindex="-1" role="dialog" aria-labelledby="EditarUsuario" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content text-center">
                                    <div class="panel panel-default">
                                        <div class="panel-heading" style="background-color: #e5e8e8;">
                                            <h3 class="panel-title">
                                                <b>EDITAR USUARIO</b>
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            </h3>
                                        </div>
                                        <div class="panel-body">
                                            <form method="POST" action="updateE">
                                                <input type="hidden" name="_token" id="_token" value="${token}">
                                                <div class="col-lg-6 text-left">
                                                    <div class="form-group">
                                                        <label for="inputTipous">Tipo de tipousuario</label>
                                                        <select class="form-control" id="tipodeusuarioedit" name="tipousuario" >
                                                            
                                                        </select>
                                                    </div>
                                                </div>
                                                <div id="empleadosEdit">

                                                </div>
                                                <div class="col-lg-12 text-left">
                                                    <div class="form-group">
                                                        <label for="inputHorario">Horario</label>
                                                        <select class="form-control" id="horarioEdit" name="horarioEdit" required>
                                                            
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12" style="border-top: 1px solid #ccc; font-size: 14px;">
                                                    <div class="col-lg-6 text-left" style="margin-top: 10px;">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                                    </div>
                                                    <div class="col-lg-6 text-right" style="margin-top: 10px;">
                                                        <button type="submit" name="registrar" value="Modificar" class="btn"  style="background-color: #48c9b0; color:white;" >
                                                            <b>MODIFICAR</b>
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>` )		

    		});
    	});
    });
    $('#nomBuscar').change(function(event){
        var nombre = $('#nomBuscar').val();
        $.get(`getName/${nombre}`, function(response){
            console.log(response)
            $("#listaEmpleados").empty();
            $("#departamentoUs").val('');
            $("#ceduBuscar").val('');
            $("#center").empty();
            if (response == '') { $('#listaEmpleados').append(`<td><h4>Disculpe, no se encontraron resultados</h4></td>`)}
        console.log(response);
            response.forEach(element=> {
                if (element.us_foto != '') {
                    var foto = element.us_foto;
                }
                else{
                    var foto = "mafalda.jpg";
                }
                $("#listaEmpleados").append(`<tr value="${element.us_ced}">
                    <td class="text-center">
                        <img src="imagenes2/${foto}" style="height: 50px;">
                    </td>
                    <td class="text-center">
                        ${element.us_nom}
                    </td>
                    <td class="text-center">
                        ${element.us_ape}<br>
                    </td>
                    <td class="text-center">
                        ${element.us_ced}<br>
                    </td>
                    <td class="text-center">
                        ${element.dp_nombre} 
                    </td>
                    </td>
                    <td class="text-right">
                        <a class="editEmpleado"  onclick="editUsuario(${element.us_ced})" href="#" data-toggle="modal"  data-target="#editarUS" style="display:none;"> 
                            <img src="assets/img/iconos/editar.svg" class="imgmenuho" >
                        </a>
                        <div class="modal fade" id="editarUS" tabindex="-1" role="dialog" aria-labelledby="EditarUsuario" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content text-center">
                                    <div class="panel panel-default">
                                        <div class="panel-heading" style="background-color: #e5e8e8;">
                                            <h3 class="panel-title">
                                                <b>EDITAR USUARIO</b>
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            </h3>
                                        </div>
                                        <div class="panel-body">
                                            <form method="POST" action="updateE">
                                                <input type="hidden" name="_token" id="_token" value="${token}">
                                                <div class="col-lg-6 text-left">
                                                    <div class="form-group">
                                                        <label for="inputTipous">Tipo de tipousuario</label>
                                                        <select class="form-control" id="tipodeusuarioedit" name="tipousuario" >
                                                            
                                                        </select>
                                                    </div>
                                                </div>
                                                <div id="empleadosEdit">

                                                </div>
                                                <div class="col-lg-12 text-left">
                                                    <div class="form-group">
                                                        <label for="inputHorario">Horario</label>
                                                        <select class="form-control" id="horarioEdit" name="horarioEdit" required>
                                                            
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12" style="border-top: 1px solid #ccc; font-size: 14px;">
                                                    <div class="col-lg-6 text-left" style="margin-top: 10px;">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                                    </div>
                                                    <div class="col-lg-6 text-right" style="margin-top: 10px;">
                                                        <button type="submit" name="registrar" value="Modificar" class="btn"  style="background-color: #48c9b0; color:white;" >
                                                            <b>MODIFICAR</b>
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>` )        

            });
        });
    });    
});

function busca_login() {
    var login = $('.loginUs').val();
    $.get(`get_login/${login}`,function(response) {
        if (response == 1) {
            swal("Este nombre de usuario ya le pertenece a otra persona, por favor intente con uno nuevo.");
            $('.loginUs').val('')
        }
    })
}
function editUsuario(cedula){ 
        $.get(`getRolUs`, function(response , departamentos){
            console.log(response);
            $("#rolEdit").empty();
            response.forEach(element=> {
                $("#rolEdit").append(`<option value="${element.ro_id}">${element.ro_nom}</option>`)
            });

        });
        $.get(`getTuUs`, function(response , departamentos){
            console.log(response);
            $("#tipodeusuarioedit").empty();
            response.forEach(element=> {
                $("#tipodeusuarioedit").append(`<option value="${element.tdu_id}">${element.tdu_tipo}</option>`)
            });
        });
        $.get(`getHoraUs`, function(response , departamentos){
            console.log(response);
            $("#horarioEdit").empty();
            response.forEach(element=> {
                $("#horarioEdit").append(`<option value="${element.tiho_id}">${element.tiho_turno} ${element.tiho_dias} ${element.tiho_hora_en}-${element.tiho_hora_sa}</option>`)
            });
        });
        $.get(`getEmp/${cedula}`, function(response, id){
            console.log(response);
                $("#empleadosEdit").empty();
                response.forEach(element=> {
                $("#tipodeusuarioedit").append(`<option selected value="${element.tdu_id}">${element.tdu_tipo}</option>`)
                $("#rolEdit").append(`<option selected value="${element.ro_id}">${element.ro_nom}</option>`)
                $("#horarioEdit").append(`<option selected value="${element.tiho_id}">${element.tiho_turno} ${element.tiho_dias} ${element.tiho_hora_en}-${element.tiho_hora_sa}</option>`)
                $("#empleadosEdit").append(`
                <input type="hidden" name="id" value="${element.us_ced}">
                <div class="col-lg-6 text-left">
                    <div class="form-group">
                        <label for="inputNombre">Nombre</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            name="nombre" 
                            id="nombreUs"
                            disabled
                            value="${element.us_nom}"
                            min="3" 
                            title="Por favor llene este campo correctamente para poder continuar"/>
                    </div>
                </div>
                <div class="col-lg-6 text-left">
                    <div class="form-group">
                        <label for="inputApellido">Apellido</label>
                        <input 
                            type="text" 
                            class="form-control"
                            name="apellido" 
                            value="${element.us_ape}"
                            id="apellidoUs" 
                            disabled />
                    </div>
                </div>
                <div class="col-lg-6 text-left">
                    <div class="form-group">
                        <label for="inputApellido">Cédula</label>
                        <input 
                            type="number" 
                            class="form-control"
                            name="apellido" 
                            value="${element.us_ced}"
                            id="apellidoUs" 
                            disabled />
                    </div>
                </div>` )        
            });
        
    });
500
}

function addUsers() {
    $.get(`getPrgus`, function(response , preguntas){
        console.log(response);
        $("#prgld").empty();
        $("#prgld").append(`<option disabled selected value="0">Elige una pregunta de seguridad</option>`)
        response.forEach(element=> {
            $("#prgld").append(`<option value="${element.prg_id}">${element.prg_pregunta}</option>`)
        });
    });
    $.get(`getTuUs`, function(response , tipous){
        console.log(response);
        $("#tduldap").empty();
        response.forEach(element=> {
            $("#tduldap").append(`<option value="${element.tdu_id}">${element.tdu_tipo}</option>`)
        });
    });
    $.get(`getDpUs`, function(response , departamentos){
        console.log(response);
        $("#deptold").empty();
         $("#deptold").append(`<option selected disabled value="0">Elige un departamento</option>`)
        response.forEach(element=> {
            $("#deptold").append(`<option value="${element.dp_id}">${element.dp_nombre}</option>`)
        });
    });
    $.get(`getRolUs`, function(response , roles){
        console.log(response);
        $("#rolesld").empty();
         $("#rolesld").append(`<option disabled selected value="0">Elige un rol</option>`)
        response.forEach(element=> {
            $("#rolesld").append(`<option value="${element.ro_id}">${element.ro_nom}</option>`)
        });
    });
    $.get(`getHoraUs`, function(response , horarios){
        console.log(response);
        $("#horald").empty();
        $("#horald").append(`<option disabled selected value="0">Elige un horario</option>`)
        response.forEach(element=> {
            $("#horald").append(`<option value="${element.tiho_id}">${element.tiho_turno} ${element.tiho_dias} ${element.tiho_hora_en}-${element.tiho_hora_sa}</option>`)
        });
    });
}

$(document).ready(function(){
    $.get(`statusLdap`, function(response){

        var token = $('#_token').val();
        var ldap = response;
        if (ldap != 1) {
            $('#search_ldap').hide();
        }
        $.get(`statusSigesp`, function(response){
            var sigesp = response
            if (sigesp != 1) {
                $('#search_sigesp').hide();
            }
            if (ldap == 1 || sigesp == 1) {
               $('#search_ldap_sigesp').show(500);
            }
            $('#search_ldap').click(function(event){
                $('#us_search_name_ldap').hide(500);
                $('#us_search_ced_ldap').hide(500);

                $('#us_search_ced_sigesp').hide(500);

                $('#us_search_ced_ldap').show(500);
                $('#icon_delete').show(500);
            });
            $('#search_sigesp').click(function(event){

                $('#us_search_name_ldap').hide(500);
                $('#us_search_ced_ldap').hide(500);


                $('#us_search_ced_sigesp').hide(500);
                

                $('#us_search_ced_sigesp').show(500);
                $('#icon_delete').show(500);
                $('#center').empty();
            });
            $('#icon_delete').click(function(event){

                $('#us_search_name_ldap').hide(500);
                $('#us_search_ced_ldap').hide(500);
                $('#us_search_ced_sigesp').hide(500);
                $('#icon_delete').hide(500);

                $('#us_search_name_ldap').val('');
                $('#us_search_ced_ldap').val('');
                $('#us_search_ced_sigesp').val('');
            });
            $('#us_search_name_ldap').change(function(event){
                var name = $('#us_search_name_ldap').val();
                if (ldap ==1) {
                    $.get(`getNameLdap/${name}`,function(response){
                        console.log(response);
                    });
                }
                else{
                    swal("Disculpe, la busqueda no se encuentra disponible en este momento.",{
                        icon: "warning"
                    })
                }
            });
            $('#us_search_ced_ldap').change(function(event){
                
                var ced = $('#us_search_ced_ldap').val();
                if (ldap ==1) {
                    $.get(`getCedLdap/${ced}`,function(response){
                        console.log(response);
                        if (response == '') {
                            swal("No se han encontrado resultados.",{
                                icon: "info"
                            });
                            $('#us_search_ced_ldap').val('');
                         }
                         else{

                            $("#center").empty();
                            $("#listaEmpleados").empty();
                            response.forEach(element=> {
                                $("#listaEmpleados").append(`
                                    <tr>
                                        <td class="text-center">
                                            <img src="imagenes2/${element.cedula}.jpg" style="height: 90px;">
                                        </td>
                                        <td class="text-center">
                                            ${element.nombres}
                                        </td>
                                        <td class="text-center">
                                            ${element.apellidos}<br>
                                        </td>
                                        <td class="text-center">
                                            ${element.cedula}<br>
                                        </td>
                                        <td class="text-center">
                                            ${element.departamento} 
                                        </td>
                                        <td class="text-right">
                                            <a class="editEmpleado" id="editEmpleadoo"  onclick="editUsuario(${element.cedula})" href="#" data-toggle="modal"  data-target="#editarUS" style="display:none;"> 
                                                <img src="assets/img/iconos/editar.svg" class="imgmenuho" >
                                            </a>
                                            <div class="modal fade" id="editarUS" tabindex="-1" role="dialog" aria-labelledby="EditarUsuario" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content text-center">
                                                        <div class="panel panel-default">
                                                            <div class="panel-heading" style="background-color: #e5e8e8;">
                                                                <h3 class="panel-title">
                                                                    <b>EDITAR USUARIO</b>
                                                                </h3>
                                                            </div>
                                                            <div class="panel-body">
                                                                <form method="POST" action="updateE">
                                                                    <input type="hidden" name="_token" id="_token" value="${token}">
                                                                    <div class="col-lg-6 text-left">
                                                                        <div class="form-group">
                                                                            <label for="inputTipous">Tipo de tipousuario</label>
                                                                            <select class="form-control" id="tipodeusuarioedit" name="tipousuario" >
                                                                                
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div id="empleadosEdit">

                                                                    </div>
                                                                    <div class="col-lg-12 text-left">
                                                                        <div class="form-group">
                                                                            <label for="inputHorario">Horario</label>
                                                                            <select class="form-control" id="horarioEdit" name="horarioEdit" required>
                                                                                
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12" style="border-top: 1px solid #ccc; font-size: 14px;">
                                                                        <div class="col-lg-6 text-left" style="margin-top: 10px;">
                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                                                        </div>
                                                                        <div class="col-lg-6 text-right" style="margin-top: 10px;">
                                                                            <button type="submit" name="registrar" value="Modificar" class="btn"  style="background-color: #48c9b0; color:white;" >
                                                                                <b>MODIFICAR</b>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <a class="addUsers" href="#" id="addUserss" onclick="addUsers()" data-toggle="modal"  data-target="#addUsers" title="agregae usuario" style="display:none;"> 
                                                <img src="assets/img/iconos/agregaru.png" style="height: 50px;" title="Agregar un nuevo usuario">
                                            </a><!-- Modal -->
                                            <div class="modal fade" id="addUsers" role="dialog">
                                                <div class="modal-dialog">
                                                    <div class="modal-content text-center">
                                                        <div class="panel panel-default">
                                                            <div class="panel-heading" style="background-color: #e5e8e8;">
                                                                <h3 class="panel-title">
                                                                    <b>REGISTRAR USUARIO</b>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                </h3>
                                                            </div>
                                                            <div class="panel-body">
                                                                <form method="POST" action="registrar" role="form" accept-charset="UTF-8" enctype="multipart/form-data">
                                                                    
                                                                    <input type="hidden" name="_token" id="_token" value="${token}">
                                                                    <ul class="list-group text-left">
                                                                        <li class="list-group-item text-center">
                                                                            <label>
                                                                                CÉDULA
                                                                            </label>
                                                                            <input 
                                                                                type="number" 
                                                                                class="form-control"
                                                                                name="cedula" 
                                                                                id="ceduUS" 
                                                                                min="1000000"
                                                                                maxlength="8" 
                                                                                minlength="7" 
                                                                                placeholder="Escribe tu cedula"
                                                                                value="${element.cedula}"
                                                                                required>
                                                                        </li>
                                                                    </ul>
                                                                    <div class="col-lg-6 text-left">
                                                                        <div class="form-group">
                                                                            <label for="inputTipous">Tipo de tipousuario</label>
                                                                            <select class="form-control" id="tduldap" name="tipousuario" >
                                                                                
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6 text-left">
                                                                        <div class="form-group">                                            
                                                                            <label for="inputFoto">Foto</label> 
                                                                            <input
                                                                                class="form-control"
                                                                                name="foto"
                                                                                id="fotoUs" 
                                                                                type="text"
                                                                                value="${element.cedula}.jpg" 
                                                                                >
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6 text-left">
                                                                        <div class="form-group">
                                                                            <label for="inputNombre">Nombre</label>
                                                                            <input 
                                                                                type="text" 
                                                                                class="form-control" 
                                                                                name="nombre" 
                                                                                id="nombreUs" 
                                                                                maxlength="20" 
                                                                                placeholder="Escribe tu nombre" 
                                                                                min="3"
                                                                                value="${element.nombres}"
                                                                                required
                                                                                title="Por favor llene este campo correctamente para poder continuar"/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6 text-left">
                                                                        <div class="form-group">
                                                                            <label for="inputApellido">Apellido</label>
                                                                            <input 
                                                                                type="text" 
                                                                                class="form-control"
                                                                                name="apellido" 
                                                                                id="apellidoUs" 
                                                                                placeholder="Escribe tu apellido"
                                                                                maxlength="20"
                                                                                value=" ${element.apellidos}"
                                                                                title="Por favor llene este campo correctamente para poder continuar" required/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6 text-left">
                                                                        <div class="form-group">
                                                                            <label for="inputApellido">Correo Electronico</label>
                                                                            <input 
                                                                                type="text" 
                                                                                class="form-control"
                                                                                name="correo" 
                                                                                id="correoUs"
                                                                                value="${element.coreo}"
                                                                                 pattern="[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*@[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{1,5}" 
                                                                                placeholder="Escribe tu correo electronico"
                                                                                title="Por favor llene este campo correctamente para poder continuar. Ejemplo: tucorreo@vive.com" required/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6 text-left">
                                                                        <div class="form-group">                                            
                                                                            <label for="inputLogin">Login</label> 
                                                                                <input 
                                                                                    class="form-control loginUs" 
                                                                                    id="loginUs" 
                                                                                    type="text" 
                                                                                    name="login"
                                                                                    onchange="busca_login()"
                                                                                    value="${element.login}" 
                                                                                    placeholder="Ingrese el login" 
                                                                                    required
                                                                                    />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6 text-left">
                                                                        <div class="form-group">                                            
                                                                            <label for="inputContraseña">Contraseña</label> 
                                                                            <input
                                                                                class="form-control passUs"
                                                                                name="pass"
                                                                                id="passUs" 
                                                                                type="password"
                                                                                value="${element.cedula}"
                                                                                placeholder="Ingrese la contraseña" 
                                                                                required
                                                                                >
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6 text-left">
                                                                        <div class="form-group">                                            
                                                                            <label for="inputContraseña">Repetir contraseña</label> 
                                                                            <input
                                                                                class="form-control repitepass"
                                                                                name="repitepass"
                                                                                id="repitepass" 
                                                                                type="password"
                                                                                value="${element.cedula}"
                                                                                placeholder="Ingrese la contraseña" 
                                                                                required
                                                                                >
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6 text-left" id="horariosAdd">
                                                                        <div class="form-group">
                                                                            <label for="inputHorario">Pregunta de seguridad</label>
                                                                            <select class="form-control"  name="pregunta" required id="prgld">
                                                                                
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6 text-left">
                                                                        <div class="form-group">                                            
                                                                            <label for="inputRespuesta">Respuesta</label> 
                                                                            <input 
                                                                                class="form-control" 
                                                                                id="respuestaUs" 
                                                                                type="password" 
                                                                                name="respuesta" 
                                                                                placeholder="Ingrese la respuesta a la pregunta" 
                                                                                pattern="[a-Z]"
                                                                                required 
                                                                                />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6 text-left" id="DeptoAddS">
                                                                        <div class="form-group">
                                                                            <label for="inputDepartamento">Departamento</label>
                                                                            <select class="form-control" id="deptold" name="departamento" required>
                                                                                <option selected value="0"> Departamento</option>
                                                                                
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6 text-left" id="rolesAddS">
                                                                        <div class="form-group">
                                                                            <label for="inputRol">Rol</label>
                                                                            <select class="form-control" id="rolesld" name="roles" required>
                                                                                
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-12 text-left" id="horariosAddS">
                                                                        <div class="form-group">
                                                                            <label for="inputHorario">Horario</label>
                                                                            <select class="form-control"  name="horario" id="horald" required>
                                                                                
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12">
                                                                        <div class="col-lg-6 text-left">
                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                                                        </div>
                                                                        <div class="col-lg-6 text-right">
                                                                            <button type="submit" name="registrar"  class="btn"  style="background-color: #48c9b0; color:white;" >
                                                                                <b>REGISTRAR</b>
                                                                            </button>
                                                                        </div>
                                                                    </div>                                          
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                `)
                            });
                            $('#repitepass').change(function(e) {
                                var pas1 = $('#passUs').val();
                                var pas2 = $('#repitepass').val();
                                if (pas1 == pas2) {
                                    return true;
                                }
                                else{
                                    swal('Las contraseñas no coinciden, por favor intente de nuevo.');
                                    $('#passUs').val('');
                                    $('#repitepass').val('');
                                    $('#passUs').focus();
                                }
                            });


                        }
                    });
                }
                else{
                    swal("Disculpe, la busqueda no se encuentra disponible en este momento.",{
                        icon: "warning"
                    })
                }
            });

            $('#us_search_ced_sigesp').change(function(event){
                $.get()
                var token = $('#_token').val();
                var ced = $('#us_search_ced_sigesp').val();
                if (sigesp ==1) {
                    $.get(`getCedSigesp/${ced}`,function(response){
                        console.log(response);
                        if (response == '') {
                            swal("No se han encontrado resultados.",{
                                icon: "info"
                            });
                            $('#us_search_ced_sigesp').val('');
                         }
                         else{
                            $("#center").empty();
                            $("#listaEmpleados").empty();
                            response.forEach(element=> {
                                $("#listaEmpleados").append(`<tr value="${element.cedula}">
                                    <td class="text-center">
                                        <img src="imagenes2/${element.cedula}.jpg" style="height: 90px;">
                                    </td>
                                    <td class="text-center">
                                        ${element.nombres}
                                    </td>
                                    <td class="text-center">
                                        ${element.apellidos}<br>
                                    </td>
                                    <td class="text-center">
                                        ${element.cedula}<br>
                                    </td>
                                    <td class="text-center">
                                        ${element.des_uni} 
                                    </td>
                                    </td>
                                    <td class="text-right">
                                        <a class="editEmpleado" id="editEmpleadoo" onclick="editUsuario(${element.cedula})" href="#" data-toggle="modal"  data-target="#editarUS" style="display:none;"> 
                                            <img src="assets/img/iconos/editar.svg" class="imgmenuho" >
                                        </a>
                                        <div class="modal fade" id="editarUS" tabindex="-1" role="dialog" aria-labelledby="EditarUsuario" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content text-center">
                                                    <div class="panel panel-default">
                                                        <div class="panel-heading" style="background-color: #e5e8e8;">
                                                            <h3 class="panel-title">
                                                                <b>EDITAR USUARIO</b>
                                                            </h3>
                                                        </div>
                                                        <div class="panel-body">
                                                            <form method="POST" action="updateE">
                                                                <input type="hidden" name="_token" id="_token" value="${token}">
                                                                <div class="col-lg-6 text-left">
                                                                    <div class="form-group">
                                                                        <label for="inputTipous">Tipo de tipousuario</label>
                                                                        <select class="form-control" id="tipodeusuarioedit" name="tipousuario" >
                                                                            
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div id="empleadosEdit">

                                                                </div>
                                                                <div class="col-lg-12 text-left">
                                                                    <div class="form-group">
                                                                        <label for="inputHorario">Horario</label>
                                                                        <select class="form-control" id="horarioEdit" name="horarioEdit" required>
                                                                            
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-12" style="border-top: 1px solid #ccc; font-size: 14px;">
                                                                    <div class="col-lg-6 text-left" style="margin-top: 10px;">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                                                    </div>
                                                                    <div class="col-lg-6 text-right" style="margin-top: 10px;">
                                                                        <button type="submit" name="registrar" value="Modificar" class="btn"  style="background-color: #48c9b0; color:white;" >
                                                                            <b>MODIFICAR</b>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <a class="addUsers" id="addUserss" onclick="addUsers()" href="#" data-toggle="modal"  data-target="#addUsers" title="Editar usuario" style="display:none;"> 
                                            <img src="assets/img/iconos/agregaru.png" style="height: 50px;" title="Agregar un nuevo usuario">
                                        </a><!-- Modal -->
                                        <div class="modal fade" id="addUsers" role="dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content text-center">
                                                        <div class="panel panel-default">
                                                            <div class="panel-heading" style="background-color: #e5e8e8;">
                                                                <h3 class="panel-title">
                                                                    <b>REGISTRAR USUARIO</b>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                </h3>
                                                            </div>
                                                            <div class="panel-body">
                                                                <form method="POST" action="registrar" role="form" accept-charset="UTF-8" enctype="multipart/form-data">
                                                                    
                                                                    <input type="hidden" name="_token" id="_token" value="${token}">
                                                                    <ul class="list-group text-left">
                                                                        <li class="list-group-item text-center">
                                                                            <label>
                                                                                CÉDULA
                                                                            </label>
                                                                            <input 
                                                                                type="number" 
                                                                                class="form-control"
                                                                                name="cedula" 
                                                                                id="ceduUS" 
                                                                                min="1000000"
                                                                                maxlength="8" 
                                                                                minlength="7" 
                                                                                placeholder="Escribe tu cedula"
                                                                                value="${element.cedula}"
                                                                                required>
                                                                        </li>
                                                                    </ul>
                                                                    <div class="col-lg-6 text-left">
                                                                        <div class="form-group">
                                                                            <label for="inputTipous">Tipo de tipousuario</label>
                                                                            <select class="form-control" id="tduldap" name="tipousuario" >
                                                                                
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6 text-left">
                                                                        <div class="form-group">                                            
                                                                            <label for="inputFoto">Foto</label> 
                                                                            <input
                                                                                class="form-control"
                                                                                name="foto"
                                                                                id="fotoUs" 
                                                                                type="text"
                                                                                value="${element.cedula}.jpg" 
                                                                                >
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6 text-left">
                                                                        <div class="form-group">
                                                                            <label for="inputNombre">Nombre</label>
                                                                            <input 
                                                                                type="text" 
                                                                                class="form-control" 
                                                                                name="nombre" 
                                                                                id="nombreUs" 
                                                                                maxlength="20" 
                                                                                placeholder="Escribe tu nombre" 
                                                                                min="3"
                                                                                value="${element.nombres}"
                                                                                required
                                                                                title="Por favor llene este campo correctamente para poder continuar"/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6 text-left">
                                                                        <div class="form-group">
                                                                            <label for="inputApellido">Apellido</label>
                                                                            <input 
                                                                                type="text" 
                                                                                class="form-control"
                                                                                name="apellido" 
                                                                                id="apellidoUs" 
                                                                                placeholder="Escribe tu apellido"
                                                                                maxlength="20"
                                                                                value=" ${element.apellidos}"
                                                                                title="Por favor llene este campo correctamente para poder continuar" required/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6 text-left">
                                                                        <div class="form-group">
                                                                            <label for="inputApellido">Correo Electronico</label>
                                                                            <input 
                                                                                type="text" 
                                                                                class="form-control"
                                                                                name="correo" 
                                                                                id="correoUs"
                                                                                 pattern="[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*@[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{1,5}" 
                                                                                placeholder="Escribe tu correo electronico"
                                                                                title="Por favor llene este campo correctamente para poder continuar. Ejemplo: tucorreo@vive.com" required/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6 text-left">
                                                                        <div class="form-group">                                            
                                                                            <label for="inputLogin">Login</label> 
                                                                                <input 
                                                                                    class="form-control loginUs" 
                                                                                    id="loginUs" 
                                                                                    type="text" 
                                                                                    name="login"
                                                                                    onchange="busca_login()"
                                                                                    placeholder="Ingrese el login" 
                                                                                    required
                                                                                    />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6 text-left">
                                                                        <div class="form-group">                                            
                                                                            <label for="inputContraseña">Contraseña</label> 
                                                                            <input
                                                                                class="form-control"
                                                                                name="pass"
                                                                                id="passUs" 
                                                                                type="password"
                                                                                placeholder="Ingrese la contraseña" 
                                                                                required
                                                                                >
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6 text-left">
                                                                        <div class="form-group">                                            
                                                                            <label for="inputContraseña">Repetir contraseña</label> 
                                                                            <input
                                                                                class="form-control repitepass"
                                                                                name="repitepass"
                                                                                id="repitepass" 
                                                                                type="password"
                                                                                placeholder="Ingrese la contraseña" 
                                                                                required
                                                                                >
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6 text-left" id="horariosAdd">
                                                                        <div class="form-group">
                                                                            <label for="inputHorario">Pregunta de seguridad</label>
                                                                            <select class="form-control"  name="pregunta" required id="prgld">
                                                                                
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6 text-left">
                                                                        <div class="form-group">                                            
                                                                            <label for="inputRespuesta">Respuesta</label> 
                                                                            <input 
                                                                                class="form-control" 
                                                                                id="respuestaUs" 
                                                                                type="password" 
                                                                                name="respuesta" 
                                                                                placeholder="Ingrese la respuesta a la pregunta" 
                                                                                pattern="[a-Z]"
                                                                                required 
                                                                                />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6 text-left" id="DeptoAddS">
                                                                        <div class="form-group">
                                                                            <label for="inputDepartamento">Departamento</label>
                                                                            <select class="form-control" id="deptold" name="departamento" required>
                                                                                <option selected value="0"> Departamento</option>
                                                                                
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6 text-left" id="rolesAddS">
                                                                        <div class="form-group">
                                                                            <label for="inputRol">Rol</label>
                                                                            <select class="form-control" id="rolesld" name="roles" required>
                                                                                
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-12 text-left" id="horariosAddS">
                                                                        <div class="form-group">
                                                                            <label for="inputHorario">Horario</label>
                                                                            <select class="form-control"  name="horario" id="horald" required>
                                                                                
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12">
                                                                        <div class="col-lg-6 text-left">
                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                                                        </div>
                                                                        <div class="col-lg-6 text-right">
                                                                            <button type="submit" name="registrar"  class="btn"  style="background-color: #48c9b0; color:white;" >
                                                                                <b>REGISTRAR</b>
                                                                            </button>
                                                                        </div>
                                                                    </div>                                          
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>` )       
                            });

                         }
                    });
                }
                else{
                    swal("Disculpe, la busqueda no se encuentra disponible en este momento.",{
                        icon: "warning"
                    })
                }
            });
        });
    });

});