{include file="admin/blocks/header_block.tpl"}
{include file="admin/blocks/left_menu_block.tpl"}
{include file="admin/blocks/breadcrumbs_block.tpl"}

<div class="content">
	
	<h2>Список разделов сайта</h2>
	
	{if $sections}
		
		<table class="table th_clear" id="table_sections">
			<tr>
				<th>ID</th>
				<th>Название раздела</th>
				<th>Дата создания</th>
				<th>Действие</th>
			</tr>
			
			{foreach from=$sections item=item}
				
				{get_sections section=`$item`}
				
			{/foreach}
			
		</table>
		
	{/if}
	
	<button class="btn btn-primary" onClick="location=protocol + location.hostname + '/admin/sections/section_add/'">Добавить раздел</button>
	
</div>

{include file="admin/blocks/footer_block.tpl"}