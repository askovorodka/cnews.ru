<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['site_name'] = "test.v2.adm.cnews.ru";

$config['site_address'] = "http://test.v2.adm.cnews.ru";

$config['admin_email'] = "";

$config['norequest'] = "";

//CSS админка
$config['css_admin'] = array(
		CSS_ROOT . 'bootstrap.min.css',
		CSS_ROOT . 'bootstrap-responsive.min.css',
		CSS_ROOT . 'admin.css',
		CSS_ROOT . 'jquery-ui-1.9.2.custom.min.css',
		CSS_ROOT . 'bootstrap-wysihtml5.css',
		CSS_ROOT . 'jquery-ui-timepicker-addon.css',
		CSS_ROOT . 'tablednd.css',
		);

//JS админка
$config['js_admin'] = array(
		JS_ROOT . 'jquery-1.8.3.min.js',
		JS_ROOT . 'bootstrap.min.js',
		JS_ROOT . 'jquery.validate.min.js',
		JS_ROOT . 'custom.js',
		JS_ROOT . 'tools.js',
		JS_ROOT . 'custom_validate.js',
		JS_ROOT . 'bootbox.min.js',
		JS_ROOT . 'jquery-ui-1.9.2.custom.min.js',
		JS_ROOT . 'wysihtml5-0.3.0.min.js',
		JS_ROOT . 'select_image.js',
		JS_ROOT . 'bootstrap-wysihtml5.js',
		JS_ROOT . 'jquery-ui-timepicker-addon.js',
		JS_ROOT . 'jquery.tablednd.js'
		);

//типы обзоров
$config['reviews_types'] = array(1=>"Тип1", 2=>"Тип2");

$config['admin_menu'] = array(
		array('section' => 'content', 'name' => 'Контент', 'url' => '', 'permission' => array('admin','redactor','reviews_redactor','news_redactor','reviews_author','reviews_writer','news_author','news_writer'), 'children' =>
			array( 
				array('section' => 'news', 'name' => 'Новости', 'url' => '/admin/news/', 'permission' => array('admin','redactor','news_redactor','news_author','news_writer')),
				array('section' => 'articles', 'name' => 'Статьи', 'url' => '/admin/articles/', 'permission' => array('admin','redactor','news_redactor','article_author','article_writer')),
				array('section' => 'events', 'name' => 'Календарь событий', 'url' => '/admin/events/', 'permission' => array('admin','redactor'), 'disabled'=>true),
				array('section' => 'reviews', 'name' => 'Обзоры', 'url' => '/admin/reviews/', 'permission' => array('admin','redactor','reviews_redactor','reviews_author','reviews_writer'))
				)
			),
			
		array('section' => 'instruments', 'name' => 'Инструменты', 'url' => '', 'permission' => array('admin','redactor','reviews_redactor','news_redactor','reviews_author','reviews_writer','news_redactor','news_author','news_writer'), 'children' =>
				array(
						array('section' => 'tables', 'name' => 'Таблицы', 'url' => '/admin/tables/', 'permission' => array('admin','redactor','reviews_redactor','news_redactor','reviews_author','reviews_writer','news_redactor','news_author','news_writer')),
						array('section' => 'gallery', 'name' => 'Галерея изображений', 'url' => '/admin/gallery/', 'permission' => array('admin','redactor'), 'disabled'=>true),
						array('section' => 'image_upload', 'name' => 'Загрузка картинок', 'url' => '/admin/image_upload/', 'permission' => array('admin','redactor'), 'disabled'=>true),
						array('section' => 'randomizer', 'name' => 'Рандомайзер', 'url' => '/admin/randomizer/', 'permission' => array('admin','redactor'),'disabled'=>true),
						array('section' => 'polls', 'name' => 'Опросы', 'url' => '/admin/polls/', 'permission' => array('admin','redactor'),'disabled'=>true),
						array('section' => 'mails', 'name' => 'Рассылки', 'url' => '/admin/mails/', 'permission' => array('admin','redactor'),'disabled'=>true)
				)
		),


		array('section' => 'statistics', 'name' => 'Статистика', 'url' => '', 'permission' => array('admin','redactor', 'news_redactor','reviews_redactor'), 'children' =>
				array(
						array('section' => 'statistics_news', 'name' => 'Новости', 'url' => '/admin/statistics_news/', 'permission' => array('admin','redactor'), 'disabled'=>true),
						array('section' => 'statistics_articles', 'name' => 'Статьи', 'url' => '/admin/statistics_articles/', 'permission' => array('admin','redactor'), 'disabled'=>true),
						array('section' => 'statistics_authors', 'name' => 'Авторы', 'url' => '/admin/statistics_authors/', 'permission' => array('admin','redactor'), 'disabled'=>true),
						array('section' => 'statistics_redirects', 'name' => 'Редиректы', 'url' => '/admin/statistics_redirects/', 'permission' => array('admin','redactor'),'disabled'=>true),
						array('section' => 'history_changes', 'name' => 'История изменений', 'url' => '/admin/users/history_changes/', 'permission' => array('admin','redactor','news_redactor','reviews_redactor'))
				)
		),
		
		
		array('section' => 'admin', 'name' => 'Админ', 'url' => '', 'permission' => array('admin'), 'children' =>
				array(
						array('section' => 'sections', 'name' => 'Разделы', 'url' => '/admin/sections/', 'permission' => array('admin')),
						array('section' => 'users', 'name' => 'Пользователи', 'url' => '/admin/users/', 'permission' => array('admin'))
				)
		)
		
		
		);

