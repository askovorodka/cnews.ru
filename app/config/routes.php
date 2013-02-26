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
| 	example.com/class/method/id/
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
| There are two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['scaffolding_trigger'] = 'scaffolding';
|
| This route lets you set a "secret" word that will trigger the
| scaffolding feature for added security. Note: Scaffolding must be
| enabled in the controller in which you intend to use it.   The reserved 
| routes must come before any wildcard or regular expression routes.
|
*/

//$route['admin'] = "admin";
$route['default_controller'] = "frontpage";
$route['scaffolding_trigger'] = "";

//$route['admin/reviews/([0-9]+)'] = "admin/reviews/index/$1";

$route['admin'] = "admin/frontpage";

$route['admin/reviews_articles/preview/(.*)/([0-9]+)'] = "admin/reviews_articles/preview/$2";
$route['admin/reviews_articles/([0-9]+)'] = "admin/reviews_articles/index/$1";
$route['admin/reviews_interviews/preview/(.*)/([0-9]+)'] = "admin/reviews_interviews/preview/$2";
$route['admin/reviews_interviews/([0-9]+)'] = "admin/reviews_interviews/index/$1";
$route['admin/reviews_cases/preview/(.*)/([0-9]+)'] = "admin/reviews_cases/preview/$2";
$route['admin/reviews_cases/([0-9]+)'] = "admin/reviews_cases/index/$1";
$route['admin/reviews_tables/([0-9]+)'] = "admin/reviews_tables/index/$1";

//$route['admin/tables/(:any)'] = "admin/tables/index/$1";

$route['admin/(:any)'] = "admin/$1";



/* End of file routes.php */
/* Location: ./system/application/config/routes.php */