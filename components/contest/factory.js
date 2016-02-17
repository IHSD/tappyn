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