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

$route['default_controller'] = "Admin";

$route['nodes/mange_pages'] = 'node/list_nodes/1';
$route['nodes/mange_pages/(:num)'] = 'node/list_nodes/1/$1';
$route['nodes/liked_pages'] = 'node/list_nodes/2';
$route['nodes/liked_pages/(:num)'] = 'node/list_nodes/2/$1';
$route['nodes/my_groups'] = 'node/list_nodes/3';
$route['nodes/my_groups/(:num)'] = 'node/list_nodes/3/$1';
$route['nodes/joined_groups'] = 'node/list_nodes/4';
$route['nodes/joined_groups/(:num)'] = 'node/list_nodes/4/$1';
$route['nodes/events'] = 'node/list_nodes/5';
$route['nodes/events/(:num)'] = 'node/list_nodes/5/$1';
$route['nodes/profiles'] = 'node/list_nodes/6';
$route['nodes/profiles/(:num)'] = 'node/list_nodes/6/$1';
$route['node/(:num)/(:num)'] = 'node/index/$1/$2';
$route['node/(:num)'] = 'node/index/$1';
$route['campaign/(:num)'] = 'post/campaign/$1';
$route['campaign/(:num)/(:num)'] = 'post/campaign/$1/$2';

/* End of file routes.php */
/* Location: ./application/config/routes.php */