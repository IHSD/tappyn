tappyn.controller('dashController', function($scope, dashFactory){
	//on page load grab all
	$scope.type = 'all';
	dashFactory.grabDash($scope.type).success(function(response){
		if(response.success) $scope.dash = response.data;
	});

	$scope.grab_dash = function(type){
		$scope.type = type;

		dashFactory.grabDash(type).success(function(response){
			if(response.success) $scope.dash = response.data;
		});
	}
})