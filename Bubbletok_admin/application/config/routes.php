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
|	https://codeigniter.com/user_guide/general/routing.html
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
$route['default_controller'] = 'welcome';

$route['admin'] = 'admin/Login';
$route['admin/dashboard'] = 'admin/Dashboard';
$route['admin/logout'] = 'admin/Login/jadminlogout';
$route['admin/user_list'] = 'admin/User/user_list';
$route['admin/edit_user/(:any)'] = 'admin/User/edit_user';
$route['admin/view_user/(:any)'] = 'admin/User/view_user';
$route['admin/admin_profile'] = 'admin/Login/admin_profile';


$route['admin/transaction_history'] = 'admin/Transaction/transaction_history_live';
$route['admin/contact_list'] = 'admin/User/contact_list';
$route['admin/notification_list'] = 'admin/User/notification_list';
$route['admin/add_referral_level'] = 'admin/Refferal/add_referral_level';
$route['admin/edit_referral_level/(:any)'] = 'admin/Refferal/edit_referral_level';
$route['admin/referral_level_manage'] = 'admin/Refferal/referral_level_manage';
$route['admin/security'] = 'admin/User/security';
$route['admin/two_fa_verify'] = 'admin/Login/two_fa_verify';
$route['admin/core_wallet'] = 'admin/Core_Wallet/core_wallet';
$route['invite'] = 'Invite/invite_link';
$route['admin/ticket_list'] = 'admin/Support/ticket_list';
$route['admin/view_ticket/(:any)'] = 'admin/Support/view_ticket';
$route['admin/mining_fees'] = 'admin/Refferal/mining_fees';
$route['admin/app_fees'] = 'admin/Core_Wallet/app_fees';
$route['admin/add_blogs'] = 'admin/Blogs/add_blogs';
$route['admin/blogs_list'] = 'admin/Blogs/blogs_list';
$route['admin/edit_blogs/(:any)'] = 'admin/Blogs/edit_blogs';


$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;