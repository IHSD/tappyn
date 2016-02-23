tappyn.controller('contestController', function($scope, $routeParams, $location, contestFactory){
	contestFactory.grabContest($routeParams.id).success(function(response){
		$scope.contest = response.data.contest;
	});

	fbq('track', 'ViewContent');

	$scope.submit_to = function(id, submission){
		if($scope.user){
			contestFactory.submitTo(id, submission).success(function(response){
				if(response.success) $location.path("/submissions/"+id);
			})
		}
		else{
			submission.as_guest = true;
			$scope.sign_up(submission).success(function(response){
				if(response.success){
					$scope.user = response.data;
					sessionStorage.setItem("user", JSON.stringify(response.data));
					contestFactory.submitTo(id, submission).success(function(response){
						if(response.success) $location.path("/submissions/"+id);
					})
				}
			})
		}
	}
});