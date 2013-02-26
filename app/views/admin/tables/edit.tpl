{include file="admin/blocks/header_block.tpl"}

{include file="admin/blocks/left_menu_block.tpl"}

{include file="admin/blocks/breadcrumbs_block.tpl"}

<div class="content">
	<form action="{$DOMAIN}/admin/tables/edit_post/{$table.table_id}/" method="post" class="add_form" id="table_edit" enctype="multipart/form-data" style="width:100%;">
		<fieldset>
			<legend>Редактирование таблицы</legend>
			
			<div class="control-group edit_status">
				<label class="control-label" for="article_status">Статус таблицы</label>
				<div class="controls">
					<select id="table_status" name="table_status"><option value="1"{if $table.table_status==1} selected{/if}>Включен</option><option value="0" {if !$table or $table.table_status==0}selected{/if}>Выключен</option></select>
				</div>
			</div>
			
			
			<div class="control-group">
				<label class="control-label" for="description">Описание таблицы</label>
				<div class="controls">
					<textarea class="input-xlarge" name="description" rows="5" id="description">{$table.description}</textarea>
				</div>
			</div>
			
			
			<div class="control-group">
				<label class="control-label" for="rating">Описание оценки таблицы</label>
				<div class="controls">
					<textarea class="input-xlarge" name="rating" rows="5" id="rating">{$table.rating|strip_tags}</textarea>
				</div>
			</div>
			
			
			<div class="control-group">
				<label class="control-label" for="source">Описание источника таблицы</label>
				<div class="controls">
					<textarea class="input-xlarge" name="source" rows="5" id="source">{$table.source|strip_tags}</textarea>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="code">HTML код таблицы</label>
				<div class="controls">
					<textarea contenteditable="true" class="input-xlarge" name="code" rows="10" id="code" onClick="this.select();">{$table.structure}</textarea>
				</div>
			</div>
			
			<div class="edit_table">
				{$table.structure}
			</div>
			
			<button type="submit" class="btn btn-success"><i class="icon-ok"></i> Редактировать таблицу</button>
			
		</fieldset>
	</form>
	
	{if $entities_in}
		{include file="admin/blocks/entities_in_block.tpl"}
	{/if}
	
</div>

{include file="admin/blocks/footer_block.tpl"}