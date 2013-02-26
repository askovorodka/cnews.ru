{include file="admin/blocks/header_block.tpl"}
{include file="admin/blocks/left_menu_block.tpl"}
{include file="admin/blocks/breadcrumbs_block.tpl"}

<div class="content">
	
	<h2 style="float:left;width:89%;">Список обзоров</h2>
	
	<div class="btn-group" style="margin-top:14px;">
		<button class="btn btn-primary" onClick="location=protocol + location.hostname + '/admin/reviews/reviews_add/'">Добавить обзор</button>
	</div>
	
	<div style="clear:both;"></div>
	{if count($reviews)}
	<table class="table table-striped th_clear" id="table_sort">
		<tr>
			<th>ID</th>
			<th><a href="{$DOMAIN}/admin/reviews/reviews_list" hash="{$hash}" sort_field="date">Дата</a>{if $array.sort_field == 'date'}&nbsp;{if $array.sort_type=='asc'}&uarr;{else}&darr;{/if}{/if}</th>
			<th><a href="{$DOMAIN}/admin/reviews/reviews_list" hash="{$hash}" sort_field="review_status">Статус</a>{if $array.sort_field == 'review_status'}&nbsp;{if $array.sort_type=='asc'}&uarr;{else}&darr;{/if}{/if}</th>
			<th nowrap><a href="{$DOMAIN}/admin/reviews/reviews_list" hash="{$hash}" sort_field="user_name">Автор обзора</a>{if $array.sort_field == 'user_name'}&nbsp;{if $array.sort_type=='asc'}&uarr;{else}&darr;{/if}{/if}</th>
			<th nowrap>Название Обзора</th>
			<th>Картинка</th>
			<th nowrap>Текст Обзора</th>
			<th>Материалы</th>
			<th></th>
		</tr>

		{if $reviews}
			{foreach from=$reviews item=item}
				<tr>
					<td>{$item.id}</td>
					<td nowrap>{$item.date|date_format:"%d.%m.%Y %H:%M"}</td>
					<td>{if $item.review_status == 1}Вкл.{else}Выкл.{/if}</td>
					<td>{if $active_user.group_name == 'admin'}<a href="{$DOMAIN}/admin/users/user_edit/{$item.users_user_id}/" title="Посмотреть пользователя">{$item.user_name}</a>{else}{$item.user_name}{/if}</td>
					<td><a href="{$DOMAIN}/admin/reviews/reviews_single/{$item.id}/">{$item.name}</a></td>
					<td>{if $item.image|isimage}<img src="{$item.image}" alt="Нет фото" width="250" />{else}Нет фото{/if}</td>
					<td>{$item.text|truncate:"200"}</td>
					<td nowrap>
						<a href="{$DOMAIN}/admin/reviews_articles/{$item.id}/">Статьи</a><br />
						<a href="{$DOMAIN}/admin/reviews_interviews/{$item.id}/">Интервью</a><br />
						<a href="{$DOMAIN}/admin/reviews_cases/{$item.id}/">Кейсы</a><br />
						<a href="{$DOMAIN}/admin/reviews_tables/{$item.id}/">Таблицы</a><br />
						<a href="{$DOMAIN}/admin/reviews/reviews_headers/{$item.id}/">Заголовки глав</a><br />
						<a href="{$DOMAIN}/admin/reviews/reviews_single/{$item.id}/">Структура обзора</a><br />
						<a href="{$DOMAIN}/admin/reviews/preview/{$item.id}/" target="_blank">Предпросмотр</a><br />
					</td>
					<td user_id="{$item.users_user_id}">
						<div class="btn-group">
						  <button class="btn dropdown-toggle" data-toggle="dropdown">Действие <span class="caret"></span></button>
						  <ul class="dropdown-menu">
						    <li><a href="{$DOMAIN}/admin/reviews/reviews_edit/{$item.id}/"><i class="icon-edit"></i> Редактировать</a></li>
						    <li><a href="{$DOMAIN}/admin/reviews/reviews_delete/{$item.id}/" class="reviews_delete"><i class="icon-remove"></i> Удалить</a></li>
						    <li><a href="" class="reviews_generate" reviews_id="{$item.id}"><i class="icon-share-alt"></i> Сгенерировать</a></li>
						  </ul>
						</div>					
					</td>
				</tr>
			{/foreach}
		{/if}
		
	</table>
	
	{$paging}
	
	{else}
	<center>Обзоров нет</center>
	{/if}
	
</div>

{include file="admin/blocks/footer_block.tpl"}