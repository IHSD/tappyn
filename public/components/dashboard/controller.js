tappyn.controller('dashController', function($scope, $rootScope, $route, dashFactory) {

    //on page load grab all
    $scope.type = '';
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


    $scope.allSelected = true;
        $scope.selectText = "De-select All";

    dashFactory.grabTotals().success(function(response) {
        if (response.http_status_code == 200) {
            if (response.success) $scope.totals = response.data;
            else alert(response.message);
        } else if (response.http_status_code == 500) alert(response.error);
        else $scope.check_code(response.http_status_code);
    })
    $scope.payment_obj.price = 0;

    $scope.before_open_payment = function(contest, type) {
        contest.submission_ids = $scope.grab_checked_submission();

        if (type == 'purchase') {
            if (contest.submission_ids.length != 1) {
                $scope.set_alert("Please select 1", "error");
                return;
            }
            contest.no_payment = 1;
        } else if (type == 'confirm_ab' || type == 'confirm_re_ab') {
            if (contest.submission_ids.length == 0) {
                $scope.set_alert("Please select 1 at least", "error");
                return;
            }
            $scope.payment_obj.h3 = 'A/B Testing Payment';
            $scope.payment_obj.h4 = 'hide';
            $scope.payment_obj.ab_aday = 15;
            $scope.payment_obj.re_ab = 0;
            if (type == 'confirm_re_ab') {
                $scope.payment_obj.ab_aday = 0;
                $scope.payment_obj.re_ab = 1;
            }
            $scope.set_model('confirm_ab');
            return;

        } else if (contest.submission_ids.length == 0) {
            $scope.set_alert("Please select at least one ad to continue", "error");
            return;
        }

        $scope.open_payment(contest, type);
    }

    $scope.$on('payContestDone', function(event) {
        $route.reload();
    });

    $scope.grab_dash = function(type) {
        $scope.type = type;

        if (type == "upvotes") {
            dashFactory.grabUpvoted().success(function(response) {
                if (response.success) $scope.dash = response.data;
            });
        }
        else {
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

    $scope.close_payment = function() {
        $scope.adding_payment = { show: false, contest: '', type: '' };
        $rootScope.modal_up = false;
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

    $scope.Toggleselect_all = function() {
        $scope.allSelected = !$scope.allSelected;
        if($scope.allSelected){
            $(".container .winner-contest .checkbox-container:visible input").attr('checked', true);
                $scope.selectText = "De-select All";


            } else {
                $(".container .winner-contest .checkbox-container:visible input").attr('checked', false);
                $scope.selectText = "Select All";
            

            }

    }



});
