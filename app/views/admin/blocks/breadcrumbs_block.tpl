{if $breadcrumbs_block}
<ul class="breadcrumb">
	{foreach from=$breadcrumbs_block->get() item=item name="brc"}
		{if !$smarty.foreach.brc.last}
  			<li><a href="{$item.url}">{$item.name|strip_tags}</a> <span class="divider">/</span></li>
  		{else}
  			<li class="active">{$item.name|strip_tags}</li>
  		{/if}
  	{/foreach}
</ul>
{/if}