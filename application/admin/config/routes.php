<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "welcome";
$route['404_override'] = '';

$route['cms/block/(:any)'] = 'cms/block/index/$1';
$route['user/dashboard/(:any)'] = 'user/dashboard/index/$1';
//$route['marketing/(:any)'] = 'marketing/index/$1/$2';

//$route['email_message/edit/(:any)'] = 'email_message/edit/$1';
//$route['email_message/add'] = 'email_message/add';
//$route['email_message/(:any)'] = 'email_message/index/$1';

//$route['franchisee/franchisee/(:any)'] = 'franchisee/franchisee/index/$1';
//$route['catalog/product/review/(:any)'] = 'catalog/product/review/$1';
//$route['catalog/product/review_posted/(:any)'] = 'catalog/product/review_posted/$1';
//$route['catalog/product/(:any)'] = 'catalog/product/index/$1';

/* End of file routes.php */
/* Location: ./application/config/routes.php */