<!DOCTYPE html>
<html lang="ru">
<head>
<title>{if $header_block}{$header_block->get_title()}{/if}</title>

{if $header_block->get_css()}
	{foreach from=$header_block->get_css() item=css_item key=key}
		<link href="{$css_item}" rel="stylesheet" media="screen">
	{/foreach}
{/if}

{if $header_block->get_js()}
	{foreach from=$header_block->get_js() item=js_item key=key}
		<script src="{$js_item}" language="JavaScript"></script>
	{/foreach}
{/if}

</head>

<body id="main">