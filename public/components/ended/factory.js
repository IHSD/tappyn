tappyn.factory("endedFactory", function($http){
	var fact = {};

	fact.grabContest = function(id){
		return $http({
			method : 'GET',
			url : 'api/v1/contests/'+id+'/winner',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		})
	}

	return fact;
})