var tiempo;
function prueba() {
	clear(tiempo)
	var codigo = $('.codigo').val();
	var ahora = moment().format('HH:mm:ss');
	console.log(codigo)
	$.get(`marcar_entrada/${codigo}`,function(response, usuario){
		if (response == 'si entrada') {
			swal({
		        title: "USTED YA MARCÓ UNA ENTRADA",
		        icon:  "warning",
     			timer: 3000,
     			button: false,
		    });
			$('.codigo').val('');
			$('.codigo').focus();
		}
		else if (response == 'si salida') {
			swal({
		        title: "USTED YA MARCÓ UNA SALIDA",
		        text:  "",
		        icon:  "warning",
     			timer: 3000,
     			button: false,
		    });
			$('.codigo').val('');
			$('.codigo').focus();
		}
		else if (response == 'error') {
			swal({
		        title: "EXISTE UN ERROR",
		        text:  "",
		        icon:  "warning",
     			timer: 3000,
     			button: false,
		    });
			$('.codigo').val('');
			$('.codigo').focus();
		}
		else if (response == 'inutilizado') {
			swal({
		        title: "CARNET INUTILIZADO",
		        text:  "",
		        icon:  "warning",
     			timer: 3000,
     			button: false,
		    });
			$('.codigo').val('');
			$('.codigo').focus();
		}
		else if (response == 'null') {
			swal({
		        title: "ESTE CARNET NO SE ENCUENTRA REGISTRADO",
		        text:  "",
		        icon:  "warning",
     			timer: 3000,
     			button: false,
		    });
			$('.codigo').val('');
			$('.codigo').focus();
		}
		else{
			response.forEach(element => {
				if (element.asi_entrada_hora == null || element.asi_entrada_hora != null && element.asi_salida_hora != null) {
					var colorb = '#B8125A';
					var accion = 'HORA DE SALIDA:';
					var sombra = '#310102';
					var mensaje = 'HASTA LUEGO';
				}
				else{
					colorb = '#73c48c';
					accion = 'HORA DE ENTRADA:';
					sombra = '#004600';
					mensaje = 'BIENVENIDO';
				}
				$('.block_foto').animate({
			        right: '0%',
			        opacity: '0.1',
			    });
			    $('#codigo').val('');
				$('.block_foto').empty();
				$('.block_foto').append(`
					<div class="panel" style="background-color: #c3b6d5 ;  border-radius: 6px; margin-bottom: 12px; width: 430px; height: 360px;">
				  		<div class="panel-body" style="padding: 8px;">
				    		<div class="default" style="height:96%;">
								<img src="imagenes2/${element.us_foto}" style="height: 68%;margin-top: 11%;border: 12px solid ${colorb};border-radius: 3px;" alt="">
								<br><b style="color: ${colorb};font-size: 40px;text-shadow: 2px 2px 2px ${sombra};">${mensaje}</b>
							</div>
				  		</div>
					</div>
					<div class="panel" style="background-color: #c3b6d5 ; border-radius: 10px;">
				  		<div class="panel-body" style="padding: 8px;">
				    		<div class="dataC">
					  			<b style="margin-top:100px;">
					  				${element.us_nom}
									${element.us_ape}<br>
									C.I.: ${element.us_ced}<br>
									${element.dp_nombre}<br><br>
									<b style="color: ${colorb};font-size: 40px;text-shadow: 2px 2px 2px ${sombra};">${accion} ${ahora} 
					  			</b>
					  		</div>
				  		</div>
					</div>
				`).animate({
			        left: '0%',
			        opacity: '1',
			    })
				$('.block_codigo').animate({
			        right: '0%',
			        opacity: '0',
			    });
			});
			tiempo = setTimeout(function() {
		    	$('.block_foto').animate({
			        right: '0%',
			        opacity: '0.1',
			    });
					$('.block_foto').empty();
					$('.block_foto').append(`
						<div class="panel" style="background-color:B8125A; border-radius: 6px; margin-bottom: 12px; width: 430px; height: 350px;">
				  		<div class="panel-body">
				    		<div class="default" >
						<img src="imagenes/boss.svg" style="height: 67%;margin-top: 11%;" alt="">
					</div>
				  		</div>
					</div>
					`).animate({
			        left: '0%',
			        opacity: '1',
			    })
				$('.block_codigo').animate({
			        right: '0%',
			        opacity: '5',
			    });
			    $('.codigo').focus();
		    }, 4000);
		}
	});
}

function clear (tiempo) {
	clearTimeout(tiempo);
}