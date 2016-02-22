var tappyn = angular.module('tappyn', [
	'ngRoute',
	'ui.bootstrap',
	'ngAnimate'
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
	.when('/contests', {
		templateUrl : 'components/contests/view.html',
		controller : 'contestsController'
	})
	.when('/contest/:id', {
		templateUrl : 'components/contest/view.html',
		controller : 'contestController'
	})
	.when('/submissions/:id', {
		templateUrl : 'components/submissions/view.html',
		controller : 'submissionsController'
	})
	.when('/payment', {
		templateUrl : 'components/payment/view.html',
		controller : 'paymentController'
	})
	.when('/contact_us', {
		templateUrl : 'components/contact_us/view.html'
	})
	.when('/login', {
		templateUrl : 'components/login/view.html'
	})
	.when('/register', {
		templateUrl : 'components/register/view.html'
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
	.otherwise({redirectTo : '/home'})

});

tappyn.filter('untilFilter', function() {
	return function(date){
		date = moment(date).fromNow();
		return date;
	};
});

tappyn.filter('legibleDate', function() {
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

tappyn.controller("ApplicationController", function($scope, $location, $timeout, AppFact){
	

	AppFact.isLoggedIn().success(function(response){
		if(response.http_status_code == 200){
			if(sessionStorage.getItem("user")) $scope.user = JSON.parse(sessionStorage.getItem("user"));
			else{
				$scope.user = response.data;
				sessionStorage.setItem("user", JSON.stringify(response.data));
			}
		}
	})

	$scope.check_code = function(code){
		if(code == 401){
			$scope.set_alert("You must be logged in", "default");
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

	/** example response
			if(response.http_status_code == 200){
				if(response.success){
					
				}
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
	
	**/

	$scope.log_in = function(email, pass){
		AppFact.loggingIn(email, pass).success(function(response){
			if(response.http_status_code == 200){
				if(response.success){
					$scope.user = response.data;
					sessionStorage.setItem("user", JSON.stringify(response.data));
					$location.path('/dashboard');
				}
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		})
	}
	$scope.log_out = function(){
		AppFact.loggingOut().success(function(response){
			$scope.user = null;
			sessionStorage.removeItem('user');
			$location.path("/login");
		});
	}

	$scope.sign_up = function(registrant){
		AppFact.signUp(registrant).success(function(response){
			if(response.http_status_code == 200){
				if(response.success){
					$scope.user = response.data;
					sessionStorage.setItem("user", JSON.stringify(response.data));
					$location.path('/dashboard');
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
	return fact;
})
tappyn.controller('contestController', function($scope, $routeParams, $location, contestFactory){
	contestFactory.grabContest($routeParams.id).success(function(response){
		$scope.contest = response.data.contest;
	});

	$scope.submit_to = function(id, submission){
		if($scope.user){
			contestFactory.submitTo(id, submission).success(function(response){
				if(response.success) $location.path("/submissions/"+id);
			})
		}
		else{
			submission.as_guest = true;
			$scope.sign_up(submission).success(function(response){
				if(response.success){
					$scope.user = response.data;
					sessionStorage.setItem("user", JSON.stringify(response.data));
					contestFactory.submitTo(id, submission).success(function(response){
						if(response.success) $location.path("/submissions/"+id);
					})
				}
			})
		}
	}
});
tappyn.factory('contestFactory', function($http){
	var fact = {};

	fact.grabContest = function(id){
		return $http({
			method : 'GET',
			url : 'index.php/contests/'+id,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	fact.submitTo = function(id, submission){
		return $http({
			method : 'POST',
			url : 'index.php/submissions/create/'+id,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			'data' : $.param(submission)
		});	
	}

	return fact;
})
tappyn.controller('contestsController', function($scope, contestsFactory){
	contestsFactory.grabContests().success(function(response){
		$scope.contests = response.data;
	});
})
tappyn.factory('contestsFactory', function($http){
	var fact = {};

	fact.grabContests = function(){
		return $http({
			method : 'GET',
			url : 'index.php/contests',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	return fact;
})
tappyn.controller('dashController', function($scope, dashFactory){
	//on page load grab all
	$scope.type = 'all';
	dashFactory.grabDash($scope.type).success(function(response){
		if(response.http_status_code == 200){
			if(response.success) $scope.dash = response.data;
			else alert(response.message);	 
		}
		else if(response.http_status_code == 500) alert(response.error);
		else $scope.check_code(response.http_status_code);
	});

	$scope.grab_dash = function(type){
		$scope.type = type;

		dashFactory.grabDash(type).success(function(response){
			if(response.success) $scope.dash = response.data;
		});
	}
})
tappyn.factory('dashFactory', function($http){
	var fact = {};

	fact.grabDash = function(type){
		return $http({
			method : 'GET',
			url : 'index.php/dashboard?type='+type,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}
	return fact;
})
tappyn.controller('homeController', function($scope, $location, homeFactory){
	

	$scope.mailing_list = function(email){
		homeFactory.mailingList(email).success(function(response){
			if(response.http_status_code == 200){
				if(response.success) window.location.reload();
				else alert(response.message);	 
			}
			else if(response.http_status_code == 500) alert(response.error);
			else $scope.check_code(response.http_status_code);
		})
	}
})
tappyn.factory('homeFactory', function($http){
	var fact = {};

	fact.mailingList = function(email){
		return $http({
			method : 'GET',
			url : 'index.php/mailing_list',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			'data' : $.param({"email" : email})
		});
	}

	return fact;
});
tappyn.controller('launchController', function($scope, $location, launchFactory){
	$scope.countries = launchModel.countries;
	$scope.steps = {
		'platform'		 : {step : 'platform', next : 'objective', previous : 'none', fill : 25},
		'objective'      : {step : 'objective', next : 'detail', previous : 'platform', fill : 50},
		'detail' 		 : {step : 'detail', next : 'payment', previous : 'objective', fill : 75},
		'payment'		 : {step : 'payment', next : 'none', previous : 'detail', fill : 100}
	}
	$scope.current = $scope.steps['platform'];

	$scope.contest = {};

	$scope.set_step = function(step){
		$scope.current = $scope.steps[step];
	}

	$scope.select_objective = function(objective){
		$scope.contest.objective = objective;
	}

	$scope.select_platform = function(platform){
		$scope.contest.platform = platform;
	}

	$scope.select_display = function(display){
		$scope.contest.display = display;
	}

	$scope.submit = function(contest){
		launchFactory.submission(contest).success(function(response){
			if(response.http_status_code == 200){
				if(response.success){
					alert("Made it");
				}
				else alert(response.message);	 
			}
			else if(response.http_status_code == 500) alert(response.error);
			else $scope.check_code(response.http_status_code);
		});	
	}
});
tappyn.factory('launchFactory', function($http){
	var fact = {}

	fact.submission = function(contest){
		return $http({
			method : 'POST',
			url : 'index.php/contest/create',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			data : $.param(contest)
		});	
	}
	return fact;
})
tappyn.controller('profileController', function($scope){
	
});
tappyn.factory('profileFactory', function($http){
	var fact = {};

	fact.grabProfile = function(){
		
	}

	return fact;
})
tappyn.controller("paymentController", function($scope, $location, paymentFactory, paymentModel){
	$scope.countries = paymentModel.countries;
	$scope.showing = "methods";
	paymentFactory.grabDetails().success(function(response){
		if(response.http_status_code == 200){
			if(response.success){
				if($scope.user.type == "member" && response.data.account == false){
					$scope.detail = {first_name : $scope.user.first_name, last_name : $scope.user.last_name};
					$scope.showing = 'details';
				}
				else $scope.showing = 'methods';
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
});
tappyn.factory("paymentFactory", function($http){
	var fact = {};

	fact.grabDetails = function(){
		return $http({
			method : 'GET',
			url : 'index.php/accounts/details',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		})	
	}

	fact.verifyIdentity = function(details){
		return $http({
			method : 'POST',
			url : 'index.php/accounts/details',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			data : $.param(details)
		})	
	}

	fact.addPayment = function(token){
		return $http({
			method : 'POST',
			url : 'index.php/accounts/payment_methods',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			data : $.param({stripeToken : token})
		})	
	}

	return fact;	
})
tappyn.service('paymentModel', function(){
	this.countries = {
    'AF' : 'Afghanistan',
    'AX' : 'Aland Islands',
    'AL' : 'Albania',
    'DZ' : 'Algeria',
    'AS' : 'American Samoa',
    'AD' : 'Andorra',
    'AO' : 'Angola',
    'AI' : 'Anguilla',
    'AQ' : 'Antarctica',
    'AG' : 'Antigua And Barbuda',
    'AR' : 'Argentina',
    'AM' : 'Armenia',
    'AW' : 'Aruba',
    'AU' : 'Australia',
    'AT' : 'Austria',
    'AZ' : 'Azerbaijan',
    'BS' : 'Bahamas',
    'BH' : 'Bahrain',
    'BD' : 'Bangladesh',
    'BB' : 'Barbados',
    'BY' : 'Belarus',
    'BE' : 'Belgium',
    'BZ' : 'Belize',
    'BJ' : 'Benin',
    'BM' : 'Bermuda',
    'BT' : 'Bhutan',
    'BO' : 'Bolivia',
    'BA' : 'Bosnia And Herzegovina',
    'BW' : 'Botswana',
    'BV' : 'Bouvet Island',
    'BR' : 'Brazil',
    'IO' : 'British Indian Ocean Territory',
    'BN' : 'Brunei Darussalam',
    'BG' : 'Bulgaria',
    'BF' : 'Burkina Faso',
    'BI' : 'Burundi',
    'KH' : 'Cambodia',
    'CM' : 'Cameroon',
    'CA' : 'Canada',
    'CV' : 'Cape Verde',
    'KY' : 'Cayman Islands',
    'CF' : 'Central African Republic',
    'TD' : 'Chad',
    'CL' : 'Chile',
    'CN' : 'China',
    'CX' : 'Christmas Island',
    'CC' : 'Cocos (Keeling) Islands',
    'CO' : 'Colombia',
    'KM' : 'Comoros',
    'CG' : 'Congo',
    'CD' : 'Congo, Democratic Republic',
    'CK' : 'Cook Islands',
    'CR' : 'Costa Rica',
    'CI' : 'Cote D\'Ivoire',
    'HR' : 'Croatia',
    'CU' : 'Cuba',
    'CY' : 'Cyprus',
    'CZ' : 'Czech Republic',
    'DK' : 'Denmark',
    'DJ' : 'Djibouti',
    'DM' : 'Dominica',
    'DO' : 'Dominican Republic',
    'EC' : 'Ecuador',
    'EG' : 'Egypt',
    'SV' : 'El Salvador',
    'GQ' : 'Equatorial Guinea',
    'ER' : 'Eritrea',
    'EE' : 'Estonia',
    'ET' : 'Ethiopia',
    'FK' : 'Falkland Islands (Malvinas)',
    'FO' : 'Faroe Islands',
    'FJ' : 'Fiji',
    'FI' : 'Finland',
    'FR' : 'France',
    'GF' : 'French Guiana',
    'PF' : 'French Polynesia',
    'TF' : 'French Southern Territories',
    'GA' : 'Gabon',
    'GM' : 'Gambia',
    'GE' : 'Georgia',
    'DE' : 'Germany',
    'GH' : 'Ghana',
    'GI' : 'Gibraltar',
    'GR' : 'Greece',
    'GL' : 'Greenland',
    'GD' : 'Grenada',
    'GP' : 'Guadeloupe',
    'GU' : 'Guam',
    'GT' : 'Guatemala',
    'GG' : 'Guernsey',
    'GN' : 'Guinea',
    'GW' : 'Guinea-Bissau',
    'GY' : 'Guyana',
    'HT' : 'Haiti',
    'HM' : 'Heard Island & Mcdonald Islands',
    'VA' : 'Holy See (Vatican City State)',
    'HN' : 'Honduras',
    'HK' : 'Hong Kong',
    'HU' : 'Hungary',
    'IS' : 'Iceland',
    'IN' : 'India',
    'ID' : 'Indonesia',
    'IR' : 'Iran, Islamic Republic Of',
    'IQ' : 'Iraq',
    'IE' : 'Ireland',
    'IM' : 'Isle Of Man',
    'IL' : 'Israel',
    'IT' : 'Italy',
    'JM' : 'Jamaica',
    'JP' : 'Japan',
    'JE' : 'Jersey',
    'JO' : 'Jordan',
    'KZ' : 'Kazakhstan',
    'KE' : 'Kenya',
    'KI' : 'Kiribati',
    'KR' : 'Korea',
    'KW' : 'Kuwait',
    'KG' : 'Kyrgyzstan',
    'LA' : 'Lao People\'s Democratic Republic',
    'LV' : 'Latvia',
    'LB' : 'Lebanon',
    'LS' : 'Lesotho',
    'LR' : 'Liberia',
    'LY' : 'Libyan Arab Jamahiriya',
    'LI' : 'Liechtenstein',
    'LT' : 'Lithuania',
    'LU' : 'Luxembourg',
    'MO' : 'Macao',
    'MK' : 'Macedonia',
    'MG' : 'Madagascar',
    'MW' : 'Malawi',
    'MY' : 'Malaysia',
    'MV' : 'Maldives',
    'ML' : 'Mali',
    'MT' : 'Malta',
    'MH' : 'Marshall Islands',
    'MQ' : 'Martinique',
    'MR' : 'Mauritania',
    'MU' : 'Mauritius',
    'YT' : 'Mayotte',
    'MX' : 'Mexico',
    'FM' : 'Micronesia, Federated States Of',
    'MD' : 'Moldova',
    'MC' : 'Monaco',
    'MN' : 'Mongolia',
    'ME' : 'Montenegro',
    'MS' : 'Montserrat',
    'MA' : 'Morocco',
    'MZ' : 'Mozambique',
    'MM' : 'Myanmar',
    'NA' : 'Namibia',
    'NR' : 'Nauru',
    'NP' : 'Nepal',
    'NL' : 'Netherlands',
    'AN' : 'Netherlands Antilles',
    'NC' : 'New Caledonia',
    'NZ' : 'New Zealand',
    'NI' : 'Nicaragua',
    'NE' : 'Niger',
    'NG' : 'Nigeria',
    'NU' : 'Niue',
    'NF' : 'Norfolk Island',
    'MP' : 'Northern Mariana Islands',
    'NO' : 'Norway',
    'OM' : 'Oman',
    'PK' : 'Pakistan',
    'PW' : 'Palau',
    'PS' : 'Palestinian Territory, Occupied',
    'PA' : 'Panama',
    'PG' : 'Papua New Guinea',
    'PY' : 'Paraguay',
    'PE' : 'Peru',
    'PH' : 'Philippines',
    'PN' : 'Pitcairn',
    'PL' : 'Poland',
    'PT' : 'Portugal',
    'PR' : 'Puerto Rico',
    'QA' : 'Qatar',
    'RE' : 'Reunion',
    'RO' : 'Romania',
    'RU' : 'Russian Federation',
    'RW' : 'Rwanda',
    'BL' : 'Saint Barthelemy',
    'SH' : 'Saint Helena',
    'KN' : 'Saint Kitts And Nevis',
    'LC' : 'Saint Lucia',
    'MF' : 'Saint Martin',
    'PM' : 'Saint Pierre And Miquelon',
    'VC' : 'Saint Vincent And Grenadines',
    'WS' : 'Samoa',
    'SM' : 'San Marino',
    'ST' : 'Sao Tome And Principe',
    'SA' : 'Saudi Arabia',
    'SN' : 'Senegal',
    'RS' : 'Serbia',
    'SC' : 'Seychelles',
    'SL' : 'Sierra Leone',
    'SG' : 'Singapore',
    'SK' : 'Slovakia',
    'SI' : 'Slovenia',
    'SB' : 'Solomon Islands',
    'SO' : 'Somalia',
    'ZA' : 'South Africa',
    'GS' : 'South Georgia And Sandwich Isl.',
    'ES' : 'Spain',
    'LK' : 'Sri Lanka',
    'SD' : 'Sudan',
    'SR' : 'Suriname',
    'SJ' : 'Svalbard And Jan Mayen',
    'SZ' : 'Swaziland',
    'SE' : 'Sweden',
    'CH' : 'Switzerland',
    'SY' : 'Syrian Arab Republic',
    'TW' : 'Taiwan',
    'TJ' : 'Tajikistan',
    'TZ' : 'Tanzania',
    'TH' : 'Thailand',
    'TL' : 'Timor-Leste',
    'TG' : 'Togo',
    'TK' : 'Tokelau',
    'TO' : 'Tonga',
    'TT' : 'Trinidad And Tobago',
    'TN' : 'Tunisia',
    'TR' : 'Turkey',
    'TM' : 'Turkmenistan',
    'TC' : 'Turks And Caicos Islands',
    'TV' : 'Tuvalu',
    'UG' : 'Uganda',
    'UA' : 'Ukraine',
    'AE' : 'United Arab Emirates',
    'GB' : 'United Kingdom',
    'US' : 'United States',
    'UM' : 'United States Outlying Islands',
    'UY' : 'Uruguay',
    'UZ' : 'Uzbekistan',
    'VU' : 'Vanuatu',
    'VE' : 'Venezuela',
    'VN' : 'Viet Nam',
    'VG' : 'Virgin Islands, British',
    'VI' : 'Virgin Islands, U.S.',
    'WF' : 'Wallis And Futuna',
    'EH' : 'Western Sahara',
    'YE' : 'Yemen',
    'ZM' : 'Zambia',
    'ZW' : 'Zimbabwe'
	};
})
tappyn.controller("submissionsController", function($scope, $routeParams, contestFactory, submissionsFactory){
	submissionsFactory.grabSubmissions($routeParams.id).success(function(response){
		$scope.contest = response.data.contest;
		$scope.submissions = response.data.submissions;
	});

});
tappyn.factory("submissionsFactory", function($http){
	var fact = {};

	fact.grabSubmissions = function(id){
		return $http({
			method : 'GET',
			url : 'index.php/submissions/'+id,
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			}
		});
	}

	return fact; 
})