{include file="admin/blocks/header_block.tpl"}

{include file="admin/blocks/left_menu_block.tpl"}

{include file="admin/blocks/breadcrumbs_block.tpl"}

<div class="content">

	<h2>������ ������ ������ &laquo;{$reviews.name}&raquo;</h2>

	
		<table class="table table-striped th_clear">
		{if $cases}
			<tr>
				<th>ID</th>
				<th nowrap>���� ����������</th>
				<th nowrap>����� �����</th>
				<th>������</th>
				<th>��������</th>
				<th>������� ��������</th>
				<th>������������</th>
				<th>��������</th>
				<th></th>
			</tr>
			{foreach from=$cases item=item}
				<tr>
					<td>{$item.id}</td>
					<td nowrap>{$item.date|date_format:"%d.%m.%Y %H:%M"}</td>
					<td>{$item.user_name}</td>
					<td>{if $item.case_status == 1}���.{else}����.{/if}</td>
					<td>{$item.name|strip_tags}</td>
					<td>{$item.small_text}</td>
					<td><a href="{$DOMAIN}/admin/reviews_cases/preview/{$item.id}/">��������</a></td>
					<td>{if $item.image|isimage}<img src="{$item.image}" />{/if}</td>
					<td user_id="{$item.users_user_id}">
						<div class="btn-group">
						  <button class="btn dropdown-toggle" data-toggle="dropdown">�������� <span class="caret"></span></button>
						  <ul class="dropdown-menu">
						    <li><a href="{$DOMAIN}/admin/reviews_cases/case_edit/{$item.id}/"><i class="icon-edit"></i> �������������</a></li>
						    <li><a href="{$DOMAIN}/admin/reviews_cases/case_delete/{$item.id}/" class="case_delete"><i class="icon-remove"></i> �������</a></li>
						  </ul>
						</div>					
					</td>
				</tr>
			{/foreach}
		{/if}
		
		<tr>
			<td colspan="9">
				<div class="control-group">
					<button type="button" class="btn" onClick="location=protocol + location.hostname + '/admin/reviews_cases/case_add/{$reviews.id}/'"><i class="icon-plus"></i> �������� ����</button>
				</div>
			</td>
		</table>
	


</div>

{include file="admin/blocks/footer_block.tpl"}