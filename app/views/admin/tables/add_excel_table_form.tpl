{include file="admin/blocks/header_block.tpl"}

{include file="admin/blocks/left_menu_block.tpl"}

{include file="admin/blocks/breadcrumbs_block.tpl"}

<div class="content">
	<form action="{$DOMAIN}/admin/tables/excel_add_post/" method="post" class="add_form" id="table_add_excel" enctype="multipart/form-data">
		<fieldset>
			<legend>���������� ������� �� Excel</legend>

			<div class="control-group edit_status">
				<label class="control-label" for="table_status">������ �������</label>
				<div class="controls">
					<select id="table_status" name="table_status"><option value="1">�������</option><option value="0" selected>��������</option></select>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="description">�������� �������</label>
				<div class="controls">
					<textarea class="input-xlarge" name="description" rows="10" id="description"></textarea>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="rating">�������� ������ �������</label>
				<div class="controls">
					<textarea class="input-xlarge" name="rating" rows="10" id="rating">{$table.rating}</textarea>
				</div>
			</div>
			

			<div class="control-group">
				<label class="control-label" for="source">�������� ��������� �������</label>
				<div class="controls">
					<textarea class="input-xlarge" name="source" rows="10" id="source">{$table.source}</textarea>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="table">���� Excel</label>
				<div class="controls">
					<input class="input-xlarge" type="file" name="table" id="table" value="" />
				</div>
			</div>

			<button type="submit" class="btn btn-success"><i class="icon-plus"></i> �������� �������</button>

		</fieldset>
	</form>
</div>

{include file="admin/blocks/footer_block.tpl"}