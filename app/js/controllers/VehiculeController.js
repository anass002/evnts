/* Setup blank page controller */
angular.module('MetronicApp').controller('VehiculeController', function($rootScope, $scope, settings,$http) {
    $scope.$on('$viewContentLoaded', function() {   
        // initialize core components
        App.initAjax();

        // set default layout mode
        $rootScope.settings.layout.pageContentWhite = true;
        $rootScope.settings.layout.pageBodySolid = false;
        $rootScope.settings.layout.pageSidebarClosed = false;
    });

    $scope.data = {};
    $scope.data.showAllVehicules = true;
    $scope.data.showNewVehicule = false;


    $scope.saveVehicule = function(vehicule){
    	console.log(vehicule);

    	$http.post('../serv/ws/vehicule.ws.php' , {action:'AddNewVehicule' , vehicule : JSON.stringify(vehicule)}).then(
    		function(res){
    			if(res.data.error === true){
    				alert("Unable to save vehicule");
    				console.log(res.data);
    				return false;
    			}

    			$scope.closeNewVehicule();
    			getVehicules();
    		},
    		function(err){
    			console.log(err);
    		}
    	)
    }

	$scope.closeNewVehicule = function(){
		$scope.data.showAllVehicules = true;
    	$scope.data.showNewVehicule = false;
    	$scope.data.vehicule = {};
	}

	$scope.AddNewVehicule = function(){
		$scope.data.showAllVehicules = false;
    	$scope.data.showNewVehicule = true;
    	$scope.data.vehicule = {};
	}








    function getVehicules(){
    	$http.post('../serv/ws/vehicule.ws.php' , {action:'getAllVehicules'}).then(
    		function(res){
    			if(res.data.error === true){
    				alert("Unable to get vehicules");
    				$scope.data.vehicules = [];
    				return false;
    			}

    			$scope.data.vehicules = res.data.data;
    		}
    	)
    }

});
