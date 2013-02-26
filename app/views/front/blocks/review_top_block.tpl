			<div class="top_bar b_r b_sh">
				<p class="reviews_type">oבחמנ</p>
				<h2><a class="no_decore" href="{$DOMAIN}/admin/reviews/preview/{$reviews.id}/">Îבחמנ: {$reviews.name|strip_tags}</a></h2>
				<div class="support_logo b_sh">
					{if $reviews.banner_image and $reviews.banner_url}
					<div class="logo_wrapp">
						<p>Îבחמנ ןמהדמעמגכום</p>
						<a href="{$reviews.banner_url}"><img alt="{$reviews.banner_url}" src="{$reviews.banner_image}" /></a>
					</div>
					{/if}
					
					{if $reviews.banner_right_image and $reviews.banner_right_url}
					<div class="logo_wrapp">
						<p>Ïנט ןמההונזךו</p>
						<a href="{$reviews.banner_right_url}"><img alt="{$reviews.banner_right_url}" src="{$reviews.banner_right_image}" /></a>
					</div>
					{/if}
				</div>
			</div>
