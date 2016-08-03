tappyn.service("contestModel", function() {
    this.sift_images = function(contest, emotions) {
        for (var i = 0; i < emotions.length; i++) {
            if (contest.emotion == emotions[i].type) {
                return { icon: emotions[i].icon, adj: emotions[i].adjectives, example: '' };
            }
        }
    }
    this.parallel_submission = function(contest) {
        var layout = {};
        if (contest.platform == "facebook") {
            layout.text = { limit: 90, placeholder: 'A clear, simple description of why you would personally pick this product.' };
            //if (contest.objective == 'conversions' || contest.objective == "clicks_to_website")
            layout.headline = { limit: 35, placeholder: "Something that grabs the readers' attention. Take a risk!" };
        } else if (contest.platform == "twitter") {
            if (contest.display_type == 'with_photo') layout.text = { limit: 116, placeholder: 'A clear, simple description of why you would personally pick this product.' };
            else layout.text = { limit: 140, placeholder: 'A clear, simple description of why you would personally pick this product.' };
            if (contest.objective == "site_clicks_conversions") layout.card_title = { limit: 70, placeholder: 'A clear, simple description of why you would personally pick this product.' }
        } else if (contest.platform == "google") {
            layout.headline = { limit: 25, placeholder: "Something that grabs the readers' attention. Take a risk!" };
            layout.line_1 = { limit: 35, placeholder: 'A clear, simple description of why you would personally pick this product.' };
            layout.line_2 = { limit: 35, placeholder: 'A clear, simple description of why you would personally pick this product.' };
        } else if (contest.platform == "general") layout.headline = { limit: 35, placeholder: "Something that grabs the readers' attention. Take a risk!" };
        else if (contest.platform == "instagram") layout.text = { limit: 90, placeholder: 'A clear, simple description of why you would personally pick this product.' };

        if (contest.use_attachment == 1) layout.photo = false;
        else if (contest.display_type == "with_photo") layout.photo = true;
        return layout;
    }

    this.checkImageSize = function(size, contest) {
        console.log(size);
        if (contest.platform == "facebook") {
            if (size.width < 1200 || size.height < 630) return false;
            else return true;
        } else if (contest.platform == "instagram") {
            if (size.width < 600 || size.height < 315) return false;
            else return true;
        } else if (contest.platform == 'twitter') {
            if (contest.objective == "site_clicks_conversions") {
                if (size.width < 800 || size.height < 320) return false;
                else return true;
            } else {
                if (size.width < 600 || size.height < 315) return false;
                else return true;
            }
        } else return true;
    }

    this.fire_google = function(contest) {
        if (contest.gender == "0") {
            switch (contest.min_age) {
                case "18":
                    ga('set', 'contentGroup11', '<18-24 All Gender Contest>');
                    ga('send', 'pageview');
                    break;
                case "25":
                    ga('set', 'contentGroup12', '<25-34 All Gender Contest>');
                    ga('send', 'pageview');
                    break;
                case "35":
                    ga('set', 'contentGroup13', '<35-44 All Gender Contest>');
                    ga('send', 'pageview');
                    break;
                case "45":
                    ga('set', 'contentGroup14', '<45+ All Gender Contest>');
                    ga('send', 'pageview');
                    break;
            }
        } else if (contest.gender == "1") {
            switch (contest.max_age) {
                case "18":
                    ga('set', 'contentGroup7', '<18-24 Male Contest>');
                    ga('send', 'pageview');
                    break;
                case "25":
                    ga('set', 'contentGroup8', '<25-34 Male Contest>');
                    ga('send', 'pageview');
                    break;
                case "35":
                    ga('set', 'contentGroup9', '<35-44 Male Contest>');
                    ga('send', 'pageview');
                    break;
                case "45":
                    ga('set', 'contentGroup10', '<45+ Male Contest>');
                    ga('send', 'pageview');
                    break;
            }
        } else if (contest.gender == "2") {
            switch (contest.max_age) {
                case "18":
                    ga('set', 'contentGroup3', '<18-24 Female Contest>');
                    ga('send', 'pageview');
                    break;
                case "25":
                    ga('set', 'contentGroup4', '<25-34 Female Contest>');
                    ga('send', 'pageview');
                    break;
                case "35":
                    ga('set', 'contentGroup5', '<35-44 Female Contest>');
                    ga('send', 'pageview');
                    break;
                case "45":
                    ga('set', 'contentGroup6', '<45+ Female Contest>');
                    ga('send', 'pageview');
                    break;
            }
        }
    }
})
