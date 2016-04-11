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
			url : 'index.php/user/follow/'+id,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	fact.unfollowCompany = function(id){
		return $http({
			method : 'POST',
			url : 'index.php/user/unfollow/'+id,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	return fact;
})