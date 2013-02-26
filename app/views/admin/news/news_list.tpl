{include file="admin/blocks/header_block.tpl"}
{include file="admin/blocks/left_menu_block.tpl"}
{include file="admin/blocks/breadcrumbs_block.tpl"}

<div class="content">
	
	<h2 style="float:left;width:89%;">Список новостей</h2>
	
	<div class="btn-group" style="margin-top:14px;">
		<a class="btn btn-primary" href="{$DOMAIN}/admin/news/news_add/">Добавить новость</a>
	</div>
	
	{include file="admin/news/news_filter_block.tpl"}
	
	{if $news}
		
		{$paging}
		
		<table class="table th_clear" id="table_sort">
			
			<tr>
				<th>ID</th>
				<th nowrap><a href="{$DOMAIN}/admin/news/news_list" hash="{$hash}" sort_field="news_date">Дата публикации</a>{if $array.sort_field == 'news_date'}&nbsp;{if $array.sort_type=='asc'}&uarr;{else}&darr;{/if}{/if}</th>
				<th nowrap><a href="{$DOMAIN}/admin/news/news_list" hash="{$hash}" sort_field="news_status">Статус новости</a>{if $array.sort_field == 'news_status'}&nbsp;{if $array.sort_type=='asc'}&uarr;{else}&darr;{/if}{/if}</th>
				<th>Заголовок новости</th>
				<th><a href="{$DOMAIN}/admin/news/news_list" hash="{$hash}" sort_field="user_name">Автор</a>{if $array.sort_field == 'user_name'}&nbsp;{if $array.sort_type=='asc'}&uarr;{else}&darr;{/if}{/if}</th>
				<th>Анонс новости</th>
				<th></th>
			</tr>
			
			{foreach from=$news item=item}
				<tr>
					<td>{$item.news_id}</td>
					<td>{$item.news_date|date_format:"%d.%m.%Y %H:%M"}</td>
					<td>{if $item.news_status == 1}Вкл.{else}Выкл.{/if}</td>
					<td>{$item.news_title|strip_tags}</tD>
					<td>{$item.user_name|strip_tags}</td>
					<td>{$item.news_annonce}</td>
					<td user_id="{$item.users_user_id}">
						<div class="btn-group">
						  <button class="btn dropdown-toggle" data-toggle="dropdown">Действие <span class="caret"></span></button>
						  <ul class="dropdown-menu">
						    <li><a href="{$DOMAIN}/admin/news/news_edit/{$item.news_id}/"><i class="icon-edit"></i> Редактировать</a></li>
						    <li><a href="{$DOMAIN}/admin/news/news_delete/{$item.news_id}/" class="news_delete"><i class="icon-remove"></i> Удалить</a></li>
						  </ul>
						</div>					
					</td>
				</tr>
			{/foreach}
			
		</table>
		
		{$paging}
		
	{/if}
	
	
</div>

{include file="admin/blocks/footer_block.tpl"}