var tappyn = angular.module('tappyn', [
	'ngRoute',
	'ui.bootstrap',
	'ngAnimate',
	'angularFileUpload'
]);

tappyn.config(function($routeProvider) {
	$routeProvider
	.when('/home', {
		templateUrl : 'components/home/view.html',
		controller : 'homeController'
	})
	.when('/dashboard', {
		templateUrl : 'components/dashboard/view.html',
		controller : 'dashController'
	})
	.when('/launch', {
		templateUrl : 'components/launch/view.html',
		controller : 'launchController'
	})
	.when('/profile', {
		templateUrl : 'components/profile/view.html',
		controller : 'profileController'
	})
	.when('/top', {
		templateUrl : 'components/top/view.html',
		controller : 'topController'
	})
	.when('/contests', {
		templateUrl : 'components/contests/view.html',
		controller : 'contestsController'
	})
	.when('/contest/:id', {
		templateUrl : 'components/contest/view.html',
		controller : 'contestController'
	})
	.when('/edit/:id', {
		templateUrl : 'components/edit/view.html',
		controller : 'editController'
	})
	.when('/ended/:id', {
		templateUrl : 'components/ended/view.html',
		controller : 'endedController'
	})
	.when('/payment', {
		templateUrl : 'components/payment/view.html',
		controller : 'paymentController'
	})
	.when('/contact_us', {
		templateUrl : 'components/contact_us/view.html'
	})
	.when('/companies', {
		templateUrl : 'components/company/view.html'
	})
	.when('/faq', {
		templateUrl : 'components/faq/view.html'
	})
	.when('/privacy', {
		templateUrl : 'components/privacy_policy/view.html'
	})
	.when('/terms', {
		templateUrl : 'components/terms_of_service/view.html'
	})
	.when('/forgot_pass', {
		templateUrl : 'components/forgot_pass/view.html'
	})
	.when('/reset_pass/:code', {
		templateUrl : 'components/reset_pass/view.html',
		controller  : 'resetController'
	})
	.otherwise({redirectTo : '/home'})

});

tappyn.filter('untilFilter', function() {
	return function(date){
		date = moment(date).fromNow("hh");
		return date;
	};
});

tappyn.filter('legibleDate', function() {
	return function(date){
		date = moment(date).format("MMM, Do");
		return date;
	};
});

tappyn.filter('dashDate', function() {
	return function(date){
		date = moment(date).format("lll");
		return date;
	};
}); 

tappyn.filter('capitalize', function() {
  return function(input) {
    if (input!=null){
    	input = input.toLowerCase();
    	return input.substring(0,1).toUpperCase()+input.substring(1);
    }
  }
});

tappyn.filter('capUnderscore', function() {
  return function(input) {
    if (input!=null){
    	input = input.split('_');
    	new_stringers = '';
    	for(var i = 0; i < input.length; i++){
    		new_stringers = new_stringers + ' ' +input[i].substring(0,1).toUpperCase()+input[i].substring(1)
    	}
    	return new_stringers;
    }
  }
});

tappyn.filter('firstChar', function() {
  return function(input) {
    if (input!=null){
    	input = input.toLowerCase();
    	return input.substring(0,1).toUpperCase();
    }
  }
});

