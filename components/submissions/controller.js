tappyn.controller("submissionsController", function($scope, $rootScope, $routeParams, contestFactory, submissionsFactory, AppFact){
	submissionsFactory.grabSubmissions($routeParams.id).success(function(response){
		$scope.contest = response.data.contest;
		$scope.submissions = response.data.submissions;
	});


	$scope.choose_winner = function(id){
		submissionsFactory.chooseWinner($scope.contest.id, id).success(function(response){
			if(response.http_status_code == 200){
				if(response.success) $scope.set_alert(response.message, "default");	
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		})
	}

	$scope.upvote = function(submission){
		if(!$rootScope.user) $scope.open_register("upvote", {contest : $scope.contest.id, submission : submission.id});
		else {	
			submissionsFactory.upvote($scope.contest.id,submission.id).success(function(response){
				if(response.http_status_code == 200){
					if(response.success){
						$scope.set_alert(response.message, "default");
						$scope.update_points(1);
						submission.user_may_vote = false;
						submission.votes++;
					}	
					else $scope.set_alert(response.message, "default");	 
				}
				else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
				else $scope.check_code(response.http_status_code);
			})
		}
	}
});