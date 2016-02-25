tappyn.controller('launchController', function($scope, $location, $upload, $rootScope, launchFactory){
	$scope.steps = {
		'package'		 : {step : 'package',  next : 'detail',  previous : 'none',    fill : 0},
		'detail' 		 : {step : 'detail',   next : 'payment', previous : 'package', fill : 33},
		'payment'		 : {step : 'payment',  next : 'none',    previous : 'detail',  fill : 66},
		'done'		 	 : {step : 'done',     next : 'none',    previous : 'none',    fill : 100}
	}
	$scope.current = $scope.steps['package'];

	$scope.contest = {};

	$scope.set_step = function(step){
		$scope.current = $scope.steps[step];
		if(step == "payment") if(!$scope.payments) $scope.grab_payments();
		else if(step == 'detail') if(!$scope.profile) $scope.grab_profile();
	}

	$scope.select_objective = function(objective){
		$scope.contest.objective = objective;
	}

	$scope.select_platform = function(platform){
		$scope.contest.platform = platform;
	}

	$scope.select_display = function(display){
		$scope.contest.display = display;
	}

	$scope.grab_profile = function(){
		launchFactory.grabDetails.success(function(response){
			if(response.http_status_code == 200){
				if(response.success) $scope.profile = response.data;
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		})
	}

	$scope.grab_payments = function(){
		launchFactory.grabDetails.success(function(response){
			if(response.http_status_code == 200){
				if(response.success) $scope.payments = response.data;
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		})
	}

	$scope.to_detail = function(contest){
		if(!contest.platform || contest.platform == '') $scope.set_alert("You need to select a platform", "error");
		else if(!contest.objective || contest.objective == '')  $scope.set_alert("You need to select an ad objective", "error");
		else if(!contest.display || contest.display == '')  $scope.set_alert("A summary of service or product is required", "error");
		else $scope.set_step("detail");
	}

	$scope.submit_contest = function(contest){
		if(!contest.company_name || contest.company_name == '') $scope.set_alert("Your company name is required", "error");
		else if(!contest.company_email || contest.company_email == '')  $scope.set_alert("Your company email is required", "error");
		else if(!contest.summary || contest.summary == '')  $scope.set_alert("A summary of service or product is required", "error");
		else if(!contest.audience || contest.audience == '')  $scope.set_alert("A longer description is required", "error");
		else if(!contest.different || contest.different == '')  $scope.set_alert("What makes you different is required", "error");
		else{	
			launchFactory.submission(contest).success(function(response){
				if(response.http_status_code == 200){
					if(response.success){
						$scope.set_alert(response.message, "default");
						$scope.set_step('payment');
					}
					else $scope.set_alert(response.message, "default");	 
				}
				else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
				else $scope.check_code(response.http_status_code);
			});	
		}
	}

	$scope.choose_payment = function(){

	}

	$scope.amazon_connect('tappyn');
	$scope.select_file = function($files, type){
	    var file = $files[0];
	    var url = 'https://tappyn.s3.amazonaws.com/';
	    var new_name = Date.now();
	    var rando = Math.random() * (10000 - 1) + 1;
	    new_name = new_name.toString() + rando.toString();
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
	       	if(type == "logo") $scope.contest.logo_url = url+new_name;
	       	else if(type == "pic1") $scope.contest.additional_image_1 = url+new_name;
	       	else if(type == 'pic2') $scope.contest.additional_image_2 = url+new_name;
	       	else if(type == 'pic3') $scope.contest.additional_image_3 = url+new_name;
	    });
	}
});