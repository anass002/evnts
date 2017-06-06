/* Setup blank page controller */
angular.module('MetronicApp').controller('RepasController', function($rootScope, $scope, settings ,$http) {
    $scope.$on('$viewContentLoaded', function() {   
        // initialize core components
        App.initAjax();

        // set default layout mode
        $rootScope.settings.layout.pageContentWhite = true;
        $rootScope.settings.layout.pageBodySolid = false;
        $rootScope.settings.layout.pageSidebarClosed = false;
    });

    $scope.data = {};
    $scope.data.showRepasTable = true;
	$scope.data.showRepasForm = false;


    getRepas();

    $scope.addNewRepas = function(){
    	$scope.data.showRepasTable = false;
		$scope.data.showRepasForm = true;
		$scope.data.repas = {};
    }

	$scope.saveRepas = function(repas){
		console.log(repas);

		$http.post('../serv/ws/repas.ws.php' , {action:'AddNewRepas' , repas : JSON.stringify(repas)}).then(
			function(res){
				if(res.data.error === true){
					alert("Unable to save Repas ! ");
					return false;
				}

				$scope.closeNewRepas();
				getRepas();
			}
		)
		

	}

	$scope.closeNewRepas = function(){
		$scope.data.showRepasTable = true;
		$scope.data.showRepasForm = false;
		$scope.data.repas = {};
	}


	function getRepas(){
		$http.post('../serv/ws/repas.ws.php' , {action:'getAllRepas'}).then(
			function(res){
				if(res.data.error === true){
					alert("Unable to get Repas ! ");
					$scope.data.repass = [];
					return false;
				}

				$scope.data.repass = res.data.data;
			}
		)
	}

});
