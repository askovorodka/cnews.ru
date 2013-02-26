{include file="admin/blocks/header_block.tpl"}
{include file="admin/blocks/left_menu_block.tpl"}
{include file="admin/blocks/breadcrumbs_block.tpl"}

<div class="content">

	<form action="{$DOMAIN}/admin/articles/{if $article}article_edit_post/{$article.article_id}{else}article_add_post{/if}/" method="post" class="add_form" id="article_add">
		
		<input id="current_status" type="hidden" name="current_status" value="{$article.article_status}" />
		
		<fieldset>
			<legend>{if $article}Редактирование статьи {$article.article_title}{else}Добавление статьи{/if}</legend>
				
				<div class="control-group">
					<label class="control-label" for="date_picker">Дата публикации</label>
					<div class="controls">
						<input class="input-xlarge select_date" type="text" name="article_date" id="date_picker" value="{if $article}{$article.article_date|date_format:'%d.%m.%Y %H:%M:%S'}{else}{php}echo date('d.m.Y H:i:s');{/php}{/if}" />
					</div>
				</div>
				
				<div class="control-group">
				
					<label class="checkbox inline">
						<input type="checkbox" name="article_top" value="1" class="check"{if $article.article_top == 1} checked{/if}> Главная статья
					</label>
					
					<label class="checkbox inline">
						<input type="checkbox" name="article_is_zoom" value="1" class="check"{if $article.article_is_zoom == 1} checked{/if}> Статья zoom
					</label>

					<label class="checkbox inline">
						<input type="checkbox" name="article_is_advert" value="1" class="check"{if $article.article_is_advert == 1} checked{/if}> Рекламная статья
					</label>

					<label class="checkbox inline">
						<input type="checkbox" name="article_publication" value="1" class="check"{if $article.article_publication == 1} checked{/if}> Публиковать на РБК
					</label>

					<label class="checkbox inline">
						<input type="checkbox" name="article_comment" value="1" class="check"{if $article.article_comment == 1} checked{/if}> Разрешить комментарии
					</label>
					
					<label class="checkbox edit_status inline">
						<input type="checkbox" name="article_status" value="1" class="check"{if $article.article_status == 1}checked{/if}> Включена
					</label>

				</div>				

				<div class="control-group">
					<label class="control-label" for="news_title">Цвет статьи</label>
					<div class="controls">
						<select name="article_color"><option value="red"{if $article.article_color == 'red'} selected{/if}>Красная<option value="black"{if $article.news_color == 'black'} selected{/if}>Черная</select>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="news_title">Заголовок статьи</label>
					<div class="controls">
						<input class="input-xlarge" type="text" name="article_title" id="article_title" value="{$article.article_title|strip_tags}" />
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="news_tv_title">Заголовок TV</label>
					<div class="controls">
						<input class="input-xlarge" type="text" name="article_tv_title" id="article_tv_title" value="{$article.article_tv_title|strip_tags}" />
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="small_text">Анонс статьи</label>
					<div class="controls">
						<textarea class="input-xlarge small_text" name="article_annonce" rows="10" id="small_text">{$article.article_annonce}</textarea>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="news_text">Текст статьи</label>
					<div class="controls">
						<textarea class="input-xlarge text" name="article_text" rows="30" id="article_text">{$article.article_text}</textarea>
						<div id="tags_list"></div>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="news_image_100x100">Теги</label>
					<div class="controls">
						<input class="input-xlarge" type="text" name="article_tags" id="article_tags" value="{if $article_tags}{foreach from=$article_tags item=tag name=ftag}{$tag.tags_name}{if !$smarty.foreach.ftag.last},{/if}{/foreach}{/if}" />
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="news_image_100x100">Картинка 100х100</label>
					<div class="controls">
						<input class="input-xlarge" type="text" name="article_image_100x100" id="article_image_100x100" value="{$article.article_image_100x100}" />
						<a href="" class="select_image" for="article_image_100x100">Выбрать</a>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="news_image_280x100">Картинка 280х120</label>
					<div class="controls">
						<input class="input-xlarge" type="text" name="article_image_280x120" id="article_image_280x120" value="{$article.article_image_280x120}" />
						<a href="" class="select_image" for="article_image_280x120">Выбрать</a>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="article_image_source">Источник картинки</label>
					<div class="controls">
						<input class="input-xlarge" type="text" name="article_image_source" id="article_image_source" value="{$article.article_image_source}" />
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="sections">Разделы</label>
					<div class="controls" id="sections">{include file="admin/articles/articles_sections.tpl" sections=`$sections` parent="0" counter="0" level="0"}</div>
				</div>

			<button type="submit" class="btn btn-success">{if $article}<i class="icon-ok"></i> Изменить статьи{else}<i class="icon-plus"></i> Добавить статью{/if}</button>
		</fieldset>
	</form>
	
	{if $entities}
		{include file="admin/blocks/entities_includes_block.tpl"}
	{/if}
	
	
</div>

{include file="admin/blocks/footer_block.tpl"}
