
<tr class="level_{$level}">

	<td>{$section.section_id}</td>

	<td>{$section.section_name|strip_tags}</td>

	<td>{$section.section_create_date|date_format:"%d.%m.%Y %H:%M"}</td>

	<td>

		<div class="btn-group">
			<button class="btn dropdown-toggle" data-toggle="dropdown">Действие <span class="caret"></span></button>
			<ul class="dropdown-menu">
				{if $level < 2}
				<li><a href="{$DOMAIN}/admin/sections/section_add/{$section.section_id}/"><i class="icon-plus"></i> Добавить подраздел</a></li>
				{/if}
				<li><a href="{$DOMAIN}/admin/sections/section_edit/{$section.section_id}/"><i class="icon-edit"></i> Редактировать раздел</a></li>
				<li><a href="{$DOMAIN}/admin/sections/section_delete/{$section.section_id}/" class="section_delete"><i class="icon-remove"></i> Удалить раздел</a></li>
			</ul>
		</div>					

	</td>

</tr>
