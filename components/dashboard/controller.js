tappyn.controller('dashController', function($scope, $rootScope, dashFactory){
	//on page load grab all
	$scope.type = 'all';
	$scope.adding_payment = {show : false, id : ''};
	dashFactory.grabDash($scope.type).success(function(response){
		if(response.http_status_code == 200){
			if(response.success) $scope.dash = response.data;
			else alert(response.message);	 
		}
		else if(response.http_status_code == 500) alert(response.error);
		else $scope.check_code(response.http_status_code);
	});

	$scope.grab_dash = function(type){
		$scope.type = type;

		dashFactory.grabDash(type).success(function(response){
			if(response.success) $scope.dash = response.data;
		});
	}

	$scope.claim_winnings = function(id){
		dashFactory.claimWinnings(id).success(function(response){
			if(response.http_status_code == 200){
				if(response.success) $scope.set_alert(response.message, "default");	
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		});
	}

	$scope.set_type = function(type){
		$scope.adding_payment.type = type;
	}

	$scope.open_payment = function(contest){
		dashFactory.grabDetails().success(function(response){
			if(response.http_status_code == 200){
				$scope.adding_payment = {show : true, contest : contest};
				$rootScope.modal_up = true;
				if(response.success){ 
					$scope.payments = response.data.customer.sources.data;
					$scope.adding_payment.type = 'old';
				}
				else $scope.adding_payment.type = 'new';
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		})
	}

	$scope.close_payment = function(){
		$scope.adding_payment = {show : false, contest : '', type : ''};
		$rootScope.modal_up = false;
	}


	var stripeResponseHandler = function(status, response) {
      if(response.error){
      	var erroring = (response.error.message).toString();
        $scope.form_disabled = false;
      } 
      else{
        // response contains id and card, which contains additional card details
        var token = response.id;
       	dashFactory.payContest($scope.adding_payment.contest.id, {stripe_token : token, save_method : $scope.save_method}).success(function(res){
       		if(res.http_status_code == 200){
				if(res.success){
					$scope.set_alert(res.message, "default");	
					if($scope.adding_payment.contest.start_time >= moment()) $scope.adding_payment.contest.status = 'active';
					else $scope.adding_payment.contest.status = 'scheduled';
					$rootScope.modal_up = false;
					$scope.adding_payment = {show : false, contest : '', type : ''};
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
			dashFactory.payContest($scope.adding_payment.contest.id, {source_id : $scope.passing_method}).success(function(res){
	       		if(res.http_status_code == 200){
					if(res.success){
						$scope.set_alert(res.message, "default");	
						if($scope.adding_payment.contest.start_time >= moment()) $scope.adding_payment.contest.status = 'active';
						else $scope.adding_payment.contest.status = 'scheduled';
						$scope.adding_payment = {show : false, contest : '', type : ''};
						$rootScope.modal_up = false;
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
			dashFactory.payContest($scope.adding_payment.contest.id, {voucher_code : $scope.voucher_code}).success(function(res){
	       		if(res.http_status_code == 200){
					if(res.success){
						$scope.set_alert(res.message, "default");	
						if($scope.adding_payment.contest.start_time >= moment()) $scope.adding_payment.contest.status = 'active';
						else $scope.adding_payment.contest.status = 'scheduled';
						$scope.adding_payment = {show : false, contest : '', type : ''};
						$rootScope.modal_up = false;
					}
					else $scope.set_alert(res.message, "default");	 
				}
				else if(res.http_status_code == 500) $scope.set_alert(res.error, "error");
				else $scope.check_code(res.http_status_code);
	       	});
		}
	}
})