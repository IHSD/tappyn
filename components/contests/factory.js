tappyn.factory('contestsFactory', function($http){
	var fact = {};

	fact.grabMyContests = function(){
		return $http({
			method : 'GET',
			url : 'index.php/contests/index/interesting',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	fact.grabAllContests = function(){
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

	fact.grabCompanies = function(){
		return $http({
			method : 'GET',
			url : 'index.php/companies',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	return fact;
})