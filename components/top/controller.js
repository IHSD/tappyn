tappyn.controller('topController', function($scope, topFactory){
	

	topFactory.grabTops().success(function(response){
		$scope.submissions = response.data.submissions;
	})
})