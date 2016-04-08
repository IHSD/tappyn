<?php defined("BASEPATH") or exit('No direct script access allowed');

$config['hooks'] = array();


$config['hooks']['test'] = array();
$config['hooks']['test'][] = [
    'event' => 'hook_initialize',
    'name' => 'test_it',
    'class' => 'test_library',
    'method' => 'greet',
    'args' => array('testarino')
];
