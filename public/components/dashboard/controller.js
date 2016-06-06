tappyn.controller('dashController', function($scope, $rootScope, $route, dashFactory){
	
	//on page load grab all
	$scope.type = '';
	$scope.adding_payment = {show : false, id : ''};
	$scope.confirm_winner = {show : false, submission : null};
	dashFactory.grabDash($scope.type).success(function(response){
		if(response.http_status_code == 200){
			if(response.success) $scope.dash = response.data;
			else alert(response.message);
		}
		else if(response.http_status_code == 500) alert(response.error);
		else $scope.check_code(response.http_status_code);
	});

	dashFactory.grabTotals().success(function(response){
		if(response.http_status_code == 200){
			if(response.success) $scope.totals = response.data;
			else alert(response.message);
		}
		else if(response.http_status_code == 500) alert(response.error);
		else $scope.check_code(response.http_status_code);
	})
	$scope.price = 99.99;

	$scope.grab_dash = function(type){
		$scope.type = type;

		if(type == "upvotes"){
			dashFactory.grabUpvoted().success(function(response){
				if(response.success) $scope.dash = response.data;
			});
		}
		else{
			dashFactory.grabDash(type).success(function(response){
				if(response.success) $scope.dash = response.data;
			});
		}
		$scope.view = 'table';
	}
	$scope.view = "table";


	$scope.set_type_dash = function(type){
		$scope.type = type;
	}

	$scope.back_table = function(){
		$scope.view = 'table';
	}



	/** start winner functions, functions for assembling the winner view, opening and closing the modal for
		confirmation and the actual choosing of a winner **/
	$scope.choosing_winner = function(contest){
		dashFactory.grabSubmissions(contest.id).success(function(response){
			if(response.http_status_code == 200){
				if(response.success){
					$scope.winner_contest = contest; //to pass with the chosen submission
					$scope.submissions = response.data.submissions;
					$scope.view = 'winner';
				}
				else alert(response.message);
			}
			else if(response.http_status_code == 500) alert(response.error);
			else $scope.check_code(response.http_status_code);
		});
	}

	$scope.confirming_winner = function(submission){
		$scope.confirm_winner = {show : true, submission : submission};
		$rootScope.modal_up = true;
	}

	$scope.choose_winner = function(id){
		dashFactory.chooseWinner($scope.winner_contest.id, id).success(function(response){
			if(response.http_status_code == 200){
				if(response.success){
					$scope.set_alert(response.message, "default");
					$scope.confirm_winner = {show : false, submission : null};
					$rootScope.modal_up = false;
					$scope.winner_contest.status = "completed";
					$scope.view_pcp($scope.winner_contest.id);
				}
				else $scope.set_alert(response.message, "default");
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		})
	}

	$scope.close_confirm = function(){
		$scope.confirm_winner = {show : false, submission : null};
		$rootScope.modal_up = false;
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
	/** end winner functions **/

	$scope.view_pcp = function(id){
		dashFactory.viewWinner(id).success(function(response){
			if(response.http_status_code == 200){
				if(response.success){
					$scope.winner = response.data;
					$scope.view = "pcp";
				}
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
					$scope.add_new = false;
				}
				else $scope.add_new = true;
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
   		alert(response.error.message);
        $scope.form_disabled = false;
      }
      else{
        // response contains id and card, which contains additional card details
        var token = response.id;
       	dashFactory.payContest($scope.adding_payment.contest.id, {stripe_token : token, save_method : $scope.save_method, voucher_code : $scope.voucher_code}).success(function(res){
       		if(res.http_status_code == 200){
				if(res.success){
					$scope.set_alert(res.message, "default");
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
		if($scope.price == 0.00){
			if(!$scope.voucher_code) $scope.set_alert("Please enter a voucher code", "error");
			else{
				dashFactory.payContest($scope.adding_payment.contest.id, {voucher_code : $scope.voucher_code}).success(function(res){
		       		if(res.http_status_code == 200){
						if(res.success){
							$scope.set_alert(res.message, "default");
							$rootScope.modal_up = false;
							$scope.adding_payment = false;
							$route.reload();
						}
						else $scope.set_alert(res.message, "default");
					}
					else if(res.http_status_code == 500) $scope.set_alert(res.error, "error");
					else $scope.check_code(res.http_status_code);
		       	});
			}
		}
		else{
			// This identifies your website in the createToken call below
			Stripe.setPublishableKey(APP_ENV.stripe_api_publishable_key);
			var $form = $('#payment-form');

	        // Disable the submit button to prevent repeated clicks
	        $scope.form_disabled = true;

			Stripe.card.createToken($form, stripeResponseHandler);
		}
	}

	$scope.old_payment = function(){
		if($scope.price == 0.00){
			if(!$scope.voucher_code) $scope.set_alert("Please enter a voucher code", "error");
			else{
				dashFactory.payContest($scope.adding_payment.contest.id, {voucher_code : $scope.voucher_code}).success(function(res){
		       		if(res.http_status_code == 200){
						if(res.success){
							$scope.set_alert(res.message, "default");
							$rootScope.modal_up = false;
							$scope.adding_payment = false;
							$route.reload();
						}
						else $scope.set_alert(res.message, "default");
					}
					else if(res.http_status_code == 500) $scope.set_alert(res.error, "error");
					else $scope.check_code(res.http_status_code);
		       	});
			}
		}
		else{
			if(!$scope.passing_method) $scope.set_alert("Please select a saved method or provide a new means of paying", "error");
			else{
				dashFactory.payContest($scope.adding_payment.contest.id, {source_id : $scope.passing_method, voucher_code : $scope.voucher_code}).success(function(res){
		       		if(res.http_status_code == 200){
						if(res.success){
							$scope.set_alert(res.message, "default");
							$rootScope.modal_up = false;
							$scope.adding_payment = false;
							$route.reload();
						}
						else $scope.set_alert(res.message, "default");
					}
					else if(res.http_status_code == 500) $scope.set_alert(res.error, "error");
					else $scope.check_code(res.http_status_code);
		       	});
			}
		}
	}

	$scope.use_voucher = function(){
		if(!$scope.voucher_code) $scope.set_alert("Please enter a voucher code", "error");
		else{
			dashFactory.voucherValid($scope.voucher_code).success(function(res){
	       		if(res.http_status_code == 200){
					if(res.success){
						$scope.price = res.data.price;
						$scope.reduction = res.data.discount;
					}
					else $scope.set_alert(res.message, "default");
				}
				else if(res.http_status_code == 500) $scope.set_alert(res.error, "error");
				else $scope.check_code(res.http_status_code);
	       	});
		}
	}

	$scope.voucher_payment = function(){
		if(!$scope.voucher_code) $scope.set_alert("Please enter a voucher code", "error");
		else{
			dashFactory.payContest($scope.adding_payment.contest.id, {voucher_code : $scope.voucher_code}).success(function(res){
	       		if(res.http_status_code == 200){
					if(res.success){
						$scope.set_alert(res.message, "default");
						$rootScope.modal_up = false;
						$scope.adding_payment = false;
						$route.reload();
					}
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

})
