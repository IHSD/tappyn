tappyn.controller('launchController', function($scope, $location, $upload, $rootScope, launchFactory, AppFact){
	$scope.steps = {
		'package'		 : {step : 'package',  next : 'detail',  previous : 'none',    fill : 0},
		'detail' 		 : {step : 'detail',   next : 'payment', previous : 'package', fill : 33},
		'payment'		 : {step : 'payment',  next : 'none',    previous : 'detail',  fill : 66},
		'done'		 	 : {step : 'done',     next : 'none',    previous : 'none',    fill : 100}
	}
	$scope.current = $scope.steps['package'];

	$scope.contest = {};
	$scope.company = {};
	$scope.save_method = false;

	$scope.registering = false;

	$scope.close_register = function(){
		$rootScope.modal_up = false;
		$scope.registering = false;
	}

	$scope.set_step = function(step){
		$scope.current = $scope.steps[step];
		if(step == "payment"){
			if(!$scope.payments && $rootScope.user) $scope.grab_payments();
		}
		else if(step == 'detail'){
			if(!$scope.profile && $rootScope.user) $scope.grab_profile();
		}
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
		launchFactory.grabProfile().success(function(response){
			if(response.http_status_code == 200){
				if(response.success) $scope.profile = response.data;
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		})
	}

	$scope.grab_payments = function(){
		launchFactory.grabDetails().success(function(response){
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
		else $scope.set_step("detail");
	}

	$scope.submit_contest = function(contest){
		if(!$rootScope.user){
			$rootScope.modal_up = true;
			$scope.registering = true;
		}
		else{
			if(!contest.summary || contest.summary == '')  $scope.set_alert("A summary of service or product is required", "error");
			else if(!contest.industry || contest.industry == '')  $scope.set_alert("An industry is required", "error");
			else if(!contest.audience || contest.audience == '')  $scope.set_alert("A longer description is required", "error");
			else if(!contest.different || contest.different == '')  $scope.set_alert("What makes you different is required", "error");
			else{	
				launchFactory.submission(contest).success(function(response){
					if(response.http_status_code == 200){
						if(response.success){
							$scope.set_alert(response.message, "default");
							$scope.contest.id = response.data.id;
							$scope.set_step('payment');
						}
						else $scope.set_alert(response.message, "default");	 
					}
					else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
					else $scope.check_code(response.http_status_code);
				});	
			}
		}
	}

	$scope.create_company = function(registrant){
		registrant.group_id = 3;
		AppFact.signUp(registrant).success(function(response){
			if(response.http_status_code == 200){
				if(response.success){
					$rootScope.user = response.data;
					sessionStorage.setItem("user", JSON.stringify(response.data));
					$rootScope.modal_up = false;
					$scope.registering = false;
					$scope.submit_contest($scope.contest);
				}
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		})
	}

	$scope.old_payment = function(id){
		launchFactory.payContest($scope.contest.id, {source_id : id}).success(function(response){
			if(response.http_status_code == 200){
				if(response.success){
					$scope.set_alert(response.message, "default");	
					$scope.set_step("done");
				}
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		});
	}

	$scope.new_payment = function(){
		// This identifies your website in the createToken call below
		Stripe.setPublishableKey("pk_live_ipFoSG1UY45RGNkCpLVUaSBx");
		var $form = $('#payment-form');

        // Disable the submit button to prevent repeated clicks
        $scope.form_disabled = true;

		Stripe.card.createToken($form, stripeResponseHandler);
	}

	function stripeResponseHandler(status, response) {
      var $form = $('#payment-form');

      if(response.error){
        $scope.set_alert(response.error.message, "error");
        $scope.form_disabled = false;
      } 
      else{
        // response contains id and card, which contains additional card details
        var token = response.id;
       	launchFactory.payContest($scope.contest.id, {stripe_token : token, save_method : $scope.save_method}).success(function(res){
       		if(res.http_status_code == 200){
				if(res.success){
					$scope.set_alert(res.message, "default");	
					$scope.set_step("done");
				}
				else $scope.set_alert(res.message, "default");	 
			}
			else if(res.http_status_code == 500) $scope.set_alert(res.error, "error");
			else $scope.check_code(res.http_status_code);
       	});
      }
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
	       	if(type == "logo") $scope.company.logo_url = url+new_name;
	       	else if(type == "pic1") $scope.contest.additional_image_1 = url+new_name;
	       	else if(type == 'pic2') $scope.contest.additional_image_2 = url+new_name;
	       	else if(type == 'pic3') $scope.contest.additional_image_3 = url+new_name;
	    });
	}
});