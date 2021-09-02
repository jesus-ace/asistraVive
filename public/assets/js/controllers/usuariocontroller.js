function submitContactForm(){
    var reg = /^[A-Z0-9._%+-]+@([A-Z0-9-]+.)+[A-Z]{2,4}$/i;
    var tipousuario = $('#inputTipous').val();
    var nombre = $('#inputNombre').val();
    var apellido = $('#inputApellido').val();
    var cedula = $('#inputCedula').val();
    var sexo = $('#inputSexo').val();
    var rol = $('#inputRol').val();
    var pregunta = $('#inputPregunta').val();
    var respuesta = $('#inputRespuesta').val();
    var login = $('#inputLogin').val();
    var contraseña = $('#inputContraseña').val();
    var token = $('_token').val();

    if(token.trim() == '' ){
        alert('Lo siento existe un error con el token.');
        $('#_token').focus();
        return false;
    }
    else if(nombre.trim() == '' ){
        alert('Por favor ingrese su nombre.');
        $('#inputNombre').focus();
        return false;
    }

    else if(apellido.trim() == '' ){
        alert('Por favor ingrese su apellido.');
        $('#inputApellido').focus();
        return false;
    }

    else if(cedula.trim() == '' ){
        alert('Por favor ingrese su cedula de identidad.');
        $('#inputCedula').focus();
        return false;
    }

    else if(sexo.trim() == '' ){
        alert('Por favor seleccione un sexo.');
        $('#inputSexo').focus();
        return false;
    }

    else if(rol.trim() == '' ){
        alert('Por favor seleccione un rol.');
        $('#inputRol').focus();
        return false;
    }

    else if(pregunta.trim() == '' ){
        alert('Por favor ingrese una pregunta de recuperacion.');
        $('#inputPregunta').focus();
        return false;
    }

    else if(respuesta.trim() == '' ){
        alert('Por favor ingrese una respuesta.');
        $('#inputRespuesta').focus();
        return false;
    }

    else if(login.trim() == '' ){
        alert('Por favor ingrese un login de usuario.');
        $('#inputLogin').focus();
        return false;
    }

    else if(contraseña.trim() == '' ){
        alert('Por favor ingrese una contraseña.');
        $('#inputContraseña').focus();
        return false;
    }
    else{
        $.ajax({
            type:'POST',
            url:'registrar',
            data:'contactFrmSubmit=1&nombre='+nombre+'&apellido='+apellido+'&cedula='+cedula+'&sexo'+sexo+'&rol'+rol+'&pregunta'+pregunta+'&respuesta'+respuesta+'&login'+login+'&contraseña'+contraseña'&_token'+token,
            beforeSend: function () {
                $('.submitBtn').attr("disabled","disabled");
                $('.modal-body').css('opacity', '.5');
            },
            success:function(msg){
                if(msg == 'ok'){
                    $('#inputNombre').val('');
                    $('#inputApellido').val('');
                    $('#inputCedula').val('');
                    $('#inputSexo').val('');
                    $('#inputRol').val('');
                    $('#inputPregunta').val('');
                    $('#inputRespuesta').val('');
                    $('#inputLogin').val('');
                    $('#inputContraseña').val('');
                    $('#_token').val('');
                    $('.statusMsg').html('<span style="color:green;">Usuario registrado de manera exitosa.</p>');
                }else{
                    $('.statusMsg').html('<span style="color:red;">Opps! Ocurrio Algún problema, intente de nuevo.</span>');
                }
                $('.submitBtn').removeAttr("disabled");
                $('.modal-body').css('opacity', '');
            }
        });
    }
}