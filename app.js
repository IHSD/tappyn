var tappyn = angular.module('tappyn', [
	'ngRoute',
	'ui.bootstrap',
	'ngAnimate',
	'angularFileUpload'
]);

tappyn.config(function($routeProvider) {
	$routeProvider
	.when('/home', {
		templateUrl : 'components/home/view.html',
		controller : 'homeController'
	})
	.when('/dashboard', {
		templateUrl : 'components/dashboard/view.html',
		controller : 'dashController'
	})
	.when('/launch', {
		templateUrl : 'components/launch/view.html',
		controller : 'launchController'
	})
	.when('/profile', {
		templateUrl : 'components/profile/view.html',
		controller : 'profileController'
	})
	.when('/top', {
		templateUrl : 'components/top/view.html',
		controller : 'topController'
	})
	.when('/contests', {
		templateUrl : 'components/contests/view.html',
		controller : 'contestsController'
	})
	.when('/contest/:id', {
		templateUrl : 'components/contest/view.html',
		controller : 'contestController'
	})
	.when('/edit/:id', {
		templateUrl : 'components/edit/view.html',
		controller : 'editController'
	})
	.when('/ended/:id', {
		templateUrl : 'components/ended/view.html',
		controller : 'endedController'
	})
	.when('/payment', {
		templateUrl : 'components/payment/view.html',
		controller : 'paymentController'
	})
	.when('/contact_us', {
		templateUrl : 'components/contact_us/view.html'
	})
	.when('/companies', {
		templateUrl : 'components/company/view.html'
	})
	.when('/faq', {
		templateUrl : 'components/faq/view.html'
	})
	.when('/privacy', {
		templateUrl : 'components/privacy_policy/view.html'
	})
	.when('/terms', {
		templateUrl : 'components/terms_of_service/view.html'
	})
	.when('/forgot_pass', {
		templateUrl : 'components/forgot_pass/view.html'
	})
	.when('/reset_pass/:code', {
		templateUrl : 'components/reset_pass/view.html',
		controller  : 'resetController'
	})
	.otherwise({redirectTo : '/home'})

});

tappyn.filter('untilFilter', function() {
	return function(date){
		date = moment(date).fromNow("hh");
		return date;
	};
});

tappyn.filter('legibleDate', function() {
	return function(date){
		date = moment(date).format("MMM, Do");
		return date;
	};
});

tappyn.filter('dashDate', function() {
	return function(date){
		date = moment(date).format("lll");
		return date;
	};
}); 

tappyn.filter('capitalize', function() {
  return function(input) {
    if (input!=null){
    	input = input.toLowerCase();
    	return input.substring(0,1).toUpperCase()+input.substring(1);
    }
  }
});

tappyn.filter('capUnderscore', function() {
  return function(input) {
    if (input!=null){
    	input = input.split('_');
    	new_stringers = '';
    	for(var i = 0; i < input.length; i++){
    		new_stringers = new_stringers + ' ' +input[i].substring(0,1).toUpperCase()+input[i].substring(1)
    	}
    	return new_stringers;
    }
  }
});

tappyn.filter('firstChar', function() {
  return function(input) {
    if (input!=null){
    	input = input.toLowerCase();
    	return input.substring(0,1).toUpperCase();
    }
  }
});


