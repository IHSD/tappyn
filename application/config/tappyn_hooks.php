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
$config['hooks']['auth'] = array();
$config['hooks']['auth'][] = [
    'event' => 'user_registration',
    'name' => 'send_user_verification_email',
    'class' => 'mailer',
    'method' => 'queueWithArray',
    'args' => array(
        'type' => 'sign_up_conf',
        'object' => 'user'
    )
];
$config['hooks']['auth'][] = [
    'event' => 'company_registration',
    'name' => 'send_company_welcome_email',
    'class' => 'mailer',
    'method' => 'queueWithArray',
    'args' => array(
        'type' => 'company_sign_up_conf',
        'object' => 'user',
    )
];
