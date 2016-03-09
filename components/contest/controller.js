tappyn.controller('contestController', function($scope, $rootScope, $route, $routeParams, $location, contestFactory, AppFact){
	contestFactory.grabContest($routeParams.id).success(function(response){
		$scope.contest = response.data.contest;
		$scope.submissions = response.data.submissions;
		if($scope.contest.status == "ended" && (!$rootScope.user || $rootScope.user.id != $scope.content.owner || !$rootScope.user.is_admin)) $location.path('/ended/'+$routeParams.id);
	});

	$scope.view = {brief : true, submissions : false};
	$scope.view_brief = function(){
		$scope.view = {brief : true, submissions : false};
	}
	$scope.view_submissions = function(){
		$scope.view = {brief : false, submissions : true};
	}

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
							$scope.update_points(2);
							$route.reload();
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

	$scope.choose_winner = function(id){
		contestFactory.chooseWinner($scope.contest.id, id).success(function(response){
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
			contestFactory.upvote($scope.contest.id,submission.id).success(function(response){
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

	$scope.share = function(submission){
		FB.ui({
  			method: 'share',
		 	href: 'https://test.tappyn.com/submissions/share/'+submission.id,
		}, function(response){
			console.log(response);
		});
	}

	$scope.show_tips = function(){
		$scope.tips = true;
	}
	$scope.hide_tips = function(){
		$scope.tips = false;
	} 
});