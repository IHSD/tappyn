tappyn.controller('contestsController', function($scope, $rootScope, contestsFactory){
	contestsFactory.grabContests().success(function(response){
		$scope.contests = response.data.contests;
		if(!$rootScope.user){
			$scope.contests_login = true;
			$rootScope.modal_up = true;
		}
	});

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

	$scope.to_account = function(){
		$scope.with_email = false;
		$scope.have_account = true;
		$scope.forgot = false;
	}

	$scope.email_regis = function(){
		$scope.with_email = true;
		$scope.have_account = false;
		$scope.forgot = false;
	}

	$scope.forgotten = function(){
		$scope.with_email = false;
		$scope.have_account = false;
		$scope.forgot = true;
	}
})