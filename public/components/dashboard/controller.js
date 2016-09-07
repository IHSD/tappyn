tappyn.controller('dashController', function($scope, $rootScope, $route, $filter, dashFactory, AppFact) {

    //on page load grab all
    $scope.type = '';
    $scope.adding_payment = { show: false, id: '' };
    $scope.confirm_winner = { show: false, submission: null };
    $scope.confirm_ab = { show: false };
    $scope.ctr_show = { show: false };
    $scope.confirm_select = { show: false };
    $scope.learn = { show: false };
    $scope.now_model = '';
    $scope.voucher = { visible: false };
    $scope.member_filter = {};
    $scope.member_filter_chose = 'all';
    $scope.dash2 = [];
    $scope.tooltip_title = {
        'cost_per_result': 'Cost Per Click',
        'ctr': 'Click Through Rate',
        'impressions': 'Views',
        'results': 'Total Clicks'
    };

    dashFactory.grabDash($scope.type).success(function(response) {
        if (response.http_status_code == 200) {
            if (response.success) {
                $scope.dash = response.data;
                $scope.dash2 = $scope.refresh_member_filter($scope.dash);
            } else alert(response.message);
        } else if (response.http_status_code == 500) alert(response.error);
        else $scope.check_code(response.http_status_code);
    });


    $scope.refresh_member_filter = function(newValue) {
        $scope.member_filter = {};
        var data = [];
        if (newValue && newValue.submissions) {
            for (var i = 0; i < newValue.submissions.length; i++) {
                $scope.member_filter['all'] = ($scope.member_filter['all']) ? $scope.member_filter['all'] + 1 : 1;
                var contest_status = newValue.submissions[i].contest.status;
                $scope.member_filter[contest_status] = ($scope.member_filter[contest_status]) ? $scope.member_filter[contest_status] + 1 : 1;
                if ($scope.show_submission(newValue.submissions[i])) {
                    data.push(newValue.submissions[i]);
                }
            }
        }
        return data;
    };

    $scope.set_member_filter = function(type) {
        $scope.member_filter_chose = type;
        $scope.dash2 = $scope.refresh_member_filter($scope.dash);
    }

    $scope.show_submission = function(submission) {
        if ($scope.member_filter_chose == 'all') {
            return true;
        }

        if (submission && submission.contest && submission.contest.status && submission.contest.status == $scope.member_filter_chose) {
            return true;
        }

        return false;
    }

    $scope.submission_headline_act = function(submission, act) {
        if (act == 'edit') {
            submission.headline_temp = submission.headline;
            submission.headline_editor = 1;
        } else if (act == 'cancel') {
            submission.headline_editor = 0;
        } else if (act == 'save') {
            AppFact.updateSubmissionHeadline(submission).success(function(response) {
                if (response.http_status_code == 200) {
                    $scope.set_alert(response.message, "default");
                    submission.headline = submission.headline_temp;
                    submission.headline_editor = 0;
                } else if (response.http_status_code == 500) $scope.set_alert(response.error, "error");
                else $scope.check_code(response.http_status_code);
            });
        }
    }

    $scope.showlearn = function() {
        $scope.set_model('learn');
    }
    $scope.showctr = function(key) {
        if (key && key != 'ctr') return;
        $scope.set_model('ctr_show');
    }

    $scope.submission_text_act = function(submission, act) {
        if (act == 'edit') {
            submission.text_temp = submission.text;
            submission.text_editor = 1;
        } else if (act == 'cancel') {
            submission.text_editor = 0;
        } else if (act == 'save') {
            AppFact.updateSubmissionHeadline(submission).success(function(response) {
                if (response.http_status_code == 200) {
                    $scope.set_alert(response.message, "default");
                    submission.text = submission.text_temp;
                    submission.text_editor = 0;
                } else if (response.http_status_code == 500) $scope.set_alert(response.error, "error");
                else $scope.check_code(response.http_status_code);
            });
        }
    }

    $scope.submission_link_act = function(submission, act) {
        if (act == 'edit') {
            submission.link_temp = submission.link_explanation;
            submission.link_editor = 1;
        } else if (act == 'cancel') {
            submission.link_editor = 0;
        } else if (act == 'save') {
            AppFact.updateSubmissionHeadline(submission).success(function(response) {
                if (response.http_status_code == 200) {
                    $scope.set_alert(response.message, "default");
                    submission.link_explanation = submission.link_temp;
                    submission.link_editor = 0;
                } else if (response.http_status_code == 500) $scope.set_alert(response.error, "error");
                else $scope.check_code(response.http_status_code);
            });
        }
    }

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
        } else if (type == "confirm_selection") {
            $scope.set_model('confirm_select');
            return;
        } else if (type == 'confirm_ab' || type == 'confirm_re_ab') {
            if (contest.submission_ids.length == 0) {
                $scope.set_alert("Please select 1 at least", "error");
                return;
            }
            $scope.payment_obj.h3 = 'A/B Testing Payment';
            $scope.payment_obj.h4 = 'A/B Testing Payment';
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
                if (response.success) {
                    $scope.dash = response.data;
                    $scope.dash2 = $scope.refresh_member_filter($scope.dash);
                }
            });
        } else {
            dashFactory.grabDash(type).success(function(response) {
                if (response.success) {
                    $scope.dash = response.data;
                    $scope.dash2 = $scope.refresh_member_filter($scope.dash);
                }
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
    $scope.choosing_winner = function(contest, view) {
        dashFactory.grabSubmissions(contest.id).success(function(response) {
            if (response.http_status_code == 200) {
                if (response.success) {
                    $scope.winner_contest = contest; //to pass with the chosen submission
                    var filtered = [];
                    angular.forEach(response.data.submissions, function(item) {
                        if (item.test_result.ctr) {
                            var _results = [];
                            for (var i in item.test_result) {
                                _results.push({ key: i, value: item.test_result[i] });
                            }
                            item.test_result_array = _results;
                        }
                        filtered.push(item);
                    });
                    $scope.submissions = filtered;
                    $scope.view = (view) ? view : 'winner';
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

    $scope.view_pcp = function(contest) {
        dashFactory.viewWinner(contest.id).success(function(response) {
            if (response.http_status_code == 200) {
                if (response.success) {
                    $scope.winner = response.data;
                    $scope.view = "pcp";
                    $scope.choosing_winner(contest, 'pcp');
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
        if ($scope.allSelected) {
            $(".container .winner-contest .checkbox-container:visible input").attr('checked', true);
            $scope.selectText = "De-select All";


        } else {
            $(".container .winner-contest .checkbox-container:visible input").attr('checked', false);
            $scope.selectText = "Select All";


        }

    }

    $scope.download_winner_image = function() {
        var node = document.getElementById('winner-div');
        var options = { bgcolor: 'white', width: node.scrollWidth + 3, height: node.scrollHeight + 3 };
        domtoimage.toJpeg(node, options).then(function(dataUrl) {
            var link = document.createElement('a');
            link.download = 'contest-' + $scope.winner.contest.id + '-winner.jpeg';
            link.href = dataUrl;
            link.click();
        });
    }

    $scope.submissions_others = function() {
        var submissions = [];
        if ($scope.winner && $scope.submissions) {
            for (var i in $scope.submissions) {
                if ($scope.submissions[i].id != $scope.winner.winner.id) {
                    submissions.push($scope.submissions[i]);
                }
            }
        }

        return submissions;
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
        return (c[$scope.winner_contest.objective] && c[$scope.winner_contest.objective] == test_result.key) ? -1 : 1;
    }
});
