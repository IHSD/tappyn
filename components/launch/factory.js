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
			url : 'index.php/accounts/details',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		})	
	}
	return fact;
})