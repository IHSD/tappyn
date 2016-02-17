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
	.when('/login', {
		templateUrl : 'components/login/view.html',
		controller : 'loginController'
	})
	.when('/register', {
		templateUrl : 'components/register/view.html'
	})
	.when('/dashboard', {
		templateUrl : 'components/dashboard/view.html',
		controller : 'dashController'
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
	.when('/contact_us', {
		templateUrl : 'components/contact_us/view.html',
		controller : 'contactController'
	})
	.when('/faq', {
		templateUrl : 'components/faq/view.html',
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

tappyn.controller("ApplicationController", function($scope, $location, AppFact){
	if(sessionStorage.getItem("user")) $scope.user = JSON.parse(sessionStorage.getItem("user"));


	$scope.check_code = function(code){
		if(code == 401) $location.path('/login');
		else if(code == 403) $location.path('/dashboard');
		else if(code == 404) $location.path('/not_found')
	}



	$scope.log_in = function(email, pass){
		AppFact.logging_in(email, pass).success(function(response){
			if(response.http_status_code == 200){
				if(response.success){
					$scope.user = response.data;
					sessionStorage.setItem("user", JSON.stringify(response.data));
					$location.path('/dashboard');
				}
				else alert(response.message);	 
			}
			else if(response.http_status_code == 500) alert(response.error);
			else $scope.check_code(response.http_status_code);
		})
	}
});

tappyn.factory("AppFact", function($http){
	var fact = {};
	fact.logging_in = function(email, pass){
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
	return fact;
})