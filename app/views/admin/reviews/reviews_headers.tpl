{include file="admin/blocks/header_block.tpl"}
{include file="admin/blocks/left_menu_block.tpl"}
{include file="admin/blocks/breadcrumbs_block.tpl"}
<div class="content">
	<h2>��������� ���� ��� ������ {$reviews.name}</h2>
	
	{if $headers}
	<form action="{$DOMAIN}/admin/reviews/reviews_headers_save/{$reviews.id}/" method="post">
		<table class="table headers_table" id="headers_table_sort">
			{foreach from=$headers item=header name=for_headers}
				<tr>
					<td>{$smarty.foreach.for_headers.iteration}</td>
					<td><input type="text" class="input-large" name="header[{$header.id}]" value="{$header.name|strip_tags}" /></td>
					<td><a href="{$DOMAIN}/admin/reviews/reviews_headers_types/{$reviews.id}/{$header.id}/">������������� ���� �������-����������</a></td>
					<td><a href="{$DOMAIN}/admin/reviews/reviews_headers_delete/{$header.id}/" class="headers_delete">������� ���������</a></td>
				</tr>
			{/foreach}
		</table>
		
		<div class="control-group">
			<button type="submit" class="btn btn-success"><i class="icon-ok"></i> ��������</button>
		</div>
		
	</form>
	{/if}
	
	
	<form action="" method="post" class="form-inline" id="add_header">
		<div class="control-group">
			<input type="text" name="header_name" class="input-large" placeholder="�������� ���������">
			<button type="submit" class="btn"><i class="icon-plus"></i> �������� ��������� ����</button>
		</div>
	</form>
</div>

{include file="admin/blocks/footer_block.tpl"}