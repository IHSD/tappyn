tappyn.factory("resetFactory", function($http){
	var fact = {};

	fact.checkCode = function(code){
		return $http({
			method : 'GET',
			url : 'index.php/auth/reset_password/'+code,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		})
	}

	fact.changePass = function(pass, code){
		return $http({
			method : 'POST',
			url : 'index.php/auth/reset_password/'+code,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			data : $.param(pass)
		})
	}

	return fact;
})