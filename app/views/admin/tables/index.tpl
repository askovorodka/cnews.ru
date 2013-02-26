{include file="admin/blocks/header_block.tpl"}
{include file="admin/blocks/left_menu_block.tpl"}
{include file="admin/blocks/breadcrumbs_block.tpl"}

<div class="content">
	
	<h2 style="float:left;width:76%;">Таблицы</h2>
	
	<div class="btn-group" style="margin-top:14px;">
		<a class="btn btn-primary" href="/admin/tables/add/excel/">Добавить таблицу Excel</a>
	</div>
	
	<div class="btn-group" style="margin-top:14px;">
		<a class="btn btn-primary" href="/admin/tables/add/">Создать таблицу</a>
	</div>
	
	<div style="clear:both;"></div>
	
	{include file="admin/tables/tables_list_filter.tpl"}
	
	{if $tables}
		
		{$paging}
		
		<table class="table th_clear" id="table_sort">
			<tr>
				<th>ID</th>
				<th><a href="{$DOMAIN}/admin/tables/tables_list" hash="{$hash}" sort_field="date">Дата создания</a>{if $array.sort_field == 'date'}&nbsp;{if $array.sort_type=='asc'}&uarr;{else}&darr;{/if}{/if}</th>
				<th><a href="{$DOMAIN}/admin/tables/tables_list" hash="{$hash}" sort_field="table_status">Статус</a>{if $array.sort_field == 'table_status'}&nbsp;{if $array.sort_type=='asc'}&uarr;{else}&darr;{/if}{/if}</th>
				<th><a href="{$DOMAIN}/admin/tables/tables_list" hash="{$hash}" sort_field="user_name">Кто создал</a>{if $array.sort_field == 'user_name'}&nbsp;{if $array.sort_type=='asc'}&uarr;{else}&darr;{/if}{/if}</th>
				<th>Описание таблицы</th>
				<th>Код вставки</th>
				<th></th>
				<th></th>
			</tr>
			
			{foreach from=$tables item=table}
				<tr>
					<td>{$table.table_id}</td>
					<td>{$table.date|date_format:"%d.%m.%Y %H:%M"}</td>
					<td>{if $table.table_status == 1}Вкл.{else}Выкл.{/if}</td>
					<td>{$table.user_name}</tD>
					<td>{$table.description}</td>
					<td><input type="text" class="input-small table_code" value='{ldelim}table id="{$table.table_id}"{rdelim}' /></td>
					<td><a href="{$DOMAIN}/admin/tables/view/{$table.table_id}/">смотреть</a></td>
					<td user_id="{$table.users_user_id}">
						<div class="btn-group">
						  <button class="btn dropdown-toggle" data-toggle="dropdown">Действие <span class="caret"></span></button>
						  <ul class="dropdown-menu">
						  	<li><a href="javascript:void(0);" class="go_table_source_code" table_id="{$table.table_id}"><i class="icon-th"></i> Взять код таблицы</a></li>
						    <li><a href="{$DOMAIN}/admin/tables/edit/{$table.table_id}/"><i class="icon-edit"></i> Редактировать</a></li>
						    <li><a href="{$DOMAIN}/admin/tables/delete/{$table.table_id}/" class="table_delete"><i class="icon-remove"></i> Удалить</a></li>
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