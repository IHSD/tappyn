<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*========================
Globals
========================*/
$route['default_controller']   = 'errors/no_route';
$route['404_override']         = 'errors/show_404';
$route['translate_uri_dashes'] = false;
$route['amazon/connect']       = 'amazon/connect';
$route['contact']['post']      = 'welcome/contact_us';
$route['mailing_list']['post'] = 'welcome/mailing_list';

/*========================
Admin
========================*/
$route['admin/mailing/execute'] = 'admin/mailing/execute';
/*========================
Authentication
========================*/
$route['login']['post']           = 'auth/login';
$route['logout']['post']          = 'auth/logout';
$route['is_logged_in']['get']     = 'auth/is_logged_in';
$route['facebook']['get']         = 'auth/facebook';
$route['password']['post']        = 'auth/change_password';
$route['forgot_password']['post'] = 'auth/forgot_password';
$route['reset_password/(:any)']   = 'auth/reset_password/$1';
$route['signup']['post']          = 'auth/create_user';

/*========================
Users
========================*/
$route['dashboard']['get']                  = 'users/dashboard';
$route['profile']['get']                    = 'users/profile';
$route['profile']['post']                   = 'users/profile';
$route['stats']['get']                      = 'users/stats';
$route['upvoted']['get']                    = 'users/upvoted';
$route['companies/(:num)/follow']['post']   = 'users/follow/$1';
$route['companies/(:num)/unfollow']['post'] = 'users/unfollow/$1';
$route['interests/(:num)/add']['post']      = 'interests/add/$1';
$route['interests/(:num)/remove']['post']   = 'interests/remove/$1';

/*========================
User Accounts
========================*/
$route['accounts/details']['get']          = 'accounts/details';
$route['accounts/details']['post']         = 'accounts/details';
$route['accounts/payment_methods']['post'] = 'accounts/payment_methods';
$route['accounts/remove_method']['post']   = 'accounts/remove_method';
$route['accounts/default_method']['post']  = 'accounts/default_method';

/*========================
Company Accounts
========================*/
$route['companies']['get']                 = 'companies/index';
$route['companies/dashboard']['get']       = 'companies/dashboard';
$route['companies/(:num)']['get']          = 'companies/show/$1';
$route['companies/show/(:num)']['get']     = 'companies/show/$1';
$route['companies/(:num)/contests']['get'] = 'companies/contests/$1';
$route['companies/contests/(:num)']['get'] = 'companies/contests/$1';
$route['companies/accounts']               = 'companies/accounts';
$route['companies/remove_method']['post']  = 'companies/removeCard';
$route['companies/payment/(:num)']['post'] = 'companies/payment/$1';
$route['companies/default_method']['post'] = 'companies/setAsDefault';

/*========================
Contests
========================*/
$route['contests/start_dates']['get']      = 'contests/start_dates';
$route['contests']['get']                  = 'contests/index';
$route['contests/leaderboard']['get']      = 'contests/leaderboard';
$route['contests/(:num)']['get']           = 'submissions/index/$1';
$route['contests/(:num)/winner']['get']    = 'contests/winner/$1';
$route['contests']['post']                 = 'contests/create';
$route['contests/(:num)']['post']          = 'contests/update/$1';
$route['contests/(:num)/winner']['post']   = 'contests/select_winner/$1';
$route['contests/(:num)/delete']['post']   = 'contests/delete/$1';
$route['contests/(:any)']['get']           = 'contests/index/$1';
$route['contests/(:num)/set_live']['post'] = 'contests/set_live/$1';

/*========================
Submissions
========================*/
$route['contests/(:num)/submissions']['post'] = 'submissions/create/$1';
$route['submissions/leaderboard']['get']      = 'submissions/leaderboard';
$route['submissions/(:num)/shares']['post']   = 'submissions/share/$1';
$route['submissions/(num)/rating']['post']    = 'submissions/rating/$1';
$route['submissions/(:num)/votes']['post']    = 'votes/create/$1';

/*========================
Notifications
========================*/
$route['notifications']['get']           = 'notifications/index';
$route['notifications/unread']['get']    = 'notifications/unread';
$route['notifications/read']['post']     = 'notifications/read';
$route['notifications/read_all']['post'] = 'notifications/read_all';

/*========================
Payouts
========================*/
$route['payouts']['get']        = 'payouts/index';
$route['payouts/(:num)']['get'] = 'payouts/show/$1';
$route['payouts/(:num)/claim']  = 'payouts/claim/$1';

/*========================
Vouchers
========================*/
$route['vouchers']                   = 'vouchers/is_valid';
$route['vouchers/get_price']['post'] = 'vouchers/get_price';