//типы
$config['reviews_content_types'] = array(
		"articles" => array("name" => "Статьи", "dbtable" => "reviews_articles", "data" => "articles", "template_list" => "front/reviews/articles_list.tpl"),
		"interviews" => array("name" => "Интервью", "dbtable" => "reviews_interviews", "data" => "interviews", "template_list" => "front/reviews/interviews_list.tpl"),
		"tables" => array("name" => "Таблицы", "dbtable" => "reviews_tables", "data" => "tables", "template_list" => "front/reviews/tables_list.tpl"),
		"cases" => array("name" => "Кейсы", "dbtable" => "reviews_cases", "data" => "cases", "template_list" => "front/reviews/cases_list.tpl")
		);

//настройка пейджинга
$config['paging']['full_tag_open'] = "<div class='pagination'><ul>";
$config['paging']['full_tag_close'] = '</ul></div>';
$config['paging']['num_tag_open'] = "<li>";
$config['paging']['num_tag_close'] = "</li>";
$config['paging']['cur_tag_open'] = "<li class='disabled'><a href='#'>";
$config['paging']['cur_tag_close'] = "</a></li>";
$config['paging']['prev_tag_open'] = "<li>";
$config['paging']['prev_tag_close'] = "</li>";
$config['paging']['next_tag_open'] = "<li>";
$config['paging']['next_tag_close'] = "</li>";
$config['paging']['last_tag_open'] = "<li>";
$config['paging']['last_tag_close'] = "</li>";
$config['paging']['first_tag_open'] = "<li>";
$config['paging']['first_tag_close'] = "</li>";
$config['paging']['last_link'] = 'Последняя';
$config['paging']['first_link'] = 'Первая';


//настройка загрузки файлов таблиц
$config["table_upload"]['upload_path'] = ROOT.'/files/';
$config["table_upload"]['allowed_types'] = 'xls|xlsx';
$config["table_upload"]['max_size'] = '10000';
$config["table_upload"]['encrypt_name'] = true;
$config["table_upload"]['remove_space'] = true;

//настройка html таблиц из файлов Excel
$config['excel_to_table'] = array(
	'table_open'          => '<table class="main_table from_excel">',
	'title_row_start' 		=> '<tr class="thead" align="center">',
	'title_row_end' 		=> '</tr>',
	'title_td_start' 		=> '<th><p class="table_title">',
	'title_td_end' 			=> '</p></th>',
	'heading_row_start'   => '<tr class="thead" align="center">',
	'heading_row_end'     => '</tr>',
	'heading_cell_start'  => '<th>',
	'heading_cell_end'    => '</th>',
	'row_start'           => '<tr align="center">',
	'row_end'             => '</tr>',
	'cell_start'          => '<td>',
	'cell_end'            => '</td>',
	'row_alt_start'       => '<tr>',
	'row_alt_end'         => '</tr>',
	'cell_alt_start'      => '<td>',
	'cell_alt_end'        => '</td>',
	'table_close'         => '</table>'
);

