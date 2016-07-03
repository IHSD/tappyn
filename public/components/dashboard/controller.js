tappyn.controller('dashController', function($scope, $rootScope, $route, dashFactory) {

    //on page load grab all
    $scope.type = '';
    $scope.payment_obj = {};
    $scope.adding_payment = { show: false, id: '' };
    $scope.confirm_winner = { show: false, submission: null };
    $scope.confirm_ab = { show: false };
    $scope.now_model = '';
    $scope.voucher = { visible: false };
    dashFactory.grabDash($scope.type).success(function(response) {
        if (response.http_status_code == 200) {
            if (response.success) $scope.dash = response.data;
            else alert(response.message);
        } else if (response.http_status_code == 500) alert(response.error);
        else $scope.check_code(response.http_status_code);
    });

    dashFactory.grabTotals().success(function(response) {
        if (response.http_status_code == 200) {
            if (response.success) $scope.totals = response.data;
            else alert(response.message);
        } else if (response.http_status_code == 500) alert(response.error);
        else $scope.check_code(response.http_status_code);
    })
    $scope.payment_obj.price = 0;

    $scope.grab_dash = function(type) {
        $scope.type = type;

        if (type == "upvotes") {
            dashFactory.grabUpvoted().success(function(response) {
                if (response.success) $scope.dash = response.data;
            });
        } else {
            dashFactory.grabDash(type).success(function(response) {
                if (response.success) $scope.dash = response.data;
            });
        }
        $scope.view = 'table';
    }
    $scope.view = "table";


    $scope.set_type_dash = function(type) {
        $scope.type = type;
    }

    $scope.back_table = function() {
        $scope.view = 'table';
        contest.status = "null";
    }



    /** start winner functions, functions for assembling the winner view, opening and closing the modal for
        confirmation and the actual choosing of a winner **/
    $scope.choosing_winner = function(contest) {
        dashFactory.grabSubmissions(contest.id).success(function(response) {
            if (response.http_status_code == 200) {
                if (response.success) {
                    $scope.winner_contest = contest; //to pass with the chosen submission
                    $scope.submissions = response.data.submissions;
                    $scope.view = 'winner';
                } else alert(response.message);
            } else if (response.http_status_code == 500) alert(response.error);
            else $scope.check_code(response.http_status_code);
        });
    }

    $scope.confirming_winner = function(submission) {
        $scope.confirm_winner = { show: true, submission: submission };
        $rootScope.modal_up = true;
    }

    $scope.choose_winner = function(id) {
        dashFactory.chooseWinner($scope.winner_contest.id, id).success(function(response) {
            if (response.http_status_code == 200) {
                if (response.success) {
                    $scope.set_alert(response.message, "default");
                    $scope.confirm_winner = { show: false, submission: null };
                    $rootScope.modal_up = false;
                    $scope.winner_contest.status = "completed";
                    $scope.view_pcp($scope.winner_contest.id);
                } else $scope.set_alert(response.message, "default");
            } else if (response.http_status_code == 500) $scope.set_alert(response.error, "error");
            else $scope.check_code(response.http_status_code);
        })
    }

    $scope.close_confirm = function() {
        $scope.confirm_winner = { show: false, submission: null };
        $rootScope.modal_up = false;
    }

    $scope.claim_winnings = function(id) {
            dashFactory.claimWinnings(id).success(function(response) {
                if (response.http_status_code == 200) {
                    if (response.success) $scope.set_alert(response.message, "default");
                    else $scope.set_alert(response.message, "default");
                } else if (response.http_status_code == 500) $scope.set_alert(response.error, "error");
                else $scope.check_code(response.http_status_code);
            });
        }
        /** end winner functions **/

    $scope.view_pcp = function(id) {
        dashFactory.viewWinner(id).success(function(response) {
            if (response.http_status_code == 200) {
                if (response.success) {
                    $scope.winner = response.data;
                    $scope.view = "pcp";
                } else $scope.set_alert(response.message, "default");
            } else if (response.http_status_code == 500) $scope.set_alert(response.error, "error");
            else $scope.check_code(response.http_status_code);
        });
    }

    $scope.set_type = function(type) {
        $scope.adding_payment.type = type;
    }

    $scope.set_model = function(model) {
        if (model) {
            $scope.now_model = model;
            $rootScope.modal_up = true;
        } else {
            $scope.now_model = '';
            $rootScope.modal_up = false;
        }
    }

    $scope.open_payment = function(contest, type) {
        $scope.payment_obj.contest_id = contest.id;
        $scope.payment_obj.submission_ids = $scope.grab_checked_submission();
        if ($scope.payment_obj.submission_ids.length == 0) {
            $scope.set_alert("Please select at least one ad to continue", "error");
            return;
        }
        if (type == 'confirm_ab') {
            $scope.set_model('confirm_ab');
            return;
        }

        $scope.payment_obj.ab_days = 1;
        if (type == 'ab' && (!parseFloat($scope.payment_obj.ab_aday) || parseFloat($scope.payment_obj.ab_aday) <= 0.0 || !parseInt($scope.payment_obj.ab_days) || parseInt($scope.payment_obj.ab_days) <= 0)) {
            $scope.set_alert("Campaign has to run at least more than 1 day and the amount cannot be $0", "error");
            return;
        }

        $scope.payment_obj.voucher_code = '';
        $scope.payment_obj.pay_for = type;

        dashFactory.grabDetails().success(function(response) {
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
        dashFactory.getPrice($scope.payment_obj).success(function(response) {
            if (response.http_status_code == 200) {
                if (response.success) {
                    $scope.payment_obj.price = response.data.price;
                    $scope.reduction = response.data.discount;
                    if (contest) {
                        $scope.set_model('adding_payment');
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

    $scope.close_payment = function() {
        $scope.adding_payment = { show: false, contest: '', type: '' };
        $rootScope.modal_up = false;
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
        if ($scope.payment_obj.price == 0.00 && !$scope.payment_obj.voucher_code) {
            $scope.set_alert("Please enter a voucher code", "error");
            return;
        }

        payment_type = (payment_type) ? payment_type : 'old';
        $scope.payment_obj.payment_type = payment_type;
        if (payment_type == 'new') {
            // This identifies your website in the createToken call below
            Stripe.setPublishableKey(APP_ENV.stripe_api_publishable_key);
            var $form = $('#payment-form');
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

        dashFactory.payContest($scope.payment_obj.contest_id, $scope.payment_obj).success(function(res) {
            if (res.http_status_code == 200) {
                if (res.success) {
                    $scope.set_alert(res.message, "default");
                    $scope.set_model();
                    $route.reload();
                } else $scope.set_alert(res.message, "default");
            } else if (res.http_status_code == 500) $scope.set_alert(res.error, "error");
            else $scope.check_code(res.http_status_code);
        });
    }

    $scope.select_current = function(pass) {
        $scope.payment_obj.passing_method = pass;
    }

    $scope.set_contest_live = function(contest) {
        dashFactory.liveContest(contest.id).success(function(res) {
            if (res.http_status_code == 200) {
                if (res.success) {
                    $scope.set_alert(res.message, "default");
                    for (var i in res.data) {
                        contest[i] = res.data[i];
                    }
                    contest.status = 'live';
                } else $scope.set_alert(res.message, "default");
            } else if (res.http_status_code == 500) $scope.set_alert(res.error, "error");
            else $scope.check_code(res.http_status_code);
        });
    }

    $scope.grab_checked_submission = function() {
        var tmp = [];
        $(".container .winner-contest .checkbox-container:visible input:checked").each(function() {
            var val = $(this).val();
            if ($.inArray(val, tmp) == -1) {
                tmp.push(val);
            }
        });
        return tmp;
    }

    $scope.select_all = function() {
        $(".container .winner-contest .checkbox-container:visible input").attr('checked', true);
    }



})
