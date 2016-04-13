tappyn.factory('comproFactory', function($http){
	var fact = {};

	fact.grabProfile = function(id){
		return $http({
			method : 'GET',
			url : 'api/v1/companies/show/'+id,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	fact.grabContests = function(id){
		return $http({
			method : 'GET',
			url : 'api/v1/companies/contests/'+id,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	fact.requestContest = function(id){
		return $http({
			method : 'POST',
			url : 'api/v1/companies/request_contest/'+id,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	fact.followCompany = function(id){
		return $http({
			method : 'POST',
			url : 'api/v1/companies/'+id+'/follow',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	fact.unfollowCompany = function(id){
		return $http({
			method : 'POST',
			url : 'api/v1/companies/'+id+'/unfollow',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	return fact;
})