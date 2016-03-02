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