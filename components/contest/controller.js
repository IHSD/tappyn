tappyn.controller('contestController', function($scope, $routeParams, contestFactory){
	contestFactory.grabContest($routeParams.id).success(function(response){
		$scope.contest = response.data.contest;
	});
});