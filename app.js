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

tappyn.controller("ApplicationController", function($scope, $location, AppFact){
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
tappyn.controller('contestController', function($scope, $routeParams, contestFactory){
	contestFactory.grabContest($routeParams.id).success(function(response){
		$scope.contest = response.data.contest;
	});
});
tappyn.factory('contestFactory', function($http){
	var fact = {};

	fact.grabContest = function(id){
		return $http({
			method : 'GET',
			url : 'index.php/contests/'+id,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	return fact;
})
tappyn.controller('contactController', function(){
	
})
tappyn.controller('contestsController', function($scope, contestsFactory){
	contestsFactory.grabContests().success(function(response){
		$scope.contests = response.data;
	});
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

	return fact;
})
tappyn.controller('dashController', function(){
	
})
tappyn.controller('loginController', function(){
	
});
tappyn.factory('loginFactory', function($http){
	
});
tappyn.controller('homeController', function(){
	
})
tappyn.controller("submissionsController", function($scope, $routeParams, submissionsFactory){
	
	submissionsFactory.grabSubmissions($routeParams.id).success(function(response){
		$scope.submissions = response.data.submissions;
	})

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

	return fact; 
})