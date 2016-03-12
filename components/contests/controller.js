tappyn.controller('contestsController', function($scope, $rootScope, contestsFactory){
	if($rootScope.user){
		contestsFactory.grabContests().success(function(response){
			$scope.contests = response.data.contests;
		});
	}
	else $scope.open_register("default", '');

	$scope.filter_industry = function(pass){
		contestsFactory.filterGrab(pass).success(function(response){
			$scope.contests = response.data.contests;
		})
	}
	
	$scope.grab_all = function(){
		$scope.industry_filter = '';
		contestsFactory.grabContests().success(function(response){
			$scope.contests = response.data.contests;
		});
	}
})