tappyn.controller('contestsController', function($scope, $rootScope, contestsFactory){
	if($rootScope.user){
		$scope.tab = "my";
		contestsFactory.grabMyContests().success(function(response){
			if(response.http_status_code == 200){
				if(response.success) $scope.contests = response.data.contests;	
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500){
				$scope.set_alert(response.error, "defaults");
				$scope.adding_interests();
			}
			else $scope.check_code(response.http_status_code);
		})
	}
	else{
		$scope.tab = "all";
		contestsFactory.grabAllContests().success(function(response){
			$scope.contests = response.data.contests;
		});
	}

	$scope.filter_industry = function(pass){
		contestsFactory.filterGrab(pass).success(function(response){
			$scope.contests = response.data.contests;
		})
	}
	
	$scope.grab_all = function(){
		$scope.tab = 'all';
		contestsFactory.grabAllContests().success(function(response){
			$scope.contests = response.data.contests;
		});
	}

	$scope.grab_my = function(){
		$scope.tab = 'my';
		contestsFactory.grabMyContests().success(function(response){
			$scope.contests = response.data.contests;
		});
	}
})