//наименование групп пользователей админки
$config['admin_groups'] = array(
		'admin' => 'Администратор сайта',
		'redactor' => 'Редактор сайта',
		'news_redactor' => 'Редактор новостей',
		'reviews_redactor' => 'Редактор обзоров',
		'reviews_author' => 'Автор обзоров выпускающий',
		'reviews_writer' => 'Автор обзоров',
		'news_author' => 'Автор новостей выпускающий',
		'news_writer' => 'Автор новостей',
		'articles_author' => 'Автор статей'
		);

//секретное слово для генерации ключей
$config['encryption_key'] = "парасенок";

//имя куки в админке
$config['admin_cookie_name'] = "cnews_admin";

//типы изменения материалов
$config['change_types'] = array('insert' => 'Добавление','update' => 'Обновление','delete' => 'Удаление');

//секции для истории изменений
$config['change_objects'] = array(
		'reviews' => array('name' => 'Обзоры', 'admin_url' => DOMAIN.'/admin/reviews/reviews_single/#ID/'),
		'reviews_articles' => array('name' => 'Обзоры-статьи', 'admin_url' => DOMAIN.'/admin/reviews_articles/#ID/', 'admin_view_url' => DOMAIN.'/admin/reviews_articles/preview/#ID/'),
		'reviews_interviews' => array('name' => 'Обзоры-интервью', 'admin_url' => DOMAIN.'/admin/reviews_interviews/#ID/', 'admin_view_url' => DOMAIN.'/admin/reviews_interviews/preview/#ID/'),
		'reviews_tables' => array('name' => 'Обзоры-таблицы', 'admin_url' => DOMAIN.'/admin/reviews_tables/#ID/'),
		'reviews_headers' => array('name' => 'Обзоры-заголовки', 'admin_url' => DOMAIN.'/admin/reviews/reviews_headers/#ID/'),
		'reviews_cases' => array('name' => 'Обзоры-кейсы', 'admin_url' => DOMAIN.'/admin/reviews_cases/#ID/', 'admin_view_url' => DOMAIN.'/admin/reviews_cases/preview/#ID/'),
		'users' => array('name' => 'Пользователи', 'admin_url' => DOMAIN.'/admin/users/user_edit/#ID/'),
		'sections' => array('name' => 'Разделы сайта', 'admin_url' => DOMAIN.'/admin/sections/'),
		'tables' => array('name' => 'Таблицы', 'admin_url' => DOMAIN.'/admin/tables/edit/#ID/', 'admin_view_url' => DOMAIN.'/admin/tables/view/#ID/'),
		'news' => array('name' => 'Новости', 'admin_url' => DOMAIN.'/admin/news/news_edit/#ID/', 'admin_view_url' => DOMAIN.'/admin/news/preview/#ID/'),
		'articles' => array('name' => 'Статьи', 'admin_url' => DOMAIN.'/admin/articles/article_edit/#ID/', 'admin_view_url' => DOMAIN.'/admin/articles/preview/#ID/')
		);

//группа редакторов, имеющих доступ к чужим статьям,обзорам, новостям...
$config['redactor_group'] = array('admin', 'redactor', 'news_redactor','reviews_redactor');

/*
|--------------------------------------------------------------------------
| Base Site URL
|--------------------------------------------------------------------------
|
| URL to your CodeIgniter root. Typically this will be your base URL,
| WITH a trailing slash:
|
|	http://example.com/
|
*/

$config['base_url']	= "http://" . $_SERVER['SERVER_NAME'] . "/";

/*
|--------------------------------------------------------------------------
| Index File
|--------------------------------------------------------------------------
|
| Typically this will be your index.php file, unless you've renamed it to
| something else. If you are using mod_rewrite to remove the page set this
| variable so that it is blank.
|
*/
$config['index_page'] = "index.php";

/*
|--------------------------------------------------------------------------
| URI PROTOCOL
|--------------------------------------------------------------------------
|
| This item determines which server global should be used to retrieve the
| URI string.  The default setting of "AUTO" works for most servers.
| If your links do not seem to work, try one of the other delicious flavors:
|
| 'AUTO'			Default - auto detects
| 'PATH_INFO'		Uses the PATH_INFO
| 'QUERY_STRING'	Uses the QUERY_STRING
| 'REQUEST_URI'		Uses the REQUEST_URI
| 'ORIG_PATH_INFO'	Uses the ORIG_PATH_INFO
|
*/
//$config['uri_protocol']	= "AUTO";
$config['uri_protocol']	= "AUTO";

