angular.module('MetronicApp').controller('MajordomeController', function($rootScope, $scope, $http, $timeout) {
    $scope.$on('$viewContentLoaded', function() {   
        App.initAjax(); // initialize core components
        //Layout.setSidebarMenuActiveLink('set', $('#sidebar_menu_link_profile')); // set profile link active in sidebar menu 
    });


    $scope.data = {};
    $scope.data.showTableMajordome = true;
	$scope.data.showAddNewMajordome = false;


    getMajordomes();

    $scope.addNewMajordome = function(){
    	$scope.data.showTableMajordome = false;
		$scope.data.showAddNewMajordome = true;
    	$scope.data.majordome = {};
    }

	$scope.saveMajordome = function(majordome){
		console.log(majordome);

		$http.post('../serv/ws/majordome.ws.php' , {action:'AddNewMajordome' , majordome : JSON.stringify(majordome)}).then(
			function(res){
				if(res.data.error === true){
					alert("unable to save Majordome ! ");
					return false;
				}

				$scope.closeNewMajordome();
				getMajordomes();
			},
			function(err){
				console.log(err);
			}
		)
	}

	$scope.closeNewMajordome = function(){
		$scope.data.showTableMajordome = true;
		$scope.data.showAddNewMajordome = false;
    	$scope.data.majordome = {};
	}


	$scope.delete = function(majordome){
		if(window.confirm("Etes vous sur de vouloir supprimer ?")){
			$http.post('../serv/ws/majordome.ws.php' , { action:'deleteMajordome', id : majordome.id }).then(
				function(res){
					if(res.data.error === true){
						alert('Unable to delete !');
						return false;
					}

					getMajordomes();
				},
				function(err){
					console.log(err);
				}
			)
		}
	}
	$scope.edit = function(majordome){
		$scope.data.showTableMajordome = false;
		$scope.data.showAddNewMajordome = true;
    	$scope.data.majordome = majordome;
	}

	function getMajordomes(){
		$http.post('../serv/ws/majordome.ws.php' , {action:'getAllMajordome'}).then(
			function(res){
				if(res.data.error === true){
					alert("Unable to get All Majordomes !");
					$scope.data.majordomes = [];
					return false;
				}
				$scope.data.majordomes = res.data.data;
			},
			function(err){
				console.log(err);
			}
		)
	}

    // set sidebar closed and body solid layout mode
    $rootScope.settings.layout.pageBodySolid = true;
    $rootScope.settings.layout.pageSidebarClosed = true;
}); 
