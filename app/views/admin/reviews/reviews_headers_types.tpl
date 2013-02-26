{include file="admin/blocks/header_block.tpl"}
{include file="admin/blocks/left_menu_block.tpl"}
{include file="admin/blocks/breadcrumbs_block.tpl"}

<div class="content">
	<h2>Заголовок глав {$header.name}: редактирование типов</h2>


	<form action="" method="post">
		<input type="hidden" name="id" value="{$header.id}" />
		<table class="table headers_table" id="headers_table_sort">
			{foreach from=$reviews_content_types item=item key=key}
				<tr><td><input type="checkbox" name="types[{$key}]" {if (!empty($floor) and in_array($key,$floor))}checked="checked"{/if} value="{$key}" id="{$key}"></td>
				<td><label for="{$key}">{$item.name}</label></td></tr>
			{/foreach}
		</table>

		<div class="control-group">
			<button type="submit" class="btn btn-success"><i class="icon-ok"></i> Изменить</button>
		</div>

	</form>

</div>

{include file="admin/blocks/footer_block.tpl"}