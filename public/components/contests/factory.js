tappyn.factory('contestsFactory', function($http) {
    var fact = {};

    fact.grabMyContests = function() {
        return $http({
            method: 'GET',
            url: 'api/v1/contests/interesting',
            headers: {
                'Content-type': 'application/x-www-form-urlencoded'
            }
        });
    }

    fact.grabAllContests = function() {
        return $http({
            method: 'GET',
            url: 'api/v1/contests',
            headers: {
                'Content-type': 'application/x-www-form-urlencoded'
            }
        });
    }

    return fact;
})
