{if $interviews}
	<div class="part_wrapp">
		{if $counter == 1}
			<h3 class="part_title" id="header{$header_id}">{$header_sort}. {$header_name|strip_tags}</h3>
		{/if}
	
		<p class="decore_title bold b_sh">Интервью с экспертами</p>
		{foreach from=$interviews item=interview}
			<div class="interview clear b_sh">
				<a href="/admin/reviews_interviews/preview/{$interview.interview_translit}/{$interview.id}/"><img class="speaker_img  b_sh" width="66" height="86" src="{$interview.image}" alt="{$interview.person}" /></a>
				<p class="title"><strong>{$interview.person}:</strong><br /> {$interview.small_text}</p>
				<p><a class="grey_link" href="/admin/reviews_interviews/preview/{$interview.interview_translit}/{$interview.id}/">читать полное интервью</a></p>
				<a target="_blank" href="{$interview.logo_url}"><img class="small_logo" src="{$interview.logo}" alt="{$interview.logo_url}" /></a>
			</div>
		{/foreach}
	</div>
{/if}