tappyn.factory('contestFactory', function($http){
	var fact = {};

	fact.grabContest = function(id){
		return $http({
			method : 'GET',
			url : 'api/v1/contests/'+id,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
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

	fact.submitTo = function(id, submission){
		return $http({
			method : 'POST',
			url : 'api/v1/contests/'+id+'/submissions',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			'data' : $.param(submission)
		});
	}

	fact.chooseWinner = function(contest, id){
		return $http({
			method : 'POST',
			url : 'api/v1/contests/'+contest+'/winner',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			data : $.param({submission : id})
		});
	}

	fact.upvote = function(contest, id){
		return $http({
			method : 'POST',
			url : 'api/v1/submissions/'+id+'/votes',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	fact.track = function(event, id){
		return $http({
			method : 'GET',
			url : 'api/v1/analytics/track?ev='+event+'&cid='+id,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}
	return fact;
})
