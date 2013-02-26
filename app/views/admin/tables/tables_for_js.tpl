{include file="admin/blocks/header_block.tpl"}
	<div style="margin-top:7px;">
		{include file="admin/tables/tables_list_filter.tpl"}
	</div>
	{if $tables}
		<table class="table th_clear" id="table_sort">
			<tr>
				<th>ID</th>
				<th>Код вставки</th>
				<th>Описание таблицы</th>
				<th></th>
			</tr>
			
			{foreach from=$tables item=table}
				<tr>
					<td>{$table.table_id}</td>
					<td><input type="text" class="input-small table_code" value='{ldelim}table id="{$table.table_id}"{rdelim}' /></td>
					<td>{$table.description}</td>
					<td><a href="" table_code='{ldelim}table id="{$table.table_id}"{rdelim}'>вставить</a></td>
				</tr>
			{/foreach}
		</table>
	{/if}
{include file="admin/blocks/footer_block.tpl"}