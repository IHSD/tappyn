tappyn.service('launchModel', function(){
	this.ages = [18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65];

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