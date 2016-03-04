tappyn.factory('topFactory', function($http){
	var fact = {}

	fact.grabTops = function(){
		return $http({
			method : 'GET',
			url : 'index.php/submissions/leaderboard',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		})
	}

	fact.upvote = function(contest, id){
		return $http({
			method : 'POST',
			url : 'index.php/votes/create',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			data : $.param({contest_id : contest, submission_id : id})
		});
	}

	return fact;
})