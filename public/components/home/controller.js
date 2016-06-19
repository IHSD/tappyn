tappyn.controller('homeController', function($scope, $rootScope, $location, homeFactory) {
    $rootScope.modal_up = false;

    homeFactory.contestGrab().success(function(response) {
        $scope.contests = response.data.contests;
    })

    homeFactory.winnersGrab().success(function(response) {
        $scope.submissions = response.data.submissions;
    })
})
