$(".buscaEmpSinC").click(function(event){

	var cedula = $('#busCarnetEmp').val();
	var token = $('#_token').val();

	if (cedula == "") {
		$("#selloN").fadeOut("slow");
		$("#fecha").fadeOut("slow");
		$("#foto").fadeOut("slow");
      	
      	$("#empleadosCarnetNew").empty();

      	$("#empleadosCarnetNew").append(`<br><div class='bg-warning' style='padding: 20px'>Disculpe, debe ingresar una cédula..</div>`)

    }else{

		$.get(`busEmpSinCar/${cedula}`, function(response, buscaEmpSinC){
		
		console.log(response);
		
		$("#empleadosCarnetNew").empty();

		if (response == "" ) {

      		$("#selloN").fadeOut("slow");
      		$("#fecha").fadeOut("slow");
      		$("#foto").fadeOut("slow");
      		
       		$("#empleadosCarnetNew").append(`<br><div class='bg-warning' style='padding: 20px'>Disculpe, no se encontraron resultados... <br> Verifique que dicho empleado tenga un horario asignado o si ya posee carnet </div>`)     		
   		}else{
   			
   			$("#selloN").fadeIn("slow");
   			$("#fecha").fadeIn("slow");
   			$("#foto").fadeIn("slow");
   			
			response.forEach(element=> {

				var fotoPerfil = 'usericonos.JPG';     
                
                var cod_car = element.cod_car;

			/*	if((cod_car == '0000000407') || (cod_car == '0000000431') || 
					(cod_car == '0000000431') || (cod_car == '0000000232') || 
					(cod_car == '0000000234') || (cod_car == '0000000098') || 
					(cod_car == '0000000357') || (cod_car == '0000000402') || 
					(cod_car == '0000000412') || (cod_car == '0000000104') || 
					(cod_car == '0000000331') || (cod_car == '000000086') || 
					(cod_car == '0000000089') || (cod_car == '000000041') || 
					(cod_car == '0000000414') || (cod_car == '0000000225') || 
					(cod_car == '0000000436') || (cod_car == '0000000097') || 
					(cod_car == '0000000092') || (cod_car == '0000000264') || 
					(cod_car == '0000000270') || (cod_car == '0000000386')){ 

				var foto = 'COORDINADOR.jpg';
				var fotoPrensa = 'COORDINADOR-PRENSA.jpg';
			
			}else if((cod_car == '0000000200') || (cod_car == '0000000399') || 
				(cod_car == '0000000391')){

				var foto = 'PRESIDENCIA.jpg';
				var fotoPrensa = 'PRESIDENCIA-PRENSA.jpg';

			}else{*/

                var cod_uni = element.cod_uni;

                if((cod_uni == '0000-00-04-01-01') || (cod_uni == '0000-00-04-01-00') || 
				(cod_uni == '0000-00-04-04-00') || (cod_uni == '0000-00-04-04-01') || 
				(cod_uni == '0000-00-04-02-00') || (cod_uni == '0000-00-05-01-00') || 
				(cod_uni == '0000-00-04-05-00') || (cod_uni == '0000-00-03-00-01') || 
				(cod_uni == '0000-00-03-00-02') || (cod_uni == '0000-00-03-00-03') || 
				(cod_uni == '0000-00-03-00-04') || (cod_uni == '0000-00-03-00-05') || 
				(cod_uni == '0000-00-03-00-06') || (cod_uni == '0000-00-04-00-00') ||
				(cod_uni == '0000-00-05-00-00') || (cod_uni == '0000-00-30-00-01') ||
				(cod_uni == '0000-00-05-02-00')
				){ 

				var foto = 'VPGT.jpg';
				var fotoPrensa = 'VPGT-PRENSA.jpg';

			}else if((cod_uni == '0000-00-07-00-00') || (cod_uni == '0000-00-03-02-00') || 
				(cod_uni == '0000-00-03-02-01') || (cod_uni == '0000-00-03-01-00') || 
				(cod_uni == '0000-00-03-01-01') || (cod_uni == '0000-00-03-05-00') || 
				(cod_uni == '0000-00-03-04-00') || (cod_uni == '0000-00-03-00-00') ||
				(cod_uni == '0000-00-06-00-00')
				){
				//VPGP - Programacion - Produccion I - Img y Prodc - Comunicacion Popular - 123Tv -Orinoco - Ficcion y animacion
				
					var foto = 'VPGP.jpg';
					var fotoPrensa = 'VPGP-PRENSA.jpg';

			}else if ((cod_uni == '0000-00-02-01-00') || (cod_uni == '0000-00-02-02-00') || 
					(cod_uni == '0000-00-02-05-00') || (cod_uni == '0000-00-02-03-00') || 
					(cod_uni == '0000-00-02-04-00') || (cod_uni == '0000-00-01-03-01') || 
					(cod_uni == '0000-00-01-03-00') || (cod_uni == '0000-00-02-00-00')){
				
						var foto = 'VPGI.jpg';
						var fotoPrensa = 'VPGI-PRENSA.jpg';

				}/*else if (cod_uni == '0000-00-01-06-00'){

					var foto = 'SEGURIDAD.jpg';
					var fotoPrensa = 'SEGURIDAD-PRENSA.jpg';

				}*/else if ((cod_uni == '0000-00-01-00-00') || (cod_uni == '0000-00-01-01-00') || 
					(cod_uni == '0000-00-01-02-00') || (cod_uni == '0000-00-01-02-01') || 
					(cod_uni == '0000-00-01-04-00') || (cod_uni == '0000-00-01-05-00') ||
					(cod_uni == '0000-00-01-06-00') || (cod_uni == '0000-00-04-03-00') ||
					(cod_uni == '0000-00-01-06-00') || (cod_uni == '0000-00-30-00-11')
					){ 

					var foto = 'PRESIDENCIA.jpg';
					var fotoPrensa = 'PRESIDENCIA-PRENSA.jpg';
				}

		//	}


		$("#empleadosCarnetNew").append(`
			<input type="hidden" name="_token" id="_token" value="${token}"> 
				<input type="hidden" name="id" id="id" value="${element.cedula}">

				<div id="empleadosCarnetNew">

				<div class="form-group col-xs-6">
              		<label>Nombres: </label>
					<input type="text" name="nombreEmpleadoN" class="form-control" value="${element.nombres}" required>
          		</div>

          		<div class="form-group col-xs-6">
          			<label>Apellidos: </label>
					<input type="text" name="apellidoEmpleadoN" class="form-control" value="${element.apellidos}" required>
				</div>

				<div class="form-group col-xs-6">
              		<label>Cédula: </label>
					<input type="text" name="cedulaEmpleadoN" class="form-control" value="${element.cedula}" required>
          		</div>

                <div class="form-group col-xs-6">
          			<label>Área: </label>
					<input type="text" name="areaEmpleadoN" class="form-control" value="${element.des_uni}" required>
				</div>

            <input type="hidden" name="cargoCodigo" class="form-control" value="${element.cod_car}" required>

             

</div>
				

<br> <br><br><br><br> <br><br><br>
          	
			

          		<div class="form-group col-md-12" id='vista_previa'>
          		<label style='color:#5183af; font-size:14px; font-family:Helvetica; font-weight:bold;'> Vista previa de impresi&oacute;n </label>
				</div>
					
					<div id='previo_carnet' style='border-radius:5px; margin:45px 5px 0 310px; border:1px solid #999; height:371px; width:233px; background:url(imagenes/${foto}); display:block; line-height:11px;display:block; line-height:11px; overflow: hidden;  background-size: 233px, 345px; background-repeat: no-repeat;'>
						<div align='center' style='padding-top:1px; height: 40px;'>
							<span style='font-size:10px; color:#47629f; display:none;'>Rep&uacute;blica Bolivariana de Venezuela</span>
							<span style='font-size:10px; color:#47629f; display:none;'>Ministerio del Poder Popular para la</span>
							<span style='font-size:10px; color:#47629f; display:none;'>Comunicaci&oacute;n y la Informaci&oacute;n</span>
							<span style='font-size:10px; color:#47629f; display:none;'>COVETEL. S.&nbsp;A.</span>
						</div>
						<div id='foto_info_previa' style='padding:38px 0 0px 5px'>
							<div id="rombo2">
					        	<div class="ima">
					        		<img id='foto_carnet_previa' src='imagenes/${fotoPerfil}' style="width: 88%;"/>
					        	</div>
					        </div>
							<div id='info_previa' style='margin-top:145px; text-align: right;'>
								<span id='nombre_previo' style='font-size:13px; color:#000; display:block; font-weight: bold; line-height:12px; '>
									${element.nombres} 
								</span>
								<span style='font-size:12px; color:#000; display:block; padding-top:3px; font-weight: bold; line-height:12px;'>
									D.I.N&deg;:&nbsp;
									<span id='din_previo' style='font-size:12px; color:#000;'>
										${element.cedula}
									</span>
								</span>
								<span id='cargo_previo' style='font-size:9px; color:#000; display:block; padding-top:3px; line-height:12px;'>
									 ${element.des_car}
								</span>
								<span id='dpto_previo' style='font-size:9px; color:#000; display:block; padding-top:3px;line-height:12px;'>
									${element.des_uni}
								</span>
								
							</div>
						</div>
					</div>
				</div>

					<div id='vista_previa_Sello' style='border-radius:5px; margin:45px 5px 0 310px; border:1px solid #999; height:371px; width:233px; background:url(imagenes/${fotoPrensa}); display:block; line-height:11px;display:none; line-height:11px; overflow: hidden;  background-size: 233px, 345px; background-repeat: no-repeat;'>
						<div align='center' style='padding-top:1px; height: 40px;'>
							<span style='font-size:10px; color:#47629f; display:none;'>Rep&uacute;blica Bolivariana de Venezuela</span>
							<span style='font-size:10px; color:#47629f; display:none;'>Ministerio del Poder Popular para la</span>
							<span style='font-size:10px; color:#47629f; display:none;'>Comunicaci&oacute;n y la Informaci&oacute;n</span>
							<span style='font-size:10px; color:#47629f; display:none;'>COVETEL. S.&nbsp;A.</span>
						</div>
						<div id='foto_info_previa' style='padding:38px 0 0px 5px'>
							<div id="rombo2">
					        	<div class="ima">
					        		<img id='foto_carnet_previa' src='imagenes/${fotoPerfil}' style="width: 88%;"/>
					        	</div>
					        </div>
							<div id='info_previa' style='margin-top:145px; text-align: right;'>
								<span id='nombre_previo' style='font-size:13px; color:#000; display:block; font-weight: bold; line-height:12px; '>
									${element.nombres}
								</span>
								<span style='font-size:12px; color:#000; display:block; padding-top:3px; font-weight: bold; line-height:12px;'>
									D.I.N&deg;:&nbsp;
									<span id='din_previo' style='font-size:12px; color:#000;'>
										${element.cedula}
									</span>
								</span>
								<span id='cargo_previo' style='font-size:9px; color:#000; display:block; padding-top:3px; line-height:12px;'>
									 ${element.des_car}
								</span>
								<span id='dpto_previo' style='font-size:9px; color:#000; display:block; padding-top:3px;line-height:12px;'>
									${element.des_uni}
								</span>

							</div>
						</div>
					</div>
				</div>

			</form>`)

		});
}
	});
}

});


