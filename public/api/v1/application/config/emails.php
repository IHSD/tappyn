<?php defined("BASEPATH") or exit('No direct script access allowed');

/**
 * Array of email types that we can send
 */
$config['email_program'] = array();

/**
 * Each email data that it needs
 *
 * array(
 *         'template' => "Path to the template we want to use",
 *         'subject' => "The subject of the message",
 * )
 */

/**
 * Contest closing
 *
 * Sent 24 hours before a contest closes
 */
$config['email_program']['contest_closing']['template']     = 'email_templates/contest_closing';
$config['email_program']['contest_closing']['from']         = 'austin@tappyn.com';
$config['email_program']['contest_closing']['subject']      = "%s campaign is about to end!";
$config['email_program']['contest_closing']['query_string'] = array(
    'utm_source'   => 'newsletter',
    'utm_medium'   => 'email',
    'utm_campaign' => '48hr',
);
$config['email_program']['contest_closing']['additional_data'] = array();

/**
 * Winner has been announced
 *
 * Sent when a winner has been selected for a contest
 */
$config['email_program']['winner_announced']['template']     = 'email_templates/winner_announced';
$config['email_program']['winner_announced']['from']         = 'austin@tappyn.com';
$config['email_program']['winner_announced']['subject']      = "%s has just Tapped ads in their campaign!";
$config['email_program']['winner_announced']['query_string'] = array(
    'utm_source'   => 'newsletter',
    'utm_medium'   => 'email',
    'utm_campaign' => 'winner',
);
$config['email_program']['winner_announced']['additional_data'] = array();

/**
 * Weekly content email
 *
 * Send weekly with new contests and contests about to end (As of now, sent manually)
 *
 */
// $config['email_program']['weekly_content']['template'] = '/';
// $config['email_program']['weekly_content']['subject'] = "%s's contest is about to end!";
// $config['email_program']['weekly_content']['query_string'] = array(
//                                                                 'utm_source' => 'newsletter',
//                                                                 'utm_medium' => 'email',
//                                                                 'utm_campaign' => 'weekly'
//                                                             );
// $config['email_program']['weekly_content']['additional_data'] = array();

/**
 * More people to tip email
 *
 * Currently not beinf used
 */
// $config['email_program']['more_people_needed']['template'] = '/';
// $config['email_program']['more_people_needed']['subject'] = "%s's contest is about to end!";
// $config['email_program']['more_people_needed']['query_string'] = array(
//                                                                 'utm_source' => 'newsletter',
//                                                                 'utm_medium' => 'email',
//                                                                 'utm_campaign' => '48hr'
//                                                             );
// $config['email_program']['more_people_needed']['additional_data'] = array();

/**
 * Mailing list confirmation
 *
 * Sent to confirm that someone has subsribed to our mailing list
 */
$config['email_program']['mailing_list_conf']['template']     = 'email_templates/mailing_list_conf';
$config['email_program']['mailing_list_conf']['from']         = 'austin@tappyn.com';
$config['email_program']['mailing_list_conf']['subject']      = "Mailing List successful";
$config['email_program']['mailing_list_conf']['query_string'] = array(
    'utm_source'   => 'newsletter',
    'utm_medium'   => 'email',
    'utm_campaign' => 'mailinglist',
);
$config['email_program']['mailing_list_conf']['additional_data'] = array();

/**
 * Mailing list confirmation
 *
 * Sent to confirm that someone has subsribed to our mailing list
 */
//$config['email_program']['post_contest_package']['template']        = 'email_templates/post_contest_package';
//$config['email_program']['post_contest_package']['from']            = 'alek@tappyn.com';
//$config['email_program']['post_contest_package']['subject']         = "Here's your Tappyn Ad!";
//$config['email_program']['post_contest_package']['query_string']    = array();
//$config['email_program']['post_contest_package']['additional_data'] = array();

/**
 * Sign Up Confirmation
 *
 * Confirmation that someones account has been created
 */
$config['email_program']['sign_up_conf']['template']     = 'email_templates/sign_up_conf';
$config['email_program']['sign_up_conf']['from']         = 'austin@tappyn.com';
$config['email_program']['sign_up_conf']['subject']      = "Registration successful";
$config['email_program']['sign_up_conf']['query_string'] = array(
    'utm_source'   => 'newsletter',
    'utm_medium'   => 'email',
    'utm_campaign' => 'signup',
);
$config['email_program']['sign_up_conf']['additional_data'] = array();

