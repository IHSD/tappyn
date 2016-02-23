tappyn.controller('contestController', function($scope, $rootScope, $routeParams, $location, contestFactory, AppFact){
	contestFactory.grabContest($routeParams.id).success(function(response){
		$scope.contest = response.data.contest;
	});

	$scope.guest_signup = false; 
	$scope.submit = {headline : '', text: ''};
	$scope.submit_to = function(id, submission){
		if(!submission.text || submission.text.length < 1) $scope.set_alert("Text is required", "error");
		else if(($scope.contest.platform == "google" || $scope.contest.platform == "facebook") && (!submission.headline || submission.headline.length < 1)) $scope.set_alert("Headline is required", "error");
		else{
			$scope.fb_pass = {contest : id, headline : submission.headline, text : submission.text};
			if($scope.user){
				contestFactory.submitTo(id, submission).success(function(response){
					if(response.http_status_code == 200){
						if(response.success){
							$scope.close_guest();
							$location.path("/submissions/"+id);
						}
						else $scope.set_alert(response.message, "default");	 
					}
					else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
					else $scope.check_code(response.http_status_code);
				})
			}
			else{
				$scope.guest_signup = true;
				$rootScope.modal_up = true;
			}
		}
	}

	$scope.sign_up_guest = function(registrant){
		registrant.group_id = 2;
		AppFact.signUp(registrant).success(function(response){
			if(response.http_status_code == 200){
				if(response.success){
					$scope.user = response.data;
					sessionStorage.setItem("user", JSON.stringify(response.data));
					$scope.submit_to($scope.contest.id, $scope.submit);
				}
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		})
	}

	$scope.close_guest = function(){
		$scope.guest_signup = false;
		$rootScope.modal_up = false;
	}
});