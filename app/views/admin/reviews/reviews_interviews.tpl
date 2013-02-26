{include file="admin/blocks/header_block.tpl"}

{include file="admin/blocks/left_menu_block.tpl"}

{include file="admin/blocks/breadcrumbs_block.tpl"}

<div class="content">

	<h2>Список интервью обзора &laquo;{$reviews.name}&raquo;</h2>

	
		<table class="table table-striped th_clear">
		{if $interviews}
			<tr>
				<th>ID</th>
				<th nowrap>Дата публикации</th>
				<th nowrap>Автор интервью</th>
				<th>Статус</th>
				<th>Персона</th>
				<th>Краткое описание</th>
				<th>Предпросмотр</th>
				<th>Картинка</th>
				<th></th>
			</tr>
			{foreach from=$interviews item=item}
				<tr>
					<td>{$item.id}</td>
					<td nowrap>{$item.date|date_format:"%d.%m.%Y %H:%M"}</td>
					<td>{$item.user_name}</td>
					<td>{if $item.interview_status == 1}Вкл.{else}Выкл.{/if}</td>
					<td>{$item.person|strip_tags}<br><small><i>{$item.description}</i></small></td>
					<td>{$item.small_text}</td>
					<td><a href="{$DOMAIN}/admin/reviews_interviews/preview/{$item.id}/">смотреть</a></td>
					<td>{if $item.image|isimage}<img src="{$item.image}" />{/if}</td>
					<td user_id="{$item.users_user_id}">
						<div class="btn-group">
						  <button class="btn dropdown-toggle" data-toggle="dropdown">Действие <span class="caret"></span></button>
						  <ul class="dropdown-menu">
						    <li><a href="{$DOMAIN}/admin/reviews_interviews/interview_edit/{$item.id}/"><i class="icon-edit"></i> Редактировать</a></li>
						    <li><a href="{$DOMAIN}/admin/reviews_interviews/interview_delete/{$item.id}/" class="interview_delete"><i class="icon-remove"></i> Удалить</a></li>
						  </ul>
						</div>					
					</td>
				</tr>
			{/foreach}
		{/if}
		
		<tr>
			<td colspan="9">
				<div class="control-group">
					<button type="button" class="btn" onClick="location=protocol + location.hostname + '/admin/reviews_interviews/interview_add/{$reviews.id}/'"><i class="icon-plus"></i> Добавить интервью</button>
				</div>
			</td>
		</tr>
		</table>
	


</div>

{include file="admin/blocks/footer_block.tpl"}