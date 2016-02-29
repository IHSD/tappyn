tappyn.factory("submissionsFactory", function($http){
	var fact = {};

	fact.grabSubmissions = function(id){
		return $http({
			method : 'GET',
			url : 'index.php/submissions/'+id,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	fact.chooseWinner = function(contest, id){
		return $http({
			method : 'POST',
			url : 'index.php/contests/select_winner/'+contest,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			data : $.param({submission : id})
		});
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