tappyn.constant('emotions', [
	{type : 'dove', adjectives : 'Wholesomeness, ethics, simplicity, purity', brand : 'Purist',
		google : '', facebook : '', twitter : 'public/img/dove_t.jpg', icon : 'public/img/dove.png'},
	{type : 'books', adjectives : 'Truth, objectivity, education, disclipline', brand : 'Source',
		google : 'public/img/book_g.jpg', facebook : '', twitter : 'public/img/book_t.jpg', icon : 'public/img/book.png'},
	{type : 'mountain',  adjectives : 'Freedom, adventure, self-discovery, ambition', brand : 'Pioneer',
		google : 'public/img/mountain_g.jpg', facebook : '', twitter : '', icon : 'public/img/mountain.png'},
	{type : 'athelete', adjectives : 'Performance, reslience, steadfastness', brand : 'Conqueror',
		google : 'public/img/athlete_g.jpg', facebook : '', twitter : 'public/img/athlete_t.jpg', icon : 'public/img/athlete.png'},
	{type : 'eagle', adjectives : 'Independence, controversy, freedom', brand : 'Rebel',
		google : 'eagle_g.jpg', facebook : '', twitter : '', icon : 'public/img/eagle.png'},
	{type : 'lightbulb', adjectives : 'Imagination, surprise, curiosity', brand : 'Wizard',
		google : 'public/img/lightbulb_g.jpg', facebook : '', twitter : 'public/img/lightbulb_t.png', icon : 'public/img/lightbulb.png'},
	{type : 'glass', adjectives : 'Spontaneity, charm, humor', brand : 'Entertainer',
		google : 'public/img/wine_g.jpg', facebook : '', twitter : 'public/img/wine_t.jpg', icon : 'public/img/wine.png'},
	{type : 'cross', adjectives : 'Compassion, kindness, care, love', brand : 'Protector',
		google : 'public/img/cross_g.jpg', facebook : '', twitter : '', icon : 'public/img/cross.png'},
	{type : 'crown', adjectives : 'Determination, respect, dominance, wealth', brand : 'Emperor',
		google : '', facebook : '', twitter : '', icon : 'public/img/crown.png'}
]);


