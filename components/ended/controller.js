tappyn.controller("endedController", function($scope, $location, $routeParams, endedFactory){
	endedFactory.grabContest($routeParams.id).success(function(response){
		$scope.contest = response.data.contest;
		$scope.winner = response.data.winner;
		if($scope.contest.status == 'active') $location.path('/contest/'+$routeParams.id);
	})
})