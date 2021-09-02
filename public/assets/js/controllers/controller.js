angular.module("PrimeraClase",[])
.controller("PrimerController",function($scope,$http){
	$scope.loader=false;
	$http({
		method:"GET",
		url:"lista/searchPersons",
		dataType:"json",
	}).then(function(success,response){
		$scope.personas=success.data;
	},function(error){
		console.log(error);
	});


	$scope.addPerson=function(){
		$scope.loader=true;
		$http({
			method:"post",
			url:"lista/addPerson",
			dataType:'JSON',
			data:{persona : $scope.nuevaPersona},//$scope.nuevaPersona}
		}).then(function(success){
			$scope.loader=false;
			if(success.data == 1){
				
			}else{
				// ALERTAS
			}

		},function(error){
			console.log(error);
		});
	}

	/*$scope.removePerson=function($id){
		$http({
			method:"post",
			url:"lista/removePerson",
			dataType:'JSON',
			data:{id : $id},//$scope.nuevaPersona}
		}).then(function(success){
			console.log(success);
		},function(error){
			console.log(error);
		});
	}*/

	$scope.getDataToPerson=function($id){
		$http({
			method:"post",
			url:"lista/getDataToPerson",
			dataType:'JSON',
			data:{id : $id},//$scope.nuevaPersona}
		}).then(function(success){
			console.log(success);
			$scope.nuevaPersona=success.data;
		},function(error){
			console.log(error);
		});
	}

	/*$scope.editPerson=function($id){
		$http({
			method:"post",
			url:"lista/getDataToPerson",
			dataType:'JSON',
			data:{id : $id,persona : $scope.nuevaPersona},//$scope.nuevaPersona}
		}).then(function(success){
			console.log(success);
			$scope.nuevaPersona=success.data;
		},function(error){
			console.log(error);
		});
	}*/
});