//-----------------cargar datos de empleado para modificar carnets en busqueda de empleado--------------------------------

$(".buscaEmpConC").click(function(event){
	
	var id = $('#searchCarnetEmp').val();

	if (id == "") {

		$("#selloM").fadeOut("slow");
      	$("#motivoC").fadeOut("slow"); 
      	$("#fecha_vec_e").fadeOut("slow");     	
      	$("#empleadoCarnet").empty();
      	$("#empleadoCarnet").append(`<br><div class='bg-warning' style='padding: 20px'>Disculpe, debe ingresar una cédula..</div>`)

	}else{

	$.get(`busEmpConCar/${id}`, function(response, buscaEmpConC){
		console.log(response);		
		

		if (response == "" ) {

      		$("#selloM").fadeOut("slow");
      		$("#motivoC").fadeOut("slow");
      		$("#fecha_vec_e").fadeOut("slow"); 
      		$("#empleadoCarnet").empty();
      		$("#empleadoCarnet").append(`<br><div class='bg-warning' style='padding: 20px'>Disculpe, no se encontraron resultados...</div>`)
      		
   		}else{
   			
   			$("#empleadoCarnet").empty();
   			$("#selloM").fadeIn("slow");
      		$("#motivoC").fadeIn("slow");
      		$("#fecha_vec_e").fadeIn("slow"); 
      		$("#modalReportes").fadeIn("slow");
      		

		response.forEach(element=> {

			var cod_car = element.cod_car;
			
			var perfil = element.cedula+'.jpg';

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
			 

			$("#empleadoCarnet").append(`
			
				<input type="hidden" name="id" id="id" value="${element.cedula}">

				<div id="empleadoCarnet">

				<div class="form-group col-xs-4">
              		<label>Cédula: </label>
					<input type="text" name="cedulaEmpleado" class="form-control" value="${element.cedula}" required>
          		</div>

          		<div class="form-group col-xs-4">
              		<label>Nombres: </label>
					<input type="text" name="nombreEmpleado" class="form-control" value="${element.nombres}" required>
          		</div>

          		<div class="form-group col-xs-4">
          			<label>Apellidos: </label>
					<input type="text" name="apellidoEmpleado" class="form-control" value="${element.apellidos}" required>
				</div>

				<div class="form-group col-xs-8">
          			<label>Área: </label>
					<input type="text" name="areaEmpleado" class="form-control" value="${element.des_uni}" required>
				</div>

			</div>
					</div>
				</div>
			</div>

			</form>

			`)
		

			$.get(`vistasDatobusEmpConCar/${id}`, function(response, buscaEmpConC){

					console.log(response);

				response.forEach(element=> {

					var status = element.status;

					if (status == 1) {
						var status = "Activo";
					}else{
						var status = "Inactivo";
					}

					$("#empleadoCarnet").append(`

						<div class="form-group col-xs-4">
			          			<label>Estatus: </label>
								<input type="text" name="status" class="form-control" value="${status}" required>
							</div>

							<div class="form-group col-xs-6">
			          			<label>Descripción: </label>
								<input type="text" name="descripcion" class="form-control" value="${element.descripcion}" required>
							</div>	

							<div class="form-group col-xs-6" id="fecha_vec_">
			              	 <label for="date">Fecha de Vencimiento: </label>
			                  <div class="input-group col-lg-12">
			                    <input type="date" class="form-control datepicker" name="empleadoFechaVecim" id="empleadoFechaVec" value="${element.fecha}"  >
			                        <div class="input-group-addon">
			                            <span class="glyphicon glyphicon-th"></span>
			                        </div>
			                  </div>
			          		</div>

			          		<div class="form-group col-xs-6">
								<input type="hidden" name="motivoRp" class="form-control" value="${element.motivoRe}" required>
							</div>

						`)
							
					});

				});


			$.get(`busEmpConCar/${id}`, function(response, buscaEmpConC){
	console.log(response);	
response.forEach(element=> {

			$("#empleadoCarnet").append(`
			<div class="form-group col-md-12" >
          		<label style='color:#5183af; font-size:14px; font-family:Helvetica; font-weight:bold;'> Vista previa de impresi&oacute;n </label>
             </div> 
          		         		
				
				<div id='previo_carnetM' style='border-radius:5px; margin:45px 5px 0 310px; border:1px solid #999; height:371px; width:233px; background:url(imagenes/${foto}); display:block; line-height:11px;display:block; line-height:11px; overflow: hidden;  background-size: 233px, 345px; background-repeat: no-repeat;'>
						<div align='center' style='padding-top:1px; height: 40px;'>
							<span style='font-size:10px; color:#47629f; display:none;'>Rep&uacute;blica Bolivariana de Venezuela</span>
							<span style='font-size:10px; color:#47629f; display:none;'>Ministerio del Poder Popular para la</span>
							<span style='font-size:10px; color:#47629f; display:none;'>Comunicaci&oacute;n y la Informaci&oacute;n</span>
							<span style='font-size:10px; color:#47629f; display:none;'>COVETEL. S.&nbsp;A.</span>
						</div>
						<div id='foto_info_previa' style='padding:38px 0 0px 5px'>
							<div id="rombo2" style="margin-top:103px;">
					        	<div class="ima">
					        		<img id='foto_carnet_previa' src='imagenes2/${perfil}'  style="width: 88%;"/>
					        	</div>
					        </div>
							<div id='info_previa' style='margin-top:144px; text-align: right;'>
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







				<div id='vista_previa_SelloM' style='border-radius:5px; margin:45px 5px 0 310px; border:1px solid #999; height:371px; width:233px; background:url(imagenes/${fotoPrensa}); display:block; line-height:11px;display:none; line-height:11px; overflow: hidden;  background-size: 233px, 345px; background-repeat: no-repeat;'>
						<div align='center' style='padding-top:1px; height: 40px;'>
							<span style='font-size:10px; color:#47629f; display:none;'>Rep&uacute;blica Bolivariana de Venezuela</span>
							<span style='font-size:10px; color:#47629f; display:none;'>Ministerio del Poder Popular para la</span>
							<span style='font-size:10px; color:#47629f; display:none;'>Comunicaci&oacute;n y la Informaci&oacute;n</span>
							<span style='font-size:10px; color:#47629f; display:none;'>COVETEL. S.&nbsp;A.</span>
						</div>
						<div id='foto_info_previaS' style='padding:38px 0 0px 5px'>
							<div id="rombo2" style="margin-top:102px;">
					        	<div class="ima">
					        		<img id='foto_carnet_previa' src='imagenes2/${perfil}'  style="width: 88%;"/>
					        	</div>
					        </div>
							<div id='info_previaS' style='margin-top:144px; text-align: right;'>
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

				<br><br>

				<!--a class="verCarnet" fotoCarnet="${element.cedula}" href="#" data-toggle="modal" data-target="#vistaCarnet" id="modalReportes"> 
                       Reportar-Reimprimir Solamente <img src="assets/img/iconos/editar.svg" class="imgmenuho" >
                </a-->
            
			`)
$(".verCarnet").click(function(event){
	var id = $(this).attr("fotoCarnet");

	$.get(`vistasDato/${id}`, function(response, verCarnet){
			console.log(response);
			$("#getCarnet").empty();
			$("#vistaPreviaCarnet").empty();
			$("#fondoCarnet").empty();
			response.forEach(element=> {

			var status = element.status;

			var sello = element.sello;

			if (status == 1) {
				var status = "Activo";
			}else{
				var status = "Inactivo";
			}

	$.get(`vistaDatos/${id}`, function(response, verCarnet){
		console.log(response);
		$("#getCarnet").empty();
			$("#vistaPreviaCarnet").empty();
			$("#fondoCarnet").empty();
		response.forEach(element=> {

			var cod_car = element.cod_car;

			var perfil = element.cedula+'.jpg';


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
					(cod_car == '0000000270') || (cod_car == '0000000386')){  //Cordinador Tec - Ing -Transporte - Prgoramacion - Prducc I -Serv a la P - Andes / Andes (E) - Oriente - Planificacion - Finanzas - Admin y cont - Serv G - Talento humano - Seguridad - Mercadeo - Img y Prod - Comunicacion P -Centro Occ - Occidente - Orinoco - Llanos- Despacho
				fondo = (sello == 'Si') ? "imagenes/COORDINADOR-PRENSA.jpg" : "imagenes/COORDINADOR.jpg";

			}else if((cod_car == '0000000200') || (cod_car == '0000000399') || 
				(cod_car == '0000000391')){//Vicepresidentes {
			var fondo = (sello == 'Si') ? "imagenes/PRESIDENCIA-PRENSA.jpg" : "imagenes/PRESIDENCIA.jpg";	
		
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
				){   //VPGT - Tec - Ing -comunicaciones - operaciones tecnicas -servcios a la produccion
					var	fondo = (sello == 'Si') ? "imagenes/VPGT-PRENSA.jpg" : "imagenes/VPGT.jpg";

			}else if((cod_uni == '0000-00-07-00-00') || (cod_uni == '0000-00-03-02-00') || 
				(cod_uni == '0000-00-03-02-01') || (cod_uni == '0000-00-03-01-00') || 
				(cod_uni == '0000-00-03-01-01') || (cod_uni == '0000-00-03-05-00') || 
				(cod_uni == '0000-00-03-04-00') || (cod_uni == '0000-00-03-00-00') ||
				(cod_uni == '0000-00-06-00-00')
				){
				//VPGP - Programacion - Produccion I - Img y Prodc - Comunicacion Popular - 123Tv -Orinoco - Ficcion y animacion
			var	fondo = (sello == 'Si') ? "imagenes/VPGP-PRENSA.jpg" : "imagenes/VPGP.jpg";

		
			}else if ((cod_uni == '0000-00-02-01-00') || (cod_uni == '0000-00-02-02-00') || 
					(cod_uni == '0000-00-02-05-00') || (cod_uni == '0000-00-02-03-00') || 
					(cod_uni == '0000-00-02-04-00') || (cod_uni == '0000-00-01-03-01') || 
					(cod_uni == '0000-00-01-03-00') || (cod_uni == '0000-00-02-00-00')){ //VPGI -planificacion - Admin y Cont - Finanzas - Servicios G -Mercadeo y Asuntos P - Talento Humano - Talento Humanos Pensionados y Jubilados
					var	fondo = (sello == 'Si') ? "imagenes/VPGI-PRENSA.jpg" : "imagenes/VPGI.jpg";

		
			}/*else if (cod_uni == '0000-00-01-06-00'){ // Seguridad
					var	fondo = (sello == 'Si') ? "imagenes/SEGURIDAD-PRENSA.jpg" : "imagenes/SEGURIDAD.jpg";

		
			}*/else if ((cod_uni == '0000-00-01-00-00') || (cod_uni == '0000-00-01-01-00') || 
					(cod_uni == '0000-00-01-02-00') || (cod_uni == '0000-00-01-02-01') || 
					(cod_uni == '0000-00-01-04-00') || (cod_uni == '0000-00-01-05-00') ||
					(cod_uni == '0000-00-01-06-00') || (cod_uni == '0000-00-04-03-00') ||
					(cod_uni == '0000-00-01-06-00') || (cod_uni == '0000-00-30-00-11')
					){ //Presidencia - Despacho - Atencio al C - Consultoria J - Aduditoria I
					var	fondo = (sello == 'Si') ? "imagenes/PRESIDENCIA-PRENSA.jpg" : "imagenes/PRESIDENCIA.jpg";				
			}
//	}

		



		$("#getCarnet").append(`

			<div class="form-group col-xs-6">
          			<label>Estatus: </label>
					<input type="text" name="statusR" class="form-control" value="${status}" required>
				</div>

			<div class="form-group col-xs-6">
          			
					<input type="hidden" name="motivoRep" class="form-control" value="${element.motivoR}" required>
				</div>

			<div class="form-group col-xs-4" id="fecha_vec_">
                  <div class="input-group col-lg-12">
                    <input type="hidden" class="form-control datepicker" name="empleadoFechaVecim" id="empleadoFechaVec" value="${element.fecha}"  >
                  </div>
          	</div>



          	<input type="hidden" name="id" id="id" value="${element.cedula}">

				

				<div class="form-group col-xs-4">
					<input type="hidden" name="cedulaEmpleado" class="form-control" value="${element.cedula}" required>
          		</div>

          		<div class="form-group col-xs-4">
					<input type="hidden" name="nombreEmpleado" class="form-control" value="${element.nombres}" required>
          		</div>

          		<div class="form-group col-xs-4">
					<input type="hidden" name="apellidoEmpleado" class="form-control" value="${element.apellidos}" required>
				</div>

				

          		<div class="form-group col-xs-8">
					<input type="hidden" name="areaEmpleado" class="form-control" value="${element.des_uni}" required>
				</div>

			`)

		$("#fondoCarnet").append(`

		<div id="vistaPreviaCarnet">

				<div class="form-group col-md-12" >
          		<label style='color:#5183af; font-size:14px; font-family:Helvetica; font-weight:bold;'> Vista previa </label>
             </div> 
					
				<div id='vista_previa'>

					<div id='previo_carnetV' style='border-radius:5px; margin:10px 5px 0 160px; border:1px solid #999; height:372px; width:233px; background:url(${fondo}); display:block; line-height:11px;display:block; line-height:11px; overflow: hidden;  background-size: 233px, 372px; background-repeat: no-repeat;'>
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
							<div id='info_previa' style='margin-top:16px; text-align: right;'>
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

				</div>
					</div>
				</div>




				</div>

				`)

		
		});
	});
		});
	});
});


});

				});

	
		});

	}
	
});

}

});


