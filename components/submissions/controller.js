tappyn.controller("submissionsController", function($scope, $routeParams, contestFactory, submissionsFactory){
	submissionsFactory.grabSubmissions($routeParams.id).success(function(response){
		$scope.contest = response.data.contest;
		$scope.submissions = response.data.submissions;
	});

});