$("#busHistorico").change(function(event){ 
  var cedula = $('#busHistorico').val();
$.get(`getHistorico/${cedula}`, function(response, cedula){
  console.log(response);  
  $("#historicos").empty();
  if (response == "") {
          $("#results").append(`<br>
            <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            Disculpe, no se encontraron resultados...</div>
            `)
      }else{ 
        $("#historicos").empty();
    response.forEach(element=> {
      $("#historicos").append(`<tr>
        <th>Responsable</th>       
        <th>Carnet</th>
        <th>Empleado</th>
        <th>Área Empleado</th>
        <th>Cédula</th>
        <th>Motivo</th>
        <th>Fecha</th>
      </tr>
    </thead>
    <tbody id="historicos">
     <?php foreach ($historicoCarnets as $historico):?>
      <tr>
        <td>${element.responsable}</td>
        <td>${element.codigo}</td>
        <td>${element.nombres} ${element.apellidos}</td>     
        <td>${element.des_uni}</td>        
        <td>${element.cedula}</td>
        <td>${element.tipoReporte}</td>
        <td>${element.fecha}</td>
      </tr>
      `)    
   
    });
     }
  });
});


// ----carnets provisionales---------




$("#busHistoricoPro").change(function(event){ 
  var cedula = $('#busHistoricoPro').val();
$.get(`getHistoricoProv/${cedula}`, function(response, cedula){
  console.log(response);  
  $("#historicosProv").empty();
  if (response == "") {
          $("#results").append(`<br>
            <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            Disculpe, no se encontraron resultados...</div>
            `)
      }else{ 
        $("#historicosProv").empty();
    response.forEach(element=> {
      $("#historicosProv").append(`<tr>
        <th>Responsable</th>       
        <th>Carnet</th>
        <th>Nombres y Apellidos</th>
        <th>Cédula</th>
        <th>Área</th>
        <th>Motivo</th>
        <th>Fecha</th>
      </tr>
    </thead>
    <tbody id="historicosProv">
     <?php foreach ($bus_historicoProv as $historico):?>
      <tr>
        <td>${element.responsable}</td>
        <td>${element.codigo}</td>
        <td>${element.nombres} ${element.apellidos}</td>            
        <td>${element.cedula}</td>
        <td>${element.des_uni}</td> 
        <td>${element.tipoReporte}</td>
        <td>${element.fecha}</td>
      </tr>
      `)    
   
    });
     }
  });
});

