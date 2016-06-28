tappyn.factory('dashFactory', function($http) {
    var fact = {};

    fact.grabDash = function(type) {
        return $http({
            method: 'GET',
            url: 'api/v1/dashboard?type=' + type,
            headers: {
                'Content-type': 'application/x-www-form-urlencoded'
            }
        });
    }


    fact.claimWinnings = function(id) {
        return $http({
            method: 'GET',
            url: 'api/v1/payouts/' + id + '/claim',
            headers: {
                'Content-type': 'application/x-www-form-urlencoded'
            }
        });
    }



    fact.grabSubmissions = function(id) {
        return $http({
            method: 'GET',
            url: 'api/v1/contests/' + id,
            headers: {
                'Content-type': 'application/x-www-form-urlencoded'
            }
        });
    }

    fact.chooseWinner = function(contest, id) {
        return $http({
            method: 'POST',
            url: 'api/v1/contests/' + contest + '/winner',
            headers: {
                'Content-type': 'application/x-www-form-urlencoded'
            },
            data: $.param({ submission: id })
        });
    }

    fact.viewWinner = function(id) {
        return $http({
            method: 'GET',
            url: 'api/v1/contests/' + id + '/winner',
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
            data: $.param({ voucher_code: id })
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
    fact.grabUpvoted = function() {
        return $http({
            method: 'GET',
            url: 'api/v1/upvoted',
            headers: {
                'Content-type': 'application/x-www-form-urlencoded'
            }
        });
    }
    fact.liveContest = function(id) {
        return $http({
            method: 'POST',
            url: 'api/v1/contests/' + id + '/set_live',
            headers: {
                'Content-type': 'application/x-www-form-urlencoded'
            },
            data: $.param(id)
        })
    }
    fact.getPrice = function(obj) {
        return $http({
            method: 'POST',
            url: 'api/v1/vouchers/get_price',
            headers: {
                'Content-type': 'application/x-www-form-urlencoded'
            },
            data: $.param(obj)
        })
    }
    return fact;
})
