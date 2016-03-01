tappyn.controller('contestController', function($scope, $rootScope, $routeParams, $location, contestFactory, AppFact){
	contestFactory.grabContest($routeParams.id).success(function(response){
		$scope.contest = response.data.contest;
	});

	$scope.submit = {headline : '', text: ''};
	$scope.submit_to = function(id, submission){
		if(!submission.text || submission.text.length < 1) $scope.set_alert("Text is required", "error");
		else if(($scope.contest.platform == "google" || $scope.contest.platform == "facebook") && (!submission.headline || submission.headline.length < 1)) $scope.set_alert("Headline is required", "error");
		else{
			if($rootScope.user){
				contestFactory.submitTo(id, submission).success(function(response){
					if(response.http_status_code == 200){
						if(response.success){
							$scope.set_alert(response.message, "default");	 
							$location.path("/submissions/"+id);
							$scope.update_points(2);
						}
						else $scope.set_alert(response.message, "default");	 
					}
					else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
					else $scope.check_code(response.http_status_code);
				})
			}
			else $scope.open_register("contest", encodeURIComponent(JSON.stringify({contest : id, headline : submission.headline, text : submission.text})));
		}
	}
});