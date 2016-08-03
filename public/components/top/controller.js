tappyn.controller('topController', function($scope, $location, $rootScope, topFactory) {

    $scope.view_winners = function() {
        topFactory.grabWinners().success(function(response) {
            $scope.submissions = response.data.submissions;
            $scope.tab = "winner";
        })
    }

    $scope.view_winners();

    $scope.upvote = function(submission) {
        if (!$rootScope.user) $scope.open_register("upvote", { contest: submission.contest_id, submission: submission.id });
        else {
            topFactory.upvote(submission.contest_id, submission.id).success(function(response) {
                if (response.http_status_code == 200) {
                    if (response.success) {
                        $scope.set_alert(response.message, "default");
                        $scope.update_points(1);
                        submission.user_may_vote = false;
                        submission.votes++;
                    } else $scope.set_alert(response.message, "default");
                } else if (response.http_status_code == 500) $scope.set_alert(response.error, "error");
                else $scope.check_code(response.http_status_code);
            })
        }
    }

    $scope.share = function(submission) {
        FB.ui({
            method: 'share',
            href: $location.protocol() + '://' + $location.host() + '/submissions/share/' + submission.id,
        }, function(response) {

        });
    }
})
