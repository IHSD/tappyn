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

	fact.removeMethod = function(id){
		return $http({
			method : 'POST',
			url : 'index.php/accounts/remove_method',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			data : $.param({source_id : id})
		})	
	}

	fact.setDefault = function(id){
		return $http({
			method : 'POST',
			url : 'index.php/accounts/remove_method',
			headers : {
				'Content-type' : 'application/x-www-form-urlencoded'
			},
			data : $.param({source_id : id})
		})	
	}
	return fact;	
})