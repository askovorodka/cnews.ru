<h5>���������� �������:</h5>
<div class="entities_block">
	{if $entities}
		<table class="table">
			<tr>
				<th>ID</th>
				<th>������</th>
				<th>����������</th>
			</tr>
			{foreach from=$entities item=item}
				<tr>
					<td>{$item.entity_id}</td>
					<td>{$item.entity.name}</td>
					<td><a href="{$item.entity.admin_view_url|replace:'#ID':$item.entity_id}">������</a></td>
				</tr>
			{/foreach}
		</table>
	{/if}
</div>