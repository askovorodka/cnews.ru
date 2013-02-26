{include file="admin/blocks/header_block.tpl"}
{include file="admin/blocks/left_menu_block.tpl"}
{include file="admin/blocks/breadcrumbs_block.tpl"}

<div class="content">
	<form action="{$DOMAIN}/admin/sections/{if $section_single}section_edit_post/{$section_single.section_id}{else}section_add_post{/if}/" method="post" class="add_form" id="section_add">
		<fieldset>
			<legend>{if $section_single}Изменение раздела {$section_single.name}{else}Добавление нового раздела{/if}</legend>
			
			<label>Родительский раздел</label>
			<select name="parent" id="sections_select">
				<option value="0" selected>Корневой раздел</option>
				{foreach from=$sections item=item} {get_sections section=`$item` type="option" current_section=`$section_single` parent_id=`$parent_id`} {/foreach}
			</select>

			<div class="control-group">
				<label class="control-label" for="section_name">Название раздела</label>
				<div class="controls">
					<input class="input-xlarge" type="text" name="section_name" id="name" value="{$section_single.section_name|strip_tags}" />
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label" for="section_description">Описание раздела</label>
				<div class="controls">
					<textarea class="input-xlarge text" name="section_description" rows="10" cols="15" id="text">{$section_single.section_description}</textarea>
				</div>
			</div>
			
			<button type="submit" class="btn btn-success">{if $section_single}<i class="icon-ok"></i> Изменить раздел{else}<i class="icon-plus"></i> Добавить раздел{/if}</button>
		</fieldset>
	</form>
</div>

{include file="admin/blocks/footer_block.tpl"}