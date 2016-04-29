tappyn.controller("editController", function($scope, $rootScope, $upload, $routeParams, editFactory){
	$scope.logged_in();
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
    	var new_name = Date.now();
	    var rando = Math.random() * (10000 - 1) + 1;
	    var namen = new_name.toString() + rando.toString();
	    $upload.upload({
	        url: url,
	        method: 'POST',
	        data : {
	            key: namen,
	            acl: 'public-read',
	            "Content-Type": file.type === null || file.type === '' ?
	            'application/octet-stream' : file.type,
	            AWSAccessKeyId: $rootScope.key.key,
	            policy: $rootScope.key.policy,
	            signature: $rootScope.key.signature
	        },
	        file: file,
	    }).success(function (){
	       	if(type == "pic1"){
	       		$scope.contest.additional_image_1 = url + namen;
	       		$scope.contest.additional_images[0] = url + namen;
	       	}
	       	else if(type == 'pic2'){
	       		$scope.contest.additional_image_2 = url + namen;
	       		$scope.contest.additional_images[1] = url + namen;
	       	}
	       	else if(type == 'pic3'){
	       		$scope.contest.additional_image_3 = url + namen;
	       		$scope.contest.additional_images[2] = url + namen;
	       	}
	    });
	}
})