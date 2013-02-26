{if !$only_content}{include file="front/blocks/header_block.tpl"}{/if}

		<!-- main part -->
		<div class="double_right">
			{include file="front/blocks/review_top_block.tpl"}
			<div class="article_lead clear">
				{if $article.image|isimage}<img width="170" height="120" class="b_r b_sh" alt="{$article.name|strip_tags}" src="{$article.image}" />{/if}
				<h3>{$article.name|strip_tags}</h3>
				<p>{$article.small_text}</p>
			</div>
			<div class="article_body">
				{$article.text}
				<p class="author"><em class="t_s">{$article.user_name|strip_tags}</em></p>
			</div>
			<a href="{$DOMAIN}/admin/reviews/preview/{$reviews.id}/" class="button">Вернуться на главную страницу обзора</a>
		</div>
		<!--// main part -->

{if !$only_content}{include file="front/blocks/footer_block.tpl"}{/if}