/*
|--------------------------------------------------------------------------
| URL suffix
|--------------------------------------------------------------------------
|
| This option allows you to add a suffix to all URLs generated by CodeIgniter.
| For more information please see the user guide:
|
| http://codeigniter.com/user_guide/general/urls.html
*/

$config['url_suffix'] = "";

/*
|--------------------------------------------------------------------------
| Default Language
|--------------------------------------------------------------------------
|
| This determines which set of language files should be used. Make sure
| there is an available translation if you intend to use something other
| than english.
|
*/
$config['language']	= "english";

/*
|--------------------------------------------------------------------------
| Default Character Set
|--------------------------------------------------------------------------
|
| This determines which character set is used by default in various methods
| that require a character set to be provided.
|
*/
$config['charset'] = "cp1251";

/*
|--------------------------------------------------------------------------
| Enable/Disable System Hooks
|--------------------------------------------------------------------------
|
| If you would like to use the "hooks" feature you must enable it by
| setting this variable to TRUE (boolean).  See the user guide for details.
|
*/
$config['enable_hooks'] = FALSE;


/*
|--------------------------------------------------------------------------
| Class Extension Prefix
|--------------------------------------------------------------------------
|
| This item allows you to set the filename/classname prefix when extending
| native libraries.  For more information please see the user guide:
|
| http://codeigniter.com/user_guide/general/core_classes.html
| http://codeigniter.com/user_guide/general/creating_libraries.html
|
*/
$config['subclass_prefix'] = 'MY_';


/*
|--------------------------------------------------------------------------
| Allowed URL Characters
|--------------------------------------------------------------------------
|
| This lets you specify with a regular expression which characters are permitted
| within your URLs.  When someone tries to submit a URL with disallowed
| characters they will get a warning message.
|
| As a security measure you are STRONGLY encouraged to restrict URLs to
| as few characters as possible.  By default only these are allowed: a-z 0-9~%.:_-
|
| Leave blank to allow all characters -- but only if you are insane.
|
| DO NOT CHANGE THIS UNLESS YOU FULLY UNDERSTAND THE REPERCUSSIONS!!
|
*/
$config['permitted_uri_chars'] = 'a-zа-я 0-9~%.:_\-\<\>!\"';


/*
|--------------------------------------------------------------------------
| Enable Query Strings
|--------------------------------------------------------------------------
|
| By default CodeIgniter uses search-engine friendly segment based URLs:
| example.com/who/what/where/
|
| You can optionally enable standard query string based URLs:
| example.com?who=me&what=something&where=here
|
| Options are: TRUE or FALSE (boolean)
|
| The other items let you set the query string "words" that will
| invoke your controllers and its functions:
| example.com/index.php?c=controller&m=function
|
| Please note that some of the helpers won't work as expected when
| this feature is enabled, since CodeIgniter is designed primarily to
| use segment based URLs.
|
*/

$config['enable_query_strings'] = TRUE;
$config['controller_trigger'] 	= 'c';
$config['function_trigger'] 	= 'm';
$config['directory_trigger'] 	= 'd'; // experimental not currently in use

/*
|--------------------------------------------------------------------------
| Error Logging Threshold
|--------------------------------------------------------------------------
|
| If you have enabled error logging, you can set an error threshold to 
| determine what gets logged. Threshold options are:
| You can enable error logging by setting a threshold over zero. The
| threshold determines what gets logged. Threshold options are:
|
|	0 = Disables logging, Error logging TURNED OFF
|	1 = Error Messages (including PHP errors)
|	2 = Debug Messages
|	3 = Informational Messages
|	4 = All Messages
|
| For a live site you'll usually only enable Errors (1) to be logged otherwise
| your log files will fill up very fast.
|
*/
$config['log_threshold'] = 0;

/*
|--------------------------------------------------------------------------
| Error Logging Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| system/logs/ folder.  Use a full server path with trailing slash.
|
*/
$config['log_path'] = '';

