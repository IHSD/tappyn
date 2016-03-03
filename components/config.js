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
	.when('/contests', {
		templateUrl : 'components/contests/view.html',
		controller : 'contestsController'
	})
	.when('/contest/:id', {
		templateUrl : 'components/contest/view.html',
		controller : 'contestController'
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
		date = moment(date).fromNow();
		return date;
	};
});

tappyn.filter('legibleDate', function() {
	return function(date){
		date = moment(date).format("MMM, Do");
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
			if(!$scope.signing_in.show && ($location.url() == "/dashboard" || $location.url() == "/profile" || $location.url() == "/payment")) $location.path("/home");
		});
	}

	$scope.sign_up = function(registrant){
		if($scope.registration.type == "contest" || $scope.registration == "upvote") registrant.group_id = 2;
		else if($scope.registration.type == "company") registrant.group_id = 3;

		AppFact.signUp(registrant).success(function(response){
			if(response.http_status_code == 200){
				if(response.success){
					$rootScope.user = response.data;
					sessionStorage.setItem("user", JSON.stringify(response.data));
					$scope.registration = {show : false, type : '', object : ''};
					$rootScope.modal_up = false;
					$scope.step = 1;
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