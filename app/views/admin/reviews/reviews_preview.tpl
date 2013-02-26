<h1>{$review.name|strip_tags}</h1>
<table width="800">
	<tr>
		<td valign="top">
			<div>{if $review.image|isimage}<img src="{$review.image}" />{else}Нет фото{/if}</div>
			<div>{if $review.banner_image|isimage}<a href="{$review.banner_url}"><img src="{$review.banner_image}" /></a>{else}Нет фото{/if}
			{if $review.banner_right_image|isimage}&nbsp;&nbsp;&nbsp;<a href="{$review.banner_right_url}"><img src="{$review.banner_right_image}" /></a>{/if}
			</div>
			</td>
		<td>
		{$review.text}
		
		{if $reviews_structure}
			<ul>
			{foreach from=$reviews_structure item=header}
				<li><a href="#header{$header.id}">{$header.name|strip_tags}</a></li>
			{/foreach}
			</ul>
		{/if}
		
		</td>
	</tr>
</table>

{if $reviews_structure}
	{foreach from=$reviews_structure item=header}
		<h2 id="header{$header.id}">{$header.name|strip_tags}</h2>
		{if count($header.data)}
			{foreach from=$header.data item=item key=key}
				<div>{$item}</div>
			{/foreach}
		{/if}
	{/foreach}
{/if}