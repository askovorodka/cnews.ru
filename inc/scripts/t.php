<?php


	$db_host='localhost';
	$db_user='virt';
	$db_password='uns+St8R';
	$db_name='virt';

	$db=@mysql_connect($db_host,$db_user,$db_password);
	if (!$db) exit;
	$select_status=@mysql_select_db($db_name);
	if (!$select_status) exit;

	$r=mysql_query('unlock tables');

?>