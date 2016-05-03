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
	.when('/company_profile/:id', {
		templateUrl : 'components/comp_pro/view.html',
		controller : 'comproController'
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
		templateUrl : 'components/companies/view.html',
		controller : 'companiesController'
	})
	.when('/for_companies', {
		templateUrl : 'components/company/view.html'
	})
	.when('/faq', {
		templateUrl : 'components/faq/view.html'
	})
	.when('/guide', {
		templateUrl : 'components/guide/view.html'
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

	$locationProvider.html5Mode(true);
});

tappyn.filter('untilFilter', function() {
	return function(date){
		date = moment(date).fromNow("hh");
		if(date == "a day") date = "1 day";
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

tappyn.filter('urlFilter', function() {
  return function(input) {
    if (/^(https?:\/\/)/.exec(input)){
    	return input
    }
    else return 'http://'+input;
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
		google : 'public/img/eagle_g.jpg', facebook : '', twitter : '', icon : 'public/img/eagle.png'},
	{type : 'lightbulb', adjectives : 'Imagination, surprise, curiosity', brand : 'Wizard',
		google : 'public/img/lightbulb_g.jpg', facebook : '', twitter : 'public/img/lightbulb_t.png', icon : 'public/img/lightbulb.png'},
	{type : 'glass', adjectives : 'Spontaneity, charm, humor', brand : 'Entertainer',
		google : 'public/img/wine_g.jpg', facebook : '', twitter : 'public/img/wine_t.jpg', icon : 'public/img/wine.png'},
	{type : 'cross', adjectives : 'Compassion, kindness, care, love', brand : 'Protector',
		google : 'public/img/cross_g.jpg', facebook : '', twitter : '', icon : 'public/img/cross.png'},
	{type : 'crown', adjectives : 'Determination, respect, dominance, wealth', brand : 'Emperor',
		google : '', facebook : '', twitter : '', icon : 'public/img/crown.png'}
]);


tappyn.controller("ApplicationController", function($scope, $rootScope, $upload, $interval, $route, $location, $anchorScroll, $timeout, AppFact){
	$rootScope.modal_up = false;		
	$scope.signing_in = {show : false, type : '', object : ''};
	$scope.registration = {show : false, type : '', object : ''};
	$scope.step = 1;


	$scope.industries = {
			'pets' : 'Pets',
			'food_beverage' : 'Food & Drink',
			'finance_business' : 'Business & Finance',
			'health_wellness' : 'Health & Fitness',
			'travel' : 'Travel',
			'social_network' : 'Social & Gaming',
			'home_garden' : 'Home & Garden',
			'education' : 'Education',
			'art_entertainment' : 'Art & Entertainment',
			'fashion_beauty' : 'Fashion & Beauty',
			'sports_outdoors' : 'Sports & Outdoors',
			'tech_science' : 'Tech & Science'
	}
	$scope.interests = [
			{id : '10', text : 'Fashion & Beauty', picture : 'public/img/fashion_interest.png', checked : false},
			{id : '2', text : 'Food & Drink', picture : 'public/img/food_interest.png', checked : false},
			{id : '4', text : 'Health & Fitness', picture : 'public/img/health_interest.png', checked : false},
			{id : '6', text : 'Social & Gaming', picture : 'public/img/social_interest.png', checked : false},
			{id : '3', text : 'Business & Finance', picture : 'public/img/business_interest.png', checked : false},
			{id : '7', text : 'Home & Garden', picture : 'public/img/home_interest.png', checked : false},
			{id : '5', text : 'Travel', picture : 'public/img/travel_interest.png', checked : false},
			{id : '9', text : 'Art & Music', picture : 'public/img/art_interest.png', checked : false},
			{id : '12', text : 'Pets', picture : 'public/img/pets_interest.png', checked : false},
			{id : '13', text : 'Sports & Outdoors', picture : 'public/img/sport_interest.png', checked : false},
			{id : '8', text : 'Education', picture : 'public/img/education_interest.png', checked : false},
			{id : '11', text : 'Tech & Science', picture : 'public/img/tech_interest.png', checked : false}
	]
	$scope.checked_amount = 0;
	$scope.check_interests = function(){
		$scope.checked_amount = 0
		for(var i = 0; i < $scope.interests.length; i++){
			if($rootScope.user.interests.indexOf($scope.interests[i].id) > -1){
				$scope.interests[i].checked = true; 
				$scope.checked_amount++;
			}
			else $scope.interests[i].checked = false; 
		}
	}

	$scope.adding_interests = function(type){
		$scope.add_interest = {show :true, type : type};
		$rootScope.modal_up = true;
		$scope.check_interests();
	}

	$scope.close_interests = function(){ 
		$scope.add_interest = {show :false, type : ''};
		$rootScope.modal_up = false;
		$route.reload();
	}

	$scope.pass_interest = function(id){
		for(var i = 0; i < $scope.interests.length; i++){
			if(id == $scope.interests[i].id){
				var interest = $scope.interests[i];
				if(interest.checked){
					AppFact.unfollowInterest(id).success(function(response){
						if(response.http_status_code == 200){
							if(response.success){
								$rootScope.user.interests.splice($rootScope.user.interests.indexOf(id), 1);
								interest.checked = false;	
								$scope.checked_amount--;
							}
							else $scope.set_alert(response.message, "default");	 
						}
						else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
						else $scope.check_code(response.http_status_code);
					});
				}
				else{
					if($scope.checked_amount < 3){
						AppFact.followInterest(id).success(function(response){
							if(response.http_status_code == 200){
								if(response.success){
									$rootScope.user.interests.push(id);
									interest.checked = true;
									$scope.checked_amount++;	
								}
								else $scope.set_alert(response.message, "default");	 
							}
							else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
							else $scope.check_code(response.http_status_code);
						});
					}
					else $scope.set_alert("You have already followed three types!", 'default');
				}
			}
		}
	}

	$scope.logged_in = function(){
		$interval(function(){
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
				}
				else{
					window.Intercom('boot', {
						 app_id: 'qj6arzfj',
						 widget: {
						 	activator: '#IntercomDefaultWidget'
						 }
					})
				}
			});
		}, 20000);
	}

	AppFact.isLoggedIn().success(function(response){
		if(response.http_status_code == 200){
			$rootScope.user = response.data;
			sessionStorage.setItem("user", JSON.stringify(response.data));
			if($rootScope.user.type == 'member'){	
				if(!$rootScope.user.age || !$rootScope.user.gender){
					$rootScope.modal_up = true;
					$scope.add_age = true;
				}
			}
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
		}
		else{
			window.Intercom('boot', {
				 app_id: 'qj6arzfj',
				 widget: {
				 	activator: '#IntercomDefaultWidget'
				 }
			})
		}
	});
	$scope.logged_in();

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

	$scope.save_agegen = function(age, gen){
		if(!age) $scope.set_alert("Please provide your age", "error");
		else if(!gen) $scope.set_alert("Please provide your gender", "error");
		else{
			AppFact.agegen(age, gen).success(function(response){
				if(response.http_status_code == 200){
					if(response.success){
						$scope.set_alert(response.message, "default");	
						$rootScope.user.age = age;
						$rootScope.user.gender = gen;
						sessionStorage.setItem("user", JSON.stringify($rootScope.user));
						$rootScope.modal_up = false;
						$scope.add_age = false;
					}
					else $scope.set_alert(response.message, "default");	 
				}
				else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
				else $scope.check_code(response.http_status_code);
			});
		}
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
		$scope.signing_in = {show : false, type : '', object : ''};
	}

	$scope.open_register = function(type, obj){
		$scope.registration = {show : true, type : type, object : obj};
		$rootScope.modal_up = true;
	}

	$scope.close_register = function(){
		$rootScope.modal_up = false;
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
					if($rootScope.user.type == 'member'){	
						if(!$rootScope.user.age || !$rootScope.user.gender){
							$rootScope.modal_up = true;
							$scope.add_age = true;
						}
					}
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
					ga('send', {
						hitType: 'event',
						eventCategory: 'User Signup',
						eventAction: 'Signup',
						eventLabel: 'New User Email'
					});
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
	$scope.first_second = function(index){
		return index%2 == 0;
	}
	$scope.second_second = function(index){
		return index%2 == 1;
	}
	$scope.first_third = function(index){
		return index%3 == 0;
	}
	$scope.second_third = function(index){
		return index%3 == 1;
	}
	$scope.third_third = function(index){
		return index%3 == 2;
	}

	$scope.first_fourth = function(index){
		return index%4 == 0;
	}
	$scope.second_fourth = function(index){
		return index%4 == 1;
	}
	$scope.third_fourth = function(index){
		return index%4 == 2;
	}
	$scope.fourth_fourth = function(index){
		return index%4 == 3;
	}

	$scope.first_six = function(index){
		return index%6 == 0;
	}
	$scope.second_six = function(index){
		return index%6 == 1;
	}
	$scope.third_six = function(index){
		return index%6 == 2;
	}
	$scope.fourth_six = function(index){
		return index%6 == 3;
	}
	$scope.fifth_six = function(index){	
		return index%6 == 4;
	}
	$scope.sixth_six = function(index){	
		return index%6 == 5;
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

	$scope.fb_login = function(){
		var route = $location.path();		
		if (route.charAt(0) == "/") route = route.substr(1);
		window.location = $location.protocol()+"://"+$location.host()+"/api/v1/facebook?route="+route;
	}
});

tappyn.factory("AppFact", function($http){
	var fact = {};
	fact.loggingIn = function(email, pass){
		var object = {'identity' : email, 'password' : pass}; 
		return $http({
			method : 'POST',
			url : 'api/v1/login',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			'data' : $.param(object)
		});
	}
	fact.loggingOut = function(){
		return $http({
			method : 'POST',
			url : 'api/v1/logout',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}
	fact.signUp = function(registrant){
		return $http({
			method : 'POST',
			url : 'api/v1/signup',
			headers : {'Content-type' : 'application/x-www-form-urlencoded'},
			'data' : $.param(registrant)
		});
	}
	fact.contactUs = function(issue){
		return $http({
			method : 'POST',
			url : 'api/v1/contact',
			headers : {'Content-type' : 'application/x-www-form-urlencoded'},
			'data' : $.param(issue)
		});	
	}
	fact.isLoggedIn = function(){
		return $http({
			method : 'GET',
			url : 'api/v1/is_logged_in',
			headers : {'Content-type' : 'application/x-www-form-urlencoded'}
		});	
	}
	fact.forgotPass = function(email){
		return $http({
			method : 'POST',
			url : 'api/v1/forgot_password',
			headers : {'Content-type' : 'application/x-www-form-urlencoded'},
			data : $.param({identity : email})
		});	
	}
	fact.aws_key = function(bucket){
        return $http({
            method:'POST',
            url:'api/v1/amazon/connect',
            headers:{'Content-Type' : 'application/x-www-form-urlencoded'},
            data : $.param({bucket : bucket})
        })
	}
	fact.grabNotifications = function(){
		return $http({
            method:'GET',
            url:'api/v1/notifications/unread',
            headers:{'Content-Type' : 'application/x-www-form-urlencoded'}
        })
	}
	fact.readNotification = function(notification){
		return $http({
            method:'POST',
            url:'api/v1/notifications/read',
            headers:{'Content-Type' : 'application/x-www-form-urlencoded'},
            data : $.param(notification)
        })
	}
	fact.readAll = function(){
		return $http({
            method:'POST',
            url:'api/v1/notifications/read_all',
            headers:{'Content-Type' : 'application/x-www-form-urlencoded'}
        })
	}
	fact.agegen = function(age, gen){
		return $http({
			method : 'POST',
			url : 'api/v1/profile',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			data : $.param({age : age, gender : gen})
		});
	}
	fact.followInterest = function(id){
		return $http({
			method : 'POST',
			url : 'api/v1/interests/'+id+'/add',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}
	fact.unfollowInterest = function(id){
		return $http({
			method : 'POST',
			url : 'api/v1/interests/'+id+'/remove',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}
	return fact;
})
tappyn.controller('comproController', function($scope, $rootScope, $routeParams, comproFactory){
	if($routeParams.id){
		comproFactory.grabProfile($routeParams.id).success(function(response){
			if(response.http_status_code == 200){
				if(response.success) $scope.company = response.data.company;
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		});	
		comproFactory.grabContests($routeParams.id).success(function(response){
			if(response.http_status_code == 200){
				if(response.success) $scope.contests = response.data.contests;
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		});	
	}

	$scope.follow = function(){
		if(!$rootScope.user){
			$scope.set_alert("Please make an account to follow companies!", "default");
			$scope.open_register("default", "");
		}	
		else{
			comproFactory.followCompany($routeParams.id).success(function(response){
				if(response.http_status_code == 200){
					if(response.success){
						$scope.set_alert("You're following "+$scope.company.name, "default");	
						$scope.company.follows++;
						$scope.company.user_may_follow = false;
					}
					else $scope.set_alert(response.message, "default");	 
				}
				else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
				else $scope.check_code(response.http_status_code);
			});
		}
	}

	$scope.unfollow = function(){
		comproFactory.unfollowCompany($routeParams.id).success(function(response){
			if(response.http_status_code == 200){
				if(response.success){
					$scope.set_alert("You unfollowed "+$scope.company.name, "default");
					$scope.company.follows--;
					$scope.company.user_may_follow = true;
				}	
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		})
	}

	$scope.request_contest = function(){
		if(!$rootScope.user){
			$scope.set_alert("Please make an account to request contests!", "default");
			$scope.open_register("default", "");
		}
		else{
			comproFactory.requestContest($routeParams.id).success(function(response){
				if(response.http_status_code == 200){
					if(response.success){
						$scope.set_alert(response.message, "default");	
						$scope.company.contest_requests++;
					}
					else $scope.set_alert(response.message, "default");	 
				}
				else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
				else $scope.check_code(response.http_status_code);
			});
		}
	}
});
tappyn.factory('comproFactory', function($http){
	var fact = {};

	fact.grabProfile = function(id){
		return $http({
			method : 'GET',
			url : 'api/v1/companies/show/'+id,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	fact.grabContests = function(id){
		return $http({
			method : 'GET',
			url : 'api/v1/companies/contests/'+id,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	fact.requestContest = function(id){
		return $http({
			method : 'POST',
			url : 'api/v1/companies/request_contest/'+id,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	fact.followCompany = function(id){
		return $http({
			method : 'POST',
			url : 'api/v1/companies/'+id+'/follow',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	fact.unfollowCompany = function(id){
		return $http({
			method : 'POST',
			url : 'api/v1/companies/'+id+'/unfollow',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	return fact;
})
tappyn.controller("companiesController", function($scope, $rootScope, companiesFactory){
	$scope.grab_my = function(){
		$scope.tab = "my";
		companiesFactory.grabMyCompanies().success(function(response){
			if(response.http_status_code == 200){
				if(response.success) $scope.companies = response.data.companies;	
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "default");
			else $scope.check_code(response.http_status_code);
		})
	}

	$scope.grab_companies = function(){
		$scope.tab = 'company';
		companiesFactory.grabCompanies().success(function(response){
			if(response.http_status_code == 200){
				if(response.success) $scope.companies = response.data.companies;	
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "default");
			else $scope.check_code(response.http_status_code);
		});
	}	

	$scope.grab_companies();
});
tappyn.factory("companiesFactory", function($http){
	var fact = {};

	fact.grabCompanies = function(){
		return $http({
			method : 'GET',
			url : 'api/v1/companies',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

 	fact.grabMyCompanies = function(){
		return $http({
			method : 'GET',
			url : 'api/v1/companies?followed=1',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	return fact;
})
tappyn.controller('contestsController', function($scope, $rootScope, $location, contestsFactory){

	contestsFactory.grabAllContests().success(function(response){
		$scope.contests = response.data.contests;
	});

	$scope.filter_industry = function(pass){
		contestsFactory.filterGrab(pass).success(function(response){
			$scope.contests = response.data.contests;
		})
	}
	
	$scope.grab_all = function(){
		$scope.tab = 'all';
		contestsFactory.grabAllContests().success(function(response){
			$scope.contests = response.data.contests;
		});
	}

	$scope.grab_my = function(){
		$scope.tab = "my";
		contestsFactory.grabMyContests().success(function(response){
			if(response.http_status_code == 200){
				if(response.success) $scope.contests = response.data.contests;	
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500){
				$scope.set_alert(response.error, "default");
				$scope.adding_interests();
			}
			else $scope.check_code(response.http_status_code);
		})
	}

	
	$scope.to_account = function(){
		$scope.with_email = false;
		$scope.have_account = true;
		$scope.forgot = false;
	}

	$scope.email_regis = function(){
		$scope.with_email = true;
		$scope.have_account = false;
		$scope.forgot = false;
	}

	$scope.forgotten = function(){
		$scope.with_email = false;
		$scope.have_account = false;
		$scope.forgot = true;
	}
})
tappyn.factory('contestsFactory', function($http){
	var fact = {};

	fact.grabMyContests = function(){
		return $http({
			method : 'GET',
			url : 'api/v1/contests/interesting',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	fact.grabAllContests = function(){
		return $http({
			method : 'GET',
			url : 'api/v1/contests',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	return fact;
})
tappyn.controller("editController", function($scope, $rootScope, $upload, $routeParams, editFactory){
	$scope.logged_in();
	if($routeParams.id){
		editFactory.grabEdit($routeParams.id).success(function(response){
			if(response.http_status_code == 200){
				if(response.success) $scope.contest = response.data.contest;	
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		})
	}

	$scope.edit = function(contest){
		editFactory.editContest(contest).success(function(response){
			if(response.http_status_code == 200){
				if(response.success) $scope.set_alert(response.message, "default");	
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		})
	}

	$scope.amazon_connect('tappyn');
	$scope.select_file = function($files, type){
	    var file = $files[0];
	    var url = 'https://tappyn.s3.amazonaws.com/';
    	var new_name = Date.now();
	    var rando = Math.random() * (10000 - 1) + 1;
	    var namen = new_name.toString() + rando.toString();
	    $upload.upload({
	        url: url,
	        method: 'POST',
	        data : {
	            key: namen,
	            acl: 'public-read',
	            "Content-Type": file.type === null || file.type === '' ?
	            'application/octet-stream' : file.type,
	            AWSAccessKeyId: $rootScope.key.key,
	            policy: $rootScope.key.policy,
	            signature: $rootScope.key.signature
	        },
	        file: file,
	    }).success(function (){
	       	if(type == "pic1"){
	       		$scope.contest.additional_image_1 = url + namen;
	       		$scope.contest.additional_images[0] = url + namen;
	       	}
	       	else if(type == 'pic2'){
	       		$scope.contest.additional_image_2 = url + namen;
	       		$scope.contest.additional_images[1] = url + namen;
	       	}
	       	else if(type == 'pic3'){
	       		$scope.contest.additional_image_3 = url + namen;
	       		$scope.contest.additional_images[2] = url + namen;
	       	}
	    });
	}
})
tappyn.factory("editFactory", function($http){
	var fact = {};

	fact.grabEdit = function(id){
		return $http({
			method : 'GET',
			url : 'api/v1/contests/'+id,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	fact.editContest = function(contest){
		return $http({
			method : 'POST',
			url : 'api/v1/contests/'+contest.id,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			data : $.param(contest)
		});
	}

	return fact;
})
tappyn.controller('dashController', function($scope, $rootScope, $route, dashFactory){
	//on page load grab all
	$scope.type = 'all';
	$scope.adding_payment = {show : false, id : ''};
	dashFactory.grabDash($scope.type).success(function(response){
		if(response.http_status_code == 200){
			if(response.success) $scope.dash = response.data;
			else alert(response.message);	 
		}
		else if(response.http_status_code == 500) alert(response.error);
		else $scope.check_code(response.http_status_code);
	});
	dashFactory.grabTotals().success(function(response){
		if(response.http_status_code == 200){
			if(response.success) $scope.totals = response.data;
			else alert(response.message);	 
		}
		else if(response.http_status_code == 500) alert(response.error);
		else $scope.check_code(response.http_status_code);
	})
	$scope.price = 99.99;

	$scope.grab_dash = function(type){
		$scope.type = type;

		if(type == "upvotes"){
			dashFactory.grabUpvoted().success(function(response){
				if(response.success) $scope.dash = response.data;
			});
		}	
		else{
			dashFactory.grabDash(type).success(function(response){
				if(response.success) $scope.dash = response.data;
			});
		}
	}

	$scope.claim_winnings = function(id){
		dashFactory.claimWinnings(id).success(function(response){
			if(response.http_status_code == 200){
				if(response.success) $scope.set_alert(response.message, "default");	
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		});
	}

	$scope.set_type = function(type){
		$scope.adding_payment.type = type;
	}

	$scope.open_payment = function(contest){
		dashFactory.grabDetails().success(function(response){
			if(response.http_status_code == 200){
				$scope.adding_payment = {show : true, contest : contest};
				$rootScope.modal_up = true;
				if(response.success){ 
					$scope.payments = response.data.customer.sources.data;
					$scope.add_new = false;
				}
				else $scope.add_new = true;
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		})
	}

	$scope.close_payment = function(){
		$scope.adding_payment = {show : false, contest : '', type : ''};
		$rootScope.modal_up = false;
	}

	var stripeResponseHandler = function(status, response) {
      if(response.error){
      	var erroring = (response.error.message).toString();
   		alert(response.error.message);
        $scope.form_disabled = false;
      } 
      else{
        // response contains id and card, which contains additional card details
        var token = response.id;
       	dashFactory.payContest($scope.adding_payment.contest.id, {stripe_token : token, save_method : $scope.save_method, voucher_code : $scope.voucher_code}).success(function(res){
       		if(res.http_status_code == 200){
				if(res.success){
					$scope.set_alert(res.message, "default");	
					$rootScope.modal_up = false;
					$scope.adding_payment = false;
					$scope.form_disabled = false;
				}
				else $scope.set_alert(res.message, "default");	 
			}
			else if(res.http_status_code == 500) $scope.set_alert(res.error, "error");
			else $scope.check_code(res.http_status_code);
       	});
      }
    }

	$scope.new_payment = function(){
		if($scope.price == 0.00){
			if(!$scope.voucher_code) $scope.set_alert("Please enter a voucher code", "error");
			else{
				dashFactory.payContest($scope.adding_payment.contest.id, {voucher_code : $scope.voucher_code}).success(function(res){
		       		if(res.http_status_code == 200){
						if(res.success){
							$scope.set_alert(res.message, "default");	
							$rootScope.modal_up = false;
							$scope.adding_payment = false;
							$route.reload();
						}
						else $scope.set_alert(res.message, "default");	 
					}
					else if(res.http_status_code == 500) $scope.set_alert(res.error, "error");
					else $scope.check_code(res.http_status_code);
		       	});
			}
		}
		else{	
			// This identifies your website in the createToken call below
			Stripe.setPublishableKey("pk_live_ipFoSG1UY45RGNkCpLVUaSBx");
			var $form = $('#payment-form');

	        // Disable the submit button to prevent repeated clicks
	        $scope.form_disabled = true;

			Stripe.card.createToken($form, stripeResponseHandler);
		}
	}

	$scope.old_payment = function(){
		if($scope.price == 0.00){
			if(!$scope.voucher_code) $scope.set_alert("Please enter a voucher code", "error");
			else{
				dashFactory.payContest($scope.adding_payment.contest.id, {voucher_code : $scope.voucher_code}).success(function(res){
		       		if(res.http_status_code == 200){
						if(res.success){
							$scope.set_alert(res.message, "default");	
							$rootScope.modal_up = false;
							$scope.adding_payment = false;
							$route.reload();
						}
						else $scope.set_alert(res.message, "default");	 
					}
					else if(res.http_status_code == 500) $scope.set_alert(res.error, "error");
					else $scope.check_code(res.http_status_code);
		       	});
			}
		}	
		else{
			if(!$scope.passing_method) $scope.set_alert("Please select a saved method or provide a new means of paying", "error");
			else{
				dashFactory.payContest($scope.adding_payment.contest.id, {source_id : $scope.passing_method, voucher_code : $scope.voucher_code}).success(function(res){
		       		if(res.http_status_code == 200){
						if(res.success){
							$scope.set_alert(res.message, "default");	
							$rootScope.modal_up = false;
							$scope.adding_payment = false;
							$route.reload();
						}
						else $scope.set_alert(res.message, "default");	 
					}
					else if(res.http_status_code == 500) $scope.set_alert(res.error, "error");
					else $scope.check_code(res.http_status_code);
		       	});
			}
		}
	}

	$scope.use_voucher = function(){
		if(!$scope.voucher_code) $scope.set_alert("Please enter a voucher code", "error");
		else{
			dashFactory.voucherValid($scope.voucher_code).success(function(res){
	       		if(res.http_status_code == 200){
					if(res.success){
						$scope.price = res.data.price;
						$scope.reduction = res.data.discount;
					}
					else $scope.set_alert(res.message, "default");	 
				}
				else if(res.http_status_code == 500) $scope.set_alert(res.error, "error");
				else $scope.check_code(res.http_status_code);
	       	});
		}
	}

	$scope.voucher_payment = function(){
		if(!$scope.voucher_code) $scope.set_alert("Please enter a voucher code", "error");
		else{
			dashFactory.payContest($scope.adding_payment.contest.id, {voucher_code : $scope.voucher_code}).success(function(res){
	       		if(res.http_status_code == 200){
					if(res.success){
						$scope.set_alert(res.message, "default");	
						$rootScope.modal_up = false;
						$scope.adding_payment = false;
						$route.reload();
					}
					else $scope.set_alert(res.message, "default");	 
				}
				else if(res.http_status_code == 500) $scope.set_alert(res.error, "error");
				else $scope.check_code(res.http_status_code);
	       	});
		}
	}

	$scope.select_current = function(pass){
		$scope.passing_method = pass;
	}

})
tappyn.factory('dashFactory', function($http){
	var fact = {};

	fact.grabDash = function(type){
		return $http({
			method : 'GET',
			url : 'api/v1/dashboard?type='+type,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	fact.claimWinnings = function(id){
		return $http({
			method : 'GET',
			url : 'api/v1/payouts/'+id+'/claim',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	fact.grabDetails = function(){
		return $http({
			method : 'GET',
			url : 'api/v1/companies/accounts',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		})	
	}

	fact.payContest = function(id, obj){
		return $http({
			method : 'POST',
			url : 'api/v1/companies/payment/'+id,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			data : $.param(obj) 
		})	
	}
	fact.voucherValid = function(id){
		return $http({
			method : 'POST',
			url : 'api/v1/vouchers',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			data : $.param({voucher_code : id}) 
		})	
	}
	fact.grabTotals = function(){
		return $http({
			method : 'GET',
			url : 'api/v1/stats',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}
	fact.grabUpvoted = function(){
		return $http({
			method : 'GET',
			url : 'api/v1/upvoted',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}
	return fact;
})
tappyn.controller('contestController', function($scope, $rootScope, $filter, $route, $anchorScroll, $routeParams, $upload, $location, emotions, contestFactory, contestModel){
	$scope.emotions = emotions;
	contestFactory.grabContest($routeParams.id).success(function(response){
		$scope.contest = response.data.contest;
		$scope.submissions = response.data.submissions;
		contestModel.fire_google($scope.contest);
		if($scope.contest.status == "ended"){
			if($rootScope.user){
				if($rootScope.user.id != $scope.contest.owner && !$rootScope.user.is_admin) $location.path('/ended/'+$routeParams.id);
			}
			else $location.path('/ended/'+$routeParams.id);
		}
		if($scope.contest.emotion){
			$scope.emotion_contest = contestModel.sift_images($scope.contest, $scope.emotions);
		}
		else $scope.example = false;
		$scope.form_limit = contestModel.parallel_submission($scope.contest);
		if($scope.contest.platform == "instagram"){
			$scope.cropper = new Cropper(document.getElementById('upload_contest'), {
				aspectRatio: 1 / 1,
			  	dragMode : 'move',
			  	scaleable : false,
			  	cropBoxResizable : false,
			  	cropBoxMovable : false,
			  	minCropBoxWidth : 100,
			  	preview : '.img-preview'
			});
		}
		else{
			$scope.cropper = new Cropper(document.getElementById('upload_contest'), {
				aspectRatio: 1.91 / 1,
			  	dragMode : 'move',
			  	scaleable : false,
			  	cropBoxResizable : false,
			  	cropBoxMovable : false,
			  	minCropBoxWidth : 100,
			  	preview : '.img-preview'
			});
		}
	});

	$scope.view = {brief : true, submissions : false};
	$scope.view_brief = function(){
		$scope.view = {brief : true, submissions : false};
	}
	$scope.view_submissions = function(){
		$scope.view = {brief : false, submissions : true};
	}

	$scope.scroll_to_submish = function(){
		var old = $location.hash();
		$location.hash("submishes");
		$anchorScroll();
		$location.hash(old);
	}

	$scope.imagerino = "";

	$scope.upload_image = function(id, submission){
		var canvas = $scope.cropper.getCroppedCanvas();
		var url = 'https://tappyn.s3.amazonaws.com/';
	    var new_name = Date.now();
	    var rando = Math.random() * (10000 - 1) + 1;
	    new_name = new_name.toString() + rando.toString();
		var blobbers = canvas.toBlob(function(blob){
			var file = blob;
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
	       		submission.attachment_url = url+new_name;
	       		contestFactory.submitTo(id, submission).success(function(response){
					if(response.http_status_code == 200){
						if(response.success){
							$scope.set_alert(response.message, "default");	 
							$scope.update_points(2);
							ga('send', {
							hitType: 'event',
							eventCategory: 'Contest Submission',
							eventAction: 'submission',
							eventLabel: 'User Submission'});
							$route.reload();
						}
						else $scope.set_alert(response.message, "default");	 
					}
					else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
					else $scope.check_code(response.http_status_code);
				})
		    });
		}, "image/png", 0.95);
	}
	$scope.submit = {headline : '', text: ''};
	$scope.submit_to = function(id, submission){
		if($scope.form_limit.headline && submission.headline.length < 1) $scope.set_alert("Headline is required", "error");
		else if($scope.form_limit.text && submission.text.length < 1) $scope.set_alert("Text is required", "error");
		else if($scope.form_limit.line_1 && submission.link_explanation.length < 1) $scope.set_alert("Line 1 is required", "error");
		else if($scope.form_limit.line_2 && submission.text.length < 1) $scope.set_alert("Line 2 is required", "error");
		else if($scope.form_limit.card_title && submission.link_explanation.length < 1) $scope.set_alert("A card title is required", "error");
		else if($scope.form_limit.photo && $scope.imagerino == "") $scope.set_alert("An uploaded image is required for this contest", "error");
		else{
			if($scope.form_limit.photo){
				submission.photo = $scope.cropper.getCroppedCanvas().toDataURL('image/jpeg');
			}
			contestFactory.submitTo(id, submission).success(function(response){
				if(response.http_status_code == 200){
					if(response.success){
						$scope.set_alert(response.message, "default");	 
						$scope.update_points(2);
						ga('send', {
						hitType: 'event',
						eventCategory: 'Contest Submission',
						eventAction: 'submission',
						eventLabel: 'User Submission'});
						$route.reload();
					}
					else $scope.set_alert(response.message, "default");	 
				}
				else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
				else $scope.check_code(response.http_status_code);
			})
		}
	}

	$scope.choose_winner = function(id){
		contestFactory.chooseWinner($scope.contest.id, id).success(function(response){
			if(response.http_status_code == 200){
				if(response.success) $scope.set_alert(response.message, "default");	
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		})
	}

	$scope.upvote = function(submission){
		if(!$rootScope.user) $scope.open_register("upvote", {contest : $scope.contest.id, submission : submission.id});
		else {	
			contestFactory.upvote($scope.contest.id,submission.id).success(function(response){
				if(response.http_status_code == 200){
					if(response.success){
						$scope.set_alert(response.message, "default");
						$scope.update_points(1);
						submission.user_may_vote = false;
						submission.votes++;
					}	
					else $scope.set_alert(response.message, "default");	 
				}
				else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
				else $scope.check_code(response.http_status_code);
			})
		}
	}

	$scope.share = function(submission){
		FB.ui({
  			method: 'share',
		 	href: $location.protocol()+'://'+$location.host()+'/submissions/share/'+submission.id,
		}, function(response){
			
		});
	}

	$scope.show_tips = function(){
		$scope.tips = true;
	}
	$scope.hide_tips = function(){
		$scope.tips = false;
	}

	var handleFileSelect=function(evt) {
      var file=evt.currentTarget.files[0];
      var reader = new FileReader();
      reader.onload = function (evt) {
        $scope.$apply(function($scope){
           $scope.cropper.replace(evt.target.result);
           $scope.imagerino = evt.target.result;
        });
      };
      reader.readAsDataURL(file);
    };
    angular.element(document.querySelector('#fileInput')).on('change',handleFileSelect);

    $scope.preview_image = function(){
    	$scope.preview = $scope.cropper.getCroppedCanvas().toDataURL("image/png");
    	$scope.image_show = 'preview';
    }

	$scope.chooserino = function(){
		var photo = angular.element(document.getElementById('upload_contest'));		
	}

	$scope.track_click = function(event, contest){
		contestFactory.track(event, contest.id).success(function(response){

		});

		if(event == 'facebook_click'){
			var win = window.open($filter('urlFilter')(contest.company.facebook_url), '_blank');
  			win.focus();	
		}
		else if(event == 'website_click'){
			var win = window.open($filter('urlFilter')(contest.company.company_url), '_blank');
  			win.focus();
		}
		else if(event == 'twitter_click'){
			var win = window.open("https://twitter.com/"+contest.company.twitter_handle, '_blank');
  			win.focus();
		}
	}
});
tappyn.factory('contestFactory', function($http){
	var fact = {};

	fact.grabContest = function(id){
		return $http({
			method : 'GET',
			url : 'api/v1/contests/'+id,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	fact.submitTo = function(id, submission){
		return $http({
			method : 'POST',
			url : 'api/v1/contests/'+id+'/submissions',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			'data' : $.param(submission)
		});	
	}

	fact.chooseWinner = function(contest, id){
		return $http({
			method : 'POST',
			url : 'api/v1/contests/'+contest+'/winner',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			data : $.param({submission : id})
		});
	}

	fact.upvote = function(contest, id){
		return $http({
			method : 'POST',
			url : 'api/v1/submissions/'+id+'/votes',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	fact.track = function(event, id){
		return $http({
			method : 'GET',
			url : 'api/v1/analytics/track?ev='+event+'&cid='+id,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}
	return fact;
})
tappyn.service("contestModel", function(){
	this.sift_images = function(contest, emotions){
		for(var i = 0; i < emotions.length; i++){
			if(contest.emotion == emotions[i].type){
				return {icon : emotions[i].icon, adj : emotions[i].adjectives, example : ''};
			}
		}
	}
	this.parallel_submission = function(contest){
		var layout = {};
		if(contest.platform == "facebook"){
			layout.text = {limit : 90, placeholder : 'Simple explanation of why this audience should pick this specific company. Speak like a human.'};
			if(contest.objective != 'engagement') layout.headline = {limit : 35, placeholder : 'Insert Headline Here. No such thing as dull products only dull writers.'};
		}
		else if(contest.platform == "twitter"){
			if(contest.display_type == 'with_photo') layout.text = {limit : 116, placeholder : 'Insert Tweet. No such thing as dull products only dull writers. '};
			else layout.text = {limit : 140, placeholder : 'Insert Tweet. No such thing as dull products only dull writers. '};
			if(contest.objective == "site_clicks_conversions") layout.card_title = {limit : 70, placeholder : 'Simple explanation of why this audience should pick this specific company. Speak like a human.'}
		}
		else if(contest.platform == "google"){
			layout.headline = {limit : 25, placeholder : 'Insert Headline Here. No such thing as dull products only dull writers.'};
			layout.line_1 = {limit : 35, placeholder : 'Simple explanation of why this audience should pick this company. Speak like a human.'};
			layout.line_2 = {limit : 35, placeholder : 'Simple explanation of why this audience should pick this company. Speak like a human.'};
		}
		else if(contest.platform == "general") layout.headline ={limit : 35, placeholder : 'Insert Headline Here. No such thing as dull products only dull writers.'};
		else if(contest.platform == "instagram") layout.text = {limit : 90, placeholder : 'Simple explanation of why this audience should pick this specific company. Speak like a human.'};

		if(contest.display_type == "with_photo") layout.photo = true;
		return layout;
	}

	this.checkImageSize = function(size, contest){
		console.log(size);
		if(contest.platform == "facebook"){
			if(size.width < 1200 || size.height < 630) return false;
			else return true;
		}
		else if(contest.platform == "instagram"){
			if(size.width < 600 || size.height < 315) return false;
			else return true;
		}
		else if(contest.platform == 'twitter'){
			if(contest.objective == "site_clicks_conversions"){	
				if(size.width < 800 || size.height < 320) return false;
				else return true;
			}
			else{
				if(size.width < 600 || size.height < 315) return false;
				else return true;
			}
		}
		else return true;
	}

	this.fire_google = function(contest){
		if(contest.gender == "0"){
			switch(contest.min_age){
				case "18" : 
					ga('set', 'contentGroup11', '<18-24 All Gender Contest>');
					ga('send', 'pageview');
				break;
				case "25" : 
					ga('set', 'contentGroup12', '<25-34 All Gender Contest>');
					ga('send', 'pageview');
				break;
				case "35" : 
					ga('set', 'contentGroup13', '<35-44 All Gender Contest>');
					ga('send', 'pageview');
				break;
				case "45" : 
					ga('set', 'contentGroup14', '<45+ All Gender Contest>');
					ga('send', 'pageview');
				break;
			}
		}
		else if(contest.gender == "1"){
			switch(contest.max_age){
				case "18" : 
					ga('set', 'contentGroup7', '<18-24 Male Contest>');
					ga('send', 'pageview');
				break;
				case "25" : 
					ga('set', 'contentGroup8', '<25-34 Male Contest>');
					ga('send', 'pageview');
				break;
				case "35" : 
					ga('set', 'contentGroup9', '<35-44 Male Contest>');
					ga('send', 'pageview');
				break;
				case "45" : 
					ga('set', 'contentGroup10', '<45+ Male Contest>');
					ga('send', 'pageview');
				break;
			}
		}
		else if(contest.gender == "2"){
			switch(contest.max_age){
				case "18" : 
					ga('set', 'contentGroup3', '<18-24 Female Contest>');
					ga('send', 'pageview');
				break;
				case "25" : 
					ga('set', 'contentGroup4', '<25-34 Female Contest>');
					ga('send', 'pageview');
				break;
				case "35" : 
					ga('set', 'contentGroup5', '<35-44 Female Contest>');
					ga('send', 'pageview');
				break;
				case "45" : 
					ga('set', 'contentGroup6', '<45+ Female Contest>');
					ga('send', 'pageview');
				break;
			}
		}
	}
})
tappyn.controller("endedController", function($scope, $location, $routeParams, endedFactory){
	endedFactory.grabContest($routeParams.id).success(function(response){
		$scope.contest = response.data.contest;
		$scope.winner = response.data.winner;
		if($scope.contest.status == 'active') $location.path('/contest/'+$routeParams.id);
	})
})
tappyn.factory("endedFactory", function($http){
	var fact = {};

	fact.grabContest = function(id){
		return $http({
			method : 'GET',
			url : 'api/v1/contests/'+id+'/winner',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		})
	}

	return fact;
})
tappyn.controller('homeController', function($scope, $rootScope, $location, homeFactory){
	$rootScope.modal_up = false;

	homeFactory.contestGrab().success(function(response){
		$scope.contests = response.data.contests;
	})

	homeFactory.winnersGrab().success(function(response){
		$scope.submissions = response.data.submissions;
	})
})
tappyn.factory('homeFactory', function($http){
	var fact = {};

	fact.contestGrab = function(){
		return $http({
			method : 'GET',
			url : 'api/v1/contests',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	fact.winnersGrab = function(){
		return $http({
			method : 'GET',
			url : 'api/v1/submissions/winners',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		})	
	}

	return fact;
});
tappyn.controller("paymentController", function($scope, $rootScope, $location, paymentFactory, paymentModel){
	$scope.logged_in();
	$scope.countries = paymentModel.countries;
	$scope.showing = "methods";
	paymentFactory.grabDetails().success(function(response){
		if(response.http_status_code == 200){
			if(response.success){
				if($rootScope.user.type == "member" && response.data.account == false){
					$scope.detail = {first_name : $rootScope.user.first_name, last_name : $rootScope.user.last_name};
					$scope.showing = 'details';
				}
				else if($rootScope.user.type == "member" && response.data.account){
					var account = response.data.account;
					$scope.detail = {first_name : account.legal_entity.first_name, 
						last_name : account.legal_entity.last_name, 
						dob_year : account.legal_entity.dob.year, 
						dob_month : account.legal_entity.dob.month, 
						dob_day : account.legal_entity.dob.day, 
						city : account.legal_entity.address.city,
						state : account.legal_entity.address.state,
						postal_code : account.legal_entity.address.postal_code,
						country : account.legal_entity.address.country,
						address_line1 : account.legal_entity.address.line1,
						address_line2 : account.legal_entity.address.line2};
					$scope.showing = 'methods';
					console.log(account);
				}
				$scope.account = response.data.account;
			}
			else $scope.set_alert(response.message, "default");	 
		}
		else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
		else $scope.check_code(response.http_status_code);
	})

	$scope.verify_identity = function(detail){
		paymentFactory.verifyIdentity(detail).success(function(response){
			if(response.http_status_code == 200){
				if(response.success){
					$scope.set_alert(response.message, "default");	
					$scope.account = response.data.account;
					$scope.showing = 'methods';
				}
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		});
	}

	$scope.toggle_view = function(view){
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
       	paymentFactory.addPayment(token).success(function(res){
       		if(res.http_status_code == 200){
				if(res.success){
					$scope.account = res.data.account;
					$scope.set_alert(res.message, "default");
					$rootScope.modal_up = false;
					$scope.add_method = false;
				}
				else $scope.set_alert(res.message, "default");	 
			}
			else if(res.http_status_code == 500) $scope.set_alert(res.error, "error");
			else $scope.check_code(res.http_status_code);
       	});
      }
    };
	$scope.process_addition = function(){
		// This identifies your website in the createToken call below
		Stripe.setPublishableKey("pk_live_ipFoSG1UY45RGNkCpLVUaSBx");
		var $form = $('#payment-form');

        // Disable the submit button to prevent repeated clicks
       $scope.form_disabled = true;

		Stripe.card.createToken($form, stripeResponseHandler);
	}

	$scope.remove_method = function(means){
		paymentFactory.removeMethod(means).success(function(response){
			if(response.http_status_code == 200){
				if(response.success){
					$scope.account = response.data.account;
					$scope.set_alert(response.message, "default");
				}
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		});
	}

	$scope.open_add = function(){
		$rootScope.modal_up = true;
		$scope.add_method = true;
	}

	$scope.close_add = function(){
		$rootScope.modal_up = false;
		$scope.add_method = false;
	}

	$scope.set_default = function(means){
		paymentFactory.setDefault(means).success(function(response){
			if(response.http_status_code == 200){
				if(response.success){
					$scope.account = response.data.account;
					$scope.set_alert(response.message, "default");
				}
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		});
	}
});
tappyn.factory("paymentFactory", function($http){
	var fact = {};

	fact.grabDetails = function(){
		return $http({
			method : 'GET',
			url : 'api/v1/accounts/details',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		})	
	}

	fact.verifyIdentity = function(details){
		return $http({
			method : 'POST',
			url : 'api/v1/accounts/details',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			data : $.param(details)
		})	
	}

	fact.addPayment = function(token){
		return $http({
			method : 'POST',
			url : 'api/v1/accounts/payment_methods',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			data : $.param({stripeToken : token})
		})	
	}

	fact.removeMethod = function(id){
		return $http({
			method : 'POST',
			url : 'api/v1/accounts/remove_method',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			data : $.param({source_id : id})
		})	
	}

	fact.setDefault = function(id){
		return $http({
			method : 'POST',
			url : 'api/v1/accounts/default_method',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			data : $.param({source_id : id})
		})	
	}
	return fact;	
})
tappyn.service('paymentModel', function(){
	this.countries = {
        'CA' : 'Canada',
        'GB' : 'United Kingdom',
        'US' : 'United States'
	};
})
tappyn.controller('launchController', function($scope, $location, $anchorScroll, $upload, $route, $rootScope, launchFactory, launchModel, emotions){
	$scope.logged_in()
	$scope.steps = {
		'package'		 : {step : 'package',  next : 'detail',  previous : 'none',    fill : 25},
		'detail' 		 : {step : 'detail',   next : 'payment', previous : 'package', fill : 50},
		'preview' 		 : {step : 'preview',   next : 'payment', previous : 'package', fill : 75},
		'done'		 	 : {step : 'done',     next : 'none',    previous : 'none',    fill : 100}
	}
	$scope.current = $scope.steps['package'];
	$scope.personalities = emotions; 
	$scope.contest = {};
	$scope.company = {};
	$scope.save_method = false;

	$scope.registering = false;

	$scope.price = 99.99;

	$scope.close_register = function(){
		$rootScope.modal_up = false;
		$scope.registering = false;
	}

	$scope.set_step = function(step){
		if(step == 'detail'){
			if(!$scope.profile && $rootScope.user) $scope.grab_profile();
			$scope.current = $scope.steps[step];
		}
		else if(step == 'preview'){
			if(!$rootScope.user) $scope.open_register("company", '');
			else if(!$scope.contest.summary || $scope.contest.summary == '')  $scope.set_alert("A summary of service or product is required", "error");
			else if(!$scope.contest.industry || $scope.contest.industry == '')  $scope.set_alert("A user interest is required", "error");
			else if(!$scope.contest.audience || $scope.contest.audience == '')  $scope.set_alert("A longer description is required", "error");
			else if(!$scope.contest.different || $scope.contest.different == '')  $scope.set_alert("What makes you different is required", "error");
			else{
				$scope.emotion_contest = launchModel.sift_images($scope.contest, $scope.personalities);
				$scope.form_limit = launchModel.parallel_submission($scope.contest);
				$scope.current = $scope.steps[step];
				$scope.contest.additional_images = [];
				if($scope.contest.additional_image_1) $scope.contest.additional_images.push($scope.contest.additional_image_1);
				if($scope.contest.additional_image_2) $scope.contest.additional_images.push($scope.contest.additional_image_2);
				if($scope.contest.additional_image_3) $scope.contest.additional_images.push($scope.contest.additional_image_3);
				if($scope.contest.additional_images.length < 1) $scope.contest.additional_images = null;
			}
		}
		else $scope.current = $scope.steps[step];
		$scope.to_top();
	}

	$scope.select_objective = function(objective){
		$scope.contest.objective = objective;
		$scope.contest.display_type = null;
		var old = $location.hash();
		$location.hash("display");
		$anchorScroll();
		$location.hash(old);
	}

	$scope.select_platform = function(platform){
		$scope.contest.platform = platform;
		$scope.contest.objective = null;
		$scope.contest.display_type = null;
		var old = $location.hash();
		$location.hash("objective");
		$anchorScroll();
		$location.hash(old);
	}

	$scope.select_display = function(type){
		$scope.contest.display_type = type;
	}	

	$scope.choose_personality = function(type){
		$scope.contest.emotion = type;
	}

	$scope.grab_profile = function(){
		launchFactory.grabProfile().success(function(response){
			if(response.http_status_code == 200){
				if(response.success){
					$scope.profile = response.data;
					if(!$scope.contest.summary || $scope.contest.summary == '') $scope.contest.summary = $scope.profile.summary;
					else if(!$scope.contest.audience || $scope.contest.audience == '') $scope.contest.audience = $scope.profile.audience;
					else if(!$scope.contest.different || $scope.contest.different == '') $scope.contest.different = $scope.profile.different;
				}
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		})
	}

	$scope.grab_payments = function(){
		launchFactory.grabDetails().success(function(response){
			if(response.http_status_code == 200){
				if(response.success) $scope.payments = response.data.customer.sources.data;
				else $scope.add_new = true; 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		})
	}

	$scope.to_detail = function(contest){
		contest.display_type = "with_photo";
		if(!contest.platform || contest.platform == '') $scope.set_alert("You need to select a platform", "error");
		else if(!contest.objective || contest.objective == '')  $scope.set_alert("You need to select an ad objective", "error");
		else $scope.set_step("detail");
	}

	$scope.open_payment = function(){
		$scope.grab_payments();
		$scope.adding_payment = true;
		$rootScope.modal_up = true;
	}

	$scope.close_payment = function(){
		$scope.adding_payment = false;
		$rootScope.modal_up = false;
	}


	$scope.submit_contest = function(contest, pay){
		if(contest.id){
			launchFactory.update(contest).success(function(response){
				if(response.http_status_code == 200){
					if(response.success){
						if(pay) $scope.open_payment();
						else{
							$scope.set_alert("Saved as draft, to launch, pay in dashboard", "default");
							$scope.set_step('done');
						}
					}
					else $scope.set_alert(response.message, "default");	 
				}
				else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
				else $scope.check_code(response.http_status_code);
			});	
		}	
		else{
			launchFactory.submission(contest).success(function(response){
				if(response.http_status_code == 200){
					if(response.success){
						$scope.contest.id = response.data.id;
						if(pay) $scope.open_payment();
						else{
							$scope.set_alert("Saved as draft, to launch, pay in dashboard", "default");
							$scope.set_step('done');
						}
					}
					else $scope.set_alert(response.message, "default");	 
				}
				else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
				else $scope.check_code(response.http_status_code);
			});	
		}
	}
	var stripeResponseHandler = function(status, response) {
      if(response.error){
      	var erroring = (response.error.message).toString();
   		alert(response.error.message);
        $scope.form_disabled = false;
      } 
      else{
        // response contains id and card, which contains additional card details
        var token = response.id;
       	launchFactory.payContest($scope.contest.id, {stripe_token : token, save_method : $scope.save_method, voucher_code : $scope.voucher_code}).success(function(res){
       		if(res.http_status_code == 200){
				if(res.success){
					$scope.set_alert(res.message, "default");	
					$scope.set_step("done");
					$rootScope.modal_up = false;
					$scope.adding_payment = false;
					$scope.form_disabled = false;
				}
				else $scope.set_alert(res.message, "default");	 
			}
			else if(res.http_status_code == 500) $scope.set_alert(res.error, "error");
			else $scope.check_code(res.http_status_code);
       	});
      }
    }


	$scope.new_payment = function(){
		if($scope.price == 0.00){
			if(!$scope.voucher_code) $scope.set_alert("Please enter a voucher code", "error");
			else{
				launchFactory.payContest($scope.contest.id, {voucher_code : $scope.voucher_code}).success(function(res){
		       		if(res.http_status_code == 200){
						if(res.success){
							$scope.set_alert(res.message, "default");	
							$scope.set_step("done");
							$rootScope.modal_up = false;
							$scope.adding_payment = false;
						}
						else $scope.set_alert(res.message, "default");	 
					}
					else if(res.http_status_code == 500) $scope.set_alert(res.error, "error");
					else $scope.check_code(res.http_status_code);
		       	});
			}
		}
		else{	
			// This identifies your website in the createToken call below
			Stripe.setPublishableKey("pk_live_ipFoSG1UY45RGNkCpLVUaSBx");
			var $form = $('#payment-form');

	        // Disable the submit button to prevent repeated clicks
	        $scope.form_disabled = true;

			Stripe.card.createToken($form, stripeResponseHandler);
		}
	}

	$scope.old_payment = function(){
		if($scope.price == 0.00){
			if(!$scope.voucher_code) $scope.set_alert("Please enter a voucher code", "error");
			else{
				launchFactory.payContest($scope.contest.id, {voucher_code : $scope.voucher_code}).success(function(res){
		       		if(res.http_status_code == 200){
						if(res.success){
							$scope.set_alert(res.message, "default");	
							$scope.set_step("done");
							$rootScope.modal_up = false;
							$scope.adding_payment = false;
						}
						else $scope.set_alert(res.message, "default");	 
					}
					else if(res.http_status_code == 500) $scope.set_alert(res.error, "error");
					else $scope.check_code(res.http_status_code);
		       	});
			}
		}	
		else{
			if(!$scope.passing_method) $scope.set_alert("Please select a saved method or provide a new means of paying", "error");
			else{
				launchFactory.payContest($scope.contest.id, {source_id : $scope.passing_method, voucher_code : $scope.voucher_code}).success(function(res){
		       		if(res.http_status_code == 200){
						if(res.success){
							$scope.set_alert(res.message, "default");	
							$scope.set_step("done");
							$rootScope.modal_up = false;
							$scope.adding_payment = false;
						}
						else $scope.set_alert(res.message, "default");	 
					}
					else if(res.http_status_code == 500) $scope.set_alert(res.error, "error");
					else $scope.check_code(res.http_status_code);
		       	});
			}
		}
	}

	$scope.use_voucher = function(){
		if(!$scope.voucher_code) $scope.set_alert("Please enter a voucher code", "error");
		else{
			launchFactory.voucherValid($scope.voucher_code).success(function(res){
	       		if(res.http_status_code == 200){
					if(res.success){
						$scope.price = res.data.price;
						$scope.reduction = res.data.discount;
					}
					else $scope.set_alert(res.message, "default");	 
				}
				else if(res.http_status_code == 500) $scope.set_alert(res.error, "error");
				else $scope.check_code(res.http_status_code);
	       	});
		}
	}

	$scope.voucher_payment = function(){
		if(!$scope.voucher_code) $scope.set_alert("Please enter a voucher code", "error");
		else{
			launchFactory.payContest($scope.contest.id, {voucher_code : $scope.voucher_code}).success(function(res){
	       		if(res.http_status_code == 200){
					if(res.success){
						$scope.set_alert(res.message, "default");	
						$scope.set_step("done");
						$rootScope.modal_up = false;
						$scope.adding_payment = false;
					}
					else $scope.set_alert(res.message, "default");	 
				}
				else if(res.http_status_code == 500) $scope.set_alert(res.error, "error");
				else $scope.check_code(res.http_status_code);
	       	});
		}
	}

	$scope.select_current = function(pass){
		$scope.passing_method = pass;
	}

	$scope.amazon_connect('tappyn');
	$scope.select_file = function($files, type){
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
	       	if(type == "logo") $scope.company.logo_url = url+new_name;
	       	else if(type == "pic1") $scope.contest.additional_image_1 = url+new_name;
	       	else if(type == 'pic2') $scope.contest.additional_image_2 = url+new_name;
	       	else if(type == 'pic3') $scope.contest.additional_image_3 = url+new_name;
	    });
	}

	$scope.reload = function(){
		$route.reload();
	}
});
tappyn.factory('launchFactory', function($http){
	var fact = {}

	fact.submission = function(contest){
		return $http({
			method : 'POST',
			url : 'api/v1/contests',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			data : $.param(contest)
		});	
	}

	fact.update = function(contest){
		return $http({
			method : 'POST',
			url : 'api/v1/contests/'+contest.id,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			data : $.param(contest)
		});	
	}

	fact.grabProfile = function(){
		return $http({
			method : 'GET',
			url : 'api/v1/profile',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	fact.grabDetails = function(){
		return $http({
			method : 'GET',
			url : 'api/v1/companies/accounts',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		})	
	}

	fact.payContest = function(id, obj){
		return $http({
			method : 'POST',
			url : 'api/v1/companies/payment/'+id,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			data : $.param(obj) 
		})	
	}

	fact.voucherValid = function(id){
		return $http({
			method : 'POST',
			url : 'api/v1/vouchers',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			data : $.param({voucher_code : id}) 
		})	
	}
	return fact;
})
tappyn.service('launchModel', function(){
	this.sift_images = function(contest, emotions){
		for(var i = 0; i < emotions.length; i++){
			if(contest.emotion == emotions[i].type){
				if(contest.platform == "google"){
					if(emotions[i].google != '') return {icon : emotions[i].icon, adj : emotions[i].adjectives, example : emotions[i].google};
					else return {icon : emotions[i].icon, adj : emotions[i].adjectives, example : 'public/img/google_submish.png'};
				}

				if(contest.platform == 'facebook'){
					if(emotions[i].facebook != '') return {icon : emotions[i].icon, adj : emotions[i].adjectives, example : emotions[i].facebook};
					else return {icon : emotions[i].icon, adj : emotions[i].adjectives, example : 'public/img/fb_submish.png'};
				}

				if(contest.platform == 'twitter'){
					if(emotions[i].twitter != '') return {icon : emotions[i].icon, adj : emotions[i].adjectives, example : emotions[i].twitter};
					else return {icon : emotions[i].icon, adj : emotions[i].adjectives, example : 'public/img/Twitter_submish.png'};
				}

				if(contest.platform == "general" && contest.display_type == "headline"){
					if(emotions[i].general.headline != '') return {icon : emotions[i].icon, adj : emotions[i].adjectives, example : emotions[i].general.headline};
					else return null;
				}

				if(contest.platform == "general" && contest.display_type == "tagline"){
					if(emotions[i].general.tagline != '') return {icon : emotions[i].icon, adj : emotions[i].adjectives, example : emotions[i].general.tagline};
					else return null;
				}

				if(contest.platform == "general" && contest.display_type == "copies") return null;
			}
		}
	}
	this.parallel_submission = function(contest){
		var layout = {};
		if(contest.platform == "facebook"){
			layout.text = {limit : 250, placeholder : 'Simple explanation of why this audience should pick this specific company. Speak like a human.'};
			if(contest.objective != 'engagement') layout.headline = {limit : 35, placeholder : 'Insert Headline Here. No such thing as dull products only dull writers.'};
		}
		else if(contest.platform == "twitter"){
			if(contest.display_type == 'with_photo') layout.text = {limit : 116, placeholder : 'Insert Tweet. No such thing as dull products only dull writers. '};
			else layout.text = {limit : 140, placeholder : 'Insert Tweet. No such thing as dull products only dull writers. '};
			if(contest.objective == "site_clicks_conversions") layout.card_title = {limit : 70, placeholder : 'Simple explanation of why this audience should pick this specific company. Speak like a human.'}
		}
		else if(contest.platform == "google"){
			layout.headline = {limit : 25, placeholder : 'Insert Headline Here. No such thing as dull products only dull writers.'};
			layout.line_1 = {limit : 35, placeholder : 'Simple explanation of why this audience should pick this company. Speak like a human.'};
			layout.line_2 = {limit : 35, placeholder : 'Simple explanation of why this audience should pick this company. Speak like a human.'};
		}
		else if(contest.platform == "general" || contest.platform == "instagram") layout.headline ={limit : 35, placeholder : 'Insert Headline Here. No such thing as dull products only dull writers.'};

		if(contest.display_type == "with_photo") layout.photo = true;
		return layout;
	}
})
tappyn.controller('profileController', function($scope, $rootScope, $upload, profileFactory, profileModel){
	$scope.logged_in();
	$scope.amazon_connect('tappyn');
	$scope.states = profileModel.states;

	$scope.select_file = function($files){
	    var file = $files[0];
	    var url = 'https://tappyn.s3.amazonaws.com/';
	    var new_name = Date.now();
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
	       	$scope.profile.logo_url = url+new_name;
	    });
	}
	//grab that funky fresh profile on load
	profileFactory.grabProfile().success(function(response){
		if(response.http_status_code == 200){
			if(response.success) $scope.profile = response.data.profile;	
			else $scope.set_alert(response.message, "default");	 
		}
		else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
		else $scope.check_code(response.http_status_code);
	})

	$scope.update_profile = function(profile){
		profileFactory.updateProfile(profile).success(function(response){
			if(response.http_status_code == 200){
				if(response.success){
					$scope.set_alert(response.message, "default");
					$rootScope.user.first_name = $scope.profile.first_name;
					$rootScope.user.last_name = $scope.profile.last_name;
					sessionStorage.setItem("user", JSON.stringify($rootScope.user));
				}	
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		})
	}

	$scope.change_pass = function(pass){
		profileFactory.updatePass(pass).success(function(response){
			if(response.http_status_code == 200){
				if(response.success) $scope.set_alert(response.message, "default");	
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		})
	}

	$scope.resend = function(){
		profileFactory.resendVerification().success(function(response){
			if(response.http_status_code == 200){
				if(response.success) $scope.set_alert(response.message, "default");	
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);	
		})
	}	
});
tappyn.factory('profileFactory', function($http){
	var fact = {};

	fact.grabProfile = function(){
		return $http({
			method : 'GET',
			url : 'api/v1/profile',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	fact.updateProfile = function(profile){
		return $http({
			method : 'POST',
			url : 'api/v1/profile',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			data : $.param(profile)
		});
	}

	fact.updatePass = function(pass){
		return $http({
			method : 'POST',
			url : 'api/v1/password',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			data : $.param(pass)
		});
	}

	fact.resendVerification = function(){
		return $http({
			method : 'POST',
			url : 'api/v1/resend_verification',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}
	return fact;
})
tappyn.service("profileModel", function(){
this.states = 
{
    "AL": "Alabama",
    "AK": "Alaska",
    "AS": "American Samoa",
    "AZ": "Arizona",
    "AR": "Arkansas",
    "CA": "California",
    "CO": "Colorado",
    "CT": "Connecticut",
    "DE": "Delaware",
    "DC": "District Of Columbia",
    "FM": "Federated States Of Micronesia",
    "FL": "Florida",
    "GA": "Georgia",
    "GU": "Guam",
    "HI": "Hawaii",
    "ID": "Idaho",
    "IL": "Illinois",
    "IN": "Indiana",
    "IA": "Iowa",
    "KS": "Kansas",
    "KY": "Kentucky",
    "LA": "Louisiana",
    "ME": "Maine",
    "MH": "Marshall Islands",
    "MD": "Maryland",
    "MA": "Massachusetts",
    "MI": "Michigan",
    "MN": "Minnesota",
    "MS": "Mississippi",
    "MO": "Missouri",
    "MT": "Montana",
    "NE": "Nebraska",
    "NV": "Nevada",
    "NH": "New Hampshire",
    "NJ": "New Jersey",
    "NM": "New Mexico",
    "NY": "New York",
    "NC": "North Carolina",
    "ND": "North Dakota",
    "MP": "Northern Mariana Islands",
    "OH": "Ohio",
    "OK": "Oklahoma",
    "OR": "Oregon",
    "PW": "Palau",
    "PA": "Pennsylvania",
    "PR": "Puerto Rico",
    "RI": "Rhode Island",
    "SC": "South Carolina",
    "SD": "South Dakota",
    "TN": "Tennessee",
    "TX": "Texas",
    "UT": "Utah",
    "VT": "Vermont",
    "VI": "Virgin Islands",
    "VA": "Virginia",
    "WA": "Washington",
    "WV": "West Virginia",
    "WI": "Wisconsin",
    "WY": "Wyoming"
}

});
tappyn.controller("resetController", function($scope, $routeParams, $location, resetFactory){
	$scope.logged_in();
	
	resetFactory.checkCode($routeParams.code).success(function(response){
		if(response.http_status_code == 200){
			if(response.success){
				$scope.set_alert("Verified, please change your password", "default");
				$scope.code = $routeParams.code;
				$scope.pass = {csrf : response.data.csrf, user_id : response.data.user_id, new : '', new_confirm : ''}
			}
			else{
				$scope.set_alert("Unauthorized", "error");
				$location.path('/login');
			}
		}
		else{
			$scope.set_alert("Unauthorized", "error");
			$location.path('/login');
		}
	});

	$scope.change_pass = function(pass){
		resetFactory.changePass(pass, $scope.code).success(function(response){
			if(response.http_status_code == 200){
				if(response.success){
					$scope.set_alert(response.message, "default");
					$location.path('/login')
				}	
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		})
	}
})
tappyn.factory("resetFactory", function($http){
	var fact = {};

	fact.checkCode = function(code){
		return $http({
			method : 'GET',
			url : 'api/v1/reset_password/'+code,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		})
	}

	fact.changePass = function(pass, code){
		return $http({
			method : 'POST',
			url : 'api/v1/reset_password/'+code,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			data : $.param(pass)
		})
	}

	return fact;
})
tappyn.controller('topController', function($scope, $location, $rootScope, topFactory){
	
	$scope.view_live = function(){
		topFactory.grabTops().success(function(response){
			$scope.submissions = response.data.submissions;
			$scope.tab = "live";
		})
	}

	$scope.view_winners = function(){
		topFactory.grabWinners().success(function(response){
			$scope.submissions = response.data.submissions;
			$scope.tab = "winner";
		})
	}

	$scope.view_live();

	$scope.upvote = function(submission){
		if(!$rootScope.user) $scope.open_register("upvote", {contest : submission.contest_id, submission : submission.id});
		else {	
			topFactory.upvote(submission.contest_id,submission.id).success(function(response){
				if(response.http_status_code == 200){
					if(response.success){
						$scope.set_alert(response.message, "default");
						$scope.update_points(1);
						submission.user_may_vote = false;
						submission.votes++;
					}	
					else $scope.set_alert(response.message, "default");	 
				}
				else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
				else $scope.check_code(response.http_status_code);
			})
		}
	}

	$scope.share = function(submission){
		FB.ui({
  			method: 'share',
		 	href: $location.protocol()+'://'+$location.host()+'/submissions/share/'+submission.id,
		}, function(response){
			
		});
	}
})
tappyn.factory('topFactory', function($http){
	var fact = {}

	fact.grabTops = function(){
		return $http({
			method : 'GET',
			url : 'api/v1/submissions/leaderboard',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		})
	}

	fact.grabWinners = function(){
		return $http({
			method : 'GET',
			url : 'api/v1/submissions/winners',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		})	
	}

	fact.upvote = function(contest, id){
		return $http({
			method : 'POST',
			url : 'api/v1/submissions/'+id+'/vote',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	return fact;
})