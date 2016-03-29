tappyn.controller('contestController', function($scope, $rootScope, $route, $routeParams, $location, emotions, contestFactory, contestModel){
	$scope.emotions = emotions;
	contestFactory.grabContest($routeParams.id).success(function(response){
		$scope.contest = response.data.contest;
		$scope.submissions = response.data.submissions;
		if($scope.contest.status == "ended" && (!$rootScope.user || $rootScope.user.id != $scope.contest.owner || !$rootScope.user.is_admin)) $location.path('/ended/'+$routeParams.id);
		if($scope.contest.emotion){
			$scope.emotion_contest = contestModel.sift_images($scope.contest, $scope.emotions);
		}
		else $scope.example = false;
		$scope.form_limit = contestModel.parallel_submission($scope.contest);
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
		if($scope.form_limit.headline && submission.headline.length < 1) $scope.set_alert("Headline is required", "error");
		else if($scope.form_limit.text && submission.text.length < 1) $scope.set_alert("Text is required", "error");
		else if($scope.form_limit.line_1 && submission.link_explanation.length < 1) $scope.set_alert("Line 1 is required", "error");
		else if($scope.form_limit.line_2 && submission.text.length < 1) $scope.set_alert("Line 2 is required", "error");
		else if($scope.form_limit.card_title && submission.link_explanation.length < 1) $scope.set_alert("A card title is required", "error");
		else{
			if($rootScope.user){
				contestFactory.submitTo(id, submission).success(function(response){
					if(response.http_status_code == 200){
						if(response.success){
							$scope.set_alert(response.message, "default");	 
							$scope.update_points(2);
							ga('send', {
							hitType: 'event',
							eventCategory: 'Contest Submission',
							eventAction: 'submission',
							eventLabel: 'User Submission'});
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
		 	href: $location.protocol()+'://'+$location.host()+'/submissions/share/'+submission.id,
		}, function(response){
			
		});
	}

	$scope.show_tips = function(){
		$scope.tips = true;
	}
	$scope.hide_tips = function(){
		$scope.tips = false;
	} 
});