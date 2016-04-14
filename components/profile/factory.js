tappyn.factory('profileFactory', function($http){
	var fact = {};

	fact.grabProfile = function(){
		return $http({
			method : 'GET',
			url : 'api/v1/profile',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	fact.updateProfile = function(profile){
		return $http({
			method : 'POST',
			url : 'api/v1/profile',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			data : $.param(profile)
		});
	}

	fact.updatePass = function(pass){
		return $http({
			method : 'POST',
			url : 'api/v1/password',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			data : $.param(pass)
		});
	}

	fact.resendVerification = function(){
		return $http({
			method : 'POST',
			url : 'api/v1/resend_verification',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}
	return fact;
})