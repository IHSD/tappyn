<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>

<?php $obj = parse_objective($contest->platform, $contest->objective); ?>
<!-- Start Template -->
<br>
<p style='width:100%;text-align:center'>
    <img align='center' height='75' src="<?php echo base_url().'public/img/TappynLogo2.png'; ?>">
</p>

<h2 style='text-align:left;margin:auto;width:600px;'>Hi <?php echo $cname; ?>,</h2>
<br>

<p style='text-align:left;margin:auto;width:600px'>Thanks for using Tappyn and congrats on picking an amazing ad!</p><br>
<p style='text-align:left;margin:auto;width:600px'>Your audience is going to love it.</p><br><br>

<p style='text-align:left;margin:auto;width:600px'><strong><u>Details</u></strong></p><br>
<p style='text-align:left;margin:auto;width:600px'><strong>Medium: </strong><?php echo ucfirst($contest->platform); ?></p><br>
<p style='text-align:left;margin:auto;width:600px'><strong>Objective: </strong><?php echo $obj; ?></p><br>
<p style='text-align:left;margin:auto;width:600px'><strong>Target Audience: </strong><?php echo $contest->min_age; ?> - <?php echo $contest->max_age; ?> year old<?php echo $contest->gender == 0 ? 's' : ($contest->gender == 1 ? ' Males' : ' Females'); ?> who like '<?php echo parse_interest($contest->industry); ?>'</p>
<br><br>
<?php $this->load->view('emails/templates/'.$contest->platform.'.php', array('submission' => $submission, 'contest' => $contest)); ?>

<br>
<br>
<!-- Test -->
<!-- Begin footer -->
<p style='margin:auto;width:600px;'>
    We hope to see you again soon!
</p>
<br>
<p style='margin:auto;width:600px;'>
    Tappyn Team
    <br>
    <a href="<?php echo base_url(); ?>">www.tappyn.com</a>
</p>
<!-- End footer -->


<?php

function parse_interest($interest)
{
    switch($interest)
    {
        case 'travel':
            return 'Travel';
            break;
        case 'food_beverage':
            return 'Food and Beverage';
            break;
        case 'finance_business':
            return 'Finance & Business';
            break;
        case 'health_wellness':
            return 'Health & Wellness';
            break;
        case 'social_network':
            return 'Social Network';
            break;
        case 'home_garden':
            return 'Home & Garden';
            break;
        case 'education':
            return 'Education';
            break;
        case 'art_entertainment':
            return 'Art & Entertainment';
            break;
        case 'fashion_beauty':
            return 'Fashion & Beauty';
            break;
        case 'tech_science':
            return 'Tech & Science';
            break;
        case 'pets':
            return 'Pets';
            break;
        case 'sports_outdoors':
            return 'Sports & Outdoors';
            break;
        default: return $interest;
    }
}

function parse_objective($platform,$objective)
{
    if($platform == 'facebook')
    {
        if($objective == 'clicks_to_website') return 'Send People To Your Website';
        if($objective == 'conversions') return 'Increase Conversions On Your Website';
        if($objective == 'engagement') return 'Boost Your Posts';
    }
    else if($platform == 'google')
    {
        if($objective == 'awareness') return 'Build Awareness';
        if($objective == 'consideration') return 'Influence Consideration';
        if($objective == 'drive_action') return 'Drive Action';
        if($objective == 'search_presence') return 'Increase Search Presence';
    }
    else if($platform == 'twitter')
    {
        if($objective == 'site_clicks_conversions') return 'Website Clicks or Conversions';
        if($objective == 'followers') return 'Gain Followers';
        if($objective == 'engagement') return 'Tweet Engagements';
    }
    else if($platform == 'general')
    {
        if($objective == 'brand_positioning') return 'Brand Positioning';
        if($objective == 'drive_action') return 'Drive Action';
        if($objective == 'engagement') return 'Increase Engagement';
    }
    return snake_to_string($objective);
}
