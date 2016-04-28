tappyn.controller('contestsController', function($scope, $rootScope, $location, contestsFactory){
	if(!$rootScope.user || $rootScope.user.type == 'company'){
		$scope.tab = "all";
		contestsFactory.grabAllContests().success(function(response){
			$scope.contests = response.data.contests;
			if(!$rootScope.user) $scope.open_register("must", "");
		});
	}
	else{
		$scope.tab = "my";
		contestsFactory.grabMyContests().success(function(response){
			if(response.http_status_code == 200){
				if(response.success) $scope.contests = response.data.contests;	
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500){
				$scope.set_alert(response.error, "default");
				$scope.adding_interests("first");
			}
			else $scope.check_code(response.http_status_code);
		})
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
		$scope.tab = "my";
		contestsFactory.grabMyContests().success(function(response){
			if(response.http_status_code == 200){
				if(response.success) $scope.contests = response.data.contests;	
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500){
				$scope.set_alert(response.error, "default");
				$scope.adding_interests();
			}
			else $scope.check_code(response.http_status_code);
		})
	}

	$scope.grab_companies = function(){
		$scope.tab = 'company';
		contestsFactory.grabCompanies().success(function(response){
			$scope.companies = response.data.companies;
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