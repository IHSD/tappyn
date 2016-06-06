tappyn.controller('comproController', function($scope, $rootScope, $routeParams, comproFactory){
	if($routeParams.id){
		comproFactory.grabProfile($routeParams.id).success(function(response){
			if(response.http_status_code == 200){
				if(response.success) $scope.company = response.data.company;
				else $scope.set_alert(response.message, "default");
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		});
		comproFactory.grabContests($routeParams.id).success(function(response){
			if(response.http_status_code == 200){
				if(response.success) $scope.contests = response.data.contests;
				else $scope.set_alert(response.message, "default");
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		});
	}

	$scope.follow = function(){
		if(!$rootScope.user){
			$scope.set_alert("Please make an account to follow companies!", "default");
			$scope.open_register("default", "", "2");
		}
		else{
			comproFactory.followCompany($routeParams.id).success(function(response){
				if(response.http_status_code == 200){
					if(response.success){
						$scope.set_alert("You're following "+$scope.company.name, "default");
						$scope.company.follows++;
						$scope.company.user_may_follow = false;
					}
					else $scope.set_alert(response.message, "default");
				}
				else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
				else $scope.check_code(response.http_status_code);
			});
		}
	}

	$scope.unfollow = function(){
		comproFactory.unfollowCompany($routeParams.id).success(function(response){
			if(response.http_status_code == 200){
				if(response.success){
					$scope.set_alert("You unfollowed "+$scope.company.name, "default");
					$scope.company.follows--;
					$scope.company.user_may_follow = true;
				}
				else $scope.set_alert(response.message, "default");
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		})
	}

	$scope.request_contest = function(){
		if(!$rootScope.user){
			$scope.set_alert("Please make an account to request contests!", "default");
			$scope.open_register("default", "");
		}
		else{
			comproFactory.requestContest($routeParams.id).success(function(response){
				if(response.http_status_code == 200){
					if(response.success){
						$scope.set_alert(response.message, "default");
						$scope.company.contest_requests++;
					}
					else $scope.set_alert(response.message, "default");
				}
				else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
				else $scope.check_code(response.http_status_code);
			});
		}
	}
});
