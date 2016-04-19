<?php defined("BASEPATH") or exit('No direct script access allowed');

/**
* Array of email types that we can send
*/
$config['email_program'] = array();

/**
* Each email data that it needs
*
* array(
* 		'template' => "Path to the template we want to use",
* 		'subject' => "The subject of the message",
* )
*/


/**
* Contest closing
*
* Sent 24 hours before a contest closes
*/
$config['email_program']['contest_closing']['template'] = 'email_templates/contest_closing';
$config['email_program']['contest_closing']['from'] = 'squad@tappyn.com';
$config['email_program']['contest_closing']['subject'] = "%s contest is about to end!";
$config['email_program']['contest_closing']['query_string'] = array(
    'utm_source' => 'newsletter',
    'utm_medium' => 'email',
    'utm_campaign' => '48hr'
);
$config['email_program']['contest_closing']['additional_data'] = array();

/**
* Winner has been announced
*
* Sent when a winner has been selected for a contest
*/
$config['email_program']['winner_announced']['template'] = 'email_templates/winner_announced';
$config['email_program']['winner_announced']['from'] = 'squad@tappyn.com';
$config['email_program']['winner_announced']['subject'] = "%s's has chosen a winner!";
$config['email_program']['winner_announced']['query_string'] = array(
    'utm_source' => 'newsletter',
    'utm_medium' => 'email',
    'utm_campaign' => 'winner'
);
$config['email_program']['winner_announced']['additional_data'] = array();

/**
* Mailing list confirmation
*
* Sent to confirm that someone has subsribed to our mailing list
*/
$config['email_program']['mailing_list_conf']['template'] = 'email_templates/mailing_list_conf';
$config['email_program']['mailing_list_conf']['from'] = 'squad@tappyn.com';
$config['email_program']['mailing_list_conf']['subject'] = "Mailing List successful";
$config['email_program']['mailing_list_conf']['query_string'] = array(
    'utm_source' => 'newsletter',
    'utm_medium' => 'email',
    'utm_campaign' => 'mailinglist'
);
$config['email_program']['mailing_list_conf']['additional_data'] = array();

/**
* Mailing list confirmation
*
* Sent to confirm that someone has subsribed to our mailing list
*/
$config['email_program']['post_contest_package']['template'] = 'email_templates/post_contest_package';
$config['email_program']['post_contest_package']['from'] = 'squad@tappyn.com';
$config['email_program']['post_contest_package']['subject'] = "Heres the submissions you chose";
$config['email_program']['post_contest_package']['query_string'] = array();
$config['email_program']['post_contest_package']['additional_data'] = array();

/**
* Sign Up Confirmation
*
* Confirmation that someones account has been created
*/
$config['email_program']['sign_up_conf']['template'] = 'email_templates/sign_up_conf';
$config['email_program']['sign_up_conf']['from'] = 'squad@tappyn.com';
$config['email_program']['sign_up_conf']['subject'] = "Tappyn Account Confirmation";
$config['email_program']['sign_up_conf']['query_string'] = array(
    'utm_source' => 'newsletter',
    'utm_medium' => 'email',
    'utm_campaign' => 'signup'
);
$config['email_program']['sign_up_conf']['additional_data'] = array();


/**
* Contest has completed
*
* Winner needs to be selected
*/
$config['email_program']['contest_completed']['template'] = 'email_templates/contest_completed';
$config['email_program']['contest_completed']['from'] = 'squad@tappyn.com';
$config['email_program']['contest_completed']['subject'] = "Your contest has finished! Time to tap someone in!";
$config['email_program']['contest_completed']['query_string'] = array(
    'utm_source' => 'newsletter',
    'utm_medium' => 'email',
    'utm_campaign' => 'contest_completed'
);
$config['email_program']['contest_completed']['additional_data'] = array();

/**
* Submission has been chosen
*
* Let a winner know that their submission has won
*/
$config['email_program']['submission_chosen']['template'] = 'email_templates/submission_chosen';
$config['email_program']['submission_chosen']['from'] = 'squad@tappyn.com';
$config['email_program']['submission_chosen']['subject'] = "Your submission won!";
$config['email_program']['submission_chosen']['query_string'] = array(
    'utm_source' => 'transaction',
    'utm_medium' => 'email',
    'utm_campign' => 'submission_chosen'
);
$config['email_program']['submission_chosen']['additional_data'] = array();

/**
* Contest closing
*
* Sent 24 hours before a contest closes
*/
$config['email_program']['company_sign_up_conf']['template'] = 'email_templates/company_sign_up';
$config['email_program']['company_sign_up_conf']['from'] = 'squad@tappyn.com';
$config['email_program']['company_sign_up_conf']['subject'] = "Thank you for registering";
$config['email_program']['company_sign_up_conf']['query_string'] = array(
    'utm_source' => 'transaction',
    'utm_medium' => 'email',
    'utm_campaign' => 'company_sign_up'
);
$config['email_program']['company_sign_up_conf']['additional_data'] = array();

/**
* Contest closing
*
* Sent 24 hours before a contest closes
*/
$config['email_program']['contest_receipt']['template'] = 'email_templates/contest_receipt';
$config['email_program']['contest_receipt']['from'] = 'squad@tappyn.com';
$config['email_program']['contest_receipt']['subject'] = "Thank you for registering";
$config['email_program']['contest_receipt']['query_string'] = array(
    'utm_source' => 'transaction',
    'utm_medium' => 'email',
    'utm_campaign' => 'contest_receipt'
);
$config['email_program']['contest_receipt']['additional_data'] = array();

/**
* Contest closing
*
* Sent 24 hours before a contest closes
*/
$config['email_program']['payout_receipt']['template'] = 'email_templates/payout_receipt';
$config['email_program']['payout_receipt']['from'] = 'squad@tappyn.com';
$config['email_program']['payout_receipt']['subject'] = "Thank you for registering";
$config['email_program']['payout_receipt']['query_string'] = array(
    'utm_source' => 'transaction',
    'utm_medium' => 'email',
    'utm_campaign' => 'payout_receipt'
);
$config['email_program']['payout_receipt']['additional_data'] = array();

/**
* Contest closing
*
* Sent 24 hours before a contest closes
*/
$config['email_program']['contact_conf']['template'] = 'email_templates/payout_receipt';
$config['email_program']['contact_conf']['from'] = 'squad@tappyn.com';
$config['email_program']['contact_conf']['subject'] = "Thank you for registering";
$config['email_program']['contact_conf']['query_string'] = array(
    'utm_source' => 'transaction',
    'utm_medium' => 'email',
    'utm_campaign' => 'payment_receipt'
);
$config['email_program']['contact_conf']['additional_data'] = array();
