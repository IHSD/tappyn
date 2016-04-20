tappyn.service("contestModel", function(){
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