$("#modalReportes").click(function(){

   $('#editaEmpl').modal('toggle');

});




function isDate(empleadoFechaVecim) { 
    var objDate,  // date object initialized from the ExpiryDate string 
        mSeconds, // ExpiryDate in milliseconds 
        day,      // day 
        month,    // month 
        year;     // year 
    // date length should be 10 characters (no more no less) 
    if (empleadoFechaVecim.length !== 10) { 
        return false; 
    } 
    // third and sixth character should be '/' 
    if (empleadoFechaVecim.substring(2, 3) !== '/' || empleadoFechaVecim.substring(5, 6) !== '/') { 
        return false; 
    } 
    // extract month, day and year from the ExpiryDate (expected format is mm/dd/yyyy) 
    // subtraction will cast variables to integer implicitly (needed 
    // for !== comparing) 

    //day = ExpiryDate.substring(3, 5) - 0; 
    day = empleadoFechaVecim.substring(0, 2) - 0;

    month = empleadoFechaVecim.substring(3, 5) - 1; // because months in JS start from 0 

	//month = ExpiryDate.substring(0, 2) - 1;

    year = empleadoFechaVecim.substring(6, 10) - 0; 
    // test year range 
    if (year < 1000 || year > 3000) { 
        return false; 
    } 
    // convert ExpiryDate to milliseconds 
    mSeconds = (new Date(year, month, day)).getTime(); 
    // initialize Date() object from calculated milliseconds 
    objDate = new Date(); 
    objDate.setTime(mSeconds); 
    // compare input date and parts from Date() object 
    // if difference exists then date isn't valid 
    if (objDate.getFullYear() !== year || 
        objDate.getMonth() !== month || 
        objDate.getDate() !== day) { 
        return false; 
    } 
    // otherwise return true 
    return true; 
}


