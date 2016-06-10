tappyn.factory('tappyn_var', function() {
    var items = {};
    var itemsService = {};

    items.industries = {
        'business': 'Business',
        'entertainment': 'Entertainment',
        'family_relationships': 'Family & Relationships',
        'fitness_wellness': 'Fitness and Wellness',
        'food_drink': 'Food and Drink',
        'hobbies': 'Hobbies',
        'shopping_fashion': 'Shopping and Fashion',
        'sports_outdoors': 'Sports & Outdoors',
        'technology': 'Technology',
        'pets': 'Pets',
        'travel': 'Travel',
        'education': 'Education',
    };
    /*items.interests = [
        { id: '10', text: 'Fashion & Beauty', picture: 'public/img/fashion_interest.png', checked: false },
        { id: '2', text: 'Food & Drink', picture: 'public/img/food_interest.png', checked: false },
        { id: '4', text: 'Health & Fitness', picture: 'public/img/health_interest.png', checked: false },
        { id: '6', text: 'Social & Gaming', picture: 'public/img/social_interest.png', checked: false },
        { id: '3', text: 'Business & Finance', picture: 'public/img/business_interest.png', checked: false },
        { id: '7', text: 'Home & Garden', picture: 'public/img/home_interest.png', checked: false },
        { id: '5', text: 'Travel', picture: 'public/img/travel_interest.png', checked: false },
        { id: '9', text: 'Art & Music', picture: 'public/img/art_interest.png', checked: false },
        { id: '12', text: 'Pets', picture: 'public/img/pets_interest.png', checked: false },
        { id: '13', text: 'Sports & Outdoors', picture: 'public/img/sport_interest.png', checked: false },
        { id: '8', text: 'Education', picture: 'public/img/education_interest.png', checked: false },
        { id: '11', text: 'Tech & Science', picture: 'public/img/tech_interest.png', checked: false }
    ];*/

    items.location_boxes = [
        { id: '1', text: 'Everyone in this location' },
        { id: '2', text: 'People who live in this location' },
        { id: '3', text: 'People recently in this location' },
        { id: '4', text: 'People traveling in this location' },
    ];

    items.additional_info_boxes = {
        '1': 'Photos of people who have downloaded your app and are using it in different situations.',
        '2': 'Photos of situations where people need your product.',
        '3': ' Photos of situations after people have used your product.',
    };

    items.locations = {
        "AL": "Alabama",
        "AK": "Alaska",
        "AZ": "Arizona",
        "AR": "Arkansas",
        "CA": "California",
        "CO": "Colorado",
        "CT": "Connecticut",
        "DE": "Delaware",
        "DC": "District Of Columbia",
        "FL": "Florida",
        "GA": "Georgia",
        "HI": "Hawaii",
        "ID": "Idaho",
        "IL": "Illinois",
        "IN": "Indiana",
        "IA": "Iowa",
        "KS": "Kansas",
        "KY": "Kentucky",
        "LA": "Louisiana",
        "ME": "Maine",
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
        "OH": "Ohio",
        "OK": "Oklahoma",
        "OR": "Oregon",
        "PA": "Pennsylvania",
        "RI": "Rhode Island",
        "SC": "South Carolina",
        "SD": "South Dakota",
        "TN": "Tennessee",
        "TX": "Texas",
        "UT": "Utah",
        "VT": "Vermont",
        "VA": "Virginia",
        "WA": "Washington",
        "WV": "West Virginia",
        "WI": "Wisconsin",
        "WY": "Wyoming",
    };

    itemsService.get = function(name) {
        if (items[name]) {
            return items[name];
        }
    };

    itemsService.id_to_obj = function(name, id) {
        if (items[name]) {
            var result = $.grep(items[name], function(e) {
                return e.id == id;
            });
            if (result.length == 1) {
                return result[0];
            } else {
                return {};
            }
        }
    }
    return itemsService;
});
