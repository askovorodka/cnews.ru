{include file="front/blocks/header_block.tpl" not_left_side="true"}
		{include file="front/blocks/review_top_block.tpl"}
		<div class="rating_large">
			<p class="decore_title bold b_sh">Рейтинг</p>
			{$table.structure}
			<div class="bottom_bar">
				{if $table.source}<p class="source">{$table.source}</p>{/if}
				{if $table.rating}<p class="legend grey_text">{$table.rating}</p>{/if}
			</div>
		</div>
		<a href="{$DOMAIN}/admin/reviews/preview/{$reviews.id}/" class="button">Вернуться на главную страницу обзора</a>
		<!--// main part -->

{include file="front/blocks/footer_block.tpl"}