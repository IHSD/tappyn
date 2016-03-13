tappyn.controller('homeController', function($scope, $location, homeFactory){
	$scope.logged_in().then(function(response){
		homeFactory.grabCool().success(function(response){
			$scope.contests = response.data.contests;
		})
	});


	$scope.mailing_list = function(email){
		homeFactory.mailingList(email).success(function(response){
			if(response.http_status_code == 200){
				if(response.success) $scope.set_alert(response.message, "default");	
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		})
	}
})