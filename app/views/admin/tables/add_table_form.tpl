{include file="admin/blocks/header_block.tpl"}

{include file="admin/blocks/left_menu_block.tpl"}

{include file="admin/blocks/breadcrumbs_block.tpl"}

<div class="content">
	
	<form action="{$DOMAIN}/admin/tables/table_add_post/" method="post" id="add_table_form">
		
		<fieldset>
			<legend>�������� �������</legend>
			
			<div class="control-group edit_status">
				<label class="control-label" for="table_status">������ �������</label>
				<div class="controls">
					<select id="table_status" name="table_status"><option value="1">�������</option><option value="0" selected>��������</option></select>
				</div>
			</div>
			
			
		  <div class="control-group">
		    <label class="control-label" for="rows_count">����� �����</label>
		    <div class="controls">
		      <input type="text" class="input-small" name="rows_count" id="rows_count" placeholder="������">
		    </div>
		  </div>

		  <div class="control-group">
		    <label class="control-label" for="cols_count">����� ��������</label>
		    <div class="controls">
		      <input type="text" class="input-small" name="cols_count" id="cols_count" placeholder="�������">
		    </div>
		  </div>

		  <div class="control-group">
		    <div class="controls">
				<button class="btn" type="submit">�������������</button>
		    </div>
		  </div>
		</fieldset>
	</form>
	
</div>

{include file="admin/blocks/footer_block.tpl"}