$("#empleadoFechaVecim").keyup(function(e){

	var fecha = document.getElementById('empleadoFechaVecim').value; 

	var total = fecha.length; 

	var d = new Date();

	var month = d.getMonth()+1;
	var day = d.getDate();

	var output = d.getFullYear() + '/' +
	    ((''+month).length<2 ? '0' : '') + month + '/' +
	    ((''+day).length<2 ? '0' : '') + day; // fecha actual

	var fechaActual = (moment(output).format('DD/MM/YYYY'));


if (isDate(fecha)) { 

	var dateActual = fechaActual.split("/");
	var dateVec = fecha.split("/");

	if ((dateVec[1] < dateActual[1]) && (dateVec[2] < dateActual[2])){

		$("#empleadoCarnet").append(`<br>
			<div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            Disculpe, fecha menor a la actual...</div>`)

		$('#empleadoFechaVecim').val("");

	}else if ((dateVec[1] == dateActual[1]) && (dateVec[2] == dateActual[2])){
		$("#empleadoCarnet").append(`<br>
			<div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            Disculpe, el mes o año no deben ser igual al actual...</div>`)

		$('#empleadoFechaVecim').val("");

	}else if ((dateVec[1] <= dateActual[1]) && (dateVec[2] == dateActual[2])){
		$("#empleadoCarnet").append(`<br>
			<div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            Disculpe, el año no debe ser igual al actual...</div>`)

		$('#empleadoFechaVecim').val("");
	}else if ((dateVec[1] >= dateActual[1]) && (dateVec[2] < dateActual[2])){
		$("#empleadoCarnet").append(`<br>
			<div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            Disculpe, el año es menor al actual...</div>`)

		$('#empleadoFechaVecim').val("");
	}

	}else{

		if (total == 10) {
			$("#empleadoCarnet").append(`<br><div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            Disculpe, la fecha no es válida...</div>`)

            $('#empleadoFechaVecim').val("");
		}else if (total > 10) {
			$("#empleadoCarnet").append(`<br><div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            Disculpe, la fecha no es válida...</div>`)

            $('#empleadoFechaVecim').val("");
		}

	}

});


