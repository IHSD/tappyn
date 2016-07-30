tappyn.controller('launchControllerNew', function($scope, $location, $anchorScroll, $upload, $route, $rootScope, launchFactory, launchModel, AppFact, emotions, tappyn_var) {
    $scope.logged_in()
    $scope.steps = {
            // 'tp-platform': { step: 'tp-platform', next: 'tp-objective', previous: 'none', fill: 16.7 },
            // 'tp-objective': { step: 'tp-objective', next: 'tp-audience', previous: 'tp-platform', fill: 33.4 },
            // 'tp-audience': { step: 'tp-audience', next: 'detail', previous: 'tp-objective', fill: 50.1 },
            'tp-prior': { step: 'tp-prior', next: 'detail', previous: 'none', fill: 25 },
            'detail': { step: 'detail', next: 'payment', previous: 'tp-audience', fill: 50 },
            'preview': { step: 'preview', next: 'payment', previous: 'package', fill: 75 },
            'subscription': { step: 'subscription', next: 'payment', previous: 'package', fill: 75 },
            'done': { step: 'done', next: 'none', previous: 'none', fill: 100 }
        }
        // $scope.current = $scope.steps['tp-platform'];
    $scope.current = $scope.steps['tp-prior'];
    $scope.personalities = emotions;
    $scope.contest = {};
    $scope.company = {};
    $scope.save_method = false;
    $scope.ages = launchModel.ages;
    $scope.registering = false;
    $scope.contest.platform = "facebook";
    $scope.contest.objective = "clicks_to_website";
    $scope.reduction = 0;
    $scope.new_img = false;

    // todo 把subscription放在launch
    $scope.platform_image_settings = tappyn_var.get('platform_image_settings');


    $scope.grab_profile = function() {
        launchFactory.grabProfile().success(function(response) {
            if (response.http_status_code == 200) {
                if (response.success) {
                    $scope.profile = response.data.profile;
                    $scope.contest.summary = $scope.profile.summary;
                    $scope.contest.different = $scope.profile.different;
                    $scope.contest.company_url = $scope.profile.company_url;
                    $scope.contest.facebook_url = $scope.profile.facebook_url;
                    $scope.contest.twitter_handle = $scope.profile.twitter_handle;
                    $scope.contest.logo_url = $scope.profile.logo_url;
                    if (!$scope.contest.summary || !$scope.contest.different) $scope.set_step("detail");
                    else $scope.set_step("preview");
                } else $scope.set_alert(response.message, "default");
            } else if (response.http_status_code == 500) $scope.set_alert(response.error, "error");
            else $scope.check_code(response.http_status_code);
        })
    }

    $scope.close_company_login = function() {
        $rootScope.modal_up = false;
        $scope.company_login = false;
    }
    $scope.open_company_login = function() {
        $rootScope.modal_up = true;
        $scope.company_login = true;
    }

    $scope.launch_log_in = function(email, pass) {
        AppFact.loggingIn(email, pass).success(function(response) {
            if (response.http_status_code == 200) {
                if (response.success) {
                    $rootScope.user = response.data;
                    sessionStorage.setItem("user", JSON.stringify(response.data));
                    window.Intercom('update', {
                        app_id: APP_ENV.intercom_app_id,
                        email: $rootScope.user.email,
                        user_id: $rootScope.user.id,
                        created_at: $rootScope.user.created_at,
                        widget: {
                            activator: APP_ENV.intercom_default_widget
                        }
                    });
                    $scope.grab_profile();
                    $scope.close_company_login();
                } else $scope.set_alert(response.message, "default");
            } else if (response.http_status_code == 500) $scope.set_alert(response.error, "error");
            else $scope.check_code(response.http_status_code);
        })
    }

    $scope.launch_signup = function(email, pass, name, logo, cpass) {
        AppFact.signUp({ identity: email, password: pass, confirm_password: cpass, name: name, logo_url: logo, group_id: 3, first_validation: 99 }).success(function(response) {
            if (response.http_status_code == 200) {
                if (response.success) {
                    $rootScope.user = response.data;
                    sessionStorage.setItem("user", JSON.stringify(response.data));
                    window.Intercom('update', {
                        app_id: APP_ENV.intercom_app_id,
                        email: $rootScope.user.email,
                        user_id: $rootScope.user.id,
                        created_at: $rootScope.user.created_at,
                        widget: {
                            activator: APP_ENV.intercom_default_widget
                        }
                    });
                    fbq('track', 'Lead');
                    ga('send', {
                        hitType: 'event',
                        eventCategory: 'User Signup',
                        eventAction: 'Signup',
                        eventLabel: 'New User Email'
                    });
                    $scope.set_step("preview");
                } else $scope.set_alert(response.message, "default");
            } else if (response.http_status_code == 500) $scope.set_alert(response.error, "error");
            else $scope.check_code(response.http_status_code);
        });
    }



    $scope.set_step = function(step) {
        if (step == 'tp-objective') {
            if (!$scope.contest.platform) $scope.set_alert("Please select a platform.", "error");
            else $scope.current = $scope.steps[step];
        } else if (step == 'tp-audience') {
            if (!$scope.contest.objective) $scope.set_alert("Please select an objective.", "error");
            else {
                var setting = $scope.platform_image_settings[$scope.contest.platform];

                $scope.current = $scope.steps[step];
                $scope.cropper.setAspectRatio(setting['aspect_ratio']);
                $scope.cropper.setCropBoxData({ width: setting['min_width'], height: setting['min_height'] });

            }
        }
        // else if(step == 'tp-audience'){
        // }
        else if (step == "tp-prior") {
            var setting = $scope.platform_image_settings[$scope.contest.platform];
            $scope.current = $scope.steps[step];
            $scope.cropper.setAspectRatio(setting['aspect_ratio']);
            $scope.cropper.setCropBoxData({ width: setting['min_width'], height: setting['min_height'] });

        } else if (step == 'detail') {
            fbq('track', 'InitiateCheckout');
            if ($rootScope.user && !$scope.profile) $scope.grab_profile();
            else $scope.current = $scope.steps[step];
        } else if (step == 'preview') {
            if (!$rootScope.user) {
                if (!$scope.contest.identity) $scope.set_alert("An email is required", "error");
                else if (!$scope.contest.password) $scope.set_alert("A password is required", "error");
                else if (!$scope.contest.name) $scope.set_alert("Your company name is required", "error");
                else if (!$scope.contest.logo_url) $scope.set_alert("Your company logo is required", "error");
                else $scope.launch_signup($scope.contest.identity, $scope.contest.password, $scope.contest.name, $scope.contest.logo_url, $scope.contest.confirm_password);
            } else if (!$scope.contest.summary || $scope.contest.summary == '') $scope.set_alert("A summary of service or product is required", "error");
            else if (!$scope.contest.different || $scope.contest.different == '') $scope.set_alert("What makes you different is required", "error");
            else {
                $scope.form_limit = launchModel.parallel_submission($scope.contest);
                $scope.current = $scope.steps[step];
                $scope.contest.photo = $scope.cropper.getCroppedCanvas().toDataURL('image/jpeg');
                fbq('track', 'CompleteRegistration');
            }
        } else if (step == 'done') {
            $.getScript("//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5767b4c2d9b6a4c2");
            $scope.current = $scope.steps[step];
        } else $scope.current = $scope.steps[step];
        $scope.to_top();
    }

    $scope.select_objective = function(objective) {
        $scope.contest.objective = objective;
        $scope.contest.display_type = null;
    }

    $scope.select_platform = function(platform) {
        $scope.contest.platform = platform;
        $scope.contest.objective = null;
        $scope.contest.display_type = null;
        $scope.image_cropper();
        var setting = $scope.platform_image_settings[$scope.contest.platform];
        $scope.cropper.setAspectRatio(setting['aspect_ratio']);
        $scope.cropper.setCropBoxData({ width: setting['min_width'], height: setting['min_height'] });
    }

    $scope.select_display = function(type) {
        $scope.contest.display_type = type;
    }

    $scope.choose_personality = function(type) {
        $scope.contest.emotion = type;
    }
    $scope.grab_payments = function() {
        launchFactory.grabDetails().success(function(response) {
            if (response.http_status_code == 200) {
                if (response.success) $scope.payments = response.data.customer.sources.data;
                else $scope.add_new = true;
            } else if (response.http_status_code == 500) $scope.set_alert(response.error, "error");
            else $scope.check_code(response.http_status_code);
        })
    }

    $scope.to_detail = function(contest) {
        contest.display_type = "with_photo";
        if (!contest.platform || contest.platform == '') $scope.set_alert("You need to select a platform", "error");
        //else if (!contest.objective || contest.objective == '') $scope.set_alert("You need to select an ad objective", "error");
        else if (!contest.industry) $scope.set_alert("Please choose an interest to target", "error");
        else if (!$scope.new_img) $scope.set_alert("Please upload the photo", "error");
        else if (!contest.additional_info) $scope.set_alert("Please provide some creative direction", "error");
        else $scope.set_step("detail");
    }

    $scope.submit_contest = function(contest, pay) {
        contest.paid = (pay == 'draft') ? 0 : 1;
        contest.submit_type = pay;
        contest.photo = $scope.cropper.getCroppedCanvas().toDataURL('image/jpeg');
        launchFactory.submission(contest).success(function(response) {
            if (response.http_status_code == 200) {
                if (response.success) {
                    contest.id = response.data.id;
                    contest.attachment_url = response.data.attachment_url;
                    if (pay == 'draft') {
                        $scope.set_alert("Saved as draft, to launch, pay in dashboard", "default");
                        window.location = "/dashboard";
                    } else if (pay == 'launch') {
                        contest.no_payment = false;
                        $scope.open_payment(contest, 'launch');
                    } else if (pay == 'subscription') {
                        $scope.set_step('subscription');
                    } else if (pay) {
                        $scope.open_payment(contest, 'launch');
                    } else {
                        //$scope.set_alert("Saved as draft, to launch, pay in dashboard", "default");
                        $scope.set_step('done');
                        fbq('track', 'AddToWishlist');
                    }
                } else $scope.set_alert(response.message, "default");
            } else if (response.http_status_code == 500) $scope.set_alert(response.error, "error");
            else $scope.check_code(response.http_status_code);
        });
    }

    $scope.select_current = function(pass) {
        $scope.passing_method = pass;
    }

    $scope.amazon_connect('tappyn');
    $scope.select_file = function($files, type) {
        var file = $files[0];
        var url = APP_ENV.amazon_aws_url;
        var new_name = Date.now();
        var rando = Math.random() * (10000 - 1) + 1;
        new_name = new_name.toString() + rando.toString();
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
            if (type == "logo") $scope.contest.logo_url = url + new_name;
            else if (type == "pic1") $scope.contest.additional_image_1 = url + new_name;
            else if (type == 'pic2') $scope.contest.additional_image_2 = url + new_name;
            else if (type == 'pic3') $scope.contest.additional_image_3 = url + new_name;
        });
    }

    $scope.reload = function() {
        $route.reload();
    }

    $scope.track_click = function(event, contest) {
        if (event == 'facebook_click') {
            var win = window.open($scope.urlFilter(contest.facebook_url), '_blank');
            win.focus();
        } else if (event == 'website_click') {
            var win = window.open($scope.urlFilter(contest.company_url), '_blank');
            win.focus();
        } else if (event == 'twitter_click') {
            var win = window.open("https://twitter.com/" + contest.twitter_handle, '_blank');
            win.focus();
        }
    }

    $scope.urlFilter = function(url) {
        if (/^(https?:\/\/)/.exec(url)) {
            return url
        } else return 'http://' + url;
    }

    $scope.image_cropper = function(evt) {
        var reader = new FileReader();
        var input = document.getElementById('contest-photo');
        if (!input || !input.files || !input.files[0]) {
            return;
        }
        var file = input.files[0];
        reader.onload = function(evt) {
            $scope.$apply(function($scope) {
                $scope.cropper.replace(evt.target.result);
                $scope.imagerino = evt.target.result;
            });
        };
        reader.readAsDataURL(file);
        $scope.new_img = true;
    }

    $scope.get_test = function() {
        console.log($scope.cropper, $scope.cropper.getCroppedCanvas().toDataURL('image/jpeg'));
    }

    $scope.cropper = new Cropper(document.getElementById('upload_contest'), {
        aspectRatio: 1.91 / 1,
        width: 1200,
        height: 628,
        dragMode: 'move',
        scaleable: false,
        cropBoxResizable: false,
        cropBoxMovable: false,
    });

    $scope.$on('payContestDone', function(event) {
        if ($scope.current.step == 'subscription' && $scope.contest.submit_type == 'subscription') {
            $scope.set_step('preview');
            $scope.contest.submit_type = 'launch';
            //$scope.contest.no_payment = true;
            $scope.open_payment($scope.contest, 'launch');
        } else {
            $scope.set_step('done');
        }
    });
});

tappyn.directive('customOnChange', function() {
    return {
        restrict: 'A',
        link: function(scope, element, attrs) {
            var onChangeFunc = scope.$eval(attrs.customOnChange);
            element.bind('change', onChangeFunc);
        }
    };
});
