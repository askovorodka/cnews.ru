<div class="left_menu">

{if $active_user}<div class="active_user"><i class="icon-user"></i> {$active_user.user_name}<a href="{$DOMAIN}/admin/login/logout/" class="btn btn-inverse btn-small" id="btn_logout">Выйти</a></div>{/if}

<ul class="nav nav-list">
	{foreach from=$left_menu item=lmenu}
		<li class="nav-header">{$lmenu.name|strip_tags}</li>
		{foreach from=$lmenu.children item=ch_menu}
			<li{if $ch_menu.active} class="active"{/if}>{if $ch_menu.disabled}{$ch_menu.name|strip_tags}{else}<a href="{$DOMAIN}{$ch_menu.url}">{$ch_menu.name|strip_tags}</a>{/if}</li>
		{/foreach}
	{/foreach}
</ul>

</div>