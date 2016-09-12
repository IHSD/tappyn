tappyn.controller("faqController", function($scope, $location, $routeParams, $filter, $document, AppFact, endedFactory, tappyn_var) {
    setTimeout(function() {
        var hash = window.location.hash;
        if (hash) {
            var hash_obj = $(hash);
            if (hash_obj) {
                if (hash_obj.is(":visible") == false) {
                    $("#business-tab a").trigger('click');
                }
                hash_obj.find("a").trigger('click');
                $("body").animate({
                    scrollTop: hash_obj.offset().top - $("body").offset().top + $("body").scrollTop()
                });
            }
        }
    }, 1000);
})
