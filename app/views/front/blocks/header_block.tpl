<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>CNews.ru - Обзоры и обозрения</title>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
	<link rel="stylesheet" type="text/css" href="/inc/css/front/style.css" />
	<script type="text/javascript" src="http://www.cnews.ru/inc/js/jquery_lightbox/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="http://www.cnews.ru/inc/js/jquery_lightbox/jquery.lightbox-0.5.min.js"></script>
	<link rel="stylesheet" type="text/css" href="http://www.cnews.ru//inc/js/jquery_lightbox/jquery.lightbox-0.5.css" media="screen" />
	{literal}
	<script type="text/javascript">
		$(document).ready(function() {
			
			$('.popup_img a').lightBox();
			$('.popup_img .zoom').click(
				function()
					{
					$(this).prev().click();
					return false;
					});
			
			$('.gallery').lightBox();
			$(".nav_list a").click(function() {
				$('html, body').animate({
				 scrollTop: $($(this).attr('href')).offset().top
				 }, 1000);
				 return false;
				});
		});
	</script>
	<!--[if IE 6]>
		<link href="http://cnews.ru/inc/css/forum2012/ie6.css" rel="stylesheet" type="text/css" />
		<script src="js/unitpn gfix.js" type="text/javascript"></script>
	<![endif]-->
	{/literal}
</head>
<body>
<div class="wrapper">
	<!-- header -->
	<div id="header" >
		<h1><a href="/"><img alt="cnews" src="http://www.cnews.ru/img/design2008/logocnews_.gif" /></a></h1>
		<div class="banner_600x90">
			<a href="#"><img src="http://content.rbc.medialand.ru/669438/rbc_video60090_16012013_112.jpg" alt="" /></a>
		</div>
		<div class="nav_bar">
			<ul class="main_nav">
				<li class="active"><a href="http://www.cnews.ru/">CNEWS</a></li>
				<li><a href="http://www.cnews.ru/news/">НОВОСТИ</a></li>
				<li><a href="http://www.cnews.ru/reviews/">АНАЛИТИКА</a></li>
				<li><a href="http://tv.cnews.ru/">ТВ</a></li>
				<li><a href="http://club.cnews.ru/">БЛОГИ</a></li>
				<li><a href="http://zoom.cnews.ru/">ТЕХНИКА</a></li>
				<li><a href="http://rnd.cnews.ru/">НАУКА</a></li>
				<li><a href="http://soft.cnews.ru/">СОФТ</a></li>
				<li><a href="http://games.cnews.ru/">ИГРЫ</a></li>
			</ul>
		</div>
	</div>
	<!--// header -->

	<!-- content -->
	<div id="content" class="clear" >
		{if !$not_left_side}
		<!-- left side -->
		<div class="left_side">
			<div class="banner_wrapp">
				<a href="http://www.a1qa.ru/"><img alt="" src="http://filearchive.cnews.ru/img/forum/2011/09/29/a1qa_banner_54cd7.gif" /></a>
			</div>
			<div class="banner_wrapp">
				<a href="http://www.a1qa.ru/"><img alt="" src="http://filearchive.cnews.ru/img/forum/2011/09/29/a1qa_banner_54cd7.gif" /></a>
			</div>
		</div>
		<!--// left side -->
		{/if}