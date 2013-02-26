{if !$only_content}{include file="front/blocks/header_block.tpl"}{/if}

		<!-- main part -->
		<div class="double_right">
			<div class="main_lead clear b_r b_sh">
				<p class="reviews_type">oבחמנ</p>
				<h2>Îבחמנ: {$review.name|strip_tags}</h2>
				<div class="left_part">
					{if $review.image|isimage}
					<div class="img_wrapp b_sh">
						<img width="270" height="210" src="{$review.image}" alt="Îבחמנ: {$review.name|strip_tags}" />
					</div>
					{/if}
					
					<div class="support_logo b_sh">
						{if $review.banner_image and $review.banner_url}
						<div class="logo_wrapp">
							<p>Îבחמנ ןמהדמעמגכום</p>
							<a href="{$review.banner_url}"><img alt="{$review.banner_url}" src="{$review.banner_image}" /></a>
						</div>
						{/if}
						
						{if $review.banner_right_image and $review.banner_right_url}
						<div class="logo_wrapp">
							<p>Ïנט ןמההונזךו</p>
							<a href="{$review.banner_right_url}"><img alt="{$review.banner_right_url}" src="{$review.banner_right_image}" /></a>
						</div>
						{/if}
					</div>
				</div>
				
				<div class="right_part">
					<p>{$review.text}</p>
					{if $reviews_structure}
						<ul class="nav_list">
						{foreach from=$reviews_structure item=header}
							<li><a href="#header{$header.id}">{$header.name|strip_tags}</a></li>
						{/foreach}
						</ul>
					{/if}
				</div>
				
			</div>
			
			{if $reviews_structure}
				{foreach from=$reviews_structure item=header}
					
					{if count($header.data)}
						{foreach from=$header.data item=item key=key}
							{$item}
						{/foreach}
					{/if}
				{/foreach}
			{/if}			
			
			<div class="reviews_about grey_text">{$review.footer}</div>
			
		</div>
{if !$only_content}{include file="front/blocks/footer_block.tpl"}{/if}