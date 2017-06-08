angular.module('MetronicApp').controller('NatureSejourController', function($rootScope, $scope, $http, $timeout) {
    $scope.$on('$viewContentLoaded', function() {   
        App.initAjax(); // initialize core components
        //Layout.setSidebarMenuActiveLink('set', $('#sidebar_menu_link_profile')); // set profile link active in sidebar menu 
    });

    $scope.data = {};
    $scope.data.showAddNewSejour = false;
    $scope.data.showAllSejours = true;


    getSejours();


    $scope.newNatureSejour = function(){
    	$scope.data.showAddNewSejour = true;
	    $scope.data.showAllSejours = false;
	    $scope.data.sejour = {};
    }

	$scope.saveSejour = function(sejour){
		console.log(sejour);

		$http.post('../serv/ws/sejour.ws.php' , {action:'AddNewSejour', sejour: JSON.stringify(sejour)}).then(
			function(res){
				if(res.data.error === true){
					alert("Unable to save Sejour ");
					return false;
				}

				$scope.closeNewSejour();
				getSejours(); 
			}
		)
	}

	$scope.closeNewSejour = function(){
		$scope.data.showAddNewSejour = false;
	    $scope.data.showAllSejours = true;
	    $scope.data.sejour = {};
	}

    $scope.delete = function(sejour){
        if(window.confirm("Etes vous sur de vouloir supprimer ?")){
            $http.post('../serv/ws/sejour.ws.php' , { action:'deleteSejour', id : sejour.id }).then(
                function(res){
                    if(res.data.error === true){
                        alert('Unable to delete !');
                        return false;
                    }

                    getSejours();
                },
                function(err){
                    console.log(err);
                }
            )
        }
    }
    $scope.edit = function(sejour){
        $scope.data.showAddNewSejour = true;
        $scope.data.showAllSejours = false;
        $scope.data.sejour = sejour;
    }

    function getSejours(){
    	$http.post('../serv/ws/sejour.ws.php', {action:'getAllSejours'}).then(
    		function(res){
    			if(res.data.error === true){
    				alert("Unable to get All Sejours ");
    				$scope.data.sejours = [];
    				return false;
    			}

    			$scope.data.sejours = res.data.data;
    		}
    	)
    }

    // set sidebar closed and body solid layout mode
    $rootScope.settings.layout.pageBodySolid = true;
    $rootScope.settings.layout.pageSidebarClosed = true;
}); 
