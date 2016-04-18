tappyn.controller('contestController', function($scope, $rootScope, $route, $routeParams, $upload, $location, emotions, contestFactory, contestModel){
	$scope.emotions = emotions;
	contestFactory.grabContest($routeParams.id).success(function(response){
		$scope.contest = response.data.contest;
		$scope.submissions = response.data.submissions;
		contestModel.fire_google($scope.contest);
		if($scope.contest.status == "ended"){
			if($rootScope.user){
				if($rootScope.user.id != $scope.contest.owner && !$rootScope.user.is_admin) $location.path('/ended/'+$routeParams.id);
			}
			else $location.path('/ended/'+$routeParams.id);
		}
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

	$scope.imagerino = '';
	$scope.image_show = 'edit';
	$scope.cropper = new Cropper(document.getElementById('upload_contest'), {
		aspectRatio: 16 / 6,
	  	dragMode : 'move',
	  	scaleable : false,
	  	cropBoxResizable : false,
	  	cropBoxMovable : false,
	  	preview : '.img-preview'
	});

	$scope.upload_image = function(id, submission){
		var canvas = $scope.cropper.getCroppedCanvas();
		var url = 'https://tappyn.s3.amazonaws.com/';
	    var new_name = Date.now();
	    var rando = Math.random() * (10000 - 1) + 1;
	    new_name = new_name.toString() + rando.toString();
		var blobbers = canvas.toBlob(function(blob){
			var file = blob;
			$upload.upload({
		        url: url,
		        method: 'POST',
		        data : {
		            key: new_name,
		            acl: 'public-read',
		            "Content-Type": file.type === null || file.type === '' ?
		            'application/octet-stream' : file.type,
		            AWSAccessKeyId: $rootScope.key.key,
		            policy: $rootScope.key.policy,
		            signature: $rootScope.key.signature
		        },
		        file: file,
		    }).success(function (){
	       		submission.attachment_url = url+new_name;
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
		    });
		}, "image/png", 0.95);
	}
	$scope.submit = {headline : '', text: ''};
	$scope.submit_to = function(id, submission){
		if($scope.form_limit.headline && submission.headline.length < 1) $scope.set_alert("Headline is required", "error");
		else if($scope.form_limit.text && submission.text.length < 1) $scope.set_alert("Text is required", "error");
		else if($scope.form_limit.line_1 && submission.link_explanation.length < 1) $scope.set_alert("Line 1 is required", "error");
		else if($scope.form_limit.line_2 && submission.text.length < 1) $scope.set_alert("Line 2 is required", "error");
		else if($scope.form_limit.card_title && submission.link_explanation.length < 1) $scope.set_alert("A card title is required", "error");
		else if($scope.form_limit.photo && $scope.imagerino == "") $scope.set_alert("An uploaded image is required for this contest", "error");
		else{
			if($scope.form_limit.photo){
				$scope.upload_image(id, submission);
			}
			else{
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

	var handleFileSelect=function(evt) {
      var file=evt.currentTarget.files[0];
      var reader = new FileReader();
      reader.onload = function (evt) {
        $scope.$apply(function($scope){
          $scope.imagerino=evt.target.result;
          $scope.cropper.replace(evt.target.result);
        });
      };
      reader.readAsDataURL(file);
    };
    angular.element(document.querySelector('#fileInput')).on('change',handleFileSelect);

    $scope.preview_image = function(){
    	$scope.preview = $scope.cropper.getCroppedCanvas().toDataURL("image/png");
    	$scope.image_show = 'preview';
    }

	$scope.chooserino = function(){
		var photo = angular.element(document.getElementById('upload_contest'));		
	}
});