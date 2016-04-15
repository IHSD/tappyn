tappyn.factory("resetFactory", function($http){
	var fact = {};

	fact.checkCode = function(code){
		return $http({
			method : 'GET',
			url : 'api/v1/reset_password/'+code,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		})
	}

	fact.changePass = function(pass, code){
		return $http({
			method : 'POST',
			url : 'api/v1/reset_password/'+code,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			data : $.param(pass)
		})
	}

	return fact;
})