tappyn.controller("companiesController", function($scope, $rootScope, companiesFactory){
	$scope.grab_my = function(){
		$scope.tab = "my";
		companiesFactory.grabMyCompanies().success(function(response){
			if(response.http_status_code == 200){
				if(response.success) $scope.companies = response.data.companies;	
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "default");
			else $scope.check_code(response.http_status_code);
		})
	}

	$scope.grab_companies = function(){
		$scope.tab = 'company';
		companiesFactory.grabCompanies().success(function(response){
			if(response.http_status_code == 200){
				if(response.success) $scope.companies = response.data.companies;	
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "default");
			else $scope.check_code(response.http_status_code);
		});
	}	

	$scope.grab_companies();
});