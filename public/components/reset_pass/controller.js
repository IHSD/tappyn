tappyn.controller("resetController", function($scope, $routeParams, $location, resetFactory){
	$scope.logged_in();
	
	resetFactory.checkCode($routeParams.code).success(function(response){
		if(response.http_status_code == 200){
			if(response.success){
				$scope.set_alert("Verified, please change your password", "default");
				$scope.code = $routeParams.code;
				$scope.pass = {csrf : response.data.csrf, user_id : response.data.user_id, new : '', new_confirm : ''}
			}
			else{
				$scope.set_alert("Unauthorized", "error");
				$location.path('/login');
			}
		}
		else{
			$scope.set_alert("Unauthorized", "error");
			$location.path('/login');
		}
	});

	$scope.change_pass = function(pass){
		resetFactory.changePass(pass, $scope.code).success(function(response){
			if(response.http_status_code == 200){
				if(response.success){
					$scope.set_alert(response.message, "default");
					$location.path('/login')
				}	
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		})
	}
})