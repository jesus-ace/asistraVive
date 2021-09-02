
$(document).ready(function(){
    $.get(`totalVencerse`,function(response){
        var vencerseC = response;
        $.get(`totalRobados`,function(response){
          var robadosC = response;
          $.get(`totalHurtados`,function(response){
            var hurtadosC = response;
            $.get(`totalExtraviados`,function(response){
              var extraviadosC = response;
              $.get(`totalVencidos`,function(response){
                var vencidosC = response;
                $.get(`totalSinCarnets`,function(response){
                  var SinC = response;
        //alert(SinC);
                  var densityCanvas = document.getElementById("densityChart");

                  Chart.defaults.global.defaultFontFamily = "Lato";
                  Chart.defaults.global.defaultFontSize = 18;

                  var densityData = {
                   // label: 'Carnets',
                    data: [vencerseC, robadosC, hurtadosC, extraviadosC, vencidosC, SinC],
                    backgroundColor: [
                      'rgba(0, 99, 132, 0.6)',
                      'rgba(30, 99, 132, 0.6)',
                    //  'rgba(60, 99, 132, 0.6)',
                      'rgba(90, 99, 132, 0.6)',
                      'rgba(120, 99, 132, 0.6)',
                      'rgba(150, 99, 132, 0.6)',
                      'rgba(180, 99, 132, 0.6)',
                     // 'rgba(210, 99, 132, 0.6)',
                      'rgba(240, 99, 132, 0.6)'
                    ],
                    borderColor: [
                      'rgba(0, 99, 132, 1)',
                      'rgba(30, 99, 132, 1)',
                    //  'rgba(60, 99, 132, 1)',
                      'rgba(90, 99, 132, 1)',
                      'rgba(120, 99, 132, 1)',
                      'rgba(150, 99, 132, 1)',
                      'rgba(180, 99, 132, 1)',
                     // 'rgba(210, 99, 132, 1)',
                      'rgba(240, 99, 132, 1)'
                    ],
                    borderWidth: 2,
                    hoverBorderWidth: 0
                  };

                  var chartOptions = {
                    scales: {
                      xAxes: [{
                        stacked: true,
                      ticks: {
                          beginAtZero: true
                      }
                  }],
                      yAxes: [{
                        stacked: true,
                        barPercentage: 0.5
                      }]
                    },
                    responsive: false,
                    legend: {
                        display: false,
                    },
                    title: {
                        display: true,
                        text: 'Estadísticas'
                    },
                    elements: {
                      rectangle: {
                        borderSkipped: 'left',
                      }
                    },
                  };

                  var barChart = new Chart(densityCanvas, {
                    
                    type: 'horizontalBar',
                    data: {
                      labels: ["Por Vencer", "Robados", "Hurtados", "Extravíados", "Vencidos", "Sin Carnet"],
                      datasets: [densityData],
                    },
                    options: chartOptions
                });

              });

            });

          });

        });

      });

    });

});



























// -------------------Por vencerse-------------------------------------------------

$(".verMas").click(function(event){

$.get(`porVencerCarnet`, function(response, verMas){
  console.log(response);
    $("#carnetPorVencerse").empty();
    if (response == "") {
       $("#carnetPorVencerse").append(`<br><div class='bg-warning' style='padding: 20px'>Disculpe, no se encontraron resultados...</div>`)
    }else{
    response.forEach(element=> {
      var perfil = element.cedula+'.jpg';
      $("#carnetPorVencerse").append(`
        
        
              <table class="table table-striped table-hover table-bordered table-condensed" style="" >
                <thead style='background: #f2f3f4  repeat-x center top; font-size:14px;'>
            <tr>
            <th>Foto</th>
            <th>Nombres</th>       
                <th>Apellidos</th>
                <th>Cédula</th>
                <th>Departamento</th>
              </tr>

            </thead>
            <tbody id="">
             <?php foreach ($empleados as $vencerse):?>
              <tr>
              <td width='30px'><img id='foto' src='imagenes/${perfil}'  width='50px' height='50px' /></td>
                <td>${element.nombres}</td>
                <td>${element.apellidos}</td>
                <td>${element.cedula}</td>
                <td>${element.des_uni}</td>
              </tr>
                </tbody>
  
  </table>

  </div>
      `)
    });
  }
  });
});



// modal de empleado con info de carnet por vencerse

