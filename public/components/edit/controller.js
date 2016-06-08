tappyn.controller("editController", function($scope, $rootScope, $location, $upload, $routeParams, editFactory, tappyn_var) {
    if ($routeParams.id) {
        editFactory.grabEdit($routeParams.id).success(function(response) {
            if (response.http_status_code == 200) {
                if (response.success) {
                    $scope.contest = response.data.contest;
                    $scope.contest.location_box = tappyn_var.id_to_obj('location_boxes', $scope.contest.location_box);
                } else $scope.set_alert(response.message, "default");
            } else if (response.http_status_code == 500) $scope.set_alert(response.error, "error");
            else $scope.check_code(response.http_status_code);
        })
    } else $location.path('/home');

    $scope.industries = tappyn_var.get('industries');
    $scope.interests = tappyn_var.get('interests');
    $scope.location_boxes = tappyn_var.get('location_boxes');
    $scope.additional_info_boxes = tappyn_var.get('additional_info_boxes');
    $scope.locations = tappyn_var.get('locations');

    $scope.edit = function(contest) {
        editFactory.editContest(contest).success(function(response) {
            if (response.http_status_code == 200) {
                if (response.success) $scope.set_alert(response.message, "default");
                else $scope.set_alert(response.message, "default");
            } else if (response.http_status_code == 500) $scope.set_alert(response.error, "error");
            else $scope.check_code(response.http_status_code);
        })
    }

    $scope.ages = [18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65];

    $scope.amazon_connect('tappyn');
    $scope.select_file = function($files, type) {
        var file = $files[0];
        var url = APP_ENV.amazon_aws_url;
        var new_name = Date.now();
        var rando = Math.random() * (10000 - 1) + 1;
        var namen = new_name.toString() + rando.toString();
        $upload.upload({
            url: url,
            method: 'POST',
            data: {
                key: namen,
                acl: 'public-read',
                "Content-Type": file.type === null || file.type === '' ?
                    'application/octet-stream' : file.type,
                AWSAccessKeyId: $rootScope.key.key,
                policy: $rootScope.key.policy,
                signature: $rootScope.key.signature
            },
            file: file,
        }).success(function() {
            if (type == "pic1") {
                $scope.contest.additional_image_1 = url + namen;
                $scope.contest.additional_images[0] = url + namen;
            } else if (type == 'pic2') {
                $scope.contest.additional_image_2 = url + namen;
                $scope.contest.additional_images[1] = url + namen;
            } else if (type == 'pic3') {
                $scope.contest.additional_image_3 = url + namen;
                $scope.contest.additional_images[2] = url + namen;
            }
        });
    }
})
