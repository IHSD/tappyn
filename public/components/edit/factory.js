tappyn.factory("editFactory", function($http) {
    var fact = {};

    fact.grabEdit = function(id) {
        return $http({
            method: 'GET',
            url: 'api/v1/contests/' + id,
            headers: {
                'Content-type': 'application/x-www-form-urlencoded'
            }
        });
    }

    fact.editContest = function(contest) {
        return $http({
            method: 'POST',
            url: 'api/v1/contests/' + contest.id,
            headers: {
                'Content-type': 'application/x-www-form-urlencoded'
            },
            data: $.param(contest)
        });
    }

    return fact;
})
