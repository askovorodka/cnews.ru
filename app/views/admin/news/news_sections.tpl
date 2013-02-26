{if !empty($sections)}
<ul class="sections level{$level}" id="block_{$parent}">
	{foreach from=$sections item=section}

		{if $section.level == 0}
			{assign var="counter" value=`$counter+1`}
		{/if}

		<li>
			<input type="checkbox" section_id="{$section.section_id}" class="check" name="sections[{$section.section_id}]" id="section_{$section.section_id}"{if $news_sections.sections and in_array($section.section_id, $news_sections.sections)} checked{/if}>
			<input type="radio" name="section_main" value="{$section.section_id}"{if $news_sections.main and in_array($section.section_id, $news_sections.main)} checked{/if}>
			<label {if $section.children}style="float:left;"{/if} for="section_{$section.section_id}">{$section.section_name|strip_tags}</label>
			{if $section.children}&nbsp;&nbsp;<label class="icon-plus children_sections" for="#block_{$section.section_id}"></label><br class="clear">{/if}

			{if $section.children}{include file="admin/news/news_sections.tpl" sections=`$section.children` parent=`$section.section_id` counter=0 level=`$section.level+1`}{/if}
		</li>

	{/foreach}
</ul>
{/if}
