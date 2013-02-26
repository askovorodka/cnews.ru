{if $tables}

	{foreach from=$tables item=table}
	<div class="part_wrapp">

		{if $counter == 1}
			<h3 class="part_title" id="header{$header_id}">{$header_sort}. {$header_name|strip_tags}</h3>
		{/if}

		<p class="decore_title bold b_sh">Рейтинг</p>
		{$table.structure}
		<div class="bottom_bar">
			{if $table.source}<p class="source">{$table.source|strip_tags}</p>{/if}
			{if $table.rating}<p class="legend grey_text">{$table.rating}</p>{/if}
			<a class="button" href="/admin/reviews_tables/preview/{$table.id}/">Перейти к полному рейтингу</a>
		</div>
	</div>
	{/foreach}

{/if}