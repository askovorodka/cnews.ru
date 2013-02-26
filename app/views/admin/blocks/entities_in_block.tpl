<h5>Подключено в следующих объектах:</h5>
<div class="entities_block">
	{if $entities_in}
		<table class="table">
			<tr>
				<th>ID</th>
				<th>Объект</th>
				<th>Посмотреть</th>
			</tr>
			{foreach from=$entities_in item=item}
				<tr>
					<td>{$item.entity_in_id}</td>
					<td>{$item.entity_in.name}</td>
					<td><a href="{$item.entity_in.admin_view_url|replace:'#ID':$item.entity_in_id}">ссылка</a></td>
				</tr>
			{/foreach}
		</table>
	{/if}
</div>