var tappyn = angular.module('tappyn', [
    'ngRoute',
    'ui.bootstrap',
    'ngAnimate',
    'angularFileUpload',
    'ngSanitize'
]);

tappyn.config(function($routeProvider, $locationProvider) {
    $routeProvider
        .when('/home', {
            templateUrl: 'components/home/view.html',
            controller: 'homeController'
        })
        .when('/dashboard', {
            templateUrl: 'components/dashboard/view.html',
            controller: 'dashController'
        })
        .when('/launch', {
            // templateUrl: 'components/launch/view.html',
            // controller: 'launchController'
            templateUrl: 'components/launch/view-new.html',
            controller: 'launchControllerNew'
        })
        .when('/profile', {
            templateUrl: 'components/profile/view.html',
            controller: 'profileController'
        })
        .when('/company_profile/:id', {
            templateUrl: 'components/comp_pro/view.html',
            controller: 'comproController'
        })
        .when('/winners', {
            templateUrl: 'components/top/view.html',
            controller: 'topController'
        })
        .when('/contests', {
            templateUrl: 'components/contests/view.html',
            controller: 'contestsController'
        })
        .when('/contest/:id', {
            templateUrl: 'components/contest/view.html',
            controller: 'contestController'
        })
        .when('/edit/:id', {
            templateUrl: 'components/edit/view.html',
            controller: 'editController'
        })
        .when('/ended/:id', {
            templateUrl: 'components/ended/view.html',
            controller: 'endedController'
        })
        .when('/payment', {
            templateUrl: 'components/payment/view.html',
            controller: 'paymentController'
        })
        .when('/contact_us', {
            templateUrl: 'components/contact_us/view.html'
        })
        .when('/companies', {
            templateUrl: 'components/companies/view.html',
            controller: 'companiesController'
        })
        .when('/for_companies', {
            templateUrl: 'components/company/view.html'
        })
        .when('/faq', {
            templateUrl: 'components/faq/view-new.html'
        })
        .when('/guide', {
            templateUrl: 'components/guide/view.html'
        })
        .when('/privacy', {
            templateUrl: 'components/privacy_policy/view.html'
        })
        .when('/terms', {
            templateUrl: 'components/terms_of_service/view.html'
        })
        .when('/forgot_pass', {
            templateUrl: 'components/forgot_pass/view.html'
        })
        .when('/reset_pass/:code', {
            templateUrl: 'components/reset_pass/view.html',
            controller: 'resetController'
        })
        .when('/how_it_works', {
            templateUrl: 'components/how_it_works/view.html'
        })
        .otherwise({ redirectTo: '/home' })

    $locationProvider.html5Mode(true);
});

tappyn.filter('untilFilter', function() {
    return function(date) {
        date = moment(date).fromNow("hh");
        if (date == "a day") date = "1 day";
        return date;
    };
});

tappyn.filter('legibleDate', function() {
    return function(date) {
        date = moment(date).format("MMM, Do");
        return date;
    };
});

tappyn.filter('dashDate', function() {
    return function(date) {
        date = moment(date).format("lll");
        return date;
    };
});

tappyn.filter('capitalize', function() {
    return function(input) {
        if (input != null) {
            input = input.toLowerCase();
            return input.substring(0, 1).toUpperCase() + input.substring(1);
        }
    }
});

tappyn.filter('capUnderscore', function() {
    return function(input) {
        if (input != null) {
            input = input.split('_');
            new_stringers = '';
            for (var i = 0; i < input.length; i++) {
                new_stringers = new_stringers + ' ' + input[i].substring(0, 1).toUpperCase() + input[i].substring(1)
            }
            return new_stringers;
        }
    }
});

tappyn.filter('urlFilter', function() {
    return function(input) {
        if (/^(https?:\/\/)/.exec(input)) {
            return input
        } else return 'http://' + input;
    }
});

tappyn.filter('firstChar', function() {
    return function(input) {
        if (input != null) {
            input = input.toLowerCase();
            return input.substring(0, 1).toUpperCase();
        }
    }
});

tappyn.constant('emotions', [{
    type: 'dove',
    adjectives: 'Wholesomeness, ethics, simplicity, purity',
    brand: 'Purist',
    google: '',
    facebook: '',
    twitter: 'public/img/dove_t.jpg',
    icon: 'public/img/dove.png'
}, {
    type: 'books',
    adjectives: 'Truth, objectivity, education, disclipline',
    brand: 'Source',
    google: 'public/img/book_g.jpg',
    facebook: '',
    twitter: 'public/img/book_t.jpg',
    icon: 'public/img/book.png'
}, {
    type: 'mountain',
    adjectives: 'Freedom, adventure, self-discovery, ambition',
    brand: 'Pioneer',
    google: 'public/img/mountain_g.jpg',
    facebook: '',
    twitter: '',
    icon: 'public/img/mountain.png'
}, {
    type: 'athelete',
    adjectives: 'Performance, reslience, steadfastness',
    brand: 'Conqueror',
    google: 'public/img/athlete_g.jpg',
    facebook: '',
    twitter: 'public/img/athlete_t.jpg',
    icon: 'public/img/athlete.png'
}, {
    type: 'eagle',
    adjectives: 'Independence, controversy, freedom',
    brand: 'Rebel',
    google: 'public/img/eagle_g.jpg',
    facebook: '',
    twitter: '',
    icon: 'public/img/eagle.png'
}, {
    type: 'lightbulb',
    adjectives: 'Imagination, surprise, curiosity',
    brand: 'Wizard',
    google: 'public/img/lightbulb_g.jpg',
    facebook: '',
    twitter: 'public/img/lightbulb_t.png',
    icon: 'public/img/lightbulb.png'
}, {
    type: 'glass',
    adjectives: 'Spontaneity, charm, humor',
    brand: 'Entertainer',
    google: 'public/img/wine_g.jpg',
    facebook: '',
    twitter: 'public/img/wine_t.jpg',
    icon: 'public/img/wine.png'
}, {
    type: 'cross',
    adjectives: 'Compassion, kindness, care, love',
    brand: 'Protector',
    google: 'public/img/cross_g.jpg',
    facebook: '',
    twitter: '',
    icon: 'public/img/cross.png'
}, {
    type: 'crown',
    adjectives: 'Determination, respect, dominance, wealth',
    brand: 'Emperor',
    google: '',
    facebook: '',
    twitter: '',
    icon: 'public/img/crown.png'
}]);

tappyn.directive('select2', function() {
    return {
        restrict: 'A',
        require: '?ngModel',
        scope: {},
        link: function(scope, element, attr, ngModel) {
            //console.log(ngModel);
            //$this becomes element

            element.select2({
                //options removed for clarity
            });

            element.on('change', function() {
                // console.log('on change event');
                var val = $(this).val();
                scope.$apply(function() {
                    //will cause the ng-model to be updated.
                    ngModel.$setViewValue(val);
                });
            });
            ngModel.$render = function() {
                //if this is called, the model was changed outside of select, and we need to set the value
                //not sure what the select2 api is, but something like:
                element.value = ngModel.$viewValue;
            }

        }
    }
});
