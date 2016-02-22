tappyn.controller('launchController', function($scope, $location, launchFactory){
	$scope.steps = {
		'platform'		 : {step : 'platform', next : 'objective', previous : 'none', fill : 25},
		'objective'      : {step : 'objective', next : 'detail', previous : 'platform', fill : 50},
		'detail' 		 : {step : 'detail', next : 'payment', previous : 'objective', fill : 75},
		'payment'		 : {step : 'payment', next : 'none', previous : 'detail', fill : 100}
	}
	$scope.current = $scope.steps['platform'];

	$scope.contest = {};

	$scope.set_step = function(step){
		$scope.current = $scope.steps[step];
	}

	$scope.select_objective = function(objective){
		$scope.contest.objective = objective;
	}

	$scope.select_platform = function(platform){
		$scope.contest.platform = platform;
	}

	$scope.select_display = function(display){
		$scope.contest.display = display;
	}

	$scope.submit = function(contest){
		launchFactory.submission(contest).success(function(response){
			if(response.http_status_code == 200){
				if(response.success){
					alert("Made it");
				}
				else alert(response.message);	 
			}
			else if(response.http_status_code == 500) alert(response.error);
			else $scope.check_code(response.http_status_code);
		});	
	}
});