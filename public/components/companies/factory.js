tappyn.factory("companiesFactory", function($http){
	var fact = {};

	fact.grabCompanies = function(){
		return $http({
			method : 'GET',
			url : 'api/v1/companies',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

 	fact.grabMyCompanies = function(){
		return $http({
			method : 'GET',
			url : 'api/v1/companies?followed=1',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	return fact;
})