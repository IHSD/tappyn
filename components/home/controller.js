tappyn.controller('homeController', function($scope, $location, homeFactory){
	
	fbq('track', 'ViewContent');
	
	$scope.mailing_list = function(email){
		homeFactory.mailingList(email).success(function(response){
			if(response.http_status_code == 200){
				if(response.success) window.location.reload();
				else alert(response.message);	 
			}
			else if(response.http_status_code == 500) alert(response.error);
			else $scope.check_code(response.http_status_code);
		})
	}
})