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
	.when('/winners', {
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