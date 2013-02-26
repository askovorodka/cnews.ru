{include file="admin/blocks/header_block.tpl"}

{include file="admin/blocks/left_menu_block.tpl"}

{include file="admin/blocks/breadcrumbs_block.tpl"}

<div class="content">

	<h2>Список таблиц обзора &laquo;{$reviews.name}&raquo;</h2>
	
		<table class="table table-striped th_clear">
		{if $tables}
			<tr>
				<th>ID</th>
				<th>Дата публикации</th>
				<th>Автор таблицы</th>
				<th>Статус</th>
				<th>Описание таблицы</th>
				<th>Предпросмотр</th>
				<th></th>
			</tr>
			{foreach from=$tables item=item}
				<tr>
					<td>{$item.id}</td>
					<td>{$item.date|date_format:"%d.%m.%Y %H:%M"}</td>
					<td>{$item.user_name}</td>
					<td>{if $item.table_status == 1}Вкл.{else}Выкл.{/if}</td>
					<td>{$item.description}</td>
					<td><a href="{$DOMAIN}/admin/reviews_tables/table_view/{$item.id}/">смотреть</a></td>
					<td user_id="{$item.users_user_id}">
						<div class="btn-group">
						  <button class="btn dropdown-toggle" data-toggle="dropdown">Действие <span class="caret"></span></button>
						  <ul class="dropdown-menu">
						    <li><a href="{$DOMAIN}/admin/reviews_tables/table_edit/{$item.id}/"><i class="icon-edit"></i> Редактировать</a></li>
						    <li><a href="{$DOMAIN}/admin/reviews_tables/table_delete/{$item.id}/" class="table_delete"><i class="icon-remove"></i> Удалить</a></li>
						  </ul>
						</div>					
					</td>
				</tr>
			{/foreach}
		{/if}
		
		<tr>
			<td colspan="7">
				<div class="control-group">
					<button type="button" class="btn" onClick="location=protocol + location.hostname + '/admin/reviews_tables/table_add/{$reviews.id}/'"><i class="icon-plus"></i> Добавить таблицу Excel</button>
					<button type="button" class="btn" onClick="location=protocol + location.hostname + '/admin/reviews_tables/table_generate/{$reviews.id}/'"><i class="icon-plus"></i> Создать таблицу</button>
				</div>
			</td>
		</tr>
		</table>
	


</div>

{include file="admin/blocks/footer_block.tpl"}