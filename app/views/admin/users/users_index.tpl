{include file="admin/blocks/header_block.tpl"}
{include file="admin/blocks/left_menu_block.tpl"}
{include file="admin/blocks/breadcrumbs_block.tpl"}

<div class="content">

		<h2>Список пользователей</h2>

		<table class="table table-striped">
		{if $users}
			<tr>
				<th>ID</th>
				<th nowrap>Дата регистрации</th>
				<th>Логин</th>
				<th>Email</th>
				<th>Инициалы</th>
				<th>Группа</th>
				<th></th>
			</tr>
			{foreach from=$users item=user}
				<tr>
					<td>{$user.user_id}</td>
					<td nowrap>{$user.user_register_date|date_format:"%d.%m.%Y %H:%M"}</td>
					<td>{$user.user_login}</td>
					<td>{$user.user_email}</td>
					<td>{$user.user_name}</td>
					<td>{$user.group_name}</td>
					<td>
						<div class="btn-group">
						  <button class="btn dropdown-toggle" data-toggle="dropdown">Действие <span class="caret"></span></button>
						  <ul class="dropdown-menu">
						    <li><a href="{$DOMAIN}/admin/users/user_edit/{$user.user_id}/"><i class="icon-edit"></i> Редактировать</a></li>
						    {if $user.group != 'admin'}<li><a href="{$DOMAIN}/admin/users/user_delete/{$user.user_id}/" class="user_delete"><i class="icon-remove"></i> Удалить</a></li>{/if}
						  </ul>
						</div>					
					</td>
				</tr>
			{/foreach}
		{/if}

		<tr>
			<td colspan="7">
				<div class="control-group">
					<button type="button" class="btn" onClick="location=protocol + location.hostname + '/admin/users/user_add/'"><i class="icon-plus"></i> Добавить пользователя</button>
				</div>
			</td>
		</tr>

		</table>

</div>

{include file="admin/blocks/footer_block.tpl"}