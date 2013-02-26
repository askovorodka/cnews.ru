{if $articles}
	<div class="part_wrapp">
		{if $counter == 1}
			<h3 class="part_title" id="header{$header_id}">{$header_sort}. {$header_name|strip_tags}</h3>
		{/if}
		<ul class="articles_list">
		{foreach from=$articles item=article}
			<li><a href="{$DOMAIN}/admin/reviews_articles/preview/{$article.article_translit}/{$article.id}/">{$article.name|strip_tags}</a></li>
		{/foreach}
		</ul>
	</div>
{/if}