tappyn.controller("ApplicationController", function($scope, $rootScope, $route, $location, $timeout, AppFact){
	$rootScope.modal_up = false;		
	$scope.signing_in = {show : false, type : '', object : ''};
	$scope.registration = {show : false, type : '', object : ''};
	$scope.step = 1;

	$scope.industries = {
			'pets' : 'Pets',
			'food_beverage' : 'Food & Beverage',
			'finance_business' : 'Finance & Business',
			'health_wellness' : 'Health & Wellness',
			'travel' : 'Travel',
			'social_network' : 'Social Network',
			'home_garden' : 'Home & Garden',
			'education' : 'Education',
			'art_entertainment' : 'Art & Entertainment',
			'fashion_beauty' : 'Fashion & Beauty'
	}

	AppFact.isLoggedIn().success(function(response){
		if(response.http_status_code == 200){
			if(sessionStorage.getItem("user")) $rootScope.user = JSON.parse(sessionStorage.getItem("user"));
			else{
				$rootScope.user = response.data;
				sessionStorage.setItem("user", JSON.stringify(response.data));
			}
		}
		if($rootScope.user){
			window.Intercom('boot', {
			   app_id: 'qj6arzfj',
			   email: $rootScope.user.email,
			   user_id: $rootScope.user.id,
			   created_at: $rootScope.user.created_at,
			   widget: {
			      activator: '#IntercomDefaultWidget'
			   }
			});
		}
		else{
			window.Intercom('boot', {
				 app_id: 'qj6arzfj',
				 widget: {
				 	activator: '#IntercomDefaultWidget'
				 }
			})	
		}
	})

	$scope.amazon_connect = function(bucket){
		AppFact.aws_key(bucket).success(function(response){
			if(response.success) $rootScope.key = response.data.access_token;
		});
	}

	$scope.update_points = function(points){
		$rootScope.user.points = $rootScope.user.points + points;
		sessionStorage.setItem("user", JSON.stringify($rootScope.user));
	}

	$scope.check_code = function(code){
		if(code == 401){
			$scope.set_alert("You must be logged in", "default");
			$scope.open_login("must", '');
			$scope.log_out(); //incase we have some JS objects still set
		}
		else if(code == 403){
			$scope.set_alert("Unauthorized access", "error")
			$location.path('/dashboard');
		}
		else if(code == 404) $location.path('/not_found')
	}

	$scope.alert = {show : false, message : '', type : ''}; //default our alert to a blank nonshowing object
	$scope.set_alert = function(msg, type){
		$scope.alert = {show : true, message : msg, type : type};
		$timeout(function() {
		  	$scope.alert = {show : false, message : '', type : ''};
		}, 5000);
	}

	$scope.close_alert = function(){
		$scope.alert = {show : false, message : '', type : ''};
	}

	$scope.open_login = function(type, obj){
		$scope.signing_in = {show : true, type : type, object : obj};
		$rootScope.modal_up = true;
	}

	$scope.close_login = function(){
		$scope.signing_in = {show : false, type : '', object : ''};
		$rootScope.modal_up = false;
	}

	$scope.open_register = function(type, obj){
		$scope.registration = {show : true, type : type, object : obj};
		$rootScope.modal_up = true;
	}

	$scope.close_register = function(){
		$scope.registration = {show : false, type : '', object : ''};
		$rootScope.modal_up = false;
	}
	$scope.login_to_register = function(){
		$scope.registration = {show : true, type : $scope.signing_in.type, object : $scope.signing_in.object};
		$scope.signing_in = {show :false, type : '', object : ''};
	}
	$scope.register_to_login = function(){
		$scope.signing_in = {show : true, type : $scope.registration.type, object : $scope.registration.object};
		$scope.registration = {show :false, type : '', object : ''};
	}

	/** example response
			if(response.http_status_code == 200){
				if(response.success) $scope.set_alert(response.message, "default");	
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
	
	**/

	$scope.log_in = function(email, pass){
		AppFact.loggingIn(email, pass).success(function(response){
			if(response.http_status_code == 200){
				if(response.success){
					$rootScope.user = response.data;
					sessionStorage.setItem("user", JSON.stringify(response.data));
					if($scope.signing_in.type == 'must') $route.reload();
					$scope.signing_in = {show : false, type : '', object : ''};
					$rootScope.modal_up = false;
					window.Intercom('update', {
					   app_id: 'qj6arzfj',
					   email: $rootScope.user.email,
					   user_id: $rootScope.user.id,
					   created_at: $rootScope.user.created_at,
					   widget: {
					      activator: '#IntercomDefaultWidget'
					   }
					});
				}
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		})
	}
	$scope.log_out = function(){
		AppFact.loggingOut().success(function(response){
			$rootScope.user = null;
			sessionStorage.removeItem('user');
			window.Intercom('shutdown');
			if(!$scope.signing_in.show && ($location.url() == "/dashboard" || $location.url() == "/profile" || $location.url() == "/payment")) $location.path("/home");
		});
	}

	$scope.sign_up = function(registrant){
		if($scope.registration.type == "contest" || $scope.registration.type == "upvote") registrant.group_id = 2;
		else if($scope.registration.type == "company") registrant.group_id = 3;

		AppFact.signUp(registrant).success(function(response){
			if(response.http_status_code == 200){
				if(response.success){
					$rootScope.user = response.data;
					sessionStorage.setItem("user", JSON.stringify(response.data));
					$scope.registration = {show : false, type : '', object : ''};
					$rootScope.modal_up = false;
					$scope.step = 1;
					window.Intercom('update', {
					   app_id: 'qj6arzfj',
					   email: $rootScope.user.email,
					   user_id: $rootScope.user.id,
					   created_at: $rootScope.user.created_at,
					   widget: {
					      activator: '#IntercomDefaultWidget'
					   }
					});
					fbq('track', 'Lead');
				}
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		});
	}

	$scope.contact = function(issue){
		AppFact.contactUs(issue).success(function(response){
			if(response.http_status_code == 200){
				if(response.success) $scope.set_alert(response.message, "default");	 
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		})
	}

	$scope.forgot_pass = function(email){
		AppFact.forgotPass(email).success(function(response){
			if(response.http_status_code == 200){
				if(response.success) $scope.set_alert(response.message, "default");	
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		})
	}

	/* column functions */
	$scope.first_third = function(index){
		var number_array = [0,3,6,9,12,15,18,21,24,27,30,33,36,39,42,45,48];
		return number_array.includes(index);
	}
	$scope.second_third = function(index){
		var number_array = [1,4,7,10,13,16,19,22,25,28,31,34,37,40,43,46,49];
		return number_array.includes(index);
	}
	$scope.third_third = function(index){
		var number_array = [2,5,8,11,14,17,20,23,26,29,32,35,38,41,44,47,50];	
		return number_array.includes(index);
	}

});

tappyn.factory("AppFact", function($http){
	var fact = {};
	fact.loggingIn = function(email, pass){
		var object = {'identity' : email, 'password' : pass}; 
		return $http({
			method : 'POST',
			url : 'index.php/login',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			'data' : $.param(object)
		});
	}
	fact.loggingOut = function(){
		return $http({
			method : 'POST',
			url : 'index.php/logout',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}
	fact.signUp = function(registrant){
		return $http({
			method : 'POST',
			url : 'index.php/signup',
			headers : {'Content-type' : 'application/x-www-form-urlencoded'},
			'data' : $.param(registrant)
		});
	}
	fact.contactUs = function(issue){
		return $http({
			method : 'POST',
			url : 'index.php/contact',
			headers : {'Content-type' : 'application/x-www-form-urlencoded'},
			'data' : $.param(issue)
		});	
	}
	fact.isLoggedIn = function(){
		return $http({
			method : 'GET',
			url : 'index.php/auth/is_logged_in',
			headers : {'Content-type' : 'application/x-www-form-urlencoded'}
		});	
	}
	fact.forgotPass = function(email){
		return $http({
			method : 'POST',
			url : 'index.php/auth/forgot_password',
			headers : {'Content-type' : 'application/x-www-form-urlencoded'},
			data : $.param({identity : email})
		});	
	}
	fact.aws_key = function(bucket){
        return $http({
            method:'POST',
            url:'index.php/amazon/connect',
            headers:{'Content-Type' : 'application/x-www-form-urlencoded'},
            data : $.param({bucket : bucket})
        })
	}
	return fact;
})
tappyn.controller('contestController', function($scope, $rootScope, $route, $routeParams, $location, contestFactory, AppFact){
	contestFactory.grabContest($routeParams.id).success(function(response){
		$scope.contest = response.data.contest;
		$scope.submissions = response.data.submissions;
		if($scope.contest.status == "ended" && (!$rootScope.user || $rootScope.user.id != $scope.content.owner || !$rootScope.user.is_admin)) $location.path('/ended/'+$routeParams.id);
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
		if(!submission.text || submission.text.length < 1) $scope.set_alert("Text is required", "error");
		else if(($scope.contest.platform == "google" || $scope.contest.platform == "facebook") && (!submission.headline || submission.headline.length < 1)) $scope.set_alert("Headline is required", "error");
		else{
			if($rootScope.user){
				contestFactory.submitTo(id, submission).success(function(response){
					if(response.http_status_code == 200){
						if(response.success){
							$scope.set_alert(response.message, "default");	 
							$scope.update_points(2);
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
tappyn.factory('contestFactory', function($http){
	var fact = {};

	fact.grabContest = function(id){
		return $http({
			method : 'GET',
			url : 'index.php/submissions/'+id,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	fact.submitTo = function(id, submission){
		return $http({
			method : 'POST',
			url : 'index.php/submissions/create/'+id,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			'data' : $.param(submission)
		});	
	}

	fact.chooseWinner = function(contest, id){
		return $http({
			method : 'POST',
			url : 'index.php/contests/select_winner/'+contest,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			data : $.param({submission : id})
		});
	}

	fact.upvote = function(contest, id){
		return $http({
			method : 'POST',
			url : 'index.php/votes/create',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			data : $.param({contest_id : contest, submission_id : id})
		});
	}

	return fact;
})
tappyn.controller('contestsController', function($scope, contestsFactory){
	contestsFactory.grabContests().success(function(response){
		$scope.contests = response.data.contests;
	});

	$scope.filter_industry = function(pass){
		contestsFactory.filterGrab(pass).success(function(response){
			$scope.contests = response.data.contests;
		})
	}
	
	$scope.grab_all = function(){
		$scope.industry_filter = '';
		contestsFactory.grabContests().success(function(response){
			$scope.contests = response.data.contests;
		});
	}
})
tappyn.factory('contestsFactory', function($http){
	var fact = {};

	fact.grabContests = function(){
		return $http({
			method : 'GET',
			url : 'index.php/contests',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	fact.filterGrab = function(pass){
		return $http({
			method : 'GET',
			url : 'index.php/contests?industry='+pass,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	return fact;
})
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

	$scope.select_current = function(pass){
		$scope.passing_method = pass;
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
tappyn.factory('dashFactory', function($http){
	var fact = {};

	fact.grabDash = function(type){
		return $http({
			method : 'GET',
			url : 'index.php/dashboard?type='+type,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	fact.claimWinnings = function(id){
		return $http({
			method : 'GET',
			url : 'index.php/payouts/claim/'+id,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	fact.grabDetails = function(){
		return $http({
			method : 'GET',
			url : 'index.php/companies/accounts',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		})	
	}

	fact.payContest = function(id, obj){
		return $http({
			method : 'POST',
			url : 'index.php/companies/payment/'+id,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			data : $.param(obj) 
		})	
	}
	return fact;
})
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
tappyn.factory("editFactory", function($http){
	var fact = {};

	fact.grabEdit = function(id){
		return $http({
			method : 'GET',
			url : 'index.php/submissions/'+id,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	fact.editContest = function(contest){
		return $http({
			method : 'POST',
			url : 'index.php/contests/create/'+contest.id,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			data : $.param(contest)
		});
	}

	return fact;
})
tappyn.controller("endedController", function($scope, $location, $routeParams, endedFactory){
	endedFactory.grabContest($routeParams.id).success(function(response){
		$scope.contest = response.data.contest;
		$scope.winner = response.data.winner;
		if($scope.contest.status == 'active') $location.path('/contest/'+$routeParams.id);
	})
})
tappyn.factory("endedFactory", function($http){
	var fact = {};

	fact.grabContest = function(id){
		return $http({
			method : 'GET',
			url : 'index.php/contests/winner/'+id,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		})
	}

	return fact;
})
tappyn.controller('homeController', function($scope, $location, homeFactory){
	homeFactory.grabCool().success(function(response){
		$scope.contests = response.data.contests;
	})


	$scope.mailing_list = function(email){
		homeFactory.mailingList(email).success(function(response){
			if(response.http_status_code == 200){
				if(response.success) $scope.set_alert(response.message, "default");	
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		})
	}
})
tappyn.factory('homeFactory', function($http){
	var fact = {};

	fact.mailingList = function(email){
		return $http({
			method : 'POST',
			url : 'index.php/mailing_list',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			'data' : $.param({"email" : email})
		});
	}

	fact.grabCool = function(){
		return $http({
			method : 'GET',
			url : 'index.php/contests/leaderboard',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	return fact;
});
tappyn.controller('launchController', function($scope, $location, $upload, $route, $rootScope, launchFactory, AppFact){
	$scope.steps = {
		'package'		 : {step : 'package',  next : 'detail',  previous : 'none',    fill : 25},
		'detail' 		 : {step : 'detail',   next : 'payment', previous : 'package', fill : 50},
		'payment'		 : {step : 'payment',  next : 'none',    previous : 'detail',  fill : 75},
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
		$scope.contest.display_type = display;
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
					$rootScope.modal_up = true;
					$scope.set_alert(response.error, "default");	
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

	$scope.submit_contest = function(contest){
		if(!$rootScope.user) $scope.open_register("company", '');
		else{
			if(!contest.summary || contest.summary == '')  $scope.set_alert("A summary of service or product is required", "error");
			else if(!contest.industry || contest.industry == '')  $scope.set_alert("An industry is required", "error");
			else if(!contest.audience || contest.audience == '')  $scope.set_alert("A longer description is required", "error");
			else if(!contest.different || contest.different == '')  $scope.set_alert("What makes you different is required", "error");
			else{
				if(contest.id){
					launchFactory.update(contest).success(function(response){
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
       	launchFactory.payContest($scope.contest.id, {stripe_token : token, save_method : $scope.save_method}).success(function(res){
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
			launchFactory.payContest($scope.contest.id, {source_id : $scope.passing_method}).success(function(res){
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

	$scope.open_payment = function(){
		$rootScope.modal_up = true;
		$scope.adding_payment = true;
	}

	$scope.close_payment = function(){
		$rootScope.modal_up = false;
		$scope.adding_payment = false;
	}

	$scope.reload = function(){
		$route.reload();
	}
});
tappyn.factory('launchFactory', function($http){
	var fact = {}

	fact.submission = function(contest){
		return $http({
			method : 'POST',
			url : 'index.php/contests/create',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			data : $.param(contest)
		});	
	}

	fact.update = function(contest){
		return $http({
			method : 'POST',
			url : 'index.php/contests/create/'+contest.id,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			data : $.param(contest)
		});	
	}

	fact.grabProfile = function(){
		return $http({
			method : 'GET',
			url : 'index.php/users/profile',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	fact.grabDetails = function(){
		return $http({
			method : 'GET',
			url : 'index.php/companies/accounts',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		})	
	}

	fact.payContest = function(id, obj){
		return $http({
			method : 'POST',
			url : 'index.php/companies/payment/'+id,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			data : $.param(obj) 
		})	
	}
	return fact;
})
tappyn.controller("paymentController", function($scope, $rootScope, $location, paymentFactory, paymentModel){
	$scope.countries = paymentModel.countries;
	$scope.showing = "methods";
	paymentFactory.grabDetails().success(function(response){
		if(response.http_status_code == 200){
			if(response.success){
				if($scope.user.type == "member" && response.data.account == false){
					$scope.detail = {first_name : $scope.user.first_name, last_name : $scope.user.last_name};
					$scope.showing = 'details';
				}
				else if($scope.user.type == "member" && response.data.account){
					var account = response.data.account;
					$scope.detail = {first_name : account.legal_entity.first_name, 
						last_name : account.legal_entity.last_name, 
						dob_year : account.legal_entity.dob.year, 
						dob_month : account.legal_entity.dob.month, 
						dob_day : account.legal_entity.dob.day, 
						country : account.legal_entity.address.country};
					$scope.showing = 'methods';
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
					$rootScope.modal_up = false;
					$scope.add_method = false;
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

	$scope.open_add = function(){
		$rootScope.modal_up = true;
		$scope.add_method = true;
	}

	$scope.close_add = function(){
		$rootScope.modal_up = false;
		$scope.add_method = false;
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
tappyn.factory("paymentFactory", function($http){
	var fact = {};

	fact.grabDetails = function(){
		return $http({
			method : 'GET',
			url : 'index.php/accounts/details',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		})	
	}

	fact.verifyIdentity = function(details){
		return $http({
			method : 'POST',
			url : 'index.php/accounts/details',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			data : $.param(details)
		})	
	}

	fact.addPayment = function(token){
		return $http({
			method : 'POST',
			url : 'index.php/accounts/payment_methods',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			data : $.param({stripeToken : token})
		})	
	}

	fact.removeMethod = function(id){
		return $http({
			method : 'POST',
			url : 'index.php/accounts/remove_method',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			data : $.param({source_id : id})
		})	
	}

	fact.setDefault = function(id){
		return $http({
			method : 'POST',
			url : 'index.php/accounts/default_method',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			data : $.param({source_id : id})
		})	
	}
	return fact;	
})
tappyn.service('paymentModel', function(){
	this.countries = {
        'CA' : 'Canada',
        'GB' : 'United Kingdom',
        'US' : 'United States'
	};
})
tappyn.controller('profileController', function($scope, $rootScope, $upload, profileFactory, profileModel){
	$scope.amazon_connect('tappyn');
	$scope.states = profileModel.states;

	$scope.select_file = function($files){
	    var file = $files[0];
	    var url = 'https://tappyn.s3.amazonaws.com/';
	    var new_name = Date.now();
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
	       	$scope.profile.logo_url = url+new_name;
	    });
	}
	//grab that funky fresh profile on load
	profileFactory.grabProfile().success(function(response){
		if(response.http_status_code == 200){
			if(response.success) $scope.profile = response.data.profile;	
			else $scope.set_alert(response.message, "default");	 
		}
		else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
		else $scope.check_code(response.http_status_code);
	})

	$scope.update_profile = function(profile){
		profileFactory.updateProfile(profile).success(function(response){
			if(response.http_status_code == 200){
				if(response.success){
					$scope.set_alert(response.message, "default");
					$rootScope.user.first_name = $scope.profile.first_name;
					$rootScope.user.last_name = $scope.profile.last_name;
					sessionStorage.setItem("user", JSON.stringify($rootScope.user));
				}	
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		})
	}

	$scope.change_pass = function(pass){
		profileFactory.updatePass(pass).success(function(response){
			if(response.http_status_code == 200){
				if(response.success) $scope.set_alert(response.message, "default");	
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		})
	}	
});
tappyn.factory('profileFactory', function($http){
	var fact = {};

	fact.grabProfile = function(){
		return $http({
			method : 'GET',
			url : 'index.php/users/profile',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	fact.updateProfile = function(profile){
		return $http({
			method : 'POST',
			url : 'index.php/users/profile',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			data : $.param(profile)
		});
	}

	fact.updatePass = function(pass){
		return $http({
			method : 'POST',
			url : 'index.php/auth/change_password',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			data : $.param(pass)
		});
	}
	return fact;
})
tappyn.service("profileModel", function(){
this.states = 
{
    "AL": "Alabama",
    "AK": "Alaska",
    "AS": "American Samoa",
    "AZ": "Arizona",
    "AR": "Arkansas",
    "CA": "California",
    "CO": "Colorado",
    "CT": "Connecticut",
    "DE": "Delaware",
    "DC": "District Of Columbia",
    "FM": "Federated States Of Micronesia",
    "FL": "Florida",
    "GA": "Georgia",
    "GU": "Guam",
    "HI": "Hawaii",
    "ID": "Idaho",
    "IL": "Illinois",
    "IN": "Indiana",
    "IA": "Iowa",
    "KS": "Kansas",
    "KY": "Kentucky",
    "LA": "Louisiana",
    "ME": "Maine",
    "MH": "Marshall Islands",
    "MD": "Maryland",
    "MA": "Massachusetts",
    "MI": "Michigan",
    "MN": "Minnesota",
    "MS": "Mississippi",
    "MO": "Missouri",
    "MT": "Montana",
    "NE": "Nebraska",
    "NV": "Nevada",
    "NH": "New Hampshire",
    "NJ": "New Jersey",
    "NM": "New Mexico",
    "NY": "New York",
    "NC": "North Carolina",
    "ND": "North Dakota",
    "MP": "Northern Mariana Islands",
    "OH": "Ohio",
    "OK": "Oklahoma",
    "OR": "Oregon",
    "PW": "Palau",
    "PA": "Pennsylvania",
    "PR": "Puerto Rico",
    "RI": "Rhode Island",
    "SC": "South Carolina",
    "SD": "South Dakota",
    "TN": "Tennessee",
    "TX": "Texas",
    "UT": "Utah",
    "VT": "Vermont",
    "VI": "Virgin Islands",
    "VA": "Virginia",
    "WA": "Washington",
    "WV": "West Virginia",
    "WI": "Wisconsin",
    "WY": "Wyoming"
}

});
tappyn.controller("resetController", function($scope, $routeParams, $location, resetFactory){
	resetFactory.checkCode($routeParams.code).success(function(response){
		if(response.http_status_code == 200){
			if(response.success){
				$scope.set_alert("Verified, please change your password", "default");
				$scope.code = $routeParams.code;
				$scope.pass = {csrf : response.data.csrf, user_id : response.data.user_id, new : '', new_confirm : ''}
			}
			else{
				$scope.set_alert("Unauthorized", "error");
				$location.path('/login');
			}
		}
		else{
			$scope.set_alert("Unauthorized", "error");
			$location.path('/login');
		}
	});

	$scope.change_pass = function(pass){
		resetFactory.changePass(pass, $scope.code).success(function(response){
			if(response.http_status_code == 200){
				if(response.success){
					$scope.set_alert(response.message, "default");
					$location.path('/login')
				}	
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		})
	}
})
tappyn.factory("resetFactory", function($http){
	var fact = {};

	fact.checkCode = function(code){
		return $http({
			method : 'GET',
			url : 'index.php/auth/reset_password/'+code,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		})
	}

	fact.changePass = function(pass, code){
		return $http({
			method : 'POST',
			url : 'index.php/auth/reset_password/'+code,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			data : $.param(pass)
		})
	}

	return fact;
})
tappyn.controller("submissionsController", function($scope, $rootScope, $routeParams, contestFactory, submissionsFactory, AppFact){
	submissionsFactory.grabSubmissions($routeParams.id).success(function(response){
		$scope.contest = response.data.contest;
		$scope.submissions = response.data.submissions;
	});


	$scope.choose_winner = function(id){
		submissionsFactory.chooseWinner($scope.contest.id, id).success(function(response){
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
			submissionsFactory.upvote($scope.contest.id,submission.id).success(function(response){
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
});
tappyn.factory("submissionsFactory", function($http){
	var fact = {};

	fact.grabSubmissions = function(id){
		return $http({
			method : 'GET',
			url : 'index.php/submissions/'+id,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	fact.chooseWinner = function(contest, id){
		return $http({
			method : 'POST',
			url : 'index.php/contests/select_winner/'+contest,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			data : $.param({submission : id})
		});
	}

	fact.upvote = function(contest, id){
		return $http({
			method : 'POST',
			url : 'index.php/votes/create',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			data : $.param({contest_id : contest, submission_id : id})
		});
	}
	return fact; 
})
tappyn.controller('topController', function($scope, $rootScope, topFactory){
	

	topFactory.grabTops().success(function(response){
		$scope.submissions = response.data.submissions;
	})


	$scope.upvote = function(submission){
		if(!$rootScope.user) $scope.open_register("upvote", {contest : submission.contest_id, submission : submission.id});
		else {	
			topFactory.upvote(submission.contest_id,submission.id).success(function(response){
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
})
tappyn.factory('topFactory', function($http){
	var fact = {}

	fact.grabTops = function(){
		return $http({
			method : 'GET',
			url : 'index.php/submissions/leaderboard',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		})
	}

	fact.upvote = function(contest, id){
		return $http({
			method : 'POST',
			url : 'index.php/votes/create',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			data : $.param({contest_id : contest, submission_id : id})
		});
	}

	return fact;
})