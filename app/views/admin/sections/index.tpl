{include file="admin/blocks/header_block.tpl"}
{include file="admin/blocks/left_menu_block.tpl"}
{include file="admin/blocks/breadcrumbs_block.tpl"}

<div class="content">
	
	<h2>������ �������� �����</h2>
	
	{if $sections}
		
		<table class="table th_clear" id="table_sections">
			<tr>
				<th>ID</th>
				<th>�������� �������</th>
				<th>���� ��������</th>
				<th>��������</th>
			</tr>
			
			{foreach from=$sections item=item}
				
				{get_sections section=`$item`}
				
			{/foreach}
			
		</table>
		
	{/if}
	
	<button class="btn btn-primary" onClick="location=protocol + location.hostname + '/admin/sections/section_add/'">�������� ������</button>
	
</div>

{include file="admin/blocks/footer_block.tpl"}