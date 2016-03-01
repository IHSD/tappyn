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

	$scope.sign_up_guest = function(registrant){
		registrant.group_id = 2;
		AppFact.signUp(registrant).success(function(response){
			if(response.http_status_code == 200){
				if(response.success){
					$rootScope.user = response.data;
					sessionStorage.setItem("user", JSON.stringify(response.data));
        			$rootScope.modal_up = false;
					$scope.upvote($scope.as_guest.submission);
					$scope.as_guest = {show : false, submission : ''};
				}
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		})
	}

	$scope.close_guest = function(){
		$scope.as_guest = {show : false, submission : ''};
        $rootScope.modal_up = false;
	}

	$scope.upvote = function(submission){
		if(!$rootScope.user){
			$scope.pass_id = submission.id;
			$scope.as_guest = {show : true, submission : submission};
			$rootScope.modal_up = true;
		}
		else {	
			submissionsFactory.upvote($scope.contest.id,submission.id).success(function(response){
				if(response.http_status_code == 200){
					if(response.success){
						$scope.set_alert(response.message, "default");
						$rootScope.user.points++;
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