tappyn.factory('dashFactory', function($http){
	var fact = {};

	fact.grabDash = function(type){
		return $http({
			method : 'GET',
			url : 'index.php/dashboard?type='+type,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	fact.claimWinnings = function(id){
		return $http({
			method : 'GET',
			url : 'index.php/payouts/claim/'+id,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}
	return fact;
})