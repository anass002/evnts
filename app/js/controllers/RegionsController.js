angular.module('MetronicApp').controller('RegionsController', function($rootScope, $scope, $http, $timeout) {
    $scope.$on('$viewContentLoaded', function() {   
        App.initAjax(); // initialize core components
        //Layout.setSidebarMenuActiveLink('set', $('#sidebar_menu_link_profile')); // set profile link active in sidebar menu 
    });

    $scope.data = {};
    $scope.data.showAllRegions = true;
    $scope.data.addNewRegion = false;

    getRegions();


    console.log("INIT regions CTRL");


    $scope.AddRegion = function(){
    	$scope.data.showAllRegions = false;
    	$scope.data.addNewRegion = true;
    	$scope.data.region = {};
    }

	$scope.saveRegion = function(region){
		console.log(region);

		$http.post('../serv/ws/regions.ws.php' , {action:'AddNewRegion', region : JSON.stringify(region)}).then(
			function(res){
				if(res.data.error === true){
					alert("Unable to save the New Region ! ");
					return false;
				}

				$scope.closeNewRegion();
				getRegions();
			},
			function(err){
				console.log(err);
			}
		)
	}

	$scope.closeNewRegion = function(){
		$scope.data.showAllRegions = true;
		$scope.data.addNewRegion = false;
		$scope.data.region = {};
	}

    $scope.delete = function(region){
        if(window.confirm("Etes vous sur de vouloir supprimer ?")){
            $http.post('../serv/ws/regions.ws.php' , { action:'deleteRegion', id : region.id }).then(
                function(res){
                    if(res.data.error === true){
                        alert('Unable to delete !');
                        return false;
                    }

                    getRegions();
                },
                function(err){
                    console.log(err);
                }
            )
        }
    }
    $scope.edit = function(region){
        $scope.data.showAllRegions = false;
        $scope.data.addNewRegion = true;
        $scope.data.region = region;
    }



    function getRegions(){
    	$http.post('../serv/ws/regions.ws.php' , {action:'getAllRegions'}).then(
    		function(res){
    			if(res.data.error === true){
    				alert("Error While Getting Regions !");
    				$scope.data.regions = [];
    				return false;
    			}

    			$scope.data.regions = res.data.data;
    		},
    		function(error){
    			console.log(err);
    		}
    	)
    }


    // set sidebar closed and body solid layout mode
    $rootScope.settings.layout.pageBodySolid = true;
    $rootScope.settings.layout.pageSidebarClosed = true;
}); 
