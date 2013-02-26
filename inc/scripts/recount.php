<?php


	$db_host='localhost';
	$db_user='virt';
	$db_password='uns+St8R';
	$db_name='virt';

	$db=@mysql_connect($db_host,$db_user,$db_password);
	if (!$db) exit;
	$select_status=@mysql_select_db($db_name);
	if (!$select_status) exit;

//	$r=mysql_query('unlock tables');
	mysql_query('LOCK TABLES post p write, vote v read, user u write, user u2 write, project j write, project j2 write');
	mysql_query('call update_ratings()');
	mysql_query('unlock tables');
	//echo 'recount done';

	$pc=$uc=0;
	if ($r=mysql_query('select count(*) from user'))	$uc=intval(@mysql_result($r,0,0));
	if ($r=mysql_query('select count(*) from project where status>0 and status<4'))	$pc=intval(@mysql_result($r,0,0));
	$f=fopen('/www/virt.cnews.ru/htdocs/inc/counts.txt','w');
	fwrite($f,'<!--#set var="uc" value="'.$uc.'" --><!--#set var="pc" value="'.$pc.'" -->');
	fclose($f);
?>