tappyn.controller("paymentController", function($scope, $location, paymentFactory, paymentModel){
	$scope.countries = paymentModel.countries;
	$scope.showing = "methods";
	paymentFactory.grabDetails().success(function(response){
		if(response.http_status_code == 200){
			if(response.success){
				if($scope.user.type == "member" && response.data.account == false){
					$scope.detail = {first_name : $scope.user.first_name, last_name : $scope.user.last_name};
					$scope.showing = 'details';
				}
				else $scope.showing = 'methods';
				$scope.account = response.data.account;
			}
			else $scope.set_alert(response.message, "default");	 
		}
		else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
		else $scope.check_code(response.http_status_code);
	})

	$scope.verify_identity = function(detail){
		paymentFactory.verifyIdentity(detail).success(function(response){
			if(response.http_status_code == 200){
				if(response.success){
					$scope.set_alert(response.message, "default");	
					$scope.account = response.data.account;
					$scope.showing = 'methods';
				}
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		});
	}

	$scope.toggle_view = function(view){
		$scope.showing = view;
	}

	function stripeResponseHandler(status, response) {
      var $form = $('#payment-form');

      if (response.error) {
        $scope.set_alert(response.error.message, "error");
        $scope.form_disabled = false;
      } else {
        // response contains id and card, which contains additional card details
        var token = response.id;
       	paymentFactory.addPayment(token).success(function(res){
       		if(res.http_status_code == 200){
				if(res.success){
					$scope.account = res.data.account;
					$scope.set_alert(res.message, "default");	
				}
				else $scope.set_alert(res.message, "default");	 
			}
			else if(res.http_status_code == 500) $scope.set_alert(res.error, "error");
			else $scope.check_code(res.http_status_code);
       	});
      }
    };
	$scope.process_addition = function(){
		// This identifies your website in the createToken call below
		Stripe.setPublishableKey("pk_live_ipFoSG1UY45RGNkCpLVUaSBx");
		var $form = $('#payment-form');

        // Disable the submit button to prevent repeated clicks
       $scope.form_disabled = true;

		Stripe.card.createToken($form, stripeResponseHandler);
	}

	$scope.remove_method = function(means){
		paymentFactory.removeMethod(means).success(function(response){
			if(response.http_status_code == 200){
				if(response.success){
					$scope.account = response.data.account;
					$scope.set_alert(response.message, "default");
				}
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		});
	}

	$scope.set_default = function(means){
		paymentFactory.setDefault(means).success(function(response){
			if(response.http_status_code == 200){
				if(response.success){
					$scope.account = response.data.account;
					$scope.set_alert(response.message, "default");
				}
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		});
	}
});