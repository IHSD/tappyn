tappyn.controller('contestsController', function($scope, contestsFactory){
	contestsFactory.grabContests().success(function(response){
		$scope.contests = response.data;
	});
})