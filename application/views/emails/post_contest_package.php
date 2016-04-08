<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>


<!-- Start Template -->
<br>
<p style='width:100%;text-align:center'>
    <img align='center' height='75' src="<?php echo base_url().'public/img/TappynLogo2.png'; ?>">
</p>

<h2 style='text-align:left;margin:auto;width:600px;'>Hi <?php echo $cname; ?>,</h2>
<br>

<p style='text-align:left;margin:auto;width:600px'>Thanks for using Tappyn and congrats on picking an amazing ad!</p><br>
<p style='text-align:left;margin:auto;width:600px'>Youre audience is going to love it.</p><br><br>

<p style='text-align:left;margin:auto;width:600px'><strong><u>Details</u></strong></p><br>
<p style='text-align:left;margin:auto;width:600px'><strong>Medium: </strong><?php echo ucfirst($contest->platform); ?></p><br>
<p style='text-align:left;margin:auto;width:600px'><strong>Objecive: </strong><?php echo snake_to_string($contest->objective); ?></p><br>
<p style='text-align:left;margin:auto;width:600px'><strong>Target Audience: </strong><?php echo $contest->min_age; ?> - <?php echo $contest->max_age; ?> year old<?php echo $contest->gender == 0 ? 's' : ($contest->gender == 1 ? ' Males' : ' Females'); ?> who like '<?php echo parse_interest($contest->industry); ?>'</p>
<br><br>
<p style='text-align:left;margin:auto;width:600px'><strong><u>Ad Creative</u></strong></p><br>
<?php if(!is_null($submission->headline) && $submission->headline != ''): ?>
    <p style='text-align:left;margin:auto;width:600px'><u><strong>Headline :</strong></u> <?php echo $submission->headline; ?></p><br>
<?php endif; ?>
<?php if(!is_null($submission->text) && $submission->text != ''): ?>
    <p style='text-align:left;margin:auto;width:600px'><u><strong>Text :</strong></u> <?php echo $submission->text; ?></p><br>
<?php endif; ?>
<?php if(!is_null($submission->description) && $submission->description != ''): ?>
    <p style='text-align:left;margin:auto;width:600px'><u><strong>Description :</strong></u> <?php echo $submission->description; ?></p><br>
<?php endif; ?>
<?php if(!is_null($submission->link_explanation) && $submission->link_explanation != ''): ?>
    <p style='text-align:left;margin:auto;width:600px'><u><strong>Link Explanation :</strong></u> <?php echo $submission->link_explanation; ?></p><br>
<?php endif; ?>
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
