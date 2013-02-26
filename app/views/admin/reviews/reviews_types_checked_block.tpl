<h5>{$item.name}</h5>
<div class="reviews_types_checked_block">
	<ul>
	{if $item.data == "articles"}
		{foreach from=$articles item=article}
			{if $article.article_status == 1}
			<li><input type="checkbox" {if !empty($article.reviews_headers_id) and $article.reviews_headers_id == $header_id}checked{elseif !empty($article.reviews_headers_id)}disabled{/if} name="article[{$header_id}][{$article.id}]" article="{$article.id}" id="article_{$header_id}_{$article.id}" /><label for="article_{$header_id}_{$article.id}" {if !empty($article.reviews_headers_id) and $article.reviews_headers_id != $header_id}class="label_disabled"{/if}>{$article.name|strip_tags}</label></li>
			{/if}
		{/foreach}

	{elseif $item.data == "interviews"}
		{foreach from=$interviews item=interview}
			{if $interview.interview_status == 1}
			<li><input type="checkbox" {if !empty($interview.reviews_headers_id) and $interview.reviews_headers_id == $header_id}checked{elseif !empty($interview.reviews_headers_id)}disabled{/if} name="interview[{$header_id}][{$interview.id}]" interview="{$interview.id}" id="interview_{$header_id}_{$interview.id}" /><label for="interview_{$header_id}_{$interview.id}" {if !empty($interview.reviews_headers_id) and $interview.reviews_headers_id != $header_id}class="label_disabled"{/if}>{$interview.person|strip_tags}</label></li>
			{/if}
		{/foreach}

	{elseif $item.data == "cases"}
		{foreach from=$cases item=case}
			{if $case.case_status == 1}
			<li><input type="checkbox" {if !empty($case.reviews_headers_id) and $case.reviews_headers_id == $header_id}checked{elseif !empty($case.reviews_headers_id)}disabled{/if} name="case[{$header_id}][{$case.id}]" case="{$case.id}" id="case_{$header_id}_{$case.id}" /><label for="case_{$header_id}_{$case.id}" {if !empty($case.reviews_headers_id) and $case.reviews_headers_id != $header_id}class="label_disabled"{/if}>{$case.name|strip_tags}</label></li>
			{/if}
		{/foreach}

	{elseif $item.data == "tables"}
		{foreach from=$tables item=table}
			{if $table.table_status == 1}
			<li><input type="checkbox" {if !empty($table.reviews_headers_id) and $table.reviews_headers_id == $header_id}checked{elseif !empty($table.reviews_headers_id)}disabled{/if} name="table[{$header_id}][{$table.id}]" table="{$table.id}" id="table_{$header_id}_{$table.id}" /><label for="table_{$header_id}_{$table.id}" {if !empty($table.reviews_headers_id) and $table.reviews_headers_id != $header_id}class="label_disabled"{/if}>{$table.description|truncate:"50"|strip_tags}</label></li>
			{/if}
		{/foreach}

	{/if}
	</ul>
</div>