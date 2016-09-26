tappyn.controller("companyController", function($scope, $rootScope) {
    var i = 0;
    // banner image url here
    var hero = [
        'https://s3-us-west-2.amazonaws.com/tappyn/ban1.jpg',
        'https://s3-us-west-2.amazonaws.com/tappyn/Example.jpg',
    ];

    if (hero.length) {
        setInterval(function() {
            $("#company-hero-banner").css('background-image', 'url(' + hero[i] + ')');
            i++;
            if (i >= hero.length) {
                i = 0;
            }
        }, 5000);
    }
});
