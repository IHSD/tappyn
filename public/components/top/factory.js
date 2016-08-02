tappyn.factory('topFactory', function($http){
	var fact = {}

	fact.grabTops = function(){
		return $http({
			method : 'GET',
			url : 'api/v1/submissions/leaderboard',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		})
	}

	fact.grabTotals = function() {
        return $http({
            method: 'GET',
            url: 'api/v1/stats',
            headers: {
                'Content-type': 'application/x-www-form-urlencoded'
            }
        });
    }

	fact.grabWinners = function(){
		return $http({
			method : 'GET',
			url : 'api/v1/submissions/winners',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		})
	}

	fact.upvote = function(contest, id){
		return $http({
			method : 'POST',
			url : 'api/v1/submissions/'+id+'/vote',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	return fact;
})
