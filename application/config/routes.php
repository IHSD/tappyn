<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome/index';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;



/**
 * Authentication
 */
$route['signup'] = 'auth/create_user';
$route['login'] = 'auth/login';
$route['logout'] = 'auth/logout';
$route['forgot_password'] = 'auth/forgot_password';
/**
 * Base Routes
 */
$route['profile'] = 'users/profile';
$route['how_it_works'] = 'welcome/how_it_works';
$route['contact'] = 'welcome/contact_us';
$route['tos'] = 'welcome/tos';
$route['privacy_policy'] = 'welcome/privacy_policy';
$route['faq'] = 'welcome/faq';
$route['mailing_list']['post'] = 'welcome/mailing_list';
$route['unsubscribe']['get'] = 'welcome/unsubscribe';
$route['dashboard'] = 'users/dashboard';
$route['in_progress'] = 'users/in_progress';
$route['completed'] = 'users/completed';
$route['uploads/(:any)'] = 'uploads/index/$1';

/**
 * Contests
 */
$route['contests/(:num)'] = 'contests/show/$1';
$route['submissions/(:num)'] = 'submissions/index/$1';
$route['contests/(:num)/submissions'] = 'contests/submissions/$1';

/**
 * Submissions
 */
$route['contests/(:num)/submissions'] = 'submissions/index/$1';


/**
 * Admin
 */
$route['admin']['get'] = 'admin/home/index';
