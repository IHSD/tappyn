var tappyn = angular.module('tappyn', [
	'ngRoute',
	'ui.bootstrap',
	'ngAnimate'
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
	.when('/submissions/:id', {
		templateUrl : 'components/submissions/view.html',
		controller : 'submissionsController'
	})
	.when('/payment', {
		templateUrl : 'components/payment/view.html',
		controller : 'paymentController'
	})
	.when('/contact_us', {
		templateUrl : 'components/contact_us/view.html'
	})
	.when('/login', {
		templateUrl : 'components/login/view.html'
	})
	.when('/register', {
		templateUrl : 'components/register/view.html'
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

tappyn.controller("ApplicationController", function($scope, $rootScope, $location, $timeout, AppFact){
	$rootScope.modal_up = false;		

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
			if(response.success) return response.data.access_token;
		});
	}

	$scope.check_code = function(code){
		if(code == 401){
			$scope.set_alert("You must be logged in", "default");
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

	/** example response
			if(response.http_status_code == 200){
				if(response.success){
					
				}
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
					$location.path('/dashboard');
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
			$location.path("/login");
		});
	}

	$scope.sign_up = function(registrant){
		AppFact.signUp(registrant).success(function(response){
			if(response.http_status_code == 200){
				if(response.success){
					$rootScope.user = response.data;
					sessionStorage.setItem("user", JSON.stringify(response.data));
					$location.path('/dashboard');
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