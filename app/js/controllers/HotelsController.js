/* Setup blank page controller */
angular.module('MetronicApp').controller('HotelsController', function($rootScope, $scope, settings , $http) {
    $scope.$on('$viewContentLoaded', function() {   
        // initialize core components
        App.initAjax();

        // set default layout mode
        $rootScope.settings.layout.pageContentWhite = true;
        $rootScope.settings.layout.pageBodySolid = false;
        $rootScope.settings.layout.pageSidebarClosed = false;
    });

    $scope.data = {};
    $scope.data.showHotelsTable = true;
    $scope.data.showHotelForm = false;

    getHotels(); 


    $scope.addNewHotel = function(){
    	$scope.data.showHotelsTable = false;
    	$scope.data.showHotelForm = true;
    	$scope.data.hotel = {};
    }

	$scope.saveHotel = function(hotel){
		console.log(hotel);
		$http.post('../serv/ws/hotel.ws.php' , {action:'AddNewHotel' , hotel:JSON.stringify(hotel)}).then(
			function(res){
				if(res.data.error === true){
					alert("Unable to save hotel");
					return false;
				}

				$scope.closeNewHotel();
				getHotels();
			}
		)
	}	

	$scope.closeNewHotel = function(){
		$scope.data.showHotelsTable = true;
    	$scope.data.showHotelForm = false;
    	$scope.data.hotel = {};
	}

    $scope.delete = function(hotel){
        if(window.confirm("Etes vous sur de vouloir supprimer ?")){
            $http.post('../serv/ws/hotel.ws.php' , { action:'deleteHotel', id : hotel.id }).then(
                function(res){
                    if(res.data.error === true){
                        alert('Unable to delete !');
                        return false;
                    }

                    getHotels();
                },
                function(err){
                    console.log(err);
                }
            )
        }
    }
    $scope.edit = function(hotel){
        $scope.data.showHotelsTable = false;
        $scope.data.showHotelForm = true;
        $scope.data.hotel = hotel;
    }




    function getHotels(){
    	$http.post('../serv/ws/hotel.ws.php' , {action:'getAllHotels'}).then(
    		function(res){
    			if(res.data.error === true){
    				alert("Unable to get Hotels");
    				$scope.data.hotels = [];
    				return false;
    			}

    			$scope.data.hotels = res.data.data;

    			console.log(res.data.data);
    		},
    		function(err){
    			console.log(err);
    		}
    	)
    } 
});
