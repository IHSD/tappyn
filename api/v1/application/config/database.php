<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$active_group = defined("ENVIRONMENT") && ENVIRONMENT == 'testing' ? 'test' : 'master';
$query_builder = TRUE;

$db['master'] = array(
	'dsn'	=> 'mysql:host=localhost;dbname=tappyn',
	'hostname' => 'localhost',
	'username' => 'root',
	'password' => 'davol350',
	'database' => 'tappyn',
	'dbdriver' => 'pdo',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => TRUE,
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	// 'encrypt' => array(
	// 	'ssl_key' => '/home/rob_wittman/Desktop/client-key.pem',
	// 	'ssl_cert' => '/home/rob_wittman/Desktop/client-cert.pem'
	// ),
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);

// $db['test'] = array(
// 	'dsn'	=> 'mysql:host=localhost;dbname=test_tappyn_db',
// 	'hostname' => 'localhost',
// 	'username' => 'root',
// 	'password' => 'davol350',
// 	'database' => 'tappyn_db',
// 	'dbdriver' => 'pdo',
// 	'dbprefix' => '',
// 	'pconnect' => FALSE,
// 	'db_debug' => FALSE,
// 	'cache_on' => FALSE,
// 	'cachedir' => '',
// 	'char_set' => 'utf8',
// 	'dbcollat' => 'utf8_general_ci',
// 	'swap_pre' => '',
// 	'encrypt' => FALSE,
// 	'compress' => FALSE,
// 	'stricton' => FALSE,
// 	'failover' => array(),
// 	'save_queries' => TRUE
// );
