{include file="admin/blocks/header_block.tpl"}

{include file="admin/blocks/left_menu_block.tpl"}

{include file="admin/blocks/breadcrumbs_block.tpl"}

<div class="content">
	<form action="{$DOMAIN}/admin/reviews_tables/table_add_post/{$reviews.id}/" method="post" class="add_form" id="table_add" enctype="multipart/form-data">
		<input type="hidden" name="reviews_id" value="{$reviews.id}" />
		<fieldset>
			<legend>Добавление таблицы</legend>


			<div class="control-group edit_status">
				<label class="control-label" for="table_status">Статус статьи обзора</label>
				<div class="controls">
					<select id="table_status" name="table_status"><option value="1">Включен</option><option value="0" selected>Выключен</option></select>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="description">Описание таблицы</label>
				<div class="controls">
					<textarea class="input-xlarge" name="description" rows="10" id="description"></textarea>
				</div>
			</div>



			<div class="control-group">
				<label class="control-label" for="table">Файл Excel</label>
				<div class="controls">
					<input class="input-xlarge" type="file" name="table" id="table" value="" />
				</div>
			</div>

			<button type="submit" class="btn btn-success"><i class="icon-plus"></i> Добавить таблицу</button>

		</fieldset>
	</form>
</div>

{include file="admin/blocks/footer_block.tpl"}