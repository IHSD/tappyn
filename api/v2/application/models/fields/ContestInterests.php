<?php defined("BASEPATH") or exit('No direct script access allowed');

class ContestInterests
{
    const FOOD_BEVERAGE     = 'food_beverage';
    const FINANCE_BUSINESS  = 'finance_business';
    const HEALTH_WELLNESS   = 'health_wellness';
    const TRAVEL            = 'travel';
    const SOCIAL_NETWORK    = 'social_network';
    const HOME_GARDEN       = 'home_garden';
    const EDUCATION         = 'education';
    const ART_ENTERTAINMENT = 'art_entertainment';
    const FASHION_BEAUTY    = 'fashion_beauty';
    const TECH_SCIENCE      = 'tech_science';
    const PETS              = 'pets';
    const SPORTS_OUTDOORS   = 'sports_outdoors';

    static function all()
    {
        return array(
            FOOD_BEVERAGE
        );
    }
}
