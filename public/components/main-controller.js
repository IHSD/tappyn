tappyn.controller("ApplicationController", function($scope, $rootScope, $upload, $interval, $route, $location, $anchorScroll, $timeout, $filter, AppFact, tappyn_var) {
    $rootScope.modal_up = false;
    $rootScope.root_modal = { now: '' };

    $scope.signing_in = { show: false, type: '', object: '' };
    $scope.registration = { show: false, type: '', object: '' };
    $scope.payment_obj = {};

    $scope.$on('$routeChangeSuccess', function() {
        $scope.currentView = $location.path();
    });

    $scope.industries = tappyn_var.get('industries');
    $scope.interests = tappyn_var.get('interests');
    $scope.location_boxes = tappyn_var.get('location_boxes');
    $scope.additional_info_boxes = tappyn_var.get('additional_info_boxes');
    $scope.locations = tappyn_var.get('locations');
    $scope.tone_of_voice_boxes = tappyn_var.get('tone_of_voice_boxes');

    $scope.set_payment_obj_default = function() {
        $scope.payment_obj = { price: 0, voucher_code: '', re_ab: 0, h4: '', h3: '', voucher_visible: 0, save_method: false, hide_voucher: false };
    }
    $scope.set_payment_obj_default();

    $scope.set_model = function(model) {
        if (model) {
            $rootScope.root_modal.now = model;
            $rootScope.modal_up = true;
        } else {
            $rootScope.root_modal.now = '';
            $rootScope.modal_up = false;
        }
    }

    $scope.open_payment = function(contest, type) {
        if ($scope.payment_obj.re_ab && $scope.payment_obj.ab_aday <= 0) {
            $scope.set_alert("Please enter a integer number", "error");
            return;
        }
        $scope.payment_obj.contest_id = contest.id;
        $scope.payment_obj.h3 = ($scope.payment_obj.h3) ? $scope.payment_obj.h3 : 'Campaign Payment';
        $scope.payment_obj.h4 = ($scope.payment_obj.h4) ? $scope.payment_obj.h4 : $filter('capitalize')(contest.platform) + ' Campaign';
        $scope.payment_obj.submission_ids = contest.submission_ids;
        $scope.payment_obj.voucher_code = '';
        $scope.payment_obj.sub_level = contest.sub_level;
        $scope.payment_obj.pay_for = type;

        if (type == 'launch') {
            $scope.pay_payment();
        }

        if (contest.no_payment) {
            $scope.payment_obj.price = 0;
            $scope.pay_payment('no_payment');
            return;
        }

        AppFact.grabDetails().success(function(response) {
            if (response.http_status_code == 200) {
                $scope.get_price('first', contest);
                if (response.success && response.data.customer) {
                    $scope.payments = response.data.customer.sources.data;
                    $scope.add_new = false;
                } else $scope.add_new = true;

            } else if (response.http_status_code == 500) $scope.set_alert(response.error, "error");
            else $scope.check_code(response.http_status_code);
        });
    }

    $scope.get_price = function(get_price_type, contest) {
        $scope.payment_obj.get_price_type = get_price_type;
        if (get_price_type == 'check_voucher' && !$scope.payment_obj.voucher_code) {
            $scope.set_alert("Please enter a voucher code", "error");
            return;
        }
        AppFact.getPrice($scope.payment_obj).success(function(response) {
            if (response.http_status_code == 200) {
                if (response.success) {
                    $scope.payment_obj.price = response.data.price;
                    $scope.reduction = response.data.discount;
                    if (response.data.no_payment) {
                        $scope.pay_payment('no_payment');
                    } else if (contest) {
                        $scope.set_model('payment');
                    }
                    if (response.data.error_alert) {
                        $scope.set_alert(response.data.error_alert, "error");
                    }
                }
                return;
            } else if (response.http_status_code == 500) $scope.set_alert(response.error, "error");
            else $scope.check_code(response.http_status_code);
            //$scope.close_payment();
        });
    }

    var stripeResponseHandler = function(status, response) {
        if (response.error) {
            var erroring = (response.error.message).toString();
            alert(response.error.message);
            $scope.form_disabled = false;
        } else {
            // response contains id and card, which contains additional card details
            $scope.payment_obj.stripe_token = response.id;
            $scope.pay_payment('pay');
        }
    }

    $scope.pay_payment = function(payment_type) {
        var form_id = '#payment-form-global';

        payment_type = (payment_type) ? payment_type : 'old';
        $scope.payment_obj.payment_type = payment_type;
        if (payment_type == 'new') {
            // This identifies your website in the createToken call below
            Stripe.setPublishableKey(APP_ENV.stripe_api_publishable_key);
            var $form = $(form_id);
            // Disable the submit button to prevent repeated clicks
            $scope.form_disabled = true;
            Stripe.card.createToken($form, stripeResponseHandler);
            return;
        } else if (payment_type == 'old') {
            if (!$scope.payment_obj.passing_method) {
                $scope.set_alert("Please select a saved method or provide a new means of paying", "error");
                return;
            }
        }

        AppFact.payContest($scope.payment_obj.contest_id, $scope.payment_obj).success(function(res) {
            if (res.http_status_code == 200) {
                if (res.success) {
                    $scope.set_alert(res.message, "default");
                    $scope.set_model();
                    $scope.$broadcast('payContestDone');
                    $(form_id).find('input[type="reset"]').trigger('click');
                    $scope.set_payment_obj_default();
                    $scope.is_login();
                } else $scope.set_alert(res.message, "default");
            } else if (res.http_status_code == 500) $scope.set_alert(res.error, "error");
            else $scope.check_code(res.http_status_code);
        });
    }

    $scope.select_current = function(pass) {
        $scope.payment_obj.passing_method = pass;
    }

    $scope.checked_amount = 0;
    $scope.check_interests = function() {
        $scope.checked_amount = 0
        for (var i = 0; i < $scope.interests.length; i++) {
            if ($rootScope.user.interests.indexOf($scope.interests[i].id) > -1) {
                $scope.interests[i].checked = true;
                $scope.checked_amount++;
            } else $scope.interests[i].checked = false;
        }
    }

    $scope.adding_interests = function(type) {
        $scope.add_interest = { show: true, type: type };
        $rootScope.modal_up = true;
        $scope.check_interests();
    }

    $scope.close_interests = function() {
        $scope.add_interest = { show: false, type: '' };
        $rootScope.modal_up = false;
        $route.reload();
    }

    $scope.pass_interest = function(id) {
        for (var i = 0; i < $scope.interests.length; i++) {
            if (id == $scope.interests[i].id) {
                var interest = $scope.interests[i];
                if (interest.checked) {
                    AppFact.unfollowInterest(id).success(function(response) {
                        if (response.http_status_code == 200) {
                            if (response.success) {
                                $rootScope.user.interests.splice($rootScope.user.interests.indexOf(id), 1);
                                interest.checked = false;
                                $scope.checked_amount--;
                            } else $scope.set_alert(response.message, "default");
                        } else if (response.http_status_code == 500) $scope.set_alert(response.error, "error");
                        else $scope.check_code(response.http_status_code);
                    });
                } else {
                    if ($scope.checked_amount < 3) {
                        AppFact.followInterest(id).success(function(response) {
                            if (response.http_status_code == 200) {
                                if (response.success) {
                                    $rootScope.user.interests.push(id);
                                    interest.checked = true;
                                    $scope.checked_amount++;
                                } else $scope.set_alert(response.message, "default");
                            } else if (response.http_status_code == 500) $scope.set_alert(response.error, "error");
                            else $scope.check_code(response.http_status_code);
                        });
                    } else $scope.set_alert("You have already followed three types!", 'default');
                }
            }
        }
    }

    $scope.logged_in = function() {
        $scope.is_login();
    }

    $scope.is_login = function(type) {
        AppFact.isLoggedIn().success(function(response) {
            if (response.http_status_code == 200) {
                $rootScope.user = response.data;
                sessionStorage.setItem("user", JSON.stringify(response.data));
                if ($rootScope.user.type == 'member' && type == 'first') {
                    if (!$rootScope.user.age || !$rootScope.user.gender || !$rootScope.user.interests || $rootScope.user.interests.length < 3) {
                        $rootScope.modal_up = true;
                        $scope.add_age = true;
                        $scope.up_age = $rootScope.user.age;
                        $scope.up_gen = $rootScope.user.gender;
                        $scope.up_interest = $rootScope.user.interests;
                    }
                }
            }
            if ($rootScope.user) {
                window.Intercom('boot', {
                    app_id: APP_ENV.intercom_app_id,
                    email: $rootScope.user.email,
                    user_id: $rootScope.user.id,
                    created_at: $rootScope.user.created_at,
                    widget: {
                        activator: APP_ENV.intercom_default_widget
                    }
                });
            } else {
                window.Intercom('boot', {
                    app_id: APP_ENV.intercom_app_id,
                    widget: {
                        activator: APP_ENV.intercom_default_widget
                    }
                })
            }
        });
    }
    $interval($scope.is_login, 20000);
    $scope.is_login('first');

    $scope.to_top = function() {
        var old = $location.hash();
        $location.hash("top-scroll");
        $anchorScroll();
        $location.hash(old);
    }

    $scope.amazon_connect = function(bucket) {
        AppFact.aws_key(bucket).success(function(response) {
            if (response.success) $rootScope.key = response.data.access_token;
        });
    }

    $scope.save_agegen = function(age, gen, interest) {
        if (!age) $scope.set_alert("Please provide your age", "error");
        else if (!gen) $scope.set_alert("Please provide your gender", "error");
        else {
            AppFact.agegen(age, gen, interest).success(function(response) {
                if (response.http_status_code == 200) {
                    if (response.success) {
                        $scope.set_alert(response.message, "default");
                        $rootScope.user.age = age;
                        $rootScope.user.gender = gen;
                        sessionStorage.setItem("user", JSON.stringify($rootScope.user));
                        $rootScope.modal_up = false;
                        $scope.add_age = false;
                    } else $scope.set_alert(response.message, "default");
                } else if (response.http_status_code == 500) $scope.set_alert(response.error, "error");
                else $scope.check_code(response.http_status_code);
            });
        }
    }

    $scope.update_points = function(points) {
        $rootScope.user.points = $rootScope.user.points + points;
        sessionStorage.setItem("user", JSON.stringify($rootScope.user));
    }

    $scope.check_code = function(code) {
        if (code == 401) {
            $scope.set_alert("You must be logged in", "default");
            $scope.open_login("must", '');
            $scope.log_out(); //incase we have some JS objects still set
        } else if (code == 403) {
            $scope.set_alert("Unauthorized access", "error")
            $location.path('/dashboard');
        } else if (code == 404) $location.path('/not_found')
    }

    $scope.alert_close_text = 'x';
    $scope.alert = { show: false, message: '', type: '' }; //default our alert to a blank nonshowing object
    $scope.set_alert = function(msg, type) {
        $scope.alert = { show: true, message: msg, type: type };
        $timeout(function() {
            $scope.alert = { show: false, message: '', type: '' };
        }, 5000);
    }

    $scope.close_alert = function() {
        $scope.alert = { show: false, message: '', type: '' };
    }

    $scope.open_login = function(type, obj) {
        $scope.signing_in = { show: true, type: type, object: obj };
        $rootScope.modal_up = true;
    }

    $scope.close_login = function() {
        $rootScope.modal_up = false;
        $scope.signing_in = { show: false, type: '', object: '' };
        $scope.add_age = false;
    }

    $scope.open_register = function(type, obj, group_id) {
        $scope.registration = { show: true, type: type, object: obj };
        $scope.registrar = { show: true, group_id: group_id, step: 1, interests: [] };
        $rootScope.modal_up = true;
    }

    $scope.close_register = function() {
        $rootScope.modal_up = false;
        $scope.registration = { show: false, type: '', object: '' };
    }
    $scope.login_to_register = function() {
        $scope.registration = { show: true, type: $scope.signing_in.type, object: $scope.signing_in.object };
        $scope.registrar = { show: true, group_id: 2 };
        $scope.signing_in = { show: false, type: '', object: '' };
    }
    $scope.register_to_login = function() {
        $scope.signing_in = { show: true, type: $scope.registration.type, object: $scope.registration.object };
        $scope.registration = { show: false, type: '', object: '' };
    }

    $scope.notification_show = false;
    $scope.toggle_notifications = function() {
        $scope.notification_show = $scope.notification_show === false ? true : false;
        AppFact.grabNotifications().success(function(response) {
            if (response.http_status_code == 200) {
                if (response.success) {
                    $scope.notifications = response.data.notifications;
                }
            }
        });
    };
    // $scope.open_notifications = function() {
    //     $scope.notification_show = true;
    //     AppFact.grabNotifications().success(function(response) {
    //         if (response.http_status_code == 200) {
    //             if (response.success) {
    //                 $scope.notifications = response.data.notifications;
    //             }
    //         }
    //     });
    // }

    // $scope.close_notifications = function() {
    //     $scope.notification_show = false;
    // }

    $scope.read_notification = function(notification, index) {
        AppFact.readNotification(notification).success(function(response) {
            if (response.http_status_code == 200) {
                if (response.success) $scope.notifications.splice(index, 1);
                else $scope.set_alert(response.message, "default");
            } else if (response.http_status_code == 500) $scope.set_alert(response.error, "error");
            else $scope.check_code(response.http_status_code);
        });
    }

    $scope.read_all = function() {
            AppFact.readAll().success(function(response) {
                if (response.http_status_code == 200) {
                    if (response.success) $scope.notifications = [];
                    else $scope.set_alert(response.message, "default");
                } else if (response.http_status_code == 500) $scope.set_alert(response.error, "error");
                else $scope.check_code(response.http_status_code);
            });
        }
        /** example response
                if(response.http_status_code == 200){
                    if(response.success) $scope.set_alert(response.message, "default");
                    else $scope.set_alert(response.message, "default");
                }
                else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
                else $scope.check_code(response.http_status_code);
        **/

    $scope.log_in = function(email, pass) {
        AppFact.loggingIn(email, pass).success(function(response) {
            if (response.http_status_code == 200) {
                if (response.success) {
                    $rootScope.user = response.data;
                    sessionStorage.setItem("user", JSON.stringify(response.data));
                    $rootScope.modal_up = false;
                    window.Intercom('update', {
                        app_id: APP_ENV.intercom_app_id,
                        email: $rootScope.user.email,
                        user_id: $rootScope.user.id,
                        created_at: $rootScope.user.created_at,
                        widget: {
                            activator: APP_ENV.intercom_default_widget
                        }
                    });
                    $scope.signing_in = { show: false, type: '', object: '' };
                    if ($rootScope.user.type == 'member') {
                        if (!$rootScope.user.age || !$rootScope.user.gender) {
                            $rootScope.modal_up = true;
                            $scope.add_age = true;
                        }
                    }
                } else $scope.set_alert(response.message, "default");
            } else if (response.http_status_code == 500) $scope.set_alert(response.error, "error");
            else $scope.check_code(response.http_status_code);
        })
    }
    $scope.log_out = function() {
        AppFact.loggingOut().success(function(response) {
            $rootScope.user = null;
            sessionStorage.removeItem('user');
            window.Intercom('shutdown');
            $location.path("/home");
        });
    }

    $scope.sign_up_validation = function(registrant, step) {
        registrant.first_validation = (step) ? step : '1';
        AppFact.signUp(registrant).success(function(response) {
            if (response.http_status_code == 200) {
                if (response.success) {
                    if (response.data && response.data.first_validation_return == 'ok') {
                        registrant.step++;
                    }
                } else $scope.set_alert(response.message, "default");
            } else if (response.http_status_code == 500) $scope.set_alert(response.error, "error");
            else $scope.check_code(response.http_status_code);
        });
    }

    $scope.sign_up = function(registrant) {
        if (registrant.group_id == 2 && registrant.step == 1) {
            return $scope.sign_up_validation(registrant);
        }
        registrant.first_validation = 99;
        if ($scope.registration.type == "contest" || $scope.registration.type == "upvote") registrant.group_id = 2;
        else if ($scope.registration.type == "company") registrant.group_id = 3;

        AppFact.signUp(registrant).success(function(response) {
            if (response.http_status_code == 200) {
                if (response.success) {
                    $rootScope.user = response.data;
                    sessionStorage.setItem("user", JSON.stringify(response.data));
                    $rootScope.modal_up = false;
                    registrant.step = 1;
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
                    if ($scope.registration.type != "company") $route.reload();
                    $scope.registration = { show: false, type: '', object: '' };
                } else $scope.set_alert(response.message, "default");
            } else if (response.http_status_code == 500) $scope.set_alert(response.error, "error");
            else $scope.check_code(response.http_status_code);
        });
    }

    $scope.contact = function(issue) {
        AppFact.contactUs(issue).success(function(response) {
            if (response.http_status_code == 200) {
                if (response.success) $scope.set_alert(response.message, "default");
                else $scope.set_alert(response.message, "default");
            } else if (response.http_status_code == 500) $scope.set_alert(response.error, "error");
            else $scope.check_code(response.http_status_code);
        })
    }

    $scope.forgot_pass = function(email) {
        AppFact.forgotPass(email).success(function(response) {
            if (response.http_status_code == 200) {
                if (response.success) $scope.set_alert(response.message, "default");
                else $scope.set_alert(response.message, "default");
            } else if (response.http_status_code == 500) $scope.set_alert(response.error, "error");
            else $scope.check_code(response.http_status_code);
        })
    }

    /* column functions */
    $scope.first_second = function(index) {
        return index % 2 == 0;
    }
    $scope.second_second = function(index) {
        return index % 2 == 1;
    }
    $scope.first_third = function(index) {
        return index % 3 == 0;
    }
    $scope.second_third = function(index) {
        return index % 3 == 1;
    }
    $scope.third_third = function(index) {
        return index % 3 == 2;
    }

    $scope.first_fourth = function(index) {
        return index % 4 == 0;
    }
    $scope.second_fourth = function(index) {
        return index % 4 == 1;
    }
    $scope.third_fourth = function(index) {
        return index % 4 == 2;
    }
    $scope.fourth_fourth = function(index) {
        return index % 4 == 3;
    }

    $scope.first_six = function(index) {
        return index % 6 == 0;
    }
    $scope.second_six = function(index) {
        return index % 6 == 1;
    }
    $scope.third_six = function(index) {
        return index % 6 == 2;
    }
    $scope.fourth_six = function(index) {
        return index % 6 == 3;
    }
    $scope.fifth_six = function(index) {
        return index % 6 == 4;
    }
    $scope.sixth_six = function(index) {
        return index % 6 == 5;
    }

    $scope.amazon_connect('tappyn');
    $scope.select_logo = function($files, register) {
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
            register.logo_url = url + new_name;
        });
    }

    $scope.upload_file_to_amazon = function($files, url_to, sub) {
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
            url_to[sub] = url + new_name;
        });
    }

    $scope.fb_login = function() {
        var route = $location.path();
        if (route.charAt(0) == "/") route = route.substr(1);
        window.location = $location.protocol() + "://" + $location.host() + "/api/v1/facebook?route=" + route;
    }

    $scope.add_interest_image = function(interest, to_array) {
        to_array = (to_array) ? to_array : $scope.registrar.interests;
        var index = $.inArray(interest, to_array);
        if (index == -1) {
            to_array.push(interest);
        } else {
            to_array.splice(index, 1);
        }
    }

    $scope.click_subscription = function(sub) {
        if (sub == '10') {
            $scope.payment_obj.h4 = 'Standard Subscription';
        } else if (sub == '20') {
            $scope.payment_obj.h4 = 'Premium Subscription';
        } else if (sub == '30') {
            $scope.payment_obj.h4 = 'Platinum Subscription';
        }
        $scope.payment_obj.hide_voucher = true;
        $scope.open_payment({ sub_level: sub }, 'subscription');

    }

});
