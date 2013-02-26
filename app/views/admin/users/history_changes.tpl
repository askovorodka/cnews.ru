{include file="admin/blocks/header_block.tpl"}
{include file="admin/blocks/left_menu_block.tpl"}
{include file="admin/blocks/breadcrumbs_block.tpl"}

<div class="content">
	
	<h2>История изменений</h2>

	{include file="admin/users/history_filter.tpl"}
	
	{if $history_changes}
		
		{$paging}
		
		<table class="table th_clear" id="table_sort">
			<tr>
				<th nowrap><a href="{$DOMAIN}/admin/users/history_changes" sort_field="change_date" hash="{$hash}">Дата изменения</a>{if $filter_params.sort_field == 'change_date'}&nbsp;{if $filter_params.sort_type=='asc'}&uarr;{else}&darr;{/if}{/if}</th>
				<th nowrap><a href="{$DOMAIN}/admin/users/history_changes" sort_field="user_name" hash="{$hash}">Пользователь</a>{if $filter_params.sort_field == 'user_name'}&nbsp;{if $filter_params.sort_type=='asc'}&uarr;{else}&darr;{/if}{/if}</th>
				<th nowrap><a href="{$DOMAIN}/admin/users/history_changes" sort_field="change_type" hash="{$hash}">Вид операции</a>{if $filter_params.sort_field == 'change_type'}&nbsp;{if $filter_params.sort_type=='asc'}&uarr;{else}&darr;{/if}{/if}</th>
				<th nowrap><a href="{$DOMAIN}/admin/users/history_changes" sort_field="change_object" hash="{$hash}">Объект изменения</a>{if $filter_params.sort_field == 'change_object'}&nbsp;{if $filter_params.sort_type=='asc'}&uarr;{else}&darr;{/if}{/if}</th>
				<th>Описание</th>
				<th>Ссылка</th>
			</tr>
			
			{foreach from=$history_changes item=item}
				<tr>
					<td>{$item.change_date|date_format:"%d.%m.%Y %H:%M"}</td>
					<td><a href="{$DOMAIN}/admin/users/user_edit/{$item.change_user_id}/">{$item.user_name}</a></td>
					<td>{$item.operation}</td>
					<td>{$item.object_name}</td>
					<td>{$item.change_description}</td>
					<td><a href="{$item.object_url}">{$item.object_url}</a></td>
				</tr>
			{/foreach}
			
		</table>
		
		{$paging}
		
	{/if}
	
</div>

{include file="admin/blocks/footer_block.tpl"}