/*
|--------------------------------------------------------------------------
| Date Format for Logs
|--------------------------------------------------------------------------
|
| Each item that is logged has an associated date. You can use PHP date
| codes to set your own date formatting
|
*/
$config['log_date_format'] = 'Y-m-d H:i:s';

/*
|--------------------------------------------------------------------------
| Cache Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| system/cache/ folder.  Use a full server path with trailing slash.
|
*/
$config['cache_path'] = '';

/*
|--------------------------------------------------------------------------
| Encryption Key
|--------------------------------------------------------------------------
|
| If you use the Encryption class or the Sessions class with encryption
| enabled you MUST set an encryption key.  See the user guide for info.
|
*/
//$config['encryption_key'] = "";

/*
|--------------------------------------------------------------------------
| Session Variables
|--------------------------------------------------------------------------
|
| 'session_cookie_name' = the name you want for the cookie
| 'encrypt_sess_cookie' = TRUE/FALSE (boolean).  Whether to encrypt the cookie
| 'session_expiration'  = the number of SECONDS you want the session to last.
|  by default sessions last 7200 seconds (two hours).  Set to zero for no expiration.
| 'time_to_update'		= how many seconds between CI refreshing Session Information
|
*/
$config['sess_cookie_name']		= 'ci_session';
$config['sess_expiration']		= 7200;
$config['sess_encrypt_cookie']	= FALSE;
$config['sess_use_database']	= FALSE;
$config['sess_table_name']		= 'ci_sessions';
$config['sess_match_ip']		= FALSE;
$config['sess_match_useragent']	= TRUE;
$config['sess_time_to_update'] 	= 300;

/*
|--------------------------------------------------------------------------
| Cookie Related Variables
|--------------------------------------------------------------------------
|
| 'cookie_prefix' = Set a prefix if you need to avoid collisions
| 'cookie_domain' = Set to .your-domain.com for site-wide cookies
| 'cookie_path'   =  Typically will be a forward slash
|
*/
$config['cookie_prefix']	= "";
$config['cookie_domain']	= "";
$config['cookie_path']		= "/";

/*
|--------------------------------------------------------------------------
| Global XSS Filtering
|--------------------------------------------------------------------------
|
| Determines whether the XSS filter is always active when GET, POST or
| COOKIE data is encountered
|
*/
$config['global_xss_filtering'] = FALSE;

/*
|--------------------------------------------------------------------------
| Output Compression
|--------------------------------------------------------------------------
|
| Enables Gzip output compression for faster page loads.  When enabled,
| the output class will test whether your server supports Gzip.
| Even if it does, however, not all browsers support compression
| so enable only if you are reasonably sure your visitors can handle it.
|
| VERY IMPORTANT:  If you are getting a blank page when compression is enabled it
| means you are prematurely outputting something to your browser. It could
| even be a line of whitespace at the end of one of your scripts.  For
| compression to work, nothing can be sent before the output buffer is called
| by the output class.  Do not "echo" any values with compression enabled.
|
*/
$config['compress_output'] = FALSE;

/*
|--------------------------------------------------------------------------
| Master Time Reference
|--------------------------------------------------------------------------
|
| Options are "local" or "gmt".  This pref tells the system whether to use
| your server's local time as the master "now" reference, or convert it to
| GMT.  See the "date helper" page of the user guide for information
| regarding date handling.
|
*/
$config['time_reference'] = 'local';


/*
|--------------------------------------------------------------------------
| Rewrite PHP Short Tags
|--------------------------------------------------------------------------
|
| If your PHP installation does not have short tag support enabled CI
| can rewrite the tags on-the-fly, enabling you to utilize that syntax
| in your view files.  Options are TRUE or FALSE (boolean)
|
*/
$config['rewrite_short_tags'] = FALSE;

/*
|========================================================
| Каталоги Smarty
|========================================================
*/

$dirView = "/views/";
$config["smarty_template_dir"] = APPPATH.$dirView;
$config["smarty_compile_dir"] = APPPATH.$dirView."/compile/"; 
$config["smarty_config_dir"] = APPPATH.$dirView."/config/"; 

date_default_timezone_set('Europe/Moscow');

/* End of file config.php */
/* Location: ./system/application/config/config.php */