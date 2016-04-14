<?php defined("BASEPATH") or exit('No direct script access allowed');

/**
 * Setting hooks for triggered application events
 *
 * $config['hooks'] = array(
 * 		'controller' => array(
 * 			'event' => "Event that should trigger the hook",
 * 			'name'  => "Name of the hook, just for namespacing",
 * 			'class' => "Name of the class to call",
 * 			'method' => "Method to call",
 * 			'args' => "Associative array of arguments to pass"
 * 		)
 * )
 */
$config['hooks'] = array();

/**
 * Authentication hooks
 */

$config['hooks'][] = [
    'event' => 'user_registration',
    'name' => 'send_user_verification_email',
    'class' => 'mailer',
    'method' => 'queueWithArray',
    'args' => array(
        'type' => 'sign_up_conf',
        'object' => 'user'
    )
];
$config['hooks'][] = [
    'event' => 'company_registration',
    'name' => 'send_company_welcome_email',
    'class' => 'mailer',
    'method' => 'queueWithArray',
    'args' => array(
        'type' => 'company_sign_up_conf',
        'object' => 'user',
    )
];

$config['hooks'][] = [
    'event' => 'viewed_contest',
    'name' => 'log_contest_impression',
    'class' => 'impression',
    'method' => 'log',
    'args' => array(
        'created_at' => time(),
        'user_agent' => $_SERVER['HTTP_USER_AGENT'],
        'ip_address' => $_SERVER['REMOTE_ADDR']
    )
];

$config['hooks'][] = [
    'event' => 'contest_created',
    'name' => 'notify_users',
    'class' => 'notification',
    'method' => 'create',
    'args' => array(
        'type' => 'new_contest_launched'
    )
];
