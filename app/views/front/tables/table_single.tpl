{if $table}
<div>
		{$table_code}
		<div class="bottom_bar">
			{if $table.source}<p class="source">{$table.source|strip_tags}</p>{/if}
			{if $table.rating}<p class="legend grey_text">{$table.rating}</p>{/if}
			<a class="button" href="/admin/tables/preview/{$table.table_id}/">Перейти к полной таблице</a>
		</div>
</div>
{/if}