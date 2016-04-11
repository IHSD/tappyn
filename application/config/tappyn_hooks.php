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

$config['hooks']['test'] = array();
$config['hooks']['test'][] = [
    'event' => 'hook_initialize',
    'name' => 'test_it',
    'class' => 'test_library',
    'method' => 'greet',
    'args' => array('testarino')
];
$config['hooks']['auth'] = array();
$config['hooks']['auth'][] = [
    'event' => 'user_registration',
    'name' => 'send_user_verification_email',
    'class' => 'mailer',
    'method' => 'queue',
    'args' => array()
];
