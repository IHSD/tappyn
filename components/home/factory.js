tappyn.factory('homeFactory', function($http){
	var fact = {};

	fact.contestGrab = function(){
		return $http({
			method : 'GET',
			url : 'api/v1/contests',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	fact.winnersGrab = function(){
		return $http({
			method : 'GET',
			url : 'api/v1/submissions/winners',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		})	
	}

	return fact;
});