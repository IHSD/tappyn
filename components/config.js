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
	.otherwise({redirectTo : '/home'})

});

tappyn.controller("ApplicationController", function($scope, AppFact){
	$scope.log_in = function(email, pass){
		AppFact.logging_in(email, pass).success(function(response){
			console.log("yo you clicked log in bud");
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
			'params' : object
		});
	}
	return fact;
})