tappyn.controller("ApplicationController", function($scope, $rootScope, $upload, $q, $route, $location, $anchorScroll, $timeout, AppFact){
	$rootScope.modal_up = false;		
	$scope.signing_in = {show : false, type : '', object : ''};
	$scope.registration = {show : false, type : '', object : ''};
	$scope.step = 1;

	$scope.industries = {
			'pets' : 'Pets',
			'food_beverage' : 'Food & Beverage',
			'finance_business' : 'Finance & Business',
			'health_wellness' : 'Health & Wellness',
			'travel' : 'Travel',
			'social_network' : 'Social Network',
			'home_garden' : 'Home & Garden',
			'education' : 'Education',
			'art_entertainment' : 'Art & Entertainment',
			'fashion_beauty' : 'Fashion & Beauty'
	}
	$scope.logged_in = function(){
		return $q(function(resolve, reject) {
			AppFact.isLoggedIn().success(function(response){
				if(response.http_status_code == 200){
					$rootScope.user = response.data;
					sessionStorage.setItem("user", JSON.stringify(response.data));
				}
				if($rootScope.user){
					window.Intercom('boot', {
					   app_id: 'qj6arzfj',
					   email: $rootScope.user.email,
					   user_id: $rootScope.user.id,
					   created_at: $rootScope.user.created_at,
					   widget: {
					      activator: '#IntercomDefaultWidget'
					   }
					});
					resolve('All logged in');
				}
				else{
					window.Intercom('boot', {
						 app_id: 'qj6arzfj',
						 widget: {
						 	activator: '#IntercomDefaultWidget'
						 }
					})
					resolve("Guesterino");	
				}
			})
		});
	}

	$scope.to_top = function(){
		var old = $location.hash();
		$location.hash("top-scroll");
		$anchorScroll();
		$location.hash(old);
	}

	$scope.amazon_connect = function(bucket){
		AppFact.aws_key(bucket).success(function(response){
			if(response.success) $rootScope.key = response.data.access_token;
		});
	}

	$scope.update_points = function(points){
		$rootScope.user.points = $rootScope.user.points + points;
		sessionStorage.setItem("user", JSON.stringify($rootScope.user));
	}

	$scope.check_code = function(code){
		if(code == 401){
			$scope.set_alert("You must be logged in", "default");
			$scope.open_login("must", '');
			$scope.log_out(); //incase we have some JS objects still set
		}
		else if(code == 403){
			$scope.set_alert("Unauthorized access", "error")
			$location.path('/dashboard');
		}
		else if(code == 404) $location.path('/not_found')
	}

	$scope.alert = {show : false, message : '', type : ''}; //default our alert to a blank nonshowing object
	$scope.set_alert = function(msg, type){
		$scope.alert = {show : true, message : msg, type : type};
		$timeout(function() {
		  	$scope.alert = {show : false, message : '', type : ''};
		}, 5000);
	}

	$scope.close_alert = function(){
		$scope.alert = {show : false, message : '', type : ''};
	}

	$scope.open_login = function(type, obj){
		$scope.signing_in = {show : true, type : type, object : obj};
		$rootScope.modal_up = true;
	}

	$scope.close_login = function(){
		$rootScope.modal_up = false;
		if($scope.signing_in.type != 'company') $location.path('/home');
		$scope.signing_in = {show : false, type : '', object : ''};
	}

	$scope.open_register = function(type, obj){
		$scope.registration = {show : true, type : type, object : obj};
		$rootScope.modal_up = true;
	}

	$scope.close_register = function(){
		$rootScope.modal_up = false;
		if($scope.registration.type != 'company') $location.path('/home');
		$scope.registration = {show : false, type : '', object : ''};
	}
	$scope.login_to_register = function(){
		$scope.registration = {show : true, type : $scope.signing_in.type, object : $scope.signing_in.object};
		$scope.signing_in = {show :false, type : '', object : ''};
	}
	$scope.register_to_login = function(){
		$scope.signing_in = {show : true, type : $scope.registration.type, object : $scope.registration.object};
		$scope.registration = {show :false, type : '', object : ''};
	}

	$scope.open_notifications = function(){
		$scope.notification_show = true;
		AppFact.grabNotifications().success(function(response){
			if(response.http_status_code == 200){
				if(response.success){
					$scope.notifications = response.data.notifications;
				}	
			}
		});
	}

	$scope.close_notifications = function(){
		$scope.notification_show = false;
	}

	$scope.read_notification = function(notification, index){
		AppFact.readNotification(notification).success(function(response){
			if(response.http_status_code == 200){
				if(response.success) $scope.notifications.splice(index, 1);
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		});
	}

	$scope.read_all = function(){
		AppFact.readAll().success(function(response){
			if(response.http_status_code == 200){
				if(response.success) $scope.notifications = [];
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
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

	$scope.log_in = function(email, pass){
		AppFact.loggingIn(email, pass).success(function(response){
			if(response.http_status_code == 200){
				if(response.success){
					$rootScope.user = response.data;
					sessionStorage.setItem("user", JSON.stringify(response.data));
					$rootScope.modal_up = false;
					window.Intercom('update', {
					   app_id: 'qj6arzfj',
					   email: $rootScope.user.email,
					   user_id: $rootScope.user.id,
					   created_at: $rootScope.user.created_at,
					   widget: {
					      activator: '#IntercomDefaultWidget'
					   }
					});
					if($scope.signing_in.type != "company") $route.reload();
					$scope.signing_in = {show : false, type : '', object : ''};
				}
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		})
	}
	$scope.log_out = function(){
		AppFact.loggingOut().success(function(response){
			$rootScope.user = null;
			sessionStorage.removeItem('user');
			window.Intercom('shutdown');
			$location.path("/home");
		});
	}

	$scope.sign_up = function(registrant){
		if($scope.registration.type == "contest" || $scope.registration.type == "upvote") registrant.group_id = 2;
		else if($scope.registration.type == "company") registrant.group_id = 3;

		AppFact.signUp(registrant).success(function(response){
			if(response.http_status_code == 200){
				if(response.success){
					$rootScope.user = response.data;
					sessionStorage.setItem("user", JSON.stringify(response.data));
					$rootScope.modal_up = false;
					$scope.step = 1;
					window.Intercom('update', {
					   app_id: 'qj6arzfj',
					   email: $rootScope.user.email,
					   user_id: $rootScope.user.id,
					   created_at: $rootScope.user.created_at,
					   widget: {
					      activator: '#IntercomDefaultWidget'
					   }
					});
					fbq('track', 'Lead');
					if($scope.registration.type != "company") $route.reload();
					$scope.registration = {show : false, type : '', object : ''};
				}
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		});
	}

	$scope.contact = function(issue){
		AppFact.contactUs(issue).success(function(response){
			if(response.http_status_code == 200){
				if(response.success) $scope.set_alert(response.message, "default");	 
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		})
	}

	$scope.forgot_pass = function(email){
		AppFact.forgotPass(email).success(function(response){
			if(response.http_status_code == 200){
				if(response.success) $scope.set_alert(response.message, "default");	
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		})
	}

	/* column functions */
	$scope.first_third = function(index){
		var number_array = [0,3,6,9,12,15,18,21,24,27,30,33,36,39,42,45,48];
		return number_array.includes(index);
	}
	$scope.second_third = function(index){
		var number_array = [1,4,7,10,13,16,19,22,25,28,31,34,37,40,43,46,49];
		return number_array.includes(index);
	}
	$scope.third_third = function(index){
		var number_array = [2,5,8,11,14,17,20,23,26,29,32,35,38,41,44,47,50];	
		return number_array.includes(index);
	}

	$scope.amazon_connect('tappyn');
	$scope.select_logo = function($files, register){
	    var file = $files[0];
	    var url = 'https://tappyn.s3.amazonaws.com/';
	    var new_name = Date.now();
	    var rando = Math.random() * (10000 - 1) + 1;
	    new_name = new_name.toString() + rando.toString();
	    $upload.upload({
	        url: url,
	        method: 'POST',
	        data : {
	            key: new_name,
	            acl: 'public-read',
	            "Content-Type": file.type === null || file.type === '' ?
	            'application/octet-stream' : file.type,
	            AWSAccessKeyId: $rootScope.key.key,
	            policy: $rootScope.key.policy,
	            signature: $rootScope.key.signature
	        },
	        file: file,
	    }).success(function (){
	       	register.logo_url = url+new_name;
	    });
	}


});

tappyn.factory("AppFact", function($http){
	var fact = {};
	fact.loggingIn = function(email, pass){
		var object = {'identity' : email, 'password' : pass}; 
		return $http({
			method : 'POST',
			url : 'index.php/login',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			'data' : $.param(object)
		});
	}
	fact.loggingOut = function(){
		return $http({
			method : 'POST',
			url : 'index.php/logout',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}
	fact.signUp = function(registrant){
		return $http({
			method : 'POST',
			url : 'index.php/signup',
			headers : {'Content-type' : 'application/x-www-form-urlencoded'},
			'data' : $.param(registrant)
		});
	}
	fact.contactUs = function(issue){
		return $http({
			method : 'POST',
			url : 'index.php/contact',
			headers : {'Content-type' : 'application/x-www-form-urlencoded'},
			'data' : $.param(issue)
		});	
	}
	fact.isLoggedIn = function(){
		return $http({
			method : 'GET',
			url : 'index.php/auth/is_logged_in',
			headers : {'Content-type' : 'application/x-www-form-urlencoded'}
		});	
	}
	fact.forgotPass = function(email){
		return $http({
			method : 'POST',
			url : 'index.php/auth/forgot_password',
			headers : {'Content-type' : 'application/x-www-form-urlencoded'},
			data : $.param({identity : email})
		});	
	}
	fact.aws_key = function(bucket){
        return $http({
            method:'POST',
            url:'index.php/amazon/connect',
            headers:{'Content-Type' : 'application/x-www-form-urlencoded'},
            data : $.param({bucket : bucket})
        })
	}
	fact.grabNotifications = function(){
		return $http({
            method:'GET',
            url:'index.php/notifications/unread',
            headers:{'Content-Type' : 'application/x-www-form-urlencoded'}
        })
	}
	fact.readNotification = function(notification){
		return $http({
            method:'POST',
            url:'index.php/notifications/read',
            headers:{'Content-Type' : 'application/x-www-form-urlencoded'},
            data : $.param(notification)
        })
	}
	fact.readAll = function(){
		return $http({
            method:'POST',
            url:'index.php/notifications/read_all',
            headers:{'Content-Type' : 'application/x-www-form-urlencoded'}
        })
	}
	return fact;
})