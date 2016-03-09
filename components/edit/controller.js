tappyn.controller("editController", function($scope, $routeParams, editFactory){
	if($routeParams.id){
		editFactory.grabEdit($routeParams.id).success(function(response){
			if(response.http_status_code == 200){
				if(response.success) $scope.contest = response.data.contest;	
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		})
	}

	$scope.edit = function(contest){
		editFactory.editContest(contest).success(function(response){
			if(response.http_status_code == 200){
				if(response.success) $scope.set_alert(response.message, "default");	
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		})
	}

	$scope.amazon_connect('tappyn');
	$scope.select_file = function($files, type){
	    var file = $files[0];
	    var url = 'https://tappyn.s3.amazonaws.com/';
		if($scope.contest.additional_image[0]) var namen = $scope.contest.additional_image[0];
       	else if($scope.contest.additional_image[1]) var namen = $scope.contest.additional_image[1];
       	else if($scope.contest.additional_image[2]) var namen = $scope.contest.additional_image[2];    
	    else {
	    	var new_name = Date.now();
		    var rando = Math.random() * (10000 - 1) + 1;
		    namen = url + new_name.toString() + rando.toString();
		}
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
	       	if(type == "pic1") $scope.contest.additional_image[0] = namen;
	       	else if(type == 'pic2') $scope.contest.additional_image[1] = namen;
	       	else if(type == 'pic3') $scope.contest.additional_image[2] = namen;
	    });
	}
})