{include file="admin/blocks/header_block.tpl"}

{include file="admin/blocks/left_menu_block.tpl"}

{include file="admin/blocks/breadcrumbs_block.tpl"}

<div class="content">
	<form action="{$DOMAIN}/admin/reviews_cases/{if $case}case_edit_post/{$case.id}{else}case_add_post{/if}/" method="post" class="add_form" id="case_add">
		<input type="hidden" name="reviews_id" value="{if $case}{$case.reviews_id}{else}{$reviews.id}{/if}" />
		<fieldset>
			<legend>{if $case}��������� �����{else}���������� �����{/if}</legend>

			<div class="control-group edit_status">
				<label class="control-label" for="case_status">������ ����� ������</label>
				<div class="controls">
					<select id="case_status" name="case_status"><option value="1"{if $case.case_status==1} selected{/if}>�������</option><option value="0" {if !$case or $case.case_status==0}selected{/if}>��������</option></select>
				</div>
			</div>

			{if $case}
				<div class="control-group">
					<label class="control-label" for="date_picker">���� ����������</label>
					<div class="controls">
						<input class="input-xlarge select_date" type="text" name="date" id="date_picker" value="{$case.date|date_format:'%d.%m.%Y %H:%M:%S'}" />
					</div>
				</div>
			{/if}
			
			<div class="control-group">
				<label class="control-label" for="name">��������</label>
				<div class="controls">
					<input class="input-xlarge" type="text" name="name" id="name" value="{$case.name}" />
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label" for="small_text">������� ��������</label>
				<div class="controls">
					<textarea class="input-xlarge small_text" name="small_text" rows="10" id="small_text">{$case.small_text}</textarea>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="text">������ ��������</label>
				<div class="controls">
					<textarea class="input-xlarge text" name="text" rows="30" id="text">{$case.text}</textarea>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="image">�������� (172x122)</label>{if $case.image|isimage}<img src="{$case.image}" width="100" />{/if}
				<div class="controls">
					<input class="input-xlarge" type="text" name="image" id="image" value="{$case.image}" />
					<a href="" class="select_image" for="image">�������</a>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="banner_image">�������� �������</label>{if $case.banner_image|isimage}<img src="{$case.banner_image}" width="100" />{/if}
				<div class="controls">
					<input class="input-xlarge" type="text" name="banner_image" id="banner_image" value="{$case.banner_image}" />
					<a href="" class="select_image" for="banner_image">�������</a>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="banner_url">������ �� ������</label>
				<div class="controls">
					<input class="input-xlarge" type="text" name="banner_url" id="banner_url" value="{$case.banner_url}" />
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="banner_right_image">�������� ������� ������</label>{if $case.banner_right_image|isimage}<img src="{$case.banner_right_image}" width="100" />{/if}
				<div class="controls">
					<input class="input-xlarge" type="text" name="banner_right_image" id="banner_right_image" value="{$case.banner_right_image}" />
					<a href="" class="select_image" for="banner_right_image">�������</a>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="banner_right_url">������ �� ������ ������</label>
				<div class="controls">
					<input class="input-xlarge" type="text" name="banner_right_url" id="banner_right_url" value="{$case.banner_right_url}" />
				</div>
			</div>
			
			<button type="button" id="reviews_case_add_btn" class="btn btn-success">{if $case}<i class="icon-ok"></i> �������� ����{else}<i class="icon-plus"></i> �������� ����{/if}</button>
			
		</fieldset>
	</form>
	
	{if $entities}
		{include file="admin/blocks/entities_includes_block.tpl"}
	{/if}
	
</div>

{include file="admin/blocks/footer_block.tpl"}