$(document).ready(function(){
	$.get(`getAsistencias`, function(response){
		var asistentes = response.length;
		$.get(`getInasistencias`,function(response){
			if (response <= 0) {
				var inasistentes = 0;
			}
			else{
				inasistentes = response;
			}
			console.log(inasistentes);
			$.get(`getRetrasos`, function(response){
				var retrasos = response.length;
				$.get(`getRetirosant`, function(response){
					var retiros = response.length;
					console.log(retiros)
					$.get(`getVacaciones`,function(response){
						var vacaciones = response;
						$.get(`getPremunerados`,function(response){
							var permisosr = response;
							console.log(permisosr)
							$.get(`getPnremunerados`,function(response){
								var permisosnr = response.length;
								$.get(`getReposos`,function(response){
									var reposos = response;
									$.get(`getSalidasm`,function(response){
										var salidasm = response.length;
										
										var oilCanvas = document.getElementById("oilChart");

										Chart.defaults.global.defaultFontFamily = "Arial";
										Chart.defaults.global.defaultFontSize = 18;

										var oilData = {
											labels: [
										        "Asistencias",
										        "Inasistencias",
										        "Retrasos",
										        "Salidas anticipadas",
										        "Vacaciones",
										        "Permisos remunerados",
										        "Permisos NO remunerados",
										        "Reposos",
										        "Salidas sin marcar",
										    ],
										    
										    datasets: [
										        {
										            data: [asistentes, inasistentes, retrasos, retiros, vacaciones,permisosr,permisosnr,reposos,salidasm],
										            backgroundColor: [
										                '#78FF00',
												        '#FF0036',
												        '#FFCD00',
												        '#FFA200',
												        '#00FFFF',
												        '#00FFB6',
												        '#8300FF',
												        '#D500FF',
												        '#FFFF00',
											            ],
										            borderColor: "black",
										            borderWidth: 2
										        }]
										};

										var chartOptions = {
										  	rotation: -Math.PI,
										  	cutoutPercentage: 30,
										  	circumference: Math.PI,
										  	legend: {
						                        display: false,
						                    },title: {
						                        display: true,
						                        text: 'GRÁFICA'
						                    },
										  	animation: {
										    	animateRotate: false,
										    	animateScale: true
										  	}
										};

										var pieChart = new Chart(oilCanvas, {
										  type: 'doughnut',
										  data: oilData,
										  options: chartOptions
										});
									});
								});
							});
						});
					});
				});
			});
		});
	});
	/*$.get(`getAsistenciasAnt`, function(response){
		var asistentesAnt = response;
		$.get(`getInasistenciasAnt`,function(response){
			var inasistentesAnt = response;
			$.get(`getRetrasosAnt`, function(response){
				var retrasosAnt = response;
				$.get(`getRetirosantAnt`, function(response){
					var retirosAnt = response;
					$.get(`getVacacionesAnt`,function(response){
						var vacacionesAnt = response;
						$.get(`getPremuneradosAnt`,function(response){
							var permisosrAnt = response;
							$.get(`getPnremuneradosAnt`,function(response){
								var permisosnrAnt = response;
								$.get(`getRepososAnt`,function(response){
									var repososAnt = response;
									$.get(`getSalidasmAnt`,function(response){
										var salidasmAnt = response;

										var oilCanvas = document.getElementById("grafico2");
										Chart.defaults.global.defaultFontFamily = "arial";
										Chart.defaults.global.defaultFontSize = 18;

										var oilData = {
										    labels: [
										        "Asistencias",
										        "Inasistencias",
										        "Retrasos",
										        "Retiros anticipados",
										        "Vacaciones",
										        "Permisos remunerados",
										        "Permisos NO remunerados",
										        "Reposos",
										        "Salidas sin marcar",
										    ],
										    datasets: [
										     {
									            data: [asistentesAnt, inasistentesAnt, retrasosAnt, retirosAnt, vacacionesAnt,permisosrAnt,permisosnrAnt,repososAnt,salidasmAnt],
									            backgroundColor: [
									                "#0044D7",
									                "#0044D7",
									                "#0044D7",
									                "#0044D7",
									                "#0044D7",
									                "#0044D7",
									                "#0044D7",
									                "#0044D7",
									                "#0044D7"
									            ]
										    }]
										};
										var pieChart = new Chart(oilCanvas, {
										  type: 'pie',
										  data: oilData
										});
									});
								});
							});
						});
					});
				});
			});
		});
	});
*/
	/*var popCanvas = document.getElementById("oilChart");
	var barChart = new Chart(popCanvas, {
	  type: 'bar',

	  options:{
		   legend: {
                        display: false,
                    },title: {
                        display: true,
                        text: 'GRÁFICAS'
                    },
		},
	  data: {
	    labels: ["Asistencias", "Inasistencias", "Retrasos", "S. anticipadas", "Vacaciones", "P. remunerados", "P. NO remunerados", "Reposos", "S. sin marcar"],
	    datasets: [{
	      data: [1500, 90, 730, 495, 110, 252, 15, 95, 28],
	      backgroundColor: [
	        'rgba(255, 99, 132, 0.6)',
	        'rgba(54, 162, 235, 0.6)',
	        'rgba(255, 206, 86, 0.6)',
	        'rgba(75, 192, 192, 0.6)',
	        'rgba(153, 102, 255, 0.6)',
	        'rgba(255, 159, 64, 0.6)',
	        'rgba(255, 99, 132, 0.6)',
	        'rgba(54, 162, 235, 0.6)',
	        'rgba(255, 206, 86, 0.6)',
	        'rgba(75, 192, 192, 0.6)',
	      ],
	      fontSize: 5,
		  fontColor: 'blue',
	    }]
	  }
	});*/
	
});

