tappyn.factory('launchFactory', function($http) {
    var fact = {}

    fact.submission = function(contest) {
        return $http({
            method: 'POST',
            url: 'api/v1/contests',
            headers: {
                'Content-type': 'application/x-www-form-urlencoded'
            },
            data: $.param(contest)
        });
    }

    fact.update = function(contest) {
        return $http({
            method: 'POST',
            url: 'api/v1/contests/' + contest.id,
            headers: {
                'Content-type': 'application/x-www-form-urlencoded'
            },
            data: $.param(contest)
        });
    }

    fact.grabProfile = function() {
        return $http({
            method: 'GET',
            url: 'api/v1/profile',
            headers: {
                'Content-type': 'application/x-www-form-urlencoded'
            }
        });
    }

    fact.grabDetails = function() {
        return $http({
            method: 'GET',
            url: 'api/v1/companies/accounts',
            headers: {
                'Content-type': 'application/x-www-form-urlencoded'
            }
        })
    }

    fact.payContest = function(id, obj) {
        return $http({
            method: 'POST',
            url: 'api/v1/companies/payment/' + id,
            headers: {
                'Content-type': 'application/x-www-form-urlencoded'
            },
            data: $.param(obj)
        })
    }

    fact.voucherValid = function(id) {
        return $http({
            method: 'POST',
            url: 'api/v1/vouchers',
            headers: {
                'Content-type': 'application/x-www-form-urlencoded'
            },
            data: $.param({
                voucher_code: id
            })
        })
    }
    return fact;
})