$(".editEmpleadoPV").click(function(event){
  var id = $(this).attr("empleadoIdPV");
  $.get(`editarEmpleadoPV/${id}`, function(response, editEmpleadoPV){
    console.log(response);

    $("#empleadoCarnet").empty();

    $("#selloM").fadeIn("slow");
    $("#motivoC").fadeIn("slow"); 
     $("#porVencerBtn").fadeIn("slow");
     $("#fecha_vec_e").fadeIn("slow"); 

    response.forEach(element=> {

      var cod_car = element.cod_car;
      var perfil = element.cedula+'.jpg';

        if((cod_car == '0000000407') || (cod_car == '0000000431') || 
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

      }else{

                var cod_uni = element.cod_uni;

                if((cod_uni == '0000-00-04-00-00') || (cod_uni == '0000-00-04-01-00') || 
        (cod_uni == '0000-00-04-03-00') || (cod_uni == '0000-00-04-01-01') || 
        (cod_uni == '0000-00-04-05-00')){ 

        var foto = 'VPGT.jpg';
        var fotoPrensa = 'VPGT-PRENSA.jpg';

      }else if((cod_uni == '0000-00-03-02-00') || (cod_uni == '0000-00-03-01-00') || 
        (cod_uni == '0000-00-03-05-00') || (cod_uni == '0000-00-04-04-00') || 
        (cod_uni == '0000-00-03-04-00') || (cod_uni == '0000-00-03-00-05') || 
        (cod_uni == '0000-00-03-00-04') || ( cod_uni == '0000-00-03-00-06') || 
        (cod_uni == '0000-00-03-00-02') || (cod_uni == '0000-00-03-00-01') || 
        (cod_uni == '0000-00-03-00-00') || (cod_uni == '0000-00-07-00-00') || 
        (cod_uni == '0000-00-03-00-03') || (cod_uni == '0000-00-03-01-01')){
        
          var foto = 'VPGP.jpg';
          var fotoPrensa = 'VPGP-PRENSA.jpg';

      }else if ((cod_uni == '0000-00-02-01-00') || (cod_uni == '0000-00-02-02-00') || 
          (cod_uni == '0000-00-02-05-00') || (cod_uni == '0000-00-02-03-00') || 
          (cod_uni == '0000-00-02-04-00') || (cod_uni == '0000-00-01-03-01') || 
          (cod_uni == '0000-00-01-03-00') || (cod_uni == '0000-00-02-00-00')){
        
            var foto = 'VPGI.jpg';
            var fotoPrensa = 'VPGI-PRENSA.jpg';

        }else if (cod_uni == '0000-00-01-06-00'){

          var foto = 'SEGURIDAD.jpg';
          var fotoPrensa = 'SEGURIDAD-PRENSA.jpg';

        }else if ((cod_uni == '0000-00-01-01-00') || (cod_uni == '0000-00-01-00-00') || 
          (cod_uni == '0000-00-01-02-00') || (cod_uni == '0000-00-01-05-00') || 
          (cod_uni == '0000-00-01-04-00')){ 

          var foto = 'PRESIDENCIA.jpg';
          var fotoPrensa = 'PRESIDENCIA-PRENSA.jpg';
        }

      }
       
    

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

        <div class="form-group col-xs-4" id="fecha_vec_e">
                 <label for="date">Fecha de Vencimiento: </label>
                  <div class="input-group col-lg-12">
                    <input type="date" class="form-control datepicker" name="empleadoFechaVecOld" id="empleadoFechaVec" value="${element.fecha}"  >
                        <div class="input-group-addon">
                            <span class="glyphicon glyphicon-th"></span>
                        </div>
                  </div>
              </div>

        <div class="form-group col-xs-8">
                <label>Área: </label>
          <input type="text" name="areaEmpleado" class="form-control" value="${element.des_uni}" required>
        </div>


      </div>
          </div>
        </div>
      </div>


      


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
              <div id='foto_previa' style="width: 115px; overflow: hidden; margin-left: 7px; margin-top: 15px;">
                <img id='foto_carnet_previa' src='imagenes/${perfil}'  width='115' height='87' />
              </div>
              <div id='info_previa' style='margin-top:16px; text-align: left;'>
                <span id='nombre_previo' style='font-size:13px; color:#000; display:block; font-weight: bold; line-height:12px; '>
                  NOMBRES ${element.nombres} ${element.apellidos}
                </span>
                <span style='font-size:12px; color:#000; display:block; padding-top:3px; font-weight: bold; line-height:12px;'>
                  D.I.N&deg;:&nbsp;
                  <span id='din_previo' style='font-size:12px; color:#000;'>
                    C&Eacute;DULA ${element.cedula}
                  </span>
                </span>
                <span id='cargo_previo' style='font-size:9px; color:#000; display:block; padding-top:3px; line-height:12px;'>
                  CARGO ${element.des_car}
                </span>
                <span id='dpto_previo' style='font-size:9px; color:#000; display:block; padding-top:3px;line-height:12px;'>
                  COORDINACI&Oacute;N ${element.des_uni}
                </span>
                <span id='area' style='font-size:9px; color:#000; display:block; padding-top:3px;'>
                  UNIDAD DE &Aacute;DSCRIPCION ${element.des_uni}
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
              <div id='foto_previaS' style="width: 115px; overflow: hidden; margin-left: 7px; margin-top: 15px;">
                <img id='foto_carnet_previa' src='imagenes/${perfil}'  width='115' height='87' />
              </div>
              <div id='info_previaS' style='margin-top:16px; text-align: left;'>
                <span id='nombre_previo' style='font-size:13px; color:#000; display:block; font-weight: bold; line-height:12px; '>
                  NOMBRES ${element.nombres} ${element.apellidos}
                </span>
                <span style='font-size:12px; color:#000; display:block; padding-top:3px; font-weight: bold; line-height:12px;'>
                  D.I.N&deg;:&nbsp;
                  <span id='din_previo' style='font-size:12px; color:#000;'>
                    C&Eacute;DULA ${element.cedula}
                  </span>
                </span>
                <span id='cargo_previo' style='font-size:9px; color:#000; display:block; padding-top:3px; line-height:12px;'>
                  CARGO ${element.des_car}
                </span>
                <span id='dpto_previo' style='font-size:9px; color:#000; display:block; padding-top:3px;line-height:12px;'>
                  COORDINACI&Oacute;N ${element.des_uni}
                </span>
                <span id='area' style='font-size:9px; color:#000; display:block; padding-top:3px;'>
                  UNIDAD DE &Aacute;DSCRIPCION ${element.des_uni}
                </span>
              </div>
            </div>
          </div>
        </div>

      </form>`)
    });
  });
});




$("#selloPrensaM").click(function(){

  var sello = $("#selloPrensaM").val();

  if (sello == "Si") {

    $("#previo_carnetM").fadeOut("slow");
    $("#vista_previa_SelloM").fadeIn("slow");

  }else{

    $("#vista_previa_SelloM").fadeOut("slow");
    $("#previo_carnetM").fadeIn("slow");

  }

});

// busqueda de empleado con carnet por vencerse
//-----------------cargar datos de empleado para modificar carnets en busqueda de empleado--------------------------------

$(".searchPorVencerse").click(function(event){
  
  var cedula = $('#buscaVencerse').val();

  if (cedula == "") {

    $("#selloM").fadeOut("slow");
    $("#motivoC").fadeOut("slow");   
    $("#porVencerBtn").fadeOut("slow"); 
    $("#fecha_vec_e").fadeOut("slow");       
    $("#empleadoCarnet").empty();
    $("#empleadoCarnet").append(`<br><div class='bg-warning' style='padding: 20px'>Disculpe, debe ingresar una cédula..</div>`)

  }else{
  $.get(`busCarPorVencerse/${cedula}`, function(response, searchPorVencerse){
    console.log(response);    
    $("#empleadoCarnet").empty();

    if (response == "" ) {

          $("#selloM").fadeOut("slow");
          $("#motivoC").fadeOut("slow");
          $("#porVencerBtn").fadeOut("slow"); 
          $("#fecha_vec_e").fadeOut("slow"); 
          $("#empleadoCarnet").append(`<br><div class='bg-warning' style='padding: 20px'>Disculpe, no se encontraron resultados...</div>`)
          
      }else{
        
          $("#selloM").fadeIn("slow");
          $("#motivoC").fadeIn("slow");
          $("#porVencerBtn").fadeIn("slow"); 
          $("#fecha_vec_e").fadeIn("slow"); 
    response.forEach(element=> {

      var cod_car = element.cod_car;
      var perfil = element.cedula+'.jpg';

        if((cod_car == '0000000407') || (cod_car == '0000000431') || 
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

      }else{

                var cod_uni = element.cod_uni;

                if((cod_uni == '0000-00-04-00-00') || (cod_uni == '0000-00-04-01-00') || 
        (cod_uni == '0000-00-04-03-00') || (cod_uni == '0000-00-04-01-01') || 
        (cod_uni == '0000-00-04-05-00')){ 

        var foto = 'VPGT.jpg';
        var fotoPrensa = 'VPGT-PRENSA.jpg';

      }else if((cod_uni == '0000-00-03-02-00') || (cod_uni == '0000-00-03-01-00') || 
        (cod_uni == '0000-00-03-05-00') || (cod_uni == '0000-00-04-04-00') || 
        (cod_uni == '0000-00-03-04-00') || (cod_uni == '0000-00-03-00-05') || 
        (cod_uni == '0000-00-03-00-04') || ( cod_uni == '0000-00-03-00-06') || 
        (cod_uni == '0000-00-03-00-02') || (cod_uni == '0000-00-03-00-01') || 
        (cod_uni == '0000-00-03-00-00') || (cod_uni == '0000-00-07-00-00') || 
        (cod_uni == '0000-00-03-00-03') || (cod_uni == '0000-00-03-01-01')){
        
          var foto = 'VPGP.jpg';
          var fotoPrensa = 'VPGP-PRENSA.jpg';

      }else if ((cod_uni == '0000-00-02-01-00') || (cod_uni == '0000-00-02-02-00') || 
          (cod_uni == '0000-00-02-05-00') || (cod_uni == '0000-00-02-03-00') || 
          (cod_uni == '0000-00-02-04-00') || (cod_uni == '0000-00-01-03-01') || 
          (cod_uni == '0000-00-01-03-00') || (cod_uni == '0000-00-02-00-00')){
        
            var foto = 'VPGI.jpg';
            var fotoPrensa = 'VPGI-PRENSA.jpg';

        }else if (cod_uni == '0000-00-01-06-00'){

          var foto = 'SEGURIDAD.jpg';
          var fotoPrensa = 'SEGURIDAD-PRENSA.jpg';

        }else if ((cod_uni == '0000-00-01-01-00') || (cod_uni == '0000-00-01-00-00') || 
          (cod_uni == '0000-00-01-02-00') || (cod_uni == '0000-00-01-05-00') || 
          (cod_uni == '0000-00-01-04-00')){ 

          var foto = 'PRESIDENCIA.jpg';
          var fotoPrensa = 'PRESIDENCIA-PRENSA.jpg';
        }

      }
       


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

        <div class="form-group col-xs-4" id="fecha_vec_e">
                 <label for="date">Fecha de Vencimiento: </label>
                  <div class="input-group col-lg-12">
                    <input type="date" class="form-control datepicker" name="empleadoFechaVecOld" id="empleadoFechaVec" value="${element.fecha}"  >
                        <div class="input-group-addon">
                            <span class="glyphicon glyphicon-th"></span>
                        </div>
                  </div>
              </div>

        <div class="form-group col-xs-8">
                <label>Área: </label>
          <input type="text" name="areaEmpleado" class="form-control" value="${element.des_uni}" required>
        </div>


      </div>
          </div>
        </div>
      </div>

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
              <div id='foto_previa' style="width: 115px; overflow: hidden; margin-left: 7px; margin-top: 15px;">
                <img id='foto_carnet_previa' src='imagenes/${perfil}'  width='115' height='87' />
              </div>
              <div id='info_previa' style='margin-top:16px; text-align: left;'>
                <span id='nombre_previo' style='font-size:13px; color:#000; display:block; font-weight: bold; line-height:12px; '>
                  NOMBRES ${element.nombres} ${element.apellidos}
                </span>
                <span style='font-size:12px; color:#000; display:block; padding-top:3px; font-weight: bold; line-height:12px;'>
                  D.I.N&deg;:&nbsp;
                  <span id='din_previo' style='font-size:12px; color:#000;'>
                    C&Eacute;DULA ${element.cedula}
                  </span>
                </span>
                <span id='cargo_previo' style='font-size:9px; color:#000; display:block; padding-top:3px; line-height:12px;'>
                  CARGO ${element.des_car}
                </span>
                <span id='dpto_previo' style='font-size:9px; color:#000; display:block; padding-top:3px;line-height:12px;'>
                  COORDINACI&Oacute;N ${element.des_uni}
                </span>
                <span id='area' style='font-size:9px; color:#000; display:block; padding-top:3px;'>
                  UNIDAD DE &Aacute;DSCRIPCION ${element.des_uni}
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
              <div id='foto_previaS' style="width: 115px; overflow: hidden; margin-left: 7px; margin-top: 15px;">
                <img id='foto_carnet_previa' src='imagenes/${perfil}'  width='115' height='87' />
              </div>
              <div id='info_previaS' style='margin-top:16px; text-align: left;'>
                <span id='nombre_previo' style='font-size:13px; color:#000; display:block; font-weight: bold; line-height:12px; '>
                  NOMBRES ${element.nombres} ${element.apellidos}
                </span>
                <span style='font-size:12px; color:#000; display:block; padding-top:3px; font-weight: bold; line-height:12px;'>
                  D.I.N&deg;:&nbsp;
                  <span id='din_previo' style='font-size:12px; color:#000;'>
                    C&Eacute;DULA ${element.cedula}
                  </span>
                </span>
                <span id='cargo_previo' style='font-size:9px; color:#000; display:block; padding-top:3px; line-height:12px;'>
                  CARGO ${element.des_car}
                </span>
                <span id='dpto_previo' style='font-size:9px; color:#000; display:block; padding-top:3px;line-height:12px;'>
                  COORDINACI&Oacute;N ${element.des_uni}
                </span>
                <span id='area' style='font-size:9px; color:#000; display:block; padding-top:3px;'>
                  UNIDAD DE &Aacute;DSCRIPCION ${element.des_uni}
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
            Disculpe, el mes o año no deben ser igual al presente...</div>`)

    $('#empleadoFechaVecim').val("");

  }else if ((dateVec[1] <=  dateActual[1]) && (dateVec[2] == dateActual[2])){
    $("#empleadoCarnet").append(`<br>
      <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            Disculpe, el mes o año no deben ser igual al presente...</div>`)

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
    }

  }

});






// ----------------- ROBADOS-----------

$(".verMasR").click(function(event){

$.get(`robadoCarnet`, function(response, verMasR){
  console.log(response);
    $("#carnetRobado").empty();
    if (response == "") {
       $("#carnetRobado").append(`<br><div class='bg-warning' style='padding: 20px'>Disculpe, no se encontraron resultados...</div>`)
    }else{
    response.forEach(element=> {
      var perfil = element.cedula+'.jpg';
      $("#carnetRobado").append(`
        
      <table class="table table-striped table-hover table-bordered table-condensed" style="" >
                <thead style='background: #f2f3f4  repeat-x center top; font-size:14px;'>
            <tr>
            <th>Foto</th>
            <th>Nombres</th>       
                <th>Apellidos</th>
                <th>Cédula</th> 
                <th>Departamento</th>              
              </tr>
            </thead>
            <tbody id="">
             <?php foreach ($empleados as $robados):?>
              <tr>
              <td width='30px'><img id='foto' src='imagenes/${perfil}'  width='50px' height='50px' /></td>
                <td>${element.nombres}</td>
                <td>${element.apellidos}</td>
                <td>${element.cedula}</td>  
                <td>${element.des_uni}</td>              
              </tr>
            </tbody>
  
  </table>

  </div>
      `)
    });
  }
  });
});


// Buscar carnets robados
$("#buscaRobados").change(function(event){ 
  var cedula = $('#buscaRobados').val();
$.get(`getRobados/${cedula}`, function(response, cedula){
  console.log(response);  
  $("#buscarRobado").empty();
  if (response == "") {
          $("#buscarRobado").append(`<br>
            <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            Disculpe, no se encontraron resultados...</div>
            `)
      }else{ 
        $("#buscarRobado").empty();
    response.forEach(element=> {
      $("#buscarRobado").append(`<tr>
        <th>Cédula</th>
        <th>Nombres</th>
        <th>Apellidos</th>
        <th>Código</th>
        <th>Fecha</th>
      </tr>
    </thead>
    <tbody id="buscarRobado">
     <?php foreach ($robados as $historico):?>
      <tr>
        <td>${element.cedula}</td>
        <td>${element.nombres}</td>    
        <td>${element.apellidos}</td>        
        <td>${element.codigo}</td>
        <td>${element.fecha}</td>
      </tr>
      `)    
   
    });
     }
  });
});


// -------------- HURTADOS--------------

$(".verMasH").click(function(event){

$.get(`hurtadoCarnet`, function(response, verMasH){
  console.log(response);
    $("#carnetHurtado").empty();
    if (response == "") {
       $("#carnetHurtado").append(`<br><div class='bg-warning' style='padding: 20px'>Disculpe, no se encontraron resultados...</div>`)
    }else{
    response.forEach(element=> {
      var perfil = element.cedula+'.jpg';
      $("#carnetHurtado").append(`
        
      <table class="table table-striped table-hover table-bordered table-condensed" style="" >
                <thead style='background: #f2f3f4  repeat-x center top; font-size:14px;'>
            <tr>
            <th>Foto</th>
            <th>Nombres</th>       
              <th>Apellidos</th>
              <th>Cédula</th> 
              <th>Departamento</th>
              </tr>
            </thead>
            <tbody id="">
             <?php foreach ($empleados as $hurtados):?>
              <tr>
              <td width='30px'><img id='foto' src='imagenes/${perfil}'  width='50px' height='50px' /></td>
                <td>${element.nombres}</td>
                <td>${element.apellidos}</td>
                <td>${element.cedula}</td> 
                <td>${element.des_uni}</td>              
              </tr>
            </tbody>
  
  </table>

  </div>
      `)
    });
  }
  });
});


// Buscar carnets hurtados
$("#buscaHurtados").change(function(event){ 
  var cedula = $('#buscaHurtados').val();
$.get(`getHurtado/${cedula}`, function(response, cedula){
  console.log(response);  
  $("#buscarHurtado").empty();
  if (response == "") {
          $("#buscarHurtado").append(`<br>
            <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            Disculpe, no se encontraron resultados...</div>
            `)
      }else{ 
        $("#buscarHurtado").empty();
    response.forEach(element=> {
      $("#buscarHurtado").append(`<tr>
        <th>Cédula</th>
        <th>Nombres</th>
        <th>Apellidos</th>
        <th>Código</th>
        <th>Fecha</th>
      </tr>
    </thead>
    <tbody id="buscarRobado">
     <?php foreach ($hurtados as $historico):?>
      <tr>
        <td>${element.cedula}</td>
        <td>${element.nombres}</td>    
        <td>${element.apellidos}</td>        
        <td>${element.codigo}</td>
        <td>${element.fecha}</td>
      </tr>
      `)    
   
    });
     }
  });
});





// -------------- Extraviados --------------

$(".verMasE").click(function(event){

$.get(`extraviadoCarnet`, function(response, verMasE){
  console.log(response);
    $("#carnetExtraviado").empty();
    if (response == "") {
       $("#carnetExtraviado").append(`<br><div class='bg-warning' style='padding: 20px'>Disculpe, no se encontraron resultados...</div>`)
    }else{
    response.forEach(element=> {
      var perfil = element.cedula+'.jpg';
      $("#carnetExtraviado").append(`
        
      <table class="table table-striped table-hover table-bordered table-condensed" style="" >
                <thead style='background: #f2f3f4  repeat-x center top; font-size:14px;'>
            <tr>
            <th>Foto</th>
            <th>Nombres</th>       
                <th>Apellidos</th>
                <th>Cédula</th> 
                <th>Departamento</th>
              </tr>
            </thead>
            <tbody id="">
             <?php foreach ($empleados as $hurtados):?>
              <tr>
              <td width='30px'><img id='foto' src='imagenes/${perfil}'  width='50px' height='50px' /></td>
                <td>${element.nombres}</td>
                <td>${element.apellidos}</td>
                <td>${element.cedula}</td> 
                <td>${element.des_uni}</td>              
              </tr>
            </tbody>
  
  </table>

  </div>
      `)
    });
  }
  });
});

// Buscar carnets extraviados
$("#buscaExtraviados").change(function(event){ 
  var cedula = $('#buscaExtraviados').val();
$.get(`getExtraviado/${cedula}`, function(response, cedula){
  console.log(response);  
  $("#buscarExtraviado").empty();
  if (response == "") {
          $("#buscarExtraviado").append(`<br>
            <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            Disculpe, no se encontraron resultados...</div>
            `)
      }else{ 
        $("#buscarExtraviado").empty();
    response.forEach(element=> {
      $("#buscarExtraviado").append(`<tr>
        <th>Cédula</th>
        <th>Nombres</th>
        <th>Apellidos</th>
        <th>Código</th>
        <th>Fecha</th>
      </tr>
    </thead>
    <tbody id="buscarExtraviado">
     <?php foreach ($extraviado as $historico):?>
      <tr>
        <td>${element.cedula}</td>
        <td>${element.nombres}</td>    
        <td>${element.apellidos}</td>        
        <td>${element.codigo}</td>
        <td>${element.fecha}</td>
      </tr>
      `)    
   
    });
     }
  });
});


// -------------- Vencidos--------------

$(".verMasV").click(function(event){

$.get(`vencidoCarnet`, function(response, verMasV){
  console.log(response);
    $("#carnetVencido").empty();
    if (response == "") {
       $("#carnetVencido").append(`<br><div class='bg-warning' style='padding: 20px'>Disculpe, no se encontraron resultados...</div>`)
    }else{
    response.forEach(element=> {
      var perfil = element.cedula+'.jpg';
      $("#carnetVencido").append(`
        
      <table class="table table-striped table-hover table-bordered table-condensed" style="" >
                <thead style='background: #f2f3f4  repeat-x center top; font-size:14px;'>
            <tr>
            <th>Foto</th>
            <th>Nombres</th>       
                <th>Apellidos</th>
                <th>Cédula</th> 
                <th>Departamento</th>
              </tr>
            </thead>
            <tbody id="">
             <?php foreach ($empleados as $vencido):?>
              <tr>
              <td width='30px'><img id='foto' src='imagenes/${perfil}'  width='50px' height='50px' /></td>
                <td>${element.nombres}</td>
                <td>${element.apellidos}</td>
                <td>${element.cedula}</td> 
                <td>${element.des_uni}</td>             
              </tr>
            </tbody>
  
  </table>

  </div>
      `)
    });
  }
  });
});


// busca vencidos, carga modal 

$(".buskaVencidos").click(function(event){
  
  var cedula = $('#searchVencido').val();

  if (cedula == "") {

    $("#selloM").fadeOut("slow");
        $("#motivoC").fadeOut("slow"); 
        $("#impremeVencido").fadeOut("slow"); 
               
        $("#empleadoCarnet").empty();
        $("#empleadoCarnet").append(`<br><div class='bg-warning' style='padding: 20px'>Disculpe, debe ingresar una cédula..</div>`)

  }else{
  $.get(`getVencido/${cedula}`, function(response, buskaVencidos){
    console.log(response);    
    $("#empleadoCarnet").empty();

    if (response == "" ) {

          $("#selloM").fadeOut("slow");
          $("#motivoC").fadeOut("slow");
          $("#impremeVencido").fadeOut("slow"); 
          $("#empleadoCarnet").append(`<br><div class='bg-warning' style='padding: 20px'>Disculpe, no se encontraron resultados...</div>`)
          
      }else{
        
        $("#selloM").fadeIn("slow");
        $("#motivoC").fadeIn("slow");
        $("#modalReportes").fadeIn("slow");
        $("#impremeVencido").fadeIn("slow"); 

    response.forEach(element=> {

      var cod_car = element.cod_car;
      var status = element.status;
      var perfil = element.cedula+'.jpg';

      if (status == 1) {
        var status = "Activo";
      }else{
        var status = "Inactivo";
      }

        if((cod_car == '0000000407') || (cod_car == '0000000431') || 
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

      }else{

                var cod_uni = element.cod_uni;

                if((cod_uni == '0000-00-04-00-00') || (cod_uni == '0000-00-04-01-00') || 
        (cod_uni == '0000-00-04-03-00') || (cod_uni == '0000-00-04-01-01') || 
        (cod_uni == '0000-00-04-05-00')){ 

        var foto = 'VPGT.jpg';
        var fotoPrensa = 'VPGT-PRENSA.jpg';

      }else if((cod_uni == '0000-00-03-02-00') || (cod_uni == '0000-00-03-01-00') || 
        (cod_uni == '0000-00-03-05-00') || (cod_uni == '0000-00-04-04-00') || 
        (cod_uni == '0000-00-03-04-00') || (cod_uni == '0000-00-03-00-05') || 
        (cod_uni == '0000-00-03-00-04') || ( cod_uni == '0000-00-03-00-06') || 
        (cod_uni == '0000-00-03-00-02') || (cod_uni == '0000-00-03-00-01') || 
        (cod_uni == '0000-00-03-00-00') || (cod_uni == '0000-00-07-00-00') || 
        (cod_uni == '0000-00-03-00-03') || (cod_uni == '0000-00-03-01-01')){
        
          var foto = 'VPGP.jpg';
          var fotoPrensa = 'VPGP-PRENSA.jpg';

      }else if ((cod_uni == '0000-00-02-01-00') || (cod_uni == '0000-00-02-02-00') || 
          (cod_uni == '0000-00-02-05-00') || (cod_uni == '0000-00-02-03-00') || 
          (cod_uni == '0000-00-02-04-00') || (cod_uni == '0000-00-01-03-01') || 
          (cod_uni == '0000-00-01-03-00') || (cod_uni == '0000-00-02-00-00')){
        
            var foto = 'VPGI.jpg';
            var fotoPrensa = 'VPGI-PRENSA.jpg';

        }else if (cod_uni == '0000-00-01-06-00'){

          var foto = 'SEGURIDAD.jpg';
          var fotoPrensa = 'SEGURIDAD-PRENSA.jpg';

        }else if ((cod_uni == '0000-00-01-01-00') || (cod_uni == '0000-00-01-00-00') || 
          (cod_uni == '0000-00-01-02-00') || (cod_uni == '0000-00-01-05-00') || 
          (cod_uni == '0000-00-01-04-00')){ 

          var foto = 'PRESIDENCIA.jpg';
          var fotoPrensa = 'PRESIDENCIA-PRENSA.jpg';
        }

      }
       


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


      </div>
          </div>
        </div>
      </div>


      


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
              <div id='foto_previa' style="width: 115px; overflow: hidden; margin-left: 7px; margin-top: 15px;">
                <img id='foto_carnet_previa' src='imagenes/${perfil}'  width='115' height='87' />
              </div>
              <div id='info_previa' style='margin-top:16px; text-align: left;'>
                <span id='nombre_previo' style='font-size:13px; color:#000; display:block; font-weight: bold; line-height:12px; '>
                  NOMBRES ${element.nombres} ${element.apellidos}
                </span>
                <span style='font-size:12px; color:#000; display:block; padding-top:3px; font-weight: bold; line-height:12px;'>
                  D.I.N&deg;:&nbsp;
                  <span id='din_previo' style='font-size:12px; color:#000;'>
                    C&Eacute;DULA ${element.cedula}
                  </span>
                </span>
                <span id='cargo_previo' style='font-size:9px; color:#000; display:block; padding-top:3px; line-height:12px;'>
                  CARGO ${element.des_car}
                </span>
                <span id='dpto_previo' style='font-size:9px; color:#000; display:block; padding-top:3px;line-height:12px;'>
                  COORDINACI&Oacute;N ${element.des_uni}
                </span>
                <span id='area' style='font-size:9px; color:#000; display:block; padding-top:3px;'>
                  UNIDAD DE &Aacute;DSCRIPCION ${element.des_uni}
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
              <div id='foto_previaS' style="width: 115px; overflow: hidden; margin-left: 7px; margin-top: 15px;">
                <img id='foto_carnet_previa' src='imagenes/${perfil}'  width='115' height='87' />
              </div>
              <div id='info_previaS' style='margin-top:16px; text-align: left;'>
                <span id='nombre_previo' style='font-size:13px; color:#000; display:block; font-weight: bold; line-height:12px; '>
                  NOMBRES ${element.nombres} ${element.apellidos}
                </span>
                <span style='font-size:12px; color:#000; display:block; padding-top:3px; font-weight: bold; line-height:12px;'>
                  D.I.N&deg;:&nbsp;
                  <span id='din_previo' style='font-size:12px; color:#000;'>
                    C&Eacute;DULA ${element.cedula}
                  </span>
                </span>
                <span id='cargo_previo' style='font-size:9px; color:#000; display:block; padding-top:3px; line-height:12px;'>
                  CARGO ${element.des_car}
                </span>
                <span id='dpto_previo' style='font-size:9px; color:#000; display:block; padding-top:3px;line-height:12px;'>
                  COORDINACI&Oacute;N ${element.des_uni}
                </span>
                <span id='area' style='font-size:9px; color:#000; display:block; padding-top:3px;'>
                  UNIDAD DE &Aacute;DSCRIPCION ${element.des_uni}
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




// -------------- Sin Carnets --------------

$(".verMasSC").click(function(event){

$.get(`sinCarnet`, function(response, verMasSC){
  console.log(response);
    $("#Sincarnet").empty();
    if (response == "") {
       $("#Sincarnet").append(`<br><div class='bg-warning' style='padding: 20px'>Disculpe, no se encontraron resultados...</div>`)
    }else{
    response.forEach(element=> {
      var perfil = 'usericonos.JPG';
      $("#Sincarnet").append(`
        
      <table class="table table-striped table-hover table-bordered table-condensed" style="" >
                <thead style='background: #f2f3f4  repeat-x center top; font-size:14px;'>
            <tr>
            <th>Foto</th>
            <th>Nombres</th>       
                <th>Apellidos</th>
                <th>Cédula</th>               
              </tr>
            </thead>
            <tbody id="">
             <?php foreach ($empleados as $vencido):?>
              <tr>
              <td width='30px'><img id='foto' src='imagenes/${perfil}'  width='50px' height='50px' /></td>
                <td>${element.nombres}</td>
                <td>${element.apellidos}</td>
                <td>${element.cedula}</td>               
              </tr>
            </tbody>
  
  </table>

  </div>
      `)
    });
  }
  });
});


function isDate(desde) { 
    var objDate,  // date object initialized from the ExpiryDate string 
        mSeconds, // ExpiryDate in milliseconds 
        day,      // day 
        month,    // month 
        year;     // year 
    // date length should be 10 characters (no more no less) 
    if (desde.length !== 10) { 
        return false; 
    } 
    // third and sixth character should be '/' 
    if (desde.substring(2, 3) !== '-' || desde.substring(5, 6) !== '-') { 
        return false; 
    } 
    // extract month, day and year from the ExpiryDate (expected format is mm/dd/yyyy) 
    // subtraction will cast variables to integer implicitly (needed 
    // for !== comparing) 

    //day = ExpiryDate.substring(3, 5) - 0; 
    day = desde.substring(0, 2) - 0;

    month = desde.substring(3, 5) - 1; // because months in JS start from 0 

  //month = ExpiryDate.substring(0, 2) - 1;

    year = desde.substring(6, 10) - 0; 
    // test year range 
    if (year < 1000 || year > 3000) { 
        swal("La fecha es inválida");
        $('#desde').val("");
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
        swal("La fecha es inválida"); 
        $('#desde').val("");
    } 
    // otherwise return true 
    return true; 
}


function isDateHasta(hasta) { 
    var objDate,  // date object initialized from the ExpiryDate string 
        mSeconds, // ExpiryDate in milliseconds 
        day,      // day 
        month,    // month 
        year;     // year 
    // date length should be 10 characters (no more no less) 
    if (hasta.length !== 10) { 
        return false; 
    } 
    // third and sixth character should be '/' 
    if (hasta.substring(2, 3) !== '-' || hasta.substring(5, 6) !== '-') { 
        return false; 
    } 
    // extract month, day and year from the ExpiryDate (expected format is mm/dd/yyyy) 
    // subtraction will cast variables to integer implicitly (needed 
    // for !== comparing) 

    //day = ExpiryDate.substring(3, 5) - 0; 
    day = hasta.substring(0, 2) - 0;

    month = hasta.substring(3, 5) - 1; // because months in JS start from 0 

  //month = ExpiryDate.substring(0, 2) - 1;

    year = hasta.substring(6, 10) - 0; 
    // test year range 
    if (year < 1000 || year > 3000) { 
        swal("La fecha es inválida"); 
        $('#hasta').val("");
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
        swal("La fecha es inválida"); 
        $('#hasta').val("");
    } 
    // otherwise return true 
    return true; 
}

$("#desde").keyup(function(e){

  var fecha = document.getElementById('desde').value; 

  var total = fecha.length; 

  if (isDate(fecha)) { 

    var datedesde = fecha.split("-");
    
  }else{
    if (total > 10) {
    swal("La fecha es inválida");
    $('#desde').val("");
    }
  }

});

$("#hasta").keyup(function(e){

  var fechaH = document.getElementById('hasta').value; 

  var total = fechaH.length; 

  if (isDateHasta(fechaH)) { 

    var datehasta = fechaH.split("-");
    
  }else{
    if (total > 10) {
    swal("La fecha es inválida");
    $('#hasta').val("");
    }
  }

});