/**
* Contest has completed
*
* Winner needs to be selected
*/
$config['email_program']['contest_completed']['template'] = 'email_templates/contest_completed';
$config['email_program']['contest_completed']['from'] = 'alek@tappyn.com';
$config['email_program']['contest_completed']['subject'] = "We're just finishing up.";
$config['email_program']['contest_completed']['query_string'] = array(
    'utm_source'   => 'newsletter',
    'utm_medium'   => 'email',
    'utm_campaign' => 'contest_completed',
);
$config['email_program']['contest_completed']['additional_data'] = array();

/**
 * Submission has been chosen
 *
 * Let a winner know that their submission has won
 */
$config['email_program']['submission_chosen']['template']     = 'email_templates/submission_chosen';
$config['email_program']['submission_chosen']['from']         = 'austin@tappyn.com';
$config['email_program']['submission_chosen']['subject']      = "Your ad won on Tappyn!";
$config['email_program']['submission_chosen']['query_string'] = array(
    'utm_source'  => 'transaction',
    'utm_medium'  => 'email',
    'utm_campign' => 'submission_chosen',
);
$config['email_program']['submission_chosen']['additional_data'] = array();

/**
 * Contest closing
 *
 * Sent 24 hours before a contest closes
 */
$config['email_program']['company_sign_up_conf']['template']     = 'email_templates/company_sign_up';
$config['email_program']['company_sign_up_conf']['from']         = 'alek@tappyn.com';
$config['email_program']['company_sign_up_conf']['subject']      = "Woot! You’re ready to get started.";
$config['email_program']['company_sign_up_conf']['query_string'] = array(
    'utm_source'   => 'transaction',
    'utm_medium'   => 'email',
    'utm_campaign' => 'company_sign_up',
);
$config['email_program']['company_sign_up_conf']['additional_data'] = array();

/**
 * Contest closing
 *
 * Sent 24 hours before a contest closes
 */
$config['email_program']['contest_receipt']['template']     = 'email_templates/contest_receipt';
$config['email_program']['contest_receipt']['from']         = 'alek@tappyn.com';
$config['email_program']['contest_receipt']['subject']      = "Receipt for your campaign";
$config['email_program']['contest_receipt']['query_string'] = array(
    'utm_source'   => 'transaction',
    'utm_medium'   => 'email',
    'utm_campaign' => 'contest_receipt',
);
$config['email_program']['contest_receipt']['additional_data'] = array();

/**
 * Contest closing
 *
 * Sent 24 hours before a contest closes
 */
$config['email_program']['payout_receipt']['template']     = 'email_templates/payout_receipt';
$config['email_program']['payout_receipt']['from']         = 'austin@tappyn.com';
$config['email_program']['payout_receipt']['subject']      = "Your payout is on the way!";
$config['email_program']['payout_receipt']['query_string'] = array(
    'utm_source'   => 'transaction',
    'utm_medium'   => 'email',
    'utm_campaign' => 'payout_receipt',
);
$config['email_program']['payout_receipt']['additional_data'] = array();

/**
 * Contest closing
 *
 * Sent 24 hours before a contest closes
 */
$config['email_program']['contact_conf']['template']     = 'email_templates/contest_closing';
$config['email_program']['contact_conf']['from']         = 'squad@tappyn.com';
$config['email_program']['contact_conf']['subject']      = "Theres a contest closing soon";
$config['email_program']['contact_conf']['query_string'] = array(
    'utm_source'   => 'transaction',
    'utm_medium'   => 'email',
    'utm_campaign' => 'payment_receipt',
);
$config['email_program']['contact_conf']['additional_data'] = array();

/**
 * Contest closing
 *
 * Sent 24 hours before a contest closes
 */
$config['email_program']['ab_test']['template']     = 'email_templates/ab_test';
$config['email_program']['ab_test']['from']         = 'squad@tappyn.com';
$config['email_program']['ab_test']['subject']      = "contest#%s paid %s amount for a/b test";
$config['email_program']['ab_test']['query_string'] = array(
    'utm_source'   => 'transaction',
    'utm_medium'   => 'email',
    'utm_campaign' => 'payment_receipt',
);
$config['email_program']['ab_test']['additional_data'] = array();
