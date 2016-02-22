tappyn.factory('homeFactory', function($http){
	var fact = {};

	fact.mailingList = function(email){
		return $http({
			method : 'GET',
			url : 'index.php/mailing_list',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			'data' : $.param({"email" : email})
		});
	}

	return fact;
});