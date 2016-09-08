tappyn.controller("endedController", function($scope, $location, $routeParams, $filter, AppFact, endedFactory, tappyn_var) {
    $scope.tooltip_title = tappyn_var.get('tooltip_title');
    /*var filtered = [];
    angular.forEach(response.data.submissions, function(item) {
        if (item.test_result.ctr) {
            var _results = [];
            for (var i in item.test_result) {
                _results.push({ key: i, value: item.test_result[i] });
            }
            item.test_result_array = _results;
        }
        filtered.push(item);
    });*/

    endedFactory.grabContest($routeParams.id).success(function(response) {
        $scope.contest = response.data.contest;
        $scope.winner = $scope.add_test_result_array([response.data.winner]);
        AppFact.grabSubmissions($routeParams.id).success(function(response) {
            $scope.submissions = $scope.add_test_result_array(response.data.submissions);
        })
        if ($scope.contest.status == 'active') $location.path('/contest/' + $routeParams.id);
    });

    $scope.add_test_result_array = function(results) {
        var filtered = [];
        var last;
        angular.forEach(results, function(item) {
            if (item.test_result.ctr) {
                var _results = [];
                for (var i in item.test_result) {
                    _results.push({ key: i, value: item.test_result[i] });
                }
                item.test_result_array = _results;
            }
            last = item;
            filtered.push(item);
        });
        return (results.length == 1) ? last : filtered;
    }

    $scope.test_result_content = function(test_result) {
        var return_value = '';
        switch (test_result.key) {
            case 'cost_per_result':
                return_value = $filter('currency')(test_result.value);
                break;
            case 'ctr':
                return_value = test_result.value + '%';
                break;
            case 'impressions':
                return_value = $filter('number')(test_result.value);
                break;
            default:
                return_value = test_result.value;
                break;

        }
        return return_value;
    }

    $scope.test_big = function(test_result) {
        var c = {
            'Price': 'cost_per_result',
            'Awareness': 'impressions',
            'Quality': 'ctr'
        };
        return (c[$scope.contest.objective] && c[$scope.contest.objective] == test_result.key) ? -1 : 1;
    }
})
