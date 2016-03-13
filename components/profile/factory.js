tappyn.factory('profileFactory', function($http){
	var fact = {};

	fact.grabProfile = function(){
		return $http({
			method : 'GET',
			url : 'index.php/users/profile',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	fact.updateProfile = function(profile){
		return $http({
			method : 'POST',
			url : 'index.php/users/profile',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			data : $.param(profile)
		});
	}

	fact.updatePass = function(pass){
		return $http({
			method : 'POST',
			url : 'index.php/auth/change_password',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			data : $.param(pass)
		});
	}

	fact.resendVerification = function(){
		return $http({
			method : 'POST',
			url : 'index.php/auth/resend_verification',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}
	return fact;
})