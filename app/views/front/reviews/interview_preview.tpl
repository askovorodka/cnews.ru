{if !$only_content}{include file="front/blocks/header_block.tpl"}{/if}
		<!-- main part -->
		<div class="double_right">
			{include file="front/blocks/review_top_block.tpl"}
			<div class="interview_lead clear  b_r b_sh">
				{if $interview.image|isimage}<img width="180" height="240" class="speaker_img b_sh" alt="{$interview.person|strip_tags}" src="{$interview.image}" />{/if}
				{if $interview.logo|isimage}<a target="_blank" href="{$interview.logo_url}"><img class="small_logo" src="{$interview.logo}" alt="" /></a>{/if}
				<h3><span>{$interview.person|strip_tags}:</span><br /> {$interview.small_text}</h3>
				<p class="lead">{$interview.description}</p>
			</div>
			<div class="article_body">{$interview.text}</div>
			<a href="{$DOMAIN}/admin/reviews/preview/{$reviews.id}/" class="button">Вернуться на главную страницу обзора</a>
		</div>
		<!--// main part -->
{if !$only_content}{include file="front/blocks/footer_block.tpl"}{/if}