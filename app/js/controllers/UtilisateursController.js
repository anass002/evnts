/* Setup blank page controller */
angular.module('MetronicApp').controller('UtilisateursController', function($rootScope, $scope, settings,$http) {
    $scope.$on('$viewContentLoaded', function() {   
        // initialize core components
        App.initAjax();

        // set default layout mode
        $rootScope.settings.layout.pageContentWhite = true;
        $rootScope.settings.layout.pageBodySolid = false;
        $rootScope.settings.layout.pageSidebarClosed = false;
    });


    console.log("INIT CTRL USERS !!");
    $scope.data = {};
   	$scope.data.showAllProfile = true;
	$scope.data.showNewProfile = false;


    getProfiles();


    $scope.AddNewProfile = function(){
    	$scope.data.showAllProfile = false;
    	$scope.data.AddNewProfile = true;
    	$scope.data.profile = {};
    }

    $scope.saveProfile = function(profile){
        console.log(profile);

        $http.post('../serv/ws/users.ws.php' , {action:'AddNewProfile' , profile : JSON.stringify(profile)}).then(
            function(res){
                if(res.data.error === true){
                    alert("Error Add New Profile");
                    return false;
                }

                $scope.closeNewProfile();
                getProfiles();
            },
            function(error){

            }
        )


    }
    $scope.closeNewProfile = function(){
        $scope.data.profile = {};
        $scope.data.AddNewProfile = false;
        $scope.data.showAllProfile = true;
    }

    $scope.delete = function(profile){
        if(window.confirm("Etes vous sur de vouloir supprimer ?")){
            $http.post('../serv/ws/profile.ws.php' , { action:'deleteProfile', id : profile.id }).then(
                function(res){
                    if(res.data.error === true){
                        alert('Unable to delete !');
                        return false;
                    }

                    getProfiles();
                },
                function(err){
                    console.log(err);
                }
            )
        }
    }
    $scope.edit = function(profile){
        $scope.data.showAllProfile = false;
        $scope.data.AddNewProfile = true;
        $scope.data.profile = profile;
    }

    function getProfiles(){
    	$http.post('../serv/ws/users.ws.php' , {action:'getAllProfiles'}).then(
    		function(res){
    			console.log(res.data.data);
    			if(res.data.error === true){
    				alert("Unable to get Users");
    				return false;
    			}

    			$scope.data.users = res.data.data;

    		},
    		function(error){
    			console.log(error);
    		}
    	)
    }


});
