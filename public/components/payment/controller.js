tappyn.controller("paymentController", function($scope, $rootScope, $location, paymentFactory, paymentModel) {
    $scope.logged_in();
    $scope.countries = paymentModel.countries;
    $scope.showing = "methods";
    paymentFactory.grabDetails().success(function(response) {
        if (response.http_status_code == 200) {
            if (response.success) {
                if ($rootScope.user.type == "member" && response.data.account == false) {
                    $scope.detail = {
                        first_name: $rootScope.user.first_name,
                        last_name: $rootScope.user.last_name
                    };
                    $scope.showing = 'details';
                } else if ($rootScope.user.type == "member" && response.data.account) {
                    var account = response.data.account;
                    $scope.detail = {
                        first_name: account.legal_entity.first_name,
                        last_name: account.legal_entity.last_name,
                        dob_year: account.legal_entity.dob.year,
                        dob_month: account.legal_entity.dob.month,
                        dob_day: account.legal_entity.dob.day,
                        city: account.legal_entity.address.city,
                        state: account.legal_entity.address.state,
                        postal_code: account.legal_entity.address.postal_code,
                        country: account.legal_entity.address.country,
                        address_line1: account.legal_entity.address.line1,
                        address_line2: account.legal_entity.address.line2
                    };
                    $scope.showing = 'methods';
                    console.log(account);
                }
                $scope.account = response.data.account;
            } else $scope.set_alert(response.message, "default");
        } else if (response.http_status_code == 500) $scope.set_alert(response.error, "error");
        else $scope.check_code(response.http_status_code);
    })

    $scope.verify_identity = function(detail) {
        paymentFactory.verifyIdentity(detail).success(function(response) {
            if (response.http_status_code == 200) {
                if (response.success) {
                    $scope.set_alert(response.message, "default");
                    $scope.account = response.data.account;
                    $scope.showing = 'methods';
                } else $scope.set_alert(response.message, "default");
            } else if (response.http_status_code == 500) $scope.set_alert(response.error, "error");
            else $scope.check_code(response.http_status_code);
        });
    }

    $scope.toggle_view = function(view) {
        $scope.showing = view;
    }

    function stripeResponseHandler(status, response) {
        var $form = $('#payment-form');

        if (response.error) {
            $scope.set_alert(response.error.message, "error");
            $scope.form_disabled = false;
        } else {
            // response contains id and card, which contains additional card details
            var token = response.id;
            paymentFactory.addPayment(token).success(function(res) {
                if (res.http_status_code == 200) {
                    if (res.success) {
                        $scope.account = res.data.account;
                        $scope.set_alert(res.message, "default");
                        $rootScope.modal_up = false;
                        $scope.add_method = false;
                    } else $scope.set_alert(res.message, "default");
                } else if (res.http_status_code == 500) $scope.set_alert(res.error, "error");
                else $scope.check_code(res.http_status_code);
            });
        }
    };
    $scope.process_addition = function() {
        // This identifies your website in the createToken call below
        Stripe.setPublishableKey(APP_ENV.stripe_api_publishable_key);
        var $form = $('#payment-form');

        // Disable the submit button to prevent repeated clicks
        $scope.form_disabled = true;

        Stripe.card.createToken($form, stripeResponseHandler);
    }

    $scope.remove_method = function(means) {
        paymentFactory.removeMethod(means).success(function(response) {
            if (response.http_status_code == 200) {
                if (response.success) {
                    $scope.account = response.data.account;
                    $scope.set_alert(response.message, "default");
                } else $scope.set_alert(response.message, "default");
            } else if (response.http_status_code == 500) $scope.set_alert(response.error, "error");
            else $scope.check_code(response.http_status_code);
        });
    }

    $scope.open_add = function() {
        $rootScope.modal_up = true;
        $scope.add_method = true;
    }

    $scope.close_add = function() {
        $rootScope.modal_up = false;
        $scope.add_method = false;
    }

    $scope.set_default = function(means) {
        paymentFactory.setDefault(means).success(function(response) {
            if (response.http_status_code == 200) {
                if (response.success) {
                    $scope.account = response.data.account;
                    $scope.set_alert(response.message, "default");
                } else $scope.set_alert(response.message, "default");
            } else if (response.http_status_code == 500) $scope.set_alert(response.error, "error");
            else $scope.check_code(response.http_status_code);
        });
    }
});