$("#selloPrensa").click(function(){

	var sello = $("#selloPrensa").val();

	if (sello == "Si") {

		$("#vista_previa_Sello").fadeIn("slow");
		$("#previo_carnet").fadeOut("slow");

	}else{

		$("#vista_previa_Sello").fadeOut("slow");
		$("#previo_carnet").fadeIn("slow");

	}

});


function readURL(input) {
  if(input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function (e) {
      $('#foto_carnet_previa').attr('src', e.target.result);
    }
      reader.readAsDataURL(input.files[0]);
  }
}

$("#image").change(function() {
	readURL(this);
});




//--------------------------

$(".buscaEmpCProv").click(function(event){

	var id = $('#busCarnetEmpPro').val();

	if (id == "") {
		$("#selloN").fadeOut("slow");
		$("#fecha").fadeOut("slow");
		$("#foto").fadeOut("slow");
		$("#motPasante").fadeOut();
      	
      	$("#provisional").empty();

      	$("#provisional").append(`<br><div class='bg-warning' style='padding: 20px'>Disculpe, debe ingresar una cédula..</div>`)

      }else{

      	$('#provisional').empty();
		$('#motivoProvisional').val('');
		var token = $('#_token').val();
		$('#motivoProvisional').change(function(e){

			var motivo = $('#motivoProvisional').val();
			if (motivo == 1) {
				$.get(`crearCarnetEmp/${id}`, function(response, newCarEmpleado){
		    	console.log(response);

		    	$("#selloN").fadeIn("slow");
			    $("#fecha").fadeIn("slow");
			    $("#foto").fadeIn("slow");
			    $(".selectSello").fadeIn("slow");
		    
		    response.forEach(element=> {

		        var fotoPerfil = 'usericonos.JPG';  
		        var perfil = element.cedula+'.jpg';  
		                
		                var cod_car = element.cod_car;

		      /*  if((cod_car == '0000000407') || (cod_car == '0000000431') || 
		          (cod_car == '0000000431') || (cod_car == '0000000232') || 
		          (cod_car == '0000000234') || (cod_car == '0000000098') || 
		          (cod_car == '0000000357') || (cod_car == '0000000402') || 
		          (cod_car == '0000000412') || (cod_car == '0000000104') || 
		          (cod_car == '0000000331') || (cod_car == '000000086') || 
		          (cod_car == '0000000089') || (cod_car == '000000041') || 
		          (cod_car == '0000000414') || (cod_car == '0000000225') || 
		          (cod_car == '0000000436') || (cod_car == '0000000097') || 
		          (cod_car == '0000000092') || (cod_car == '0000000264') || 
		          (cod_car == '0000000270') || (cod_car == '0000000386')){ 

		        var foto = 'COORDINADOR.jpg';
		        var fotoPrensa = 'COORDINADOR-PRENSA.jpg';
		      
		      }else if((cod_car == '0000000200') || (cod_car == '0000000399') || 
		        (cod_car == '0000000391')){

		        var foto = 'PRESIDENCIA.jpg';
		        var fotoPrensa = 'PRESIDENCIA-PRENSA.jpg';

		      }else{*/

		                var cod_uni = element.cod_uni;

		        if((cod_uni == '0000-00-04-01-01') || (cod_uni == '0000-00-04-01-00') || 
				(cod_uni == '0000-00-04-04-00') || (cod_uni == '0000-00-04-04-01') || 
				(cod_uni == '0000-00-04-02-00') || (cod_uni == '0000-00-05-01-00') || 
				(cod_uni == '0000-00-04-05-00') || (cod_uni == '0000-00-03-00-01') || 
				(cod_uni == '0000-00-03-00-02') || (cod_uni == '0000-00-03-00-03') || 
				(cod_uni == '0000-00-03-00-04') || (cod_uni == '0000-00-03-00-05') || 
				(cod_uni == '0000-00-03-00-06') || (cod_uni == '0000-00-04-00-00') ||
				(cod_uni == '0000-00-05-00-00') || (cod_uni == '0000-00-30-00-01') ||
				(cod_uni == '0000-00-05-02-00')
				){ 

		        var foto = 'VPGT.jpg';
		        var fotoPrensa = 'VPGT-PRENSA.jpg';

		      }else if((cod_uni == '0000-00-07-00-00') || (cod_uni == '0000-00-03-02-00') || 
				(cod_uni == '0000-00-03-02-01') || (cod_uni == '0000-00-03-01-00') || 
				(cod_uni == '0000-00-03-01-01') || (cod_uni == '0000-00-03-05-00') || 
				(cod_uni == '0000-00-03-04-00') || (cod_uni == '0000-00-03-00-00') ||
				(cod_uni == '0000-00-06-00-00')
				){
				//VPGP - Programacion - Produccion I - Img y Prodc - Comunicacion Popular - 123Tv -Orinoco - Ficcion y animacion
		        
		          var foto = 'VPGP.jpg';
		          var fotoPrensa = 'VPGP-PRENSA.jpg';

		      }else if ((cod_uni == '0000-00-02-01-00') || (cod_uni == '0000-00-02-02-00') || 
		          (cod_uni == '0000-00-02-05-00') || (cod_uni == '0000-00-02-03-00') || 
		          (cod_uni == '0000-00-02-04-00') || (cod_uni == '0000-00-01-03-01') || 
		          (cod_uni == '0000-00-01-03-00') || (cod_uni == '0000-00-02-00-00')){
		        
		            var foto = 'VPGI.jpg';
		            var fotoPrensa = 'VPGI-PRENSA.jpg';

		        }/*else if (cod_uni == '0000-00-01-06-00'){

		          var foto = 'SEGURIDAD.jpg';
		          var fotoPrensa = 'SEGURIDAD-PRENSA.jpg';

		        }*/else if ((cod_uni == '0000-00-01-00-00') || (cod_uni == '0000-00-01-01-00') || 
					(cod_uni == '0000-00-01-02-00') || (cod_uni == '0000-00-01-02-01') || 
					(cod_uni == '0000-00-01-04-00') || (cod_uni == '0000-00-01-05-00') ||
					(cod_uni == '0000-00-01-06-00') || (cod_uni == '0000-00-04-03-00') ||
					(cod_uni == '0000-00-01-06-00') || (cod_uni == '0000-00-30-00-11')
					){ 

		          var foto = 'PRESIDENCIA.jpg';
		          var fotoPrensa = 'PRESIDENCIA-PRENSA.jpg';
		        }

		  //    }

			$("#provisional").empty();

		  /*  $("#provisional").append(`
		    <form method="POST" action="updateCarnet" enctype="multipart/form-data" onsubmit="return confirm('¿Seguro que quiere imprimir este carnet?')" >
		      <input type="hidden" name="_token" id="_token" value="${token}"> 
		      
		      

		      <div class="form-group col-xs-4" id="fecha">
		        <label for="date">Fecha de Vencimiento: </label>
		        <div class="input-group col-lg-12">
		          <input type="date" class="form-control datepicker" name="empleadoFechaVecN" id="empleadoFechaVecs" placeholder="dd-mm-yyyy" required="">
		          <div class="input-group-addon">
		            <span class="glyphicon glyphicon-th"></span>
		          </div>
		        </div>
		      </div>
		      <input type="hidden" name="id" id="id" value="${element.cedula}">
		      <div id="empleadosCarnetNew">
		        <div class="form-group col-xs-6">
		          <label>Nombres: </label>
		          <input type="text" name="nombreEmpleadoN" class="form-control" value="${element.nombres}" required>
		        </div>
		        <div class="form-group col-xs-6">
		          <label>Apellidos: </label>
		          <input type="text" name="apellidoEmpleadoN" class="form-control" value="${element.apellidos}" required>
		        </div>
		        <div class="form-group col-xs-6">
		          <label>Cédula: </label>
		          <input type="text" name="cedulaEmpleadoN" class="form-control" value="${element.cedula}" required>
		        </div>
		        <div class="form-group col-xs-6">
		          <label>Área: </label>
		          <input type="text" name="areaEmpleadoN" class="form-control" value="${element.des_uni}" required>
		        </div>
		        <input type="hidden" name="cargoCodigo" class="form-control" value="${element.cod_car}" required>
		      </div>
		      <br><br><br><br><br> <br><br><br>
		      <div class="form-group col-md-12" >
		        <label style='color:#5183af; font-size:14px; font-family:Helvetica; font-weight:bold;'> 
		          Vista previa de impresi&oacute;n 
		        </label>
		      </div> 
		      <div id='previo_carnet' style='border-radius:5px; margin:45px 5px 0 310px; border:1px solid #999; height:371px; width:233px; background:url(imagenes/${foto}); display:block; line-height:11px;display:block; line-height:11px; overflow: hidden;  background-size: 233px, 345px; background-repeat: no-repeat;'>
		        <div align='center' style='padding-top:1px; height: 40px;'>
		          <span style='font-size:10px; color:#47629f; display:none;'>Rep&uacute;blica Bolivariana de Venezuela</span>
		          <span style='font-size:10px; color:#47629f; display:none;'>Ministerio del Poder Popular para la</span>
		          <span style='font-size:10px; color:#47629f; display:none;'>Comunicaci&oacute;n y la Informaci&oacute;n</span>
		          <span style='font-size:10px; color:#47629f; display:none;'>COVETEL. S.&nbsp;A.</span>
		        </div>
		        <div id='foto_info_previa' style='padding:38px 0 0px 5px'>
		          <div id="rombo2" style="margin-top:79px;">
					        	<div class="ima">
					        		<img id='foto_carnet_previa' src='imagenes/${fotoPerfil}' style="width: 88%;"/>
					        	</div>
					        </div>
		          <div id='info_previa' style='margin-top:166px; text-align: right;'>
		            <span id='nombre_previo' style='font-size:13px; color:#000; display:block; font-weight: bold; line-height:12px; '>
		             ${element.nombres} 
		            </span>
		            <span style='font-size:12px; color:#000; display:block; padding-top:3px; font-weight: bold; line-height:12px;'>
		              D.I.N&deg;:&nbsp;
		                <span id='din_previo' style='font-size:12px; color:#000;'>
		                   ${element.cedula}
		                </span>
		            </span>
		            <span id='cargo_previo' style='font-size:9px; color:#000; display:block; padding-top:3px; line-height:12px;'>
		               ${element.des_car}
		            </span>
		            <span id='dpto_previo' style='font-size:9px; color:#000; display:block; padding-top:3px;line-height:12px;'>
		              ${element.des_uni}
		              </span>

		          </div>
		        </div>
		      </div>
		      <div id='vista_previa_Sello' class="empSello" style='border-radius:5px; margin:45px 5px 0 310px; border:1px solid #999; height:371px; width:233px; background:url(imagenes/${fotoPrensa}); display:block; line-height:11px;display:none; line-height:11px; overflow: hidden;  background-size: 233px, 345px; background-repeat: no-repeat;'>
		        <div align='center' style='padding-top:1px; height: 40px;'>
		          <span style='font-size:10px; color:#47629f; display:none;'>Rep&uacute;blica Bolivariana de Venezuela</span>
		          <span style='font-size:10px; color:#47629f; display:none;'>Ministerio del Poder Popular para la</span>
		          <span style='font-size:10px; color:#47629f; display:none;'>Comunicaci&oacute;n y la Informaci&oacute;n</span>
		          <span style='font-size:10px; color:#47629f; display:none;'>COVETEL. S.&nbsp;A.</span>
		        </div>
		        <div id='foto_info_previa' style='padding:38px 0 0px 5px'>
		          <div id="rombo2" style="margin-top:77px;">
					        	<div class="ima">
					        		<img id='foto_carnet_previa' src='imagenes/${fotoPerfil}'  style="width: 88%;"/>
					        	</div>
					        </div>
		          <div id='info_previa' style='margin-top:166px; text-align: right;'>
		            <span id='nombre_previo' style='font-size:13px; color:#000; display:block; font-weight: bold; line-height:12px; '>
		               ${element.nombres} ${element.apellidos}
		            </span>
		            <span style='font-size:12px; color:#000; display:block; padding-top:3px; font-weight: bold; line-height:12px;'>
		              D.I.N&deg;:&nbsp;
		              <span id='din_previo' style='font-size:12px; color:#000;'>
		                ${element.cedula}
		              </span>
		            </span>
		            <span id='cargo_previo' style='font-size:9px; color:#000; display:block; padding-top:3px; line-height:12px;'>
		              ${element.des_car}
		            </span>
		            <span id='dpto_previo' style='font-size:9px; color:#000; display:block; padding-top:3px;line-height:12px;'>
		               ${element.des_uni}
		            </span>
		          </div>
		        </div>
		      </div>
		      <div class="modal-footer">
                <button type="button" data-dismiss=modal class="btn btn-warning">Cerrar</button>
                <input type="submit" name="registrar" value="Imprimir" class="btn" style="background-color:  #48c9b0; color: white;"/>
              </div>
		    </form>`)*/


		//---------------------------------------------------------

				$("#provisional").append(`

					<form method="POST" action="updateCarnet" enctype="multipart/form-data" onsubmit="return confirm('¿Seguro que quiere imprimir este carnet?')" >
		      <input type="hidden" name="_token" id="_token" value="${token}"> 

				<input type="hidden" name="id" id="id" value="${element.cedula}">

				<div id="provisional">

				 <div class="form-group col-xs-4 selectSello" id="selloN" style="">
                               <label>Sello de Prensa: </label>
                                  <select name="selloPrensaN" class="form-control" id="selloPrensaN" >
                                    <option disabled selected value="No">Seleccione </option>
                                    <option value="Si"> Si </option>
                                    <option value="No"> No </option>     
                                </select>
                            </div>

                            <div class="form-group col-xs-4" id="foto">
            <label>Foto</label>
            <input type="file" class="form-control" name="image" id="image" accept="image/*" >
          </div>

          <div class="form-group col-xs-4" id="fecha">
		        <label for="date">Fecha de Vencimiento: </label>
		        <div class="input-group col-lg-12">
		          <input type="date" class="form-control datepicker" name="empleadoFechaVecN" id="empleadoFechaVecs" placeholder="dd-mm-yyyy" required="">
		          <div class="input-group-addon">
		            <span class="glyphicon glyphicon-th"></span>
		          </div>
		        </div>
		      </div>

				<div class="form-group col-xs-6">
              		<label>Nombres: </label>
					<input type="text" name="nombreEmpleadoN" class="form-control" value="${element.nombres}" required>
          		</div>

          		<div class="form-group col-xs-6">
          			<label>Apellidos: </label>
					<input type="text" name="apellidoEmpleadoN" class="form-control" value="${element.apellidos}" required>
				</div>

				<div class="form-group col-xs-6">
              		<label>Cédula: </label>
					<input type="text" name="cedulaEmpleadoN" class="form-control" value="${element.cedula}" required>
          		</div>

                <div class="form-group col-xs-6">
          			<label>Área: </label>
					<input type="text" name="areaEmpleadoN" class="form-control" value="${element.des_uni}" required>
				</div>

            <input type="hidden" name="cargoCodigo" class="form-control" value="${element.cod_car}" required>

             

</div>
				

<br> <br><br><br><br> <br><br><br>
          	
			

          		<div class="form-group col-md-12" id='vista_previa'>
          		<label style='color:#5183af; font-size:14px; font-family:Helvetica; font-weight:bold;'> Vista previa de impresi&oacute;n </label>
				</div>
					
					<div id='previo_carnet' style='border-radius:5px; margin:45px 5px 0 310px; border:1px solid #999; height:371px; width:233px; background:url(imagenes/${foto}); display:block; line-height:11px;display:block; line-height:11px; overflow: hidden;  background-size: 233px, 345px; background-repeat: no-repeat;'>
						<div align='center' style='padding-top:1px; height: 40px;'>
							<span style='font-size:10px; color:#47629f; display:none;'>Rep&uacute;blica Bolivariana de Venezuela</span>
							<span style='font-size:10px; color:#47629f; display:none;'>Ministerio del Poder Popular para la</span>
							<span style='font-size:10px; color:#47629f; display:none;'>Comunicaci&oacute;n y la Informaci&oacute;n</span>
							<span style='font-size:10px; color:#47629f; display:none;'>COVETEL. S.&nbsp;A.</span>
						</div>
						<div id='foto_info_previa' style='padding:38px 0 0px 5px'>
							<div id="rombo2" style="margin-top:77px;">
					        	<div class="ima">
					        		<img id='foto_carnet_previa' src='imagenes2/${perfil}'  style="width: 88%;"/>
					        	</div>
					        </div>
							<div id='info_previa' style='margin-top:145px; text-align: right;'>
								<span id='nombre_previo' style='font-size:13px; color:#000; display:block; font-weight: bold; line-height:12px; '>
									${element.nombres} 
								</span>
								<span style='font-size:12px; color:#000; display:block; padding-top:3px; font-weight: bold; line-height:12px;'>
									D.I.N&deg;:&nbsp;
									<span id='din_previo' style='font-size:12px; color:#000;'>
										${element.cedula}
									</span>
								</span>
								<span id='cargo_previo' style='font-size:9px; color:#000; display:block; padding-top:3px; line-height:12px;'>
									 ${element.des_car}
								</span>
								<span id='dpto_previo' style='font-size:9px; color:#000; display:block; padding-top:3px;line-height:12px;'>
									${element.des_uni}
								</span>
								
							</div>
						</div>
					</div>
				</div>

					<div id='vista_previa_Sello' style='border-radius:5px; margin:45px 5px 0 310px; border:1px solid #999; height:371px; width:233px; background:url(imagenes/${fotoPrensa}); display:block; line-height:11px;display:none; line-height:11px; overflow: hidden;  background-size: 233px, 345px; background-repeat: no-repeat;'>
						<div align='center' style='padding-top:1px; height: 40px;'>
							<span style='font-size:10px; color:#47629f; display:none;'>Rep&uacute;blica Bolivariana de Venezuela</span>
							<span style='font-size:10px; color:#47629f; display:none;'>Ministerio del Poder Popular para la</span>
							<span style='font-size:10px; color:#47629f; display:none;'>Comunicaci&oacute;n y la Informaci&oacute;n</span>
							<span style='font-size:10px; color:#47629f; display:none;'>COVETEL. S.&nbsp;A.</span>
						</div>
						<div id='foto_info_previa' style='padding:38px 0 0px 5px'>
							<div id="rombo2" style="margin-top:77px;">
					        	<div class="ima">
					        		<img id='foto_carnet_previa' src='imagenes2/${perfil}'  style="width: 88%;"/>
					        	</div>
					        </div>
							<div id='info_previa' style='margin-top:145px; text-align: right;'>
								<span id='nombre_previo' style='font-size:13px; color:#000; display:block; font-weight: bold; line-height:12px; '>
									${element.nombres}
								</span>
								<span style='font-size:12px; color:#000; display:block; padding-top:3px; font-weight: bold; line-height:12px;'>
									D.I.N&deg;:&nbsp;
									<span id='din_previo' style='font-size:12px; color:#000;'>
										${element.cedula}
									</span>
								</span>
								<span id='cargo_previo' style='font-size:9px; color:#000; display:block; padding-top:3px; line-height:12px;'>
									 ${element.des_car}
								</span>
								<span id='dpto_previo' style='font-size:9px; color:#000; display:block; padding-top:3px;line-height:12px;'>
									${element.des_uni}
								</span>

							</div>
						</div>
					</div>
				</div>

				<div class="modal-footer">
                <button type="button" data-dismiss=modal class="btn btn-warning">Cerrar</button>
                <input type="submit" name="registrar" value="Imprimir" class="btn" style="background-color:  #48c9b0; color: white;"/>
              </div>

			</form>`)


























//------------------------------------------------------------------------------------------
		    });




















				});

		    }

		});



      }

    });