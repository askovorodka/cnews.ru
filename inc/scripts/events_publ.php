<?php
ini_set('max_execution_time',60*4);
include ('show_errors.php');
date_default_timezone_set('Europe/Moscow');
function _declension($n,$string=array('����','���','����'))
{
	$n = abs($n) % 100;
	$n1 = $n % 10;
	if ($n > 10 && $n < 20) return $string[2];
	if ($n1 > 1 && $n1 < 5) return $string[1];
	if ($n1 == 1) return $string[0];
	return $string[2];
}

$db_host='localhost';
$db_user='virt';
$db_password='uns+St8R';
$db_name='virt';

$db=@mysql_connect($db_host,$db_user,$db_password);
if (!$db) exit;
$select_status=@mysql_select_db($db_name);
if (!$select_status) exit;
mysql_query ("set character_set_client='cp1251'");
mysql_query ("set character_set_results='cp1251'");
mysql_query ("set collation_connection='cp1251_general_ci'");

//$start_date='2010-03-01';
$end_date=mktime(23, 59, 59, 5, 17, 2010);
/****************** ���� ������� � ������� events *****************************/

// ����� �������
$r=mysql_query('insert into events (
	select now(), "project", j.id, concat("�������� ������: ",j.caption," / �����: ",u.nikname), "", concat("/project/byid/",j.id),"0"
	from project j
	left join user u on u.id=j.user_id
	where j.status>0 and j.status<4 and u.blocked=0 and j.id not in (select original_id from events where type="project") order by j.add_date desc
	)');

// ����� �����������
$r=mysql_query('insert into events (
	select now(), "post", p.id, concat(u.nikname," ������� ����������� � ������� ", j.caption), p.text, concat("/project/byid/",j.id),"0"
	from post p
	left join user u on u.id=p.user_id
	left join project j on j.id=p.project_id
	where p.ban=0 and p.parent_post=0 and cast((p.positive_votes_cnt-p.negative_votes_cnt)as signed)>2 and u.blocked=0
	and p.id not in (select original_id from events where type="post")
	 order by p.add_date desc
	)');

