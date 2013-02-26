{include file="admin/blocks/header_block.tpl"}

{include file="admin/blocks/left_menu_block.tpl"}

{include file="admin/blocks/breadcrumbs_block.tpl"}

<div class="content">
	<form action="{$DOMAIN}/admin/reviews_interviews/{if $interview}interview_edit_post/{$interview.id}{else}interview_add_post{/if}/" method="post" class="add_form" id="interview_add">
		<input type="hidden" name="reviews_id" value="{if $interview}{$interview.reviews_id}{else}{$reviews.id}{/if}" />
		<fieldset>
			<legend>{if $interview}Изменение интервью{else}Добавление интервью{/if}</legend>


			<div class="control-group edit_status">
				<label class="control-label" for="interview_status">Статус интервью обзора</label>
				<div class="controls">
					<select id="interview_status" name="interview_status"><option value="1"{if $interview.interview_status==1} selected{/if}>Включен</option><option value="0" {if !$interview or $interview.interview_status==0}selected{/if}>Выключен</option></select>
				</div>
			</div>


			{if $interview}
				<div class="control-group">
					<label class="control-label" for="date_picker">Дата публикации</label>
					<div class="controls">
						<input class="input-xlarge select_date" type="text" name="date" id="date_picker" value="{$interview.date|date_format:'%d.%m.%Y %H:%M:%S'}" />
					</div>
				</div>
			{/if}
			
			<div class="control-group">
				<label class="control-label" for="person">Респондент</label>
				<div class="controls">
					<input class="input-xlarge" type="text" name="person" id="person" value="{$interview.person}" />
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label" for="description">Пояснение к интервью</label>
				<div class="controls">
					<textarea class="input-xlarge" name="description" rows="10" id="description">{$interview.description}</textarea>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="small_text">Краткое описание</label>
				<div class="controls">
					<textarea class="input-xlarge small_text" name="small_text" rows="10" id="small_text">{$interview.small_text}</textarea>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="text">Полное описание</label>
				<div class="controls">
					<textarea class="input-xlarge text" name="text" rows="30" id="text">{$interview.text}</textarea>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="image">Картинка к интервью (180x240)</label>{if $interview.image|isimage}<img src="{$interview.image}" width="100" />{/if}
				<div class="controls">
					<input class="input-xlarge" type="text" name="image" id="image" value="{$interview.image}" />
					<a href="" class="select_image" for="image">Выбрать</a>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="logo">Логотип справа</label>{if $interview.logo|isimage}<img src="{$interview.logo}" width="100" />{/if}
				<div class="controls">
					<input class="input-xlarge" type="text" name="logo" id="logo" value="{$interview.logo}" />
					<a href="" class="select_image" for="logo">Выбрать</a>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="logo_url">Ссылка с логотипа</label>
				<div class="controls">
					<input class="input-xlarge" type="text" name="logo_url" id="logo_url" value="{$interview.logo_url}" />
				</div>
			</div>
			
			<button type="button" id="reviews_interviews_add_btn" class="btn btn-success">{if $interview}<i class="icon-ok"></i> Изменить интервью{else}<i class="icon-plus"></i> Добавить интервью{/if}</button>
			
		</fieldset>
	</form>
</div>

{include file="admin/blocks/footer_block.tpl"}