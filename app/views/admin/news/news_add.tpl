{include file="admin/blocks/header_block.tpl"}
{include file="admin/blocks/left_menu_block.tpl"}
{include file="admin/blocks/breadcrumbs_block.tpl"}

<div class="content">

	<form action="{$DOMAIN}/admin/news/{if $news}news_edit_post/{$news.news_id}{else}news_add_post{/if}/" method="post" class="add_form" id="news_add">
		
		<input id="current_status" type="hidden" name="current_status" value="{$news.news_status}" />
		
		<fieldset>
			<legend>{if $news}�������������� ������� {$news.news_title}{else}���������� �������{/if}</legend>
				
				<div class="control-group">
					<label class="control-label" for="date_picker">���� ����������</label>
					<div class="controls">
						<input class="input-xlarge select_date" type="text" name="news_date" id="date_picker" value="{if $news}{$news.news_date|date_format:'%d.%m.%Y %H:%M:%S'}{else}{php}echo date('d.m.Y H:i:s');{/php}{/if}" />
					</div>
				</div>
				
				<div class="control-group">
				
					<label class="checkbox inline">
						<input type="checkbox" name="news_top" value="1" class="check"{if $news.news_top == 1} checked{/if}> ������� �������
					</label>
					
					<label class="checkbox inline">
						<input type="checkbox" name="news_is_zoom" value="1" class="check"{if $news.news_is_zoom == 1} checked{/if}> ������� zoom
					</label>

					<label class="checkbox inline">
						<input type="checkbox" name="news_is_advert" value="1" class="check"{if $news.news_is_advert == 1} checked{/if}> ��������� �������
					</label>

					<label class="checkbox inline">
						<input type="checkbox" name="news_publication" value="1" class="check"{if $news.news_publication == 1} checked{/if}> ����������� �� ���
					</label>

					<label class="checkbox inline">
						<input type="checkbox" name="news_comment" value="1" class="check"{if $news.news_comment == 1} checked{/if}> ��������� �����������
					</label>
					
					<label class="checkbox edit_status inline">
						<input type="checkbox" name="news_status" value="1" class="check"{if $news.news_status == 1}checked{/if}> ��������
					</label>

				</div>				

				<div class="control-group">
					<label class="control-label" for="news_title">���� �������</label>
					<div class="controls">
						<select name="news_color"><option value="red"{if $news.news_color == 'red'} selected{/if}>�������<option value="black"{if $news.news_color == 'black' or !$news} selected{/if}>������</select>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="news_title">��������� �������</label>
					<div class="controls">
						<input class="input-xlarge" type="text" name="news_title" id="news_title" value="{$news.news_title|strip_tags}" />
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="news_tv_title">��������� TV</label>
					<div class="controls">
						<input class="input-xlarge" type="text" name="news_tv_title" id="news_tv_title" value="{$news.news_tv_title|strip_tags}" />
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="small_text">����� �������</label>
					<div class="controls">
						<textarea class="input-xlarge small_text" name="news_annonce" rows="10" id="small_text">{$news.news_annonce}</textarea>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="news_text">����� �������</label>
					<div class="controls">
						<textarea class="input-xlarge text" name="news_text" rows="30" id="news_text">{$news.news_text}</textarea>
						<div id="tags_list"></div>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="news_image_100x100">����</label>
					<div class="controls">
						<input class="input-xlarge" type="text" name="news_tags" id="news_tags" value="{if $news_tags}{foreach from=$news_tags item=tag name=ftag}{$tag.tags_name}{if !$smarty.foreach.ftag.last},{/if}{/foreach}{/if}" />
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="news_image_100x100">�������� 100�100</label>
					<div class="controls">
						<input class="input-xlarge" type="text" name="news_image_100x100" id="news_image_100x100" value="{$news.news_image_100x100}" />
						<a href="" class="select_image" for="news_image_100x100">�������</a>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="news_image_280x100">�������� 280�120</label>
					<div class="controls">
						<input class="input-xlarge" type="text" name="news_image_280x120" id="news_image_280x120" value="{$news.news_image_280x120}" />
						<a href="" class="select_image" for="news_image_280x120">�������</a>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="news_image_source">�������� ��������</label>
					<div class="controls">
						<input class="input-xlarge" type="text" name="news_image_source" id="news_image_source" value="{$news.news_image_source}" />
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="sections">�������</label>
					<div class="controls" id="sections">{include file="admin/news/news_sections.tpl" sections=`$sections` parent="0" counter="0" level="0"}</div>
				</div>

			<button type="submit" class="btn btn-success">{if $news}<i class="icon-ok"></i> �������� �������{else}<i class="icon-plus"></i> �������� �������{/if}</button>
		</fieldset>
	</form>
	
	{if $entities}
		{include file="admin/blocks/entities_includes_block.tpl"}
	{/if}
	
	
</div>

{include file="admin/blocks/footer_block.tpl"}
