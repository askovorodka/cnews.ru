<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the "Database Connection"
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the "default" group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

$active_group = "default";
$active_record = TRUE;
/*
$db['default']['hostname'] = "mysql.c2023.ip4.ru:3306";
$db['default']['username'] = "c2023_anime";
$db['default']['password'] = "az108yva";
$db['default']['database'] = "c2023_anime3";
*/
/*$db['default']['hostname'] = "localhost";
$db['default']['username'] = "root";
$db['default']['password'] = "";
$db['default']['database'] = "reviews";*/

$db['default']['hostname'] = "localhost";
$db['default']['username'] = "v2_adm_cnews_ru";
$db['default']['password'] = "SuF=ir5M";
$db['default']['database'] = "v2_adm_cnews_ru_base";

$db['default']['dbdriver'] = "mysql";
$db['default']['dbprefix'] = "";
$db['default']['pconnect'] = FALSE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = "";
$db['default']['char_set'] = "cp1251";
$db['default']['dbcollat'] = "cp1251_general_ci";



/*$active_group = "cnews";

$db['cnews']['hostname'] = "CNN";
$db['cnews']['username'] = "";
$db['cnews']['password'] = "";
$db['cnews']['database'] = "";
$db['cnews']['dbdriver'] = "oci8";
$db['cnews']['dbprefix'] = "";
$db['cnews']['active_r'] = TRUE;
$db['cnews']['pconnect'] = FALSE;
$db['cnews']['db_debug'] = TRUE;
$db['cnews']['cache_on'] = FALSE;
$db['cnews']['cachedir'] = APPPATH."/cache/";
*/


/* End of file database.php */
/* Location: ./system/application/config/database.php */