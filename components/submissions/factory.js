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