tappyn.factory('comproFactory', function($http){
	var fact = {};

	fact.grabProfile = function(id){
		return $http({
			method : 'GET',
			url : 'index.php/companies/show/'+id,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	fact.grabContests = function(id){
		return $http({
			method : 'GET',
			url : 'index.php/companies/contests/'+id,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	fact.requestContest = function(id){
		return $http({
			method : 'POST',
			url : 'index.php/companies/request_contest/'+id,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	fact.followCompany = function(id){
		return $http({
			method : 'POST',
			url : 'index.php/users/follow/'+id,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	fact.unfollowCompany = function(id){
		return $http({
			method : 'POST',
			url : 'index.php/users/unfollow/'+id,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	return fact;
})