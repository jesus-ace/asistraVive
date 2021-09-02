$(".verCarnetHis").click(function(event){

	var codigo = $(this).attr("codigoCarnet");

	$.get(`listPasante/${codigo}`, function(response, editPasante){
		console.log(response);
		$("#vistaHistory").empty();

		if (response == "" ) {

          $("#vistaHistory").append(`<br>
            <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            Disculpe, no se encontraron resultados... Este carnet aún no posee registros </div>`)          
      
      }else{
				response.forEach(element=> {

					var fotoPerfil = 'usericonos.JPG'; 

					var foto = "PROVISIONAL.jpg";
					//var fotoPerfil = element.cedula+'.jpg';

					$("#vistaHistory").append(`
						<div class="panel panel-default" style="">
						<div class="panel-heading"  style="background-color: #e5e8e8;">   <!-- Default panel contents -->
						      <label>Carnet Provisional</label>
						  </div>
						  <table class="table table-striped table-hover table-bordered table-condensed" style="" >
  <thead style='background: #f2f3f4  repeat-x center top; font-size:14px;'>
						<tr>
						<th>Foto</th>
						<th>Nombres</th>       
				        <th>Apellidos</th>
				        <th>Cédula</th>
				        <th>Departamento</th>
				        <th>Fecha</th>
				      </tr>

				    </thead>
				    <tbody id="historicosProv">
				     <?php foreach ($datosProv as $historico):?>
				      <tr>
              <td width='30px'><img id='foto' src='imagenes/${fotoPerfil}'  width='50px' height='50px' /></td>
				        <td>${element.nombres}</td>
				        <td>${element.apellidos}</td>
				        <td>${element.cedula}</td>
				        <td>${element.des_uni}</td>
				        <td>${element.fecha}</td>
				      </tr>
				        </tbody>
  
  </table>













  <div class="form-group col-md-12" >
          		<label style='color:#5183af; font-size:14px; font-family:Helvetica; font-weight:bold;'> Carnet Provisional </label>
             </div> 
          		         		
				
				<div id='previo_carnetM' style='border-radius:5px; margin:45px 5px 0 172px; border:1px solid #999; height:371px; width:233px; background:url(imagenes/${foto}); display:block; line-height:11px;display:block; line-height:11px; overflow: hidden;  background-size: 233px, 345px; background-repeat: no-repeat;'>

						<div id='foto_info_previa' style='padding:38px 0 0px 5px'>

							<div id='info_previa' style='margin-top:16px; text-align: left;'> 
								<span id='area' style='font-size:100px; color:#31488E; display:block; padding-top:52px;'>
								 ${element.nro}
								</span>
							</div>
						</div>
					</div>
				</div>























  </div>
						`)

		});
		}
	});

});





$("#totalSerial").click(function(){

	var total = $("#totalSerial").val();

	if (total == 'NO') {
		$("#totales").fadeOut("slow");
	}else{
		$("#totales").fadeIn("slow");
	}


});