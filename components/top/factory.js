tappyn.factory('topFactory', function($http){
	var fact = {}

	fact.grabTops = function(){
		return $http({
			method : 'GET',
			url : 'index.php/welcome/leaderboards',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		})
	}

	return fact;
})