{include file="admin/blocks/header_block.tpl"}

{include file="admin/blocks/left_menu_block.tpl"}

{include file="admin/blocks/breadcrumbs_block.tpl"}

<div class="content">
	<form action="{$DOMAIN}/admin/users/{if $user}user_edit_post/{$user.user_id}{else}user_add_post{/if}/" method="post" class="add_form" id="user_add">
		{if $user}<input type="hidden" name="user_id" value="{$user.user_id}" />{/if}
		<fieldset>
			<legend>{if $user}Редактирование пользователя {$user.user_name}{else}Добавление нового пользователя{/if}</legend>
			
			<label>Группа пользователя</label>
			<select name="group_name">{foreach from=$admin_groups item=group key=key}<option {if $user && $user.group_name == $key}selected{/if} value="{$key}">{$group}</option>{/foreach}</select>
			
			{if $user}
				<div class="control-group">
					<label class="control-label" for="date_picker">Дата регистрации</label>
					<div class="controls">
						<input class="input-xlarge select_date" type="text" name="user_register_date" id="date_picker" value="{$user.user_register_date|date_format:'%d.%m.%Y %H:%M:%S'}" />
					</div>
				</div>
			{/if}
			
			<div class="control-group">
				<label class="control-label" for="user_login">Логин пользователя</label>
				<div class="controls">
					<input class="input-xlarge" type="text" name="user_login" id="user_login" value="{$user.user_login}" />
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label" for="user_email">Email пользователя</label>
				<div class="controls">
					<input class="input-xlarge" type="text" name="user_email" id="user_email" value="{$user.user_email}" />
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label" for="user_name">Инициалы пользователя</label>
				<div class="controls">
					<input class="input-xlarge" type="text" name="user_name" id="user_name" value="{$user.user_name}" />
				</div>
			</div>
			
			
			<div class="control-group">
				<label class="control-label" for="user_password">Пароль пользователя</label>
				<div class="controls">
					<input class="input-xlarge" type="password" name="user_password" id="user_password" value="{$user.user_password}" />
				</div>
			</div>
			
			
			<div class="control-group">
				<label class="control-label" for="user_description">Доп. информация</label>
				<div class="controls">
					<textarea class="input-xlarge text" name="user_description" rows="10" cols="15" id="user_description">{$user.user_description}</textarea>
				</div>
			</div>
			
			<button type="submit" class="btn btn-success">{if $user}<i class="icon-ok"></i> Изменить пользователя{else}<i class="icon-plus"></i> Добавить пользователя{/if}</button>
		</fieldset>
	</form>
</div>

{include file="admin/blocks/footer_block.tpl"}