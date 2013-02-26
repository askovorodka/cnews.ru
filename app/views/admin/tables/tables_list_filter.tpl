<div>
	<form action="" method="post" class="form-inline" id="tables_list_filter">
		{if !empty($array.table_status)}<input type="hidden" name="table_status" value="{$array.table_status}" />{/if}
		{if !empty($array.for_wisiwyg)}<input type="hidden" name="for_wisiwyg" value="{$array.for_wisiwyg}" />{/if}
		<input class="input-small" type="text" name="date_start" id="date_start_picker" value="{if !empty($array.date_start)}{$array.date_start}{/if}" placeholder="Дата с..." />
		<input class="input-small" type="text" name="date_end" id="date_end_picker" value="{if !empty($array.date_end)}{$array.date_end}{/if}" placeholder="Дата по..." />
		<select name="change_user"><option selected value=""> - все пользователи - </option>{foreach from=$users item=user}<option value="{$user.user_id}"{if $array.change_user == $user.user_id}selected{/if}>{$user.user_name}</option>{/foreach}</select>
		<button type="submit" class="btn btn-success" id="tables_list_filter_btn">Фильтровать</button>
	</form>
</div>