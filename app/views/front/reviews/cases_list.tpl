{if $cases}
	<div class="part_wrapp">
		{if $counter == 1}
			<p class="decore_title bold b_sh" id="header{$header_id}">{$header_name|strip_tags}</p>
		{/if}
		{foreach from=$cases item=case}
			<div class="case b_sh b_r">
				<a target="_blank" href="{$case.banner_right_url}"><img class="small_logo" alt="{$case.banner_right_url}" src="{$case.banner_right_image}" /></a>
				<p><a href="{$case.banner_url}"><img alt="{$case.banner_url}" src="{$case.banner_image}" /></a></p>
				<p><a href="/admin/reviews_cases/preview/{$case.case_translit}/{$case.id}/">{$case.name|strip_tags}</a></p>
			</div>
		{/foreach}
	</div>
{/if}