/* Setup blank page controller */
angular.module('MetronicApp').controller('PackSurpriseController', function($rootScope, $scope, settings,$http) {
    $scope.$on('$viewContentLoaded', function() {   
        // initialize core components
        App.initAjax();

        // set default layout mode
        $rootScope.settings.layout.pageContentWhite = true;
        $rootScope.settings.layout.pageBodySolid = false;
        $rootScope.settings.layout.pageSidebarClosed = false;
    });

    $scope.data = {};
    $scope.data.showTablesurprise = true;
	$scope.data.showSurpriseForm = false;


	getSurprise();

	$scope.AddNewSurprise = function(){
		$scope.data.showTablesurprise = false;
		$scope.data.showSurpriseForm = true;
		$scope.data.surprise = {}
	}

	$scope.saveSurprise = function(surprise){
		console.log(surprise);

		$http.post('../serv/ws/surprise.ws.php' , {action: 'AddNewSurprise' , surprise : JSON.stringify(surprise)}).then(
			function(res){
				if(res.data.error === true){
					alert("Unable to save Surprise !");
					return false;
				}

				$scope.closeNewSurprise();
				getSurprise();
			}
		)
	}

	$scope.closeNewSurprise = function(){
		$scope.data.showTablesurprise = true;
		$scope.data.showSurpriseForm = false;
		$scope.data.surprise = {}
	}


	function getSurprise(){
		$http.post('../serv/ws/surprise.ws.php' , {action:'getAllSurprises'}).then(
			function(res){
				if(res.data.error === true){
					alert("Unable to get All surprise !");
					$scope.data.surprises = [];
					return false;
				}

				$scope.data.surprises = res.data.data;
			}
		)
	}
});
