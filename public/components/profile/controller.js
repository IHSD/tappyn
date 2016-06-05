tappyn.controller('profileController', function($scope, $rootScope, $upload, profileFactory, profileModel) {
    $scope.logged_in();
    $scope.amazon_connect('tappyn');
    $scope.states = profileModel.states;

    $scope.select_file = function($files, upload_field_to) {
            var file = $files[0];
            var url = APP_ENV.amazon_aws_url;
            var new_name = Date.now();
            upload_field_to = (typeof upload_field_to == 'undefined') ? 'logo_url' : upload_field_to;
            $upload.upload({
                url: url,
                method: 'POST',
                data: {
                    key: new_name,
                    acl: 'public-read',
                    "Content-Type": file.type === null || file.type === '' ?
                        'application/octet-stream' : file.type,
                    AWSAccessKeyId: $rootScope.key.key,
                    policy: $rootScope.key.policy,
                    signature: $rootScope.key.signature
                },
                file: file,
            }).success(function() {
                if (upload_field_to == 'avatar_url') {
                    $scope.profile.avatar_url = url + new_name;
                } else {
                    $scope.profile.logo_url = url + new_name;
                }
            });
        }
        //grab that funky fresh profile on load
    profileFactory.grabProfile().success(function(response) {
        if (response.http_status_code == 200) {
            if (response.success) $scope.profile = response.data.profile;
            else $scope.set_alert(response.message, "default");
        } else if (response.http_status_code == 500) $scope.set_alert(response.error, "error");
        else $scope.check_code(response.http_status_code);
    })

    $scope.update_profile = function(profile) {
        profileFactory.updateProfile(profile).success(function(response) {
            if (response.http_status_code == 200) {
                if (response.success) {
                    $scope.set_alert(response.message, "default");
                    $rootScope.user.first_name = $scope.profile.first_name;
                    $rootScope.user.last_name = $scope.profile.last_name;
                    sessionStorage.setItem("user", JSON.stringify($rootScope.user));
                } else $scope.set_alert(response.message, "default");
            } else if (response.http_status_code == 500) $scope.set_alert(response.error, "error");
            else $scope.check_code(response.http_status_code);
        })
    }

    $scope.change_pass = function(pass) {
        profileFactory.updatePass(pass).success(function(response) {
            if (response.http_status_code == 200) {
                if (response.success) $scope.set_alert(response.message, "default");
                else $scope.set_alert(response.message, "default");
            } else if (response.http_status_code == 500) $scope.set_alert(response.error, "error");
            else $scope.check_code(response.http_status_code);
        })
    }

    $scope.resend = function() {
        profileFactory.resendVerification().success(function(response) {
            if (response.http_status_code == 200) {
                if (response.success) $scope.set_alert(response.message, "default");
                else $scope.set_alert(response.message, "default");
            } else if (response.http_status_code == 500) $scope.set_alert(response.error, "error");
            else $scope.check_code(response.http_status_code);
        })
    }
});