// �������
$places=array('������','������','������','���������','�����','������','�������','�������','�������','�������');
if (date('H')==15) //���� ������ ��� ���� ���
{	// �� ������� �������� (���� ��� ������� ��� �� �������)
	// ���������
	$r=mysql_query('select original_id from events e where e.add_date>"'.date('Y-m-d').' 14:59:59" and e.type="users rating"');
	if (!$r || @mysql_num_rows($r)==0)
	{
		if (($r=mysql_query('select position, id, nikname, rating  from user where blocked=0 and rating>0 order by position limit 10')) && mysql_num_rows($r)>0)
		{	$text='';
			$i=0;
			while($row=mysql_fetch_assoc($r))
			{
				$text.=$places[$i].' ����� - '.$row['nikname'].' / '.$row['rating'].' '._declension($row['rating'],array('����','�����','������'))."\r\n";
				$i++;
			}
			mysql_query('insert into events values (now(),"users rating",(select if(max(e.original_id) is null,0,max(e.original_id)+1) from events e where e.type="users rating"),"������� ����������","'.$text.'","/user/rating",0)');
		}
	}

	//�������
	$r=mysql_query('select original_id from events e where e.add_date>"'.date('Y-m-d').' 14:59:59" and e.type="projects rating"');
	if (!$r || @mysql_num_rows($r)==0)
	{
		if (($r=mysql_query('select j.id, j.caption, j.rating, u.nikname, j.comments_count
				from project j
				left join user u on u.id=j.user_id
				where u.blocked=0 and j.rating>0 and j.status>0 and j.status<4
				order by j.rating desc, id desc limit 10')) && @mysql_num_rows($r)>0)
		{
			while($row=mysql_fetch_assoc($r))
			{	$text='';
				$i=0;
				while($row=mysql_fetch_assoc($r))
				{
					$text.=$places[$i].' ����� - '.$row['caption'].' / '.$row['nikname'].' / '.$row['rating'].' '._declension($row['rating'],array('����','�����','������')).' / '.$row['comments_count'].' '._declension($row['comments_count'],array('�����������','�����������','������������'))."\r\n";
					$i++;
				}
				mysql_query('insert into events values (now(),"projects rating",(select if(max(e.original_id) is null,0,max(e.original_id)+1) from events e where e.type="projects rating"),"������� ��������","'.$text.'","/project/all",0)');
			}
		}
	}

	// �� ����� ���������� .. ����
/*	$r=mysql_query('select original_id from events e where e.add_date>"'.date('Y-m-d').' 14:59:59" and e.type="end"');
	if (!$r || @mysql_num_rows($r)==0)
	{
		$end_days=ceil(($end_date-time())/60/60/24);
		mysql_query('insert into events values (now(),"end",(select if(max(e.original_id) is null,0,max(e.original_id)+1) from events e where e.type="end"),"�� ����� ���������� '.$end_days.' '._declension($end_days).'","","/",0)');
	}*/
}
/***************** /���� ������� � ������� events *****************************/

/***************** RSS ********************************************************/
$rss='<?xml version="1.0" encoding="windows-1251"?>
<rss version="2.0">
<channel>
<title>��������� �� �������������</title>
<link>http://virt.cnews.ru/</link>
<description>�������� ������� ���������� �� �������������</description>
<image><url>http://www.cnews.ru/img/cnews_logo.gif</url><title>Cnews logo</title><link>http://www.cnews.ru/</link></image>';

$r=mysql_query('select *,UNIX_TIMESTAMP(add_date) as d from events order by add_date desc limit 50');
while($row=mysql_fetch_assoc($r))
{
	if (stripos($row['url'],'http://')===false) {
		$row['url']='http://virt.cnews.ru'.$row['url'];
	}

	$rss.='<item>
	<title><![CDATA['.stripslashes($row['caption']).']]></title>
	<link><![CDATA['.$row['url'].']]></link>
	<pubDate>'.date('r',$row['d']).'</pubDate>
	<description><![CDATA['.nl2br(stripslashes($row['text'])).']]></description>
	<guid></guid>
</item>
';
}
$rss.='</channel></rss>';
$f=fopen('/www/virt.cnews.ru/htdocs/rss/virt.xml','w');
fwrite($f,$rss);
fclose($f);
/**************** /RSS ********************************************************/

/***************** Twitter ****************************************************/
require('twitterAPI.php');
$r=mysql_query('select *,UNIX_TIMESTAMP(add_date) as d from events where posted=0 order by add_date desc limit 50');
mysql_query('update events set posted=1');
while($row=mysql_fetch_assoc($r))
{
	if (stripos($row['url'],'http://')===false) {
		$row['url']='http://virt.cnews.ru'.$row['url'];
	}

	$mess=stripslashes($row['caption']);
	if (($row['type']=='projects rating') || ($row['type']=='users rating'))
	{
		$e=explode("\r\n",stripslashes($row['text']));
		//var_dump($e);
		foreach ($e as $er)
		{
			if ($er>'') sendToTwitter($mess.': '.$er,$row['url']);
		}
	}
	else
	{
		sendToTwitter($mess,$row['url']);
	}
}
function sendToTwitter($mess,$url){
	$len=strlen($url);
	if (strlen($mess)+$len>139) { //��������� + ������ + url ������ ���� �� ������� 140 ��������
		$mess=substr($mess,0,(140-$len-4)).'...';
	}
	$mess=urlencode(iconv('windows-1251','utf-8',$mess.' '.$url));
	//echo '<br />mess='.$mess.' '.$url.'<br />';
	postToTwitter('VirtChamp2010', '6IMeo7WohT', $mess);
}
/**************** /Twitter ****************************************************/
?>