tappyn.service("contestModel", function(){
	this.sift_images = function(contest, emotions){
		for(var i = 0; i < emotions.length; i++){
			if(contest.emotion == emotions[i].type){
				if(contest.platform == "google" && emotions[i].google != '') return {icon : emotions[i].icon, adj : emotions[i].adjectives, example : emotions[i].google};
				else return 'public/img/google_submish.png';

				if(contest.platform == 'facebook' && emotions[i].facebook != '') return {icon : emotions[i].icon, adj : emotions[i].adjectives, example : emotions[i].facebook};
				else return 'public/img/facebook_submish.png';

				if(contest.platform == 'twitter' && emotions[i].twitter != '') return {icon : emotions[i].icon, adj : emotions[i].adjectives, example : emotions[i].twitter};
				else return 'public/img/Twitter_submish.png';

				if(contest.platform == "general" && contest.display_type == "headline" && emotions[i].general.headline != '') return {icon : emotions[i].icon, adj : emotions[i].adjectives, example : emotions[i].general.headline};
				else return null;

				if(contest.platform == "general" && contest.display_type == "tagline" && emotions[i].general.tagline != '') return {icon : emotions[i].icon, adj : emotions[i].adjectives, example : emotions[i].general.tagline};
				else return null;

				if(contest.platform == "general" && contest.display_type == "copies") return null;
			}
		}
	}
})