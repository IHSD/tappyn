tappyn.controller('contestController', function($scope, $routeParams, $location, contestFactory){
	contestFactory.grabContest($routeParams.id).success(function(response){
		$scope.contest = response.data.contest;
	});

	$scope.submit_to = function(id, submission){
		contestFactory.submitTo(id, submission).success(function(response){
			if(response.success) $location.path("/submissions/"+id);
		})
	}
});