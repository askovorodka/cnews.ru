{include file="admin/blocks/header_block.tpl"}
{include file="admin/blocks/left_menu_block.tpl"}
{include file="admin/blocks/breadcrumbs_block.tpl"}

<div class="content">
	
	<h2 style="float:left;width:89%;">Список статей</h2>
	
	<div class="btn-group" style="margin-top:14px;">
		<a class="btn btn-primary" href="{$DOMAIN}/admin/articles/article_add/">Добавить статью</a>
	</div>
	
	{include file="admin/articles/articles_filter_block.tpl"}
	
	{if $articles}
		
		{$paging}
		
		<table class="table th_clear" id="table_sort">
			
			<tr>
				<th>ID</th>
				<th nowrap><a href="{$DOMAIN}/admin/articles/articles_list" hash="{$hash}" sort_field="article_date">Дата публикации</a>{if $array.sort_field == 'article_date'}&nbsp;{if $array.sort_type=='asc'}&uarr;{else}&darr;{/if}{/if}</th>
				<th nowrap><a href="{$DOMAIN}/admin/articles/articles_list" hash="{$hash}" sort_field="article_status">Статус статьи</a>{if $array.sort_field == 'article_status'}&nbsp;{if $array.sort_type=='asc'}&uarr;{else}&darr;{/if}{/if}</th>
				<th>Заголовок статьи</th>
				<th><a href="{$DOMAIN}/admin/articles/articles_list" hash="{$hash}" sort_field="user_name">Автор</a>{if $array.sort_field == 'user_name'}&nbsp;{if $array.sort_type=='asc'}&uarr;{else}&darr;{/if}{/if}</th>
				<th>Анонс новости</th>
				<th></th>
			</tr>
			
			{foreach from=$articles item=item}
				<tr>
					<td>{$item.article_id}</td>
					<td>{$item.article_date|date_format:"%d.%m.%Y %H:%M"}</td>
					<td>{if $item.article_status == 1}Вкл.{else}Выкл.{/if}</td>
					<td>{$item.article_title|strip_tags}</tD>
					<td>{$item.user_name|strip_tags}</td>
					<td>{$item.article_annonce}</td>
					<td user_id="{$item.users_user_id}">
						<div class="btn-group">
						  <button class="btn dropdown-toggle" data-toggle="dropdown">Действие <span class="caret"></span></button>
						  <ul class="dropdown-menu">
						    <li><a href="{$DOMAIN}/admin/articles/article_edit/{$item.article_id}/"><i class="icon-edit"></i> Редактировать</a></li>
						    <li><a href="{$DOMAIN}/admin/articles/article_delete/{$item.article_id}/" class="article_delete"><i class="icon-remove"></i> Удалить</a></li>
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