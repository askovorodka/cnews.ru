{include file="admin/blocks/header_block.tpl"}

{include file="admin/blocks/left_menu_block.tpl"}

{include file="admin/blocks/breadcrumbs_block.tpl"}

<div class="content">
	<form action="{$DOMAIN}/admin/reviews_articles/{if $articles}articles_edit_post/{$articles.id}{else}articles_add_post{/if}/" method="post" class="add_form" id="articles_add">
		<input type="hidden" name="reviews_id" value="{if $articles}{$articles.reviews_id}{else}{$reviews.id}{/if}" />
		<fieldset>
			<legend>{if $articles}Изменение статьи {$reviews.name}{else}Добавление статьи{/if}</legend>
			
			<div class="control-group edit_status">
				<label class="control-label" for="article_status">Статус статьи обзора</label>
				<div class="controls">
					<select id="article_status" name="article_status"><option value="1"{if $articles.article_status==1} selected{/if}>Включен</option><option value="0" {if !$articles or $articles.article_status==0}selected{/if}>Выключен</option></select>
				</div>
			</div>
			
			{if $articles}
				<div class="control-group">
					<label class="control-label" for="date_picker">Дата публикации</label>
					<div class="controls">
						<input class="input-xlarge select_date" type="text" name="date" id="date_picker" value="{$articles.date|date_format:'%d.%m.%Y %H:%M:%S'}" />
					</div>
				</div>
			{/if}
			
			<div class="control-group">
				<label class="control-label" for="name">Название статьи</label>
				<div class="controls">
					<input class="input-xlarge" type="text" name="name" id="name" value="{$articles.name}" />
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label" for="small_text">Краткое описание</label>
				<div class="controls">
					<textarea class="input-xlarge small_text" name="small_text" rows="10" id="small_text">{$articles.small_text}</textarea>
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label" for="text">Полное описание</label>
				<div class="controls">
					<textarea class="input-xlarge text" name="text" rows="30" id="text">{$articles.text}</textarea>
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label" for="image">Картинка (170x120)</label>{if $articles.image|isimage}<img src="{$articles.image}" width="100" />{/if}
				<div class="controls">
					<input class="input-xlarge" type="text" name="image" id="image" value="{$articles.image}" />
					<a href="" class="select_image" for="image">Выбрать</a>
				</div>
			</div>
			
			<button type="button" id="reviews_articles_add_btn" class="btn btn-success">{if $articles}<i class="icon-ok"></i> Изменить статью{else}<i class="icon-plus"></i> Добавить статью{/if}</button>
			
		</fieldset>
	</form>
	
	{if $entities}
		{include file="admin/blocks/entities_includes_block.tpl"}
	{/if}
	
</div>

{include file="admin/blocks/footer_block.tpl"}