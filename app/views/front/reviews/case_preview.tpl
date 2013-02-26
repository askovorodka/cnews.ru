{if !$only_content}{include file="front/blocks/header_block.tpl"}{/if}
		<div class="double_right">
			{include file="front/blocks/review_top_block.tpl"}
			{if $case.banner_image or $case.banner_right_image}
			<div class="case b_sh b_r">
				<p>{if $case.banner_image|isimage}<a href="{$case.banner_url}"><img alt="{$case.banner_url}" src="{$case.banner_image}" /></a>{/if}
				{if $case.banner_right_image|isimage}<a href="{$case.banner_right_url}"><img alt="{$case.banner_right_url}" src="{$case.banner_right_image}" /></a>{/if}</p>
			</div>
			{/if}
			<div class="case_lead clear">
				<h3>{$case.name|strip_tags}</h3>
				{if $case.image|isimage}<img width="170" height="120" class="b_r b_sh" alt="{$case.name|strip_tags}" src="{$case.image}" />{/if}
				<p>{$case.small_text}</p>
			</div>
			<div class="article_body">{$case.text}</div>
			<a href="{$DOMAIN}/admin/reviews/preview/{$reviews.id}/" class="button">Вернуться на главную страницу обзора</a>
		</div>
		<!--// main part -->

{if !$only_content}{include file="front/blocks/footer_block.tpl"}{/if}