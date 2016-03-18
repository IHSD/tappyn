tappyn.controller('launchController', function($scope, $location, $anchorScroll, $upload, $route, $rootScope, launchFactory, launchModel, emotions){
	$scope.logged_in()
	$scope.steps = {
		'package'		 : {step : 'package',  next : 'detail',  previous : 'none',    fill : 25},
		'detail' 		 : {step : 'detail',   next : 'payment', previous : 'package', fill : 50},
		'preview' 		 : {step : 'preview',   next : 'payment', previous : 'package', fill : 75},
		'done'		 	 : {step : 'done',     next : 'none',    previous : 'none',    fill : 100}
	}
	$scope.current = $scope.steps['package'];
	$scope.personalities = emotions; 
	$scope.contest = {};
	$scope.company = {};
	$scope.save_method = false;

	$scope.registering = false;

	$scope.price = 99.99;

	$scope.close_register = function(){
		$rootScope.modal_up = false;
		$scope.registering = false;
	}

	$scope.set_step = function(step){
		if(step == 'detail'){
			if(!$scope.profile && $rootScope.user) $scope.grab_profile();
			$scope.current = $scope.steps[step];
		}
		else if(step == 'preview'){
			if(!$rootScope.user) $scope.open_register("company", '');
			else if(!$scope.contest.summary || $scope.contest.summary == '')  $scope.set_alert("A summary of service or product is required", "error");
			else if(!$scope.contest.industry || $scope.contest.industry == '')  $scope.set_alert("An industry is required", "error");
			else if(!$scope.contest.audience || $scope.contest.audience == '')  $scope.set_alert("A longer description is required", "error");
			else if(!$scope.contest.different || $scope.contest.different == '')  $scope.set_alert("What makes you different is required", "error");
			else{
				$scope.emotion_contest = launchModel.sift_images($scope.contest, $scope.personalities);
				$scope.current = $scope.steps[step];
			}
		}
		else $scope.current = $scope.steps[step];
		$scope.to_top();
	}

	$scope.select_objective = function(objective){
		$scope.contest.objective = objective;
		$scope.contest.display_type = null;
		var old = $location.hash();
		$location.hash("display");
		$anchorScroll();
		$location.hash(old);
	}

	$scope.select_platform = function(platform){
		$scope.contest.platform = platform;
		$scope.contest.objective = null;
		$scope.contest.display_type = null;
		var old = $location.hash();
		$location.hash("objective");
		$anchorScroll();
		$location.hash(old);
	}

	$scope.select_display = function(type){
		$scope.contest.display_type = type;
	}	

	$scope.choose_personality = function(type){
		$scope.contest.emotion = type;
	}

	$scope.grab_profile = function(){
		launchFactory.grabProfile().success(function(response){
			if(response.http_status_code == 200){
				if(response.success){
					$scope.profile = response.data;
					$scope.contest.different = $scope.profile.different;
					$scope.contest.audience = $scope.profile.audience;
					$scope.contest.summary = $scope.profile.summary;
				}
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		})
	}

	$scope.grab_payments = function(){
		launchFactory.grabDetails().success(function(response){
			if(response.http_status_code == 200){
				if(response.success) $scope.payments = response.data.customer.sources.data;
				else{
					$scope.adding_payment = true;	
				} 
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

	$scope.open_payment = function(){
		$scope.adding_payment = true;
		$rootScope.modal_up = true;
	}

	$scope.close_payment = function(){
		$scope.adding_payment = false;
		$rootScope.modal_up = false;
		$scope.set_alert("Saved as draft, to launch, pay in dashboard", "default");
		$scope.set_step("done");
	}


	$scope.submit_contest = function(contest, pay){
		if(contest.id){
			launchFactory.update(contest).success(function(response){
				if(response.http_status_code == 200){
					if(response.success){
						if(pay) $scope.open_payment();
						else{
							$scope.set_alert("Saved as draft, to launch, pay in dashboard", "default");
							$scope.set_step('done');
						}
					}
					else $scope.set_alert(response.message, "default");	 
				}
				else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
				else $scope.check_code(response.http_status_code);
			});	
		}	
		else{
			launchFactory.submission(contest).success(function(response){
				if(response.http_status_code == 200){
					if(response.success){
						$scope.contest.id = response.data.id;
						if(pay) $scope.open_payment();
						else{
							$scope.set_alert("Saved as draft, to launch, pay in dashboard", "default");
							$scope.set_step('done');
						}
					}
					else $scope.set_alert(response.message, "default");	 
				}
				else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
				else $scope.check_code(response.http_status_code);
			});	
		}
	}
	var stripeResponseHandler = function(status, response) {
      if(response.error){
      	var erroring = (response.error.message).toString();
   		alert(response.error.message);
        $scope.form_disabled = false;
      } 
      else{
        // response contains id and card, which contains additional card details
        var token = response.id;
       	launchFactory.payContest($scope.contest.id, {stripe_token : token, save_method : $scope.save_method, voucher_code : $scope.voucher_code}).success(function(res){
       		if(res.http_status_code == 200){
				if(res.success){
					$scope.set_alert(res.message, "default");	
					$scope.set_step("done");
					$rootScope.modal_up = false;
					$scope.adding_payment = false;
					$scope.form_disabled = false;
				}
				else $scope.set_alert(res.message, "default");	 
			}
			else if(res.http_status_code == 500) $scope.set_alert(res.error, "error");
			else $scope.check_code(res.http_status_code);
       	});
      }
    }


	$scope.new_payment = function(){
		// This identifies your website in the createToken call below
		Stripe.setPublishableKey("pk_live_ipFoSG1UY45RGNkCpLVUaSBx");
		var $form = $('#payment-form');

        // Disable the submit button to prevent repeated clicks
        $scope.form_disabled = true;

		Stripe.card.createToken($form, stripeResponseHandler);
	}

	$scope.old_payment = function(){
		if(!$scope.passing_method) $scope.set_alert("Please select a saved method or provide a new means of paying", "error");
		else{
			launchFactory.payContest($scope.contest.id, {source_id : $scope.passing_method, voucher_code : $scope.voucher_code}).success(function(res){
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

	$scope.use_voucher = function(){
		if(!$scope.voucher_code) $scope.set_alert("Please enter a voucher code", "error");
		else{
			launchFactory.payContest($scope.contest.id, {voucher_code : $scope.voucher_code}).success(function(res){
	       		if(res.http_status_code == 200){
					if(res.success) $scope.price = response.data.price;
					else $scope.set_alert(res.message, "default");	 
				}
				else if(res.http_status_code == 500) $scope.set_alert(res.error, "error");
				else $scope.check_code(res.http_status_code);
	       	});
		}
	}

	$scope.select_current = function(pass){
		$scope.passing_method = pass;
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

	$scope.reload = function(){
		$route.reload();
	}
});