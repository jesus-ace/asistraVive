
function registrar(){
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
            data:'contactFrmSubmit=1&nombre='+nombre+'&apellido='+apellido+'&cedula='+cedula+'&sexo'+sexo+'&rol'+rol+'&pregunta'+pregunta+'&respuesta'+respuesta+'&login'+login+'&contraseña'+contraseña'&_token'+token,
            type:"POST",
            dataType: "json",
            url:"registrar",
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

angular.module("asistencias",[])
.controller("usuarios",function($scope, $http){
    $http({
        method: "GET",
        url: "UsuarioController/buscarPersonas",
        dataType: json,
        data:[];
    }).
    $scope.personas=
    [
        {
            id: "",
            nombre:"",
            apellido:"",
            cedula:"",
            profesion:"",
            sexo:""
        }
    ];

    $scope.newPerson={};
    $scope.updatePerson={};

    $scope.probando=function(){
        if($scope.bool2==true){
            $scope.bool2=false;
        }
        if($scope.bool==true){
            $scope.bool=false;
        }else
        $scope.bool=true;

    }
    $scope.modificar=function(id){
        if($scope.bool==true){
            $scope.bool=false;
        }
        if($scope.bool2==true){
            $scope.bool2=false;
        }else
        $scope.bool2=true;
        $scope.updatePerson=$scope.personas[id-1];
        if(id=="listo"){
            alert("Se ha modificado el registro");
        }

    }

    $scope.addPerson=function(){
        //console.log($scope.personas.length);
        $scope.newPerson.id=$scope.personas.length+1;
        $scope.personas.push($scope.newPerson);
        $scope.newPerson={};
    }


    $scope.removePerson=function(index){
        // USTED VA A ESCRIBIR SU MAGNIFICO Y PERFECTO CODIGO EN ESTA AREA, DESPUES QUE SE TOME UNAS BIRRAS.
        $scope.personas.splice(index-1,1);
        for (var i = 0 ; i < $scope.personas.length; i++) {
            $scope.personas[i].id=i+1;
        }

    }
})