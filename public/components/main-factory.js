tappyn.factory("AppFact", function($http) {
    var fact = {};
    fact.loggingIn = function(email, pass) {
        var object = { 'identity': email, 'password': pass };
        return $http({
            method: 'POST',
            url: 'api/v1/login',
            headers: {
                'Content-type': 'application/x-www-form-urlencoded'
            },
            'data': $.param(object)
        });
    }
    fact.loggingOut = function() {
        return $http({
            method: 'POST',
            url: 'api/v1/logout',
            headers: {
                'Content-type': 'application/x-www-form-urlencoded'
            }
        });
    }
    fact.signUp = function(registrant) {
        return $http({
            method: 'POST',
            url: 'api/v1/signup',
            headers: { 'Content-type': 'application/x-www-form-urlencoded' },
            'data': $.param(registrant)
        });
    }
    fact.contactUs = function(issue) {
        return $http({
            method: 'POST',
            url: 'api/v1/contact',
            headers: { 'Content-type': 'application/x-www-form-urlencoded' },
            'data': $.param(issue)
        });
    }
    fact.isLoggedIn = function() {
        return $http({
            method: 'GET',
            url: 'api/v1/is_logged_in',
            headers: { 'Content-type': 'application/x-www-form-urlencoded' }
        });
    }
    fact.forgotPass = function(email) {
        return $http({
            method: 'POST',
            url: 'api/v1/forgot_password',
            headers: { 'Content-type': 'application/x-www-form-urlencoded' },
            data: $.param({ identity: email })
        });
    }
    fact.aws_key = function(bucket) {
        return $http({
            method: 'POST',
            url: 'api/v1/amazon/connect',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            data: $.param({ bucket: bucket })
        })
    }
    fact.grabNotifications = function() {
        return $http({
            method: 'GET',
            url: 'api/v1/notifications/unread',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
    }
    fact.readNotification = function(notification) {
        return $http({
            method: 'POST',
            url: 'api/v1/notifications/read',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            data: $.param(notification)
        })
    }
    fact.readAll = function() {
        return $http({
            method: 'POST',
            url: 'api/v1/notifications/read_all',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
    }
    fact.agegen = function(age, gen, interest) {
        return $http({
            method: 'POST',
            url: 'api/v1/profile',
            headers: {
                'Content-type': 'application/x-www-form-urlencoded'
            },
            data: $.param({ age: age, gender: gen, interests: interest })
        });
    }
    fact.followInterest = function(id) {
        return $http({
            method: 'POST',
            url: 'api/v1/interests/' + id + '/add',
            headers: {
                'Content-type': 'application/x-www-form-urlencoded'
            }
        });
    }
    fact.unfollowInterest = function(id) {
        return $http({
            method: 'POST',
            url: 'api/v1/interests/' + id + '/remove',
            headers: {
                'Content-type': 'application/x-www-form-urlencoded'
            }
        });
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
    fact.updateSubmissionHeadline = function(submission) {
        return $http({
            method: 'POST',
            url: 'api/v1/submissions/update/' + submission.id,
            headers: {
                'Content-type': 'application/x-www-form-urlencoded'
            },
            data: $.param({ submission: submission })
        })
    }
    fact.getContest = function(id) {
        return $http({
            method: 'GET',
            url: 'api/v1/contests/' + id,
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
    return fact;
});
