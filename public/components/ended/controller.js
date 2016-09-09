tappyn.controller("endedController", function($scope, $location, $routeParams, $filter, AppFact, endedFactory, tappyn_var) {
    endedFactory.grabContest($routeParams.id).success(function(response) {
        $scope.contest = response.data.contest;
        $scope.winner = response.data.winner;
        AppFact.grabSubmissions($routeParams.id).success(function(response) {
            $scope.submissions = response.data.submissions;
        });
        if ($scope.contest.status == 'active') $location.path('/contest/' + $routeParams.